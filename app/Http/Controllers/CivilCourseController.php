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

        return view('civil_services.courses.index', compact('courses'));
    }

    /**
     * Store a new Civil Services course (no regulation needed).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:courses,code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'string', 'max:10'],
        ], [
            'code.required' => 'Course code is required (e.g., UPSC-GS1, CSAT-01).',
            'code.unique' => 'A course with this code already exists.',
            'name.required' => 'Course name is required.',
        ]);

        Course::create([
            'department_id' => 'dep_cs',
            'regulation_id' => null, // Regulation NOT required for Civil Services
            'code' => strtoupper(trim($validated['code'])),
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
            'code' => ['required', 'string', 'max:50', Rule::unique('courses', 'code')->ignore($course->id)],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'string', 'max:10'],
        ]);

        $course->update([
            'code' => strtoupper(trim($validated['code'])),
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
        $course = Course::where('department_id', 'dep_cs')->findOrFail($id);

        // Delete associated files from storage
        foreach ($course->materials as $mat) {
            if ($mat->type === 'file' && $mat->url_or_path) {
                Storage::disk('public')->delete($mat->url_or_path);
            }
        }

        $courseName = $course->name;
        $course->delete();

        return redirect()->route('civil.courses.index')
            ->with('success', "Course '{$courseName}' and its modules were deleted.");
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

        return redirect()->route('civil.courses.modules', $course->id)
            ->with('success', "Module '{$title}' removed successfully.");
    }
}
