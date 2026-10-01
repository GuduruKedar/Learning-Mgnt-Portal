<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Enrolled Courses - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="w-full space-y-6">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">My Enrolled Courses</h2>
                        <p class="mt-1 text-sm text-gray-500">Access your study materials and course content.</p>
                    </div>
                    <a href="{{ route('student.enrollment.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Enroll in New Courses
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($courses->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-sm">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No enrolled courses</h3>
                        <p class="mt-2 text-sm text-gray-500">You are not currently enrolled in any courses.</p>
                        <div class="mt-6">
                            <a href="{{ route('student.enrollment.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Browse Courses
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($courses as $course)
                            <div class="bg-white rounded-2xl border border-gray-200/80 hover:border-indigo-300 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col justify-between">
                                <div>
                                    <!-- Top Accent Line -->
                                    <div class="h-1.5 {{ $course->department_id === 'dep_cs' ? 'bg-gradient-to-r from-blue-500 to-indigo-600' : 'bg-gradient-to-r from-indigo-500 to-purple-600' }}"></div>
                                    
                                    <div class="p-6">
                                        <!-- Header Pill & Badge -->
                                        <div class="flex items-start justify-between gap-2 mb-4">
                                            <div class="inline-flex p-2.5 rounded-xl {{ $course->department_id === 'dep_cs' ? 'bg-blue-50 text-blue-600' : 'bg-indigo-50 text-indigo-600' }}">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $course->department_id === 'dep_cs' ? 'bg-blue-100 text-blue-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                {{ $course->department_id === 'dep_cs' ? 'Civil Services' : 'Enrolled' }}
                                            </span>
                                        </div>

                                        <!-- Course Title & Code -->
                                        <h3 class="text-base font-bold text-gray-900 leading-snug mb-1.5 hover:text-indigo-600 transition-colors">
                                            {{ $course->name }}
                                        </h3>
                                        <div class="inline-block px-2.5 py-0.5 rounded bg-gray-100 text-gray-700 text-xs font-mono font-semibold mb-4">
                                            {{ $course->code }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="px-6 pb-6 pt-2">
                                    <div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-100">
                                        <a href="{{ route('student.courses.materials', $course->id) }}" class="inline-flex items-center justify-center py-2 px-3 bg-gray-50 hover:bg-gray-100 text-xs font-bold text-gray-700 rounded-xl border border-gray-200 transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            Materials
                                        </a>
                                        <a href="{{ route('student.assignments.index', ['course_id' => $course->id]) }}" class="inline-flex items-center justify-center py-2 px-3 bg-indigo-50 hover:bg-indigo-100 text-xs font-bold text-indigo-700 rounded-xl border border-indigo-200 transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            Assignments
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <footer class="mt-12 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <!-- Scripts -->
    <script>        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI(); }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>



