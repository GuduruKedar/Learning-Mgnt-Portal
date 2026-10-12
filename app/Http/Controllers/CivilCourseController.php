<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CivilCourseController extends Controller
{
    /**
     * Display listing of Civil Services courses (without any regulation dependency).
     */
    public function index(Request $request)
    {
        $query = Course::where('department_id', 'dep_cs')
            ->withCount('materials');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('semester', $request->category);
        }

        $courses = $query->latest('id')->paginate(10)->withQueryString();
        $existingCourseCodes = Course::pluck('code')->map(fn($c) => strtoupper(trim($c)))->unique()->values();

        return view('civil_services.courses.index', compact('courses', 'existingCourseCodes'));
    }

    /**
     * Store a new Civil Services course (no regulation needed).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'string', 'max:10'],
        ], [
            'code.required' => 'Course code is required (e.g., UPSC-GS1, CSAT-01).',
            'name.required' => 'Course name is required.',
        ]);

        $courseCode = strtoupper(trim($validated['code']));
        if (Course::whereRaw('UPPER(TRIM(code)) = ?', [$courseCode])->exists()) {
            return back()->withInput()->withErrors([
                'code' => "A course with code '{$courseCode}' already exists.",
            ]);
        }

        Course::create([
            'department_id' => 'dep_cs',
            'regulation_id' => null, // Regulation NOT required for Civil Services
            'code' => $courseCode,
            'name' => trim($validated['name']),
            'year' => $validated['year'] ?? date('Y'),
            'semester' => $validated['category'] ?? 'General Studies',
        ]);

        return redirect()->route('civil.courses.index')
            ->with('success', "Course '{$validated['name']}' created successfully!");
    }

    /**
     * Update an existing Civil Services course.
     */
    public function update(Request $request, $id)
    {
        $course = Course::where('department_id', 'dep_cs')->findOrFail($id);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'string', 'max:10'],
        ]);

        $courseCode = strtoupper(trim($validated['code']));
        if (Course::where('id', '!=', $course->id)->whereRaw('UPPER(TRIM(code)) = ?', [$courseCode])->exists()) {
            return back()->withInput()->withErrors([
                'code' => "A course with code '{$courseCode}' already exists.",
            ]);
        }

        $course->update([
            'code' => $courseCode,
            'name' => trim($validated['name']),
            'year' => $validated['year'] ?? $course->year,
            'semester' => $validated['category'] ?? $course->semester,
        ]);

        return redirect()->route('civil.courses.index')
            ->with('success', "Course '{$course->name}' updated successfully!");
    }

    /**
     * Delete a Civil Services course and all its materials.
     */
    public function destroy($id)
    {
        $course = Course::with(['materials', 'staff'])->where('department_id', 'dep_cs')->findOrFail($id);

        $courseName = $course->name;
        $courseCode = $course->code;
        $materialsCount = $course->materials()->count();
        $enrollmentCount = \Illuminate\Support\Facades\DB::table('enrollments')->where('course_id', $course->id)->count();
        $staffCount = $course->staff()->count();

        \Illuminate\Support\Facades\DB::transaction(function () use ($course) {
            // Delete associated files from storage & records
            foreach ($course->materials as $mat) {
                if ($mat->type === 'file' && $mat->url_or_path) {
                    if (Storage::disk('public')->exists($mat->url_or_path)) {
                        Storage::disk('public')->delete($mat->url_or_path);
                    }
                }
                $mat->delete();
            }

            \Illuminate\Support\Facades\DB::table('enrollments')->where('course_id', $course->id)->delete();
            $course->staff()->detach();
            $course->delete();
        });

        \App\Services\ActivityLogger::log(
            'cascade_civil_course_deleted',
            'Civil Services Course & Modules Deleted',
            'Civil Services',
            "Permanently deleted Civil Services course {$courseName} ({$courseCode}). Cascaded removals: {$materialsCount} module(s), {$enrollmentCount} enrollment(s), and {$staffCount} instructor(s) detached.",
            'danger',
            [
                'department_id' => 'dep_cs',
                'entity_type'   => 'Course',
                'entity_id'     => $id,
                'entity_name'   => "{$courseCode} - {$courseName}",
                'payload'       => [
                    'course_code'         => $courseCode,
                    'course_name'         => $courseName,
                    'materials_deleted'   => $materialsCount,
                    'enrollments_removed' => $enrollmentCount,
                    'staff_detached'      => $staffCount,
                ]
            ]
        );

        return redirect()->route('civil.courses.index')
            ->with('success', "Course '{$courseCode} - {$courseName}' and all associated modules were deleted successfully.");
    }

    /**
     * Display modules/materials for a specific Civil Services course.
     */
    public function modules(Request $request, $course_id)
    {
        $course = Course::where('department_id', 'dep_cs')->findOrFail($course_id);

        $query = CourseMaterial::where('course_id', $course->id);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $materials = $query->latest()->paginate(15)->withQueryString();

        return view('civil_services.courses.modules', compact('course', 'materials'));
    }

    /**
     * Store a module/material for a Civil Services course.
     */
    public function storeModule(Request $request, $course_id)
    {
        $course = Course::where('department_id', 'dep_cs')->findOrFail($course_id);

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:file,link',
            'platform' => 'nullable|string|max:100',
            'url' => 'nullable|required_if:type,link|url',
            'file' => 'nullable|required_if:type,file|file|max:20480|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip',
        ], [
            'title.required' => 'Module / Material title is required.',
            'url.required_if' => 'Please provide a valid web URL.',
            'file.required_if' => 'Please select a document file to upload.',
            'file.max' => 'File size cannot exceed 20MB.',
        ]);

        $material = new CourseMaterial();
        $material->course_id = $course->id;
        $material->staff_id = Auth::id();
        $material->title = trim($request->title);
        $material->type = $request->type;
        $material->platform = $request->platform ?? 'Study Material';

        if ($request->type === 'link') {
            $material->url_or_path = $request->url;
        } else {
            $path = $request->file('file')->store('civil_courses/materials', 'public');
            $material->url_or_path = $path;
        }

        $material->save();

        \App\Services\ActivityLogger::log(
            'civil_module_uploaded',
            'Civil Services Module Uploaded',
            'Civil Services',
            "Uploaded study module \"{$material->title}\" for Civil Services course {$course->name} ({$course->code}).",
            'success',
            [
                'department_id' => 'dep_cs',
                'entity_type'   => 'CourseMaterial',
                'entity_id'     => $material->id,
                'entity_name'   => $material->title,
                'payload'       => ['course_code' => $course->code, 'title' => $material->title]
            ]
        );

        return redirect()->route('civil.courses.modules', $course->id)
            ->with('success', "Module '{$material->title}' uploaded successfully!");
    }

    /**
     * Delete a module/material.
     */
    public function destroyModule($course_id, $material_id)
    {
        $course = Course::where('department_id', 'dep_cs')->findOrFail($course_id);
        $material = CourseMaterial::where('course_id', $course->id)->findOrFail($material_id);

        if ($material->type === 'file' && $material->url_or_path) {
            Storage::disk('public')->delete($material->url_or_path);
        }

        $title = $material->title;
        $material->delete();

        \App\Services\ActivityLogger::log(
            'civil_module_deleted',
            'Civil Services Module Deleted',
            'Civil Services',
            "Deleted module \"{$title}\" from Civil Services course {$course->name} ({$course->code}).",
            'warning',
            [
                'department_id' => 'dep_cs',
                'entity_type'   => 'CourseMaterial',
                'entity_id'     => $material_id,
                'entity_name'   => $title,
                'payload'       => ['course_code' => $course->code, 'title' => $title]
            ]
        );

        return redirect()->route('civil.courses.modules', $course->id)
            ->with('success', "Module '{$title}' removed successfully.");
    }
}
