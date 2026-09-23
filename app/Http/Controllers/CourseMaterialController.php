<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class CourseMaterialController extends Controller
{
    public function myCourses()
    {
        $user = Auth::user();
        if ($user->role !== 'sta') {
            abort(403);
        }

        // Fetch assigned courses grouped by regulation & curriculum
        $courses = $user->courses()
            ->with(['regulation', 'department'])
            ->withCount(['materials', 'assignments', 'enrollments'])
            ->get();
            
        $groupedCourses = $courses->groupBy(function($course) {
            return $course->regulation_id ?: 0;
        });

        $courseIds = $courses->pluck('id');

        // Fetch all assignments for assigned courses
        $assignments = \App\Models\Assignment::whereIn('course_id', $courseIds)
            ->with(['course', 'questions'])
            ->withCount('submissions')
            ->latest()
            ->get();

        $recentUploads = CourseMaterial::where('staff_id', $user->id)
            ->with('course')
            ->latest()
            ->take(6)
            ->get();

        $totalMaterialsCount = \App\Models\CourseMaterial::where(function($q) use ($user, $courseIds) {
            $q->where('staff_id', $user->id)
              ->orWhereIn('course_id', $courseIds);
        })->count();

        $totalAssignmentsCount = \App\Models\Assignment::where(function($q) use ($user, $courseIds) {
            $q->where('staff_id', $user->id)
              ->orWhereIn('course_id', $courseIds);
        })->count();

        return view('staff.my_courses', compact(
            'groupedCourses', 
            'recentUploads', 
            'courses',
            'assignments',
            'totalMaterialsCount',
            'totalAssignmentsCount'
        ));
    }

    public function index(Request $request, $course_id)
    {
        $user = Auth::user();
        if ($user->role !== 'sta') {
            abort(403);
        }

        $course = Course::with([
            'regulation', 
            'department.school', 
            'staff.profile',
            'enrollments.profile.department',
            'enrollments.profile.program'
        ])->findOrFail($course_id);

        // Verify the staff is assigned to this course OR is in the same department
        $isAssigned = $course->staff->contains($user->id);
        $isSameDept = $user->profile && $user->profile->departments_id === $course->department_id;
        
        if (!$isAssigned && !$isSameDept) {
            abort(403, 'You do not have permission to access this course.');
        }

        $materials = CourseMaterial::where('course_id', $course_id)->latest()->get();

        // Exact counts and breakdown for stats cards
        $totalMaterialsCount = $materials->count();
        $filesCount = $materials->where('type', 'file')->count();
        $linksCount = $materials->where('type', 'link')->count();

        // Fetch assignments for this specific course
        $assignments = \App\Models\Assignment::where('course_id', $course_id)
            ->with(['questions'])
            ->withCount('submissions')
            ->latest()
            ->get();

        $totalAssignmentsCount = $assignments->count();
        $activeAssignmentsCount = $assignments->where('status', '!=', 'draft')->count();
        $draftAssignmentsCount = $assignments->where('status', 'draft')->count();

        $enrolledStudentsCount = $course->enrollments ? $course->enrollments->count() : 0;

        return view('staff.course_materials', compact(
            'course', 
            'materials', 
            'assignments',
            'totalMaterialsCount',
            'filesCount',
            'linksCount',
            'totalAssignmentsCount',
            'activeAssignmentsCount',
            'draftAssignmentsCount',
            'enrolledStudentsCount'
        ));
    }

    public function store(Request $request, $course_id)
    {
        $user = Auth::user();
        if ($user->role !== 'sta') {
            abort(403);
        }

        $course = Course::findOrFail($course_id);
        
        $isAssigned = $course->staff->contains($user->id);
        $isSameDept = $user->profile->departments_id === $course->department_id;
        
        if (!$isAssigned && !$isSameDept) {
            abort(403, 'You do not have permission to modify this course.');
        }

        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:link,file',
            'platform' => 'nullable|string',
            'url' => 'nullable|required_if:type,link|url',
        ];

        // If not base64 provided, validate standard file input if present
        if ($request->type === 'file' && !$request->filled('file_base64')) {
            $rules['file'] = 'required|file|mimes:pdf,doc,docx,ppt,pptx,pps,ppsx,xls,xlsx,csv,txt,zip,rar,png,jpg,jpeg,webp|max:25600';
        }

        $request->validate($rules, [
            'file.required' => 'Please select a file to upload.',
            'file.mimes' => 'The uploaded file must be a valid document (PDF, PowerPoint, Word, Excel, Text, Zip, or Image).',
            'file.max' => 'The file size must not exceed 25MB.',
        ]);

        $material = new CourseMaterial();
        $material->course_id = $course_id;
        $material->staff_id = $user->id;
        $material->title = $request->title;
        $material->type = $request->type;
        $material->platform = $request->platform ?: 'file';

        if ($request->type === 'link') {
            $material->url_or_path = $request->url;
        } else {
            $filePath = null;

            // 1. Process Base64 upload if provided from client FileReader
            if ($request->filled('file_base64')) {
                $base64Data = $request->input('file_base64');
                if (preg_match('/^data:.*?;base64,/', $base64Data, $match)) {
                    $base64Data = substr($base64Data, strlen($match[0]));
                }
                $fileContent = base64_decode($base64Data);
                if ($fileContent === false || empty($fileContent)) {
                    return back()->withInput()->withErrors(['file' => 'Failed to read uploaded file data. Please try again.']);
                }

                $origName = $request->input('file_name', 'document.pdf');
                $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION)) ?: 'pdf';
                
                $allowedExts = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'xls', 'xlsx', 'csv', 'txt', 'zip', 'rar', 'png', 'jpg', 'jpeg', 'webp'];
                if (!in_array($ext, $allowedExts)) {
                    return back()->withInput()->withErrors(['file' => 'Invalid file extension (.'.$ext.'). Allowed formats: PDF, PowerPoint, Word, Excel, Zip, Images.']);
                }

                if (strlen($fileContent) > 26214400) { // 25MB
                    return back()->withInput()->withErrors(['file' => 'File size exceeds the 25MB limit.']);
                }

                $storageFolder = storage_path('app/public/course_materials');
                if (!file_exists($storageFolder)) {
                    mkdir($storageFolder, 0777, true);
                }

                $uniqueName = Str::random(40) . '.' . $ext;
                file_put_contents($storageFolder . '/' . $uniqueName, $fileContent);
                $filePath = 'course_materials/' . $uniqueName;

                // Auto-detect platform icon if not explicitly chosen
                if (!$request->filled('platform') || $request->platform === 'file') {
                    if (in_array($ext, ['ppt', 'pptx', 'pps', 'ppsx'])) $material->platform = 'ppt';
                    elseif (in_array($ext, ['doc', 'docx'])) $material->platform = 'word';
                    elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) $material->platform = 'excel';
                    elseif ($ext === 'pdf') $material->platform = 'pdf';
                }
            } 
            // 2. Standard Multipart File Upload
            elseif ($request->hasFile('file')) {
                $file = $request->file('file');
                if (!$file->isValid()) {
                    return back()->withInput()->withErrors(['file' => 'The file upload failed. Please try selecting the file again.']);
                }
                $ext = strtolower($file->getClientOriginalExtension());
                $filePath = $file->store('course_materials', 'public');

                if (!$request->filled('platform') || $request->platform === 'file') {
                    if (in_array($ext, ['ppt', 'pptx', 'pps', 'ppsx'])) $material->platform = 'ppt';
                    elseif (in_array($ext, ['doc', 'docx'])) $material->platform = 'word';
                    elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) $material->platform = 'excel';
                    elseif ($ext === 'pdf') $material->platform = 'pdf';
                }
            } else {
                return back()->withInput()->withErrors(['file' => 'Please select a file to upload.']);
            }

            $material->url_or_path = $filePath;
        }

        $material->save();

        return back()->with('success', 'Material added successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['sta', 'sa', 'admin'])) {
            abort(403);
        }

        $material = CourseMaterial::findOrFail($id);
        $course = $material->course;

        /*
        // PREVIOUS RESTRICTION: Each faculty can only delete their own uploaded course material
        $isOwner = $material->staff_id === $user->id;
        if (!$isOwner) {
            abort(403, 'You do not have permission to delete this material.');
        }
        */

        // NEW FUNCTIONALITY: In that department, every faculty can delete the materials
        $isSameDept = $user->profile && ($user->profile->departments_id === $course->department_id);
        $isAssigned = $course && $course->staff->contains($user->id);
        $isOwner = $material->staff_id === $user->id;
        $isPrivileged = in_array($user->role, ['sa', 'admin']);

        if (!$isSameDept && !$isAssigned && !$isOwner && !$isPrivileged) {
            abort(403, 'You do not have permission to delete this material.');
        }

        if ($material->type === 'file' && $material->url_or_path) {
            Storage::disk('public')->delete($material->url_or_path);
        }

        $material->delete();

        return back()->with('success', 'Material deleted successfully.');
    }

    public function viewFile(CourseMaterial $material)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($material->type === 'link') {
            return redirect()->away($material->url_or_path);
        }

        $filePath = $this->resolveMaterialPath($material->url_or_path);
        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'Material file could not be found on the server.');
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
        $fileSizeFormatted = $this->formatBytes($fileSize);
        $cleanTitle = $material->title ?: 'Learning Material';
        $fileName = basename($filePath);
        $rawUrl = route('materials.raw', $material->id);
        $downloadUrl = route('materials.download', $material->id);

        $course = $material->course()->with(['department', 'regulation'])->first();

        return view('materials.viewer', compact(
            'material',
            'course',
            'ext',
            'rawUrl',
            'downloadUrl',
            'cleanTitle',
            'fileName',
            'fileSizeFormatted'
        ));
    }

    public function rawFile(CourseMaterial $material)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($material->type === 'link') {
            return redirect()->away($material->url_or_path);
        }

        $filePath = $this->resolveMaterialPath($material->url_or_path);
        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'Material file could not be found on the server.');
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $cleanTitle = Str::slug($material->title ?: 'material') . '.' . ($ext ?: 'pdf');
        $mime = $this->getMimeType($ext);

        return response()->file($filePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $cleanTitle . '"',
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'no-cache, must-revalidate'
        ]);
    }

    private function formatBytes($bytes, $precision = 1)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function downloadFile(CourseMaterial $material)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($material->type === 'link') {
            return redirect()->away($material->url_or_path);
        }

        $filePath = $this->resolveMaterialPath($material->url_or_path);
        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'Material file could not be found on the server.');
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $cleanTitle = Str::slug($material->title ?: 'material') . '.' . ($ext ?: 'pdf');

        return response()->download($filePath, $cleanTitle);
    }

    public function serveStorage($path)
    {
        $filePath = storage_path('app/public/' . $path);
        if (!file_exists($filePath)) {
            $filePath = public_path('storage/' . $path);
        }
        if (!file_exists($filePath)) {
            $filePath = storage_path('app/' . $path);
        }

        if (!file_exists($filePath) || is_dir($filePath)) {
            abort(404, 'File not found.');
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = $this->getMimeType($ext);

        return response()->file($filePath, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function resolveMaterialPath($relativePath)
    {
        if (empty($relativePath)) return null;

        $candidates = [
            storage_path('app/public/' . $relativePath),
            public_path('storage/' . $relativePath),
            storage_path('app/' . $relativePath),
            public_path($relativePath),
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && !is_dir($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function getMimeType($ext)
    {
        $mimes = [
            'pdf' => 'application/pdf',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'pps' => 'application/vnd.ms-powerpoint',
            'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv' => 'text/csv',
            'txt' => 'text/plain',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
        ];

        return $mimes[$ext] ?? 'application/octet-stream';
    }
}
