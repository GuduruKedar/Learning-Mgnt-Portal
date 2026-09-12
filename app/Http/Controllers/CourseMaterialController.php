<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    public function myCourses()
    {
        $user = Auth::user();
        if ($user->role !== 'sta') {
            abort(403);
        }

        // Fetch assigned courses grouped by regulation
        $courses = $user->courses()->with(['regulation', 'materials'])->get();
            
        $groupedCourses = $courses->groupBy(function($course) {
            return $course->regulation ? $course->regulation->name : 'Uncategorized';
        });

        // Fetch recent uploads
        $recentUploads = \App\Models\CourseMaterial::where('staff_id', $user->id)
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('staff.my_courses', compact('groupedCourses', 'recentUploads'));
    }

    public function index(Request $request, $course_id)
    {
        $user = Auth::user();
        if ($user->role !== 'sta') {
            abort(403);
        }

        $course = Course::findOrFail($course_id);

        // Verify the staff is assigned to this course OR is in the same department
        $isAssigned = $course->staff->contains($user->id);
        $isSameDept = $user->profile->departments_id === $course->department_id;
        
        if (!$isAssigned && !$isSameDept) {
            abort(403, 'You do not have permission to access this course.');
        }

        $query = CourseMaterial::where('course_id', $course_id);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $materials = $query->latest()->paginate(10)->withQueryString();

        return view('staff.course_materials', compact('course', 'materials'));
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

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:link,file',
            'platform' => 'nullable|string',
            'url' => 'nullable|required_if:type,link|url',
            'file' => 'nullable|required_if:type,file|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $material = new CourseMaterial();
        $material->course_id = $course_id;
        $material->staff_id = $user->id;
        $material->title = $request->title;
        $material->type = $request->type;
        $material->platform = $request->platform;

        if ($request->type === 'link') {
            $material->url_or_path = $request->url;
        } else {
            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('course_materials', 'public');
                $material->url_or_path = $path;
            }
        }

        $material->save();

        return back()->with('success', 'Material added successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role !== 'sta') {
            abort(403);
        }

        $material = CourseMaterial::findOrFail($id);
        $course = $material->course;

        $isOwner = $material->staff_id === $user->id;
        $isSameDept = $user->profile->departments_id === $course->department_id;

        if (!$isOwner && !$isSameDept) {
            abort(403, 'You do not have permission to delete this material.');
        }

        if ($material->type === 'file' && $material->url_or_path) {
            Storage::disk('public')->delete($material->url_or_path);
        }

        $material->delete();

        return back()->with('success', 'Material deleted successfully.');
    }
}
