<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment - LMS Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center space-x-2">
                <a href="{{ route('staff.assignments.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">Assignments</a>
                <span class="text-gray-300">/</span>
                <span class="text-sm font-semibold text-indigo-700">Edit Assignment</span>
            </div>
            <div class="flex items-center">
                <a href="{{ route('staff.assignments.show', $assignment->id) }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Details
                </a>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-4xl mx-auto space-y-6">

                <!-- Alert Messages -->
                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <h4 class="text-sm font-bold text-red-800 mb-1">Please fix the following validation errors:</h4>
                    <ul class="text-xs text-red-700 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h1 class="text-xl font-bold text-gray-900">Edit Assignment: {{ $assignment->title }}</h1>
                        <p class="text-xs text-gray-500 mt-1">Update the instructions, due date, maximum marks, or attached reference document.</p>
                    </div>

                    <div class="p-6 sm:p-8">
                        <form action="{{ route('staff.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Target Course -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Assigned Course <span class="text-red-500">*</span></label>
                                    <select name="course_id" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                        @foreach($assignedCourses as $course)
                                            <option value="{{ $course->id }}" {{ (old('course_id', $assignment->course_id) == $course->id) ? 'selected' : '' }}>
                                                {{ $course->code }} - {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Title -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Assignment Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $assignment->title) }}" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Max Marks -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maximum Marks <span class="text-red-500">*</span></label>
                                    <input type="number" name="max_marks" value="{{ old('max_marks', $assignment->max_marks) }}" min="1" max="1000" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Due Date -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Due Date & Time <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="due_date" value="{{ old('due_date', $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '') }}" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description & Assignment Questions / Instructions</label>
                                    <textarea name="description" rows="5" class="w-full text-sm border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-sans leading-relaxed">{{ old('description', $assignment->description) }}</textarea>
                                </div>

                                <!-- Attachment -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Replace Reference File <span class="text-xs font-normal text-gray-500">(Optional)</span></label>
                                    <input type="file" name="attachment" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg p-1.5">
                                    @if($assignment->attachment_path)
                                        <p class="text-xs text-emerald-600 font-semibold mt-1">Current File: Attached</p>
                                    @endif
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                        <option value="published" {{ old('status', $assignment->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="draft" {{ old('status', $assignment->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="closed" {{ old('status', $assignment->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                <a href="{{ route('staff.assignments.show', $assignment->id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
                                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-colors">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
    </script>
</body>
</html>
