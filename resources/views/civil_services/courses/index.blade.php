<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Civil Services Courses & Modules - LMS</title>
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
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-2.5 ml-auto">
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Page Header & Action -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Civil Services Courses & Modules</h1>
                        <p class="text-sm text-gray-500 mt-1">Create courses and manage study modules directly for Civil Services aspirants.</p>
                    </div>
                    <button type="button" onclick="openAddCourseModal()" class="inline-flex items-center px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-xl shadow-md transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add New Course
                    </button>
                </div>

                <!-- Filters -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <form method="GET" action="{{ route('civil.courses.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search Courses</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by course code or title..." class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-700 text-white font-semibold text-sm rounded-lg hover:bg-blue-800 transition-colors">
                                Search
                            </button>
                            @if(request()->has('search'))
                            <a href="{{ route('civil.courses.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 font-semibold text-sm rounded-lg hover:bg-gray-200 transition-colors">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Course Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">Course Code</th>
                                    <th class="px-6 py-3.5 text-left">Course Name</th>
                                    <th class="px-6 py-3.5 text-left">Category / Stream</th>
                                    <th class="px-6 py-3.5 text-center">Modules / Materials</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse($courses as $course)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $course->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $course->name }}</div>
                                        <div class="text-xs text-gray-400">Department of Civil Services</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $course->semester ?? 'General Studies' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('civil.courses.modules', $course->id) }}" class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            {{ $course->materials_count }} Module{{ $course->materials_count == 1 ? '' : 's' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('civil.courses.modules', $course->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50/90 text-sky-600 border border-sky-200/70 hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Manage Modules">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <button type="button" onclick="openEditModal({{ $course->id }}, '{{ addslashes($course->code) }}', '{{ addslashes($course->name) }}', '{{ addslashes($course->semester ?? '') }}', '{{ addslashes($course->year ?? '') }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50/90 text-indigo-600 border border-indigo-200/70 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Edit Course">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <form method="POST" action="{{ route('civil.courses.destroy', $course->id) }}" onsubmit="return confirm('Delete course {{ $course->code }} and all its uploaded modules?');" class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50/90 text-rose-600 border border-rose-200/70 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Delete Course">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        No Civil Services courses created yet. Click "Add New Course" above to create one.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($courses->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $courses->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- Create Course Modal -->
    <div id="addCourseModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-900">Add Civil Services Course</h3>
                <button type="button" onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('civil.courses.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Course Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required placeholder="e.g. UPSC-GS1, CSAT-01, POL-101" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                    <p class="text-xs text-gray-400 mt-1">Unique course identifier.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Course Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Indian Polity & Governance" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Category / Stream</label>
                        <select name="category" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="General Studies">General Studies</option>
                            <option value="Prelims GS Paper 1">Prelims GS Paper 1</option>
                            <option value="CSAT (Prelims Paper 2)">CSAT (Prelims Paper 2)</option>
                            <option value="Mains GS Paper 1">Mains GS Paper 1</option>
                            <option value="Mains GS Paper 2">Mains GS Paper 2</option>
                            <option value="Mains GS Paper 3">Mains GS Paper 3</option>
                            <option value="Ethics & Integrity">Ethics & Integrity</option>
                            <option value="Essay Writing">Essay Writing</option>
                            <option value="Current Affairs">Current Affairs</option>
                            <option value="Optional Subject">Optional Subject</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Batch Year</label>
                        <input type="text" name="year" value="{{ date('Y') }}" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow transition-colors">
                        Save Course
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div id="editCourseModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-900">Edit Course</h3>
                <button type="button" onclick="document.getElementById('editCourseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="editCourseForm" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Course Code <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_code" name="code" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Course Name <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Category / Stream</label>
                        <input type="text" id="edit_category" name="category" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Batch Year</label>
                        <input type="text" id="edit_year" name="year" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('editCourseModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow transition-colors">
                        Update Course
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddCourseModal() {
            document.getElementById('addCourseModal').classList.remove('hidden');
        }

        function openEditModal(id, code, name, category, year) {
            const form = document.getElementById('editCourseForm');
            form.action = `/civil-services/courses/${id}`;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_year').value = year;
            document.getElementById('editCourseModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
