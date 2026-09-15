<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Materials - LMS</title>
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
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-10 shrink-0">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 mr-4 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Back to Dashboard
                </a>
            </div>
            <div class="flex items-center">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                    @if(Auth::user()->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->first_name ?? 'S', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Staff' }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="profileDropdownMenu" class="absolute -right-2 sm:right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 py-1 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Edit Profile</a>
                        <button type="button" id="openPasswordModalBtn" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Change Password</button>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $course->name }} ({{ $course->code }})</h1>
                        <p class="text-sm text-gray-500 mt-1">Manage learning materials for this course</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('staff.assignments.index', ['course_id' => $course->id]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Course Assignments
                        </a>
                        <a href="{{ route('staff.assignments.create', ['course_id' => $course->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-xs font-bold text-white rounded-lg shadow-sm hover:bg-indigo-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Create Assignment
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center mb-6 shadow-sm">
                        <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Upload/Add Forms -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Add Material Tabbed Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="flex border-b border-gray-100">
                                <button type="button" id="tab-link-btn" class="flex-1 py-3 px-4 text-sm font-medium text-center border-b-2 border-indigo-600 text-indigo-600 focus:outline-none transition-colors bg-indigo-50/30">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        Add Link
                                    </div>
                                </button>
                                <button type="button" id="tab-file-btn" class="flex-1 py-3 px-4 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-colors hover:bg-gray-50">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        Upload File
                                    </div>
                                </button>
                            </div>

                            <div class="p-5">
                                <!-- Add Link Form -->
                                <div id="tab-link-content" class="block">
                                    <form action="{{ route('staff.courses.materials.store', $course->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="link">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                                <input type="text" name="title" value="{{ old('type') === 'link' ? old('title') : '' }}" placeholder="e.g., Lecture 1 Recording" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 @error('title') border-red-500 @enderror" required>
                                                @if(old('type') === 'link')
                                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                                                <select name="platform" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 @error('platform') border-red-500 @enderror" required>
                                                    <option value="">Select Platform</option>
                                                    <option value="youtube" {{ old('type') === 'link' && old('platform') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                                    <option value="drive" {{ old('type') === 'link' && old('platform') == 'drive' ? 'selected' : '' }}>Google Drive</option>
                                                    <option value="onedrive" {{ old('type') === 'link' && old('platform') == 'onedrive' ? 'selected' : '' }}>OneDrive</option>
                                                </select>
                                                @if(old('type') === 'link')
                                                    @error('platform') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                                <input type="url" name="url" value="{{ old('type') === 'link' ? old('url') : '' }}" placeholder="https://..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 @error('url') border-red-500 @enderror" required>
                                                @if(old('type') === 'link')
                                                    @error('url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                                @endif
                                            </div>
                                            <div class="pt-2">
                                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors">
                                                    Add Link
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Upload File Form -->
                                <div id="tab-file-content" class="hidden">
                                    <form action="{{ route('staff.courses.materials.store', $course->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="type" value="file">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                                <input type="text" name="title" value="{{ old('type') === 'file' ? old('title') : '' }}" placeholder="e.g., Unit 1 Notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 @error('title') border-red-500 @enderror" required>
                                                @if(old('type') === 'file')
                                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">File Type</label>
                                                <select name="platform" id="file-type-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 @error('platform') border-red-500 @enderror" required>
                                                    <option value="">Select File Type</option>
                                                    <option value="pdf" {{ old('type') === 'file' && old('platform') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                                    <option value="word" {{ old('type') === 'file' && old('platform') == 'word' ? 'selected' : '' }}>Word Document</option>
                                                    <option value="excel" {{ old('type') === 'file' && old('platform') == 'excel' ? 'selected' : '' }}>Excel Spreadsheet</option>
                                                    <option value="ppt" {{ old('type') === 'file' && old('platform') == 'ppt' ? 'selected' : '' }}>PowerPoint</option>
                                                </select>
                                                @if(old('type') === 'file')
                                                    @error('platform') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Choose File <span class="text-xs text-gray-500 font-normal">(Max 10MB)</span></label>
                                                <div class="flex items-center space-x-3">
                                                    <div id="file-icon-preview" class="hidden h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 border border-indigo-100">
                                                    </div>
                                                    <input type="file" name="file" id="file-upload-input" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('file') border-red-500 @enderror" required>
                                                </div>
                                                @if(old('type') === 'file')
                                                    @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                                @endif
                                            </div>
                                            <div class="pt-2">
                                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors">
                                                    Upload File
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Materials List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <h3 class="text-lg font-semibold text-gray-800">Course Materials</h3>
                                
                                <!-- Search Filter -->
                                <form action="{{ route('staff.courses.materials', $course->id) }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title..." class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <select name="type" class="block w-full sm:w-32 py-2 px-3 border border-gray-300 bg-white rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Types</option>
                                        <option value="link" {{ request('type') === 'link' ? 'selected' : '' }}>Links</option>
                                        <option value="file" {{ request('type') === 'file' ? 'selected' : '' }}>Files</option>
                                    </select>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        Filter
                                    </button>
                                    @if(request('search') || request('type'))
                                        <a href="{{ route('staff.courses.materials', $course->id) }}" class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                            Clear
                                        </a>
                                    @endif
                                </form>
                            </div>
                            <div class="p-6 bg-gray-50/50 flex-1 overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-5">
                                    @forelse($materials as $material)
                                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all flex flex-col group h-full">
                                        <div class="p-5 flex-1 relative">
                                            @if($material->staff_id === Auth::id())
                                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <form action="{{ route('staff.courses.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this material?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-white border border-red-100 text-red-500 hover:text-white hover:bg-red-500 p-1.5 rounded-full shadow-sm transition-all" title="Delete Material">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                            @endif
                                            
                                            <div class="flex items-start space-x-4">
                                                <!-- Icon based on type/platform -->
                                                <div class="flex-shrink-0">
                                                    @if($material->platform === 'youtube')
                                                        <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center text-red-600 shadow-inner">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                    @elseif($material->platform === 'drive' || $material->platform === 'onedrive')
                                                        <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-inner">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                                        </div>
                                                    @elseif($material->platform === 'pdf')
                                                        <div class="h-12 w-12 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-500 shadow-sm">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @elseif($material->platform === 'word')
                                                        <div class="h-12 w-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        </div>
                                                    @elseif($material->platform === 'excel')
                                                        <div class="h-12 w-12 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center text-green-600 shadow-sm">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @elseif($material->platform === 'ppt')
                                                        <div class="h-12 w-12 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-500 shadow-sm">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                        </div>
                                                    @else
                                                        <div class="h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 shadow-sm border border-gray-200">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0 pr-8">
                                                    <h4 class="text-base font-bold text-gray-900 truncate" title="{{ $material->title }}">{{ $material->title }}</h4>
                                                    <div class="flex items-center text-xs text-gray-500 mt-1 capitalize space-x-2">
                                                        <span class="bg-gray-100 px-2 py-0.5 rounded-full font-medium text-gray-600">{{ $material->platform }}</span>
                                                        <span>•</span>
                                                        <span>{{ $material->created_at->format('M d, Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-4 pt-4 border-t border-gray-100">
                                                <div class="flex items-center space-x-2">
                                                    @if($material->staff && $material->staff->profile && $material->staff->profile->photo)
                                                        <img src="{{ asset('storage/' . $material->staff->profile->photo) }}" class="w-6 h-6 rounded-full object-cover">
                                                    @else
                                                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">
                                                            {{ substr($material->staff->profile->first_name ?? 'S', 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <span class="text-xs text-gray-500">Added by <span class="font-medium text-gray-700">{{ $material->staff->profile->first_name ?? 'Staff' }}</span></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-50/80 px-5 py-3 border-t border-gray-100 mt-auto">
                                            @if($material->type === 'link')
                                                <a href="{{ $material->url_or_path }}" target="_blank" class="flex items-center justify-center w-full text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-md transition-colors">
                                                    Open Link <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/' . $material->url_or_path) }}" download="{{ Str::slug($material->title) }}.{{ pathinfo($material->url_or_path, PATHINFO_EXTENSION) }}" target="_blank" class="flex items-center justify-center w-full text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-md transition-colors">
                                                    Download File <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-span-full py-16 px-6 text-center bg-white border border-gray-100 rounded-xl shadow-sm">
                                        <div class="mx-auto w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No Materials Found</h3>
                                        <p class="text-sm text-gray-500">There are currently no learning materials added to this course.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="p-4 border-t border-gray-100">
                                {{ $materials->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
            </footer>
        </main>
    </div>

<!-- Change Password Modal -->
    <div id="passwordModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50">
                <h3 class="text-lg font-bold text-indigo-900">Change Password</h3>
                <button type="button" id="closePasswordModal" class="text-indigo-400 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" placeholder="Enter new password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Tab switching logic for material upload forms
        const tabLinkBtn = document.getElementById('tab-link-btn');
        const tabFileBtn = document.getElementById('tab-file-btn');
        const tabLinkContent = document.getElementById('tab-link-content');
        const tabFileContent = document.getElementById('tab-file-content');

        if(tabLinkBtn && tabFileBtn) {
            tabLinkBtn.addEventListener('click', () => {
                tabLinkBtn.classList.add('border-indigo-600', 'text-indigo-600', 'bg-indigo-50/30');
                tabLinkBtn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'hover:bg-gray-50');
                
                tabFileBtn.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-indigo-50/30');
                tabFileBtn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'hover:bg-gray-50');

                tabFileContent.classList.add('hidden');
                tabFileContent.classList.remove('block');
                
                tabLinkContent.classList.add('block');
                tabLinkContent.classList.remove('hidden');
            });

            tabFileBtn.addEventListener('click', () => {
                tabFileBtn.classList.add('border-indigo-600', 'text-indigo-600', 'bg-indigo-50/30');
                tabFileBtn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'hover:bg-gray-50');
                
                tabLinkBtn.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-indigo-50/30');
                tabLinkBtn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'hover:bg-gray-50');

                tabLinkContent.classList.add('hidden');
                tabLinkContent.classList.remove('block');
                
                tabFileContent.classList.add('block');
                tabFileContent.classList.remove('hidden');
            });
            
            @if(old('type') === 'file')
                tabFileBtn.click();
            @endif
        }
        // File type detection and icon preview
        const fileInput = document.getElementById('file-upload-input');
        const fileTypeSelect = document.getElementById('file-type-select');
        const fileIconPreview = document.getElementById('file-icon-preview');

        if (fileInput && fileTypeSelect && fileIconPreview) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    fileIconPreview.innerHTML = '';
                    fileIconPreview.classList.add('hidden');
                    fileIconPreview.classList.remove('flex');
                    return;
                }

                const filename = file.name.toLowerCase();
                let type = '';
                let iconSvg = '';

                if (filename.endsWith('.pdf')) {
                    type = 'pdf';
                    iconSvg = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                } else if (filename.endsWith('.doc') || filename.endsWith('.docx')) {
                    type = 'word';
                    iconSvg = '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                } else if (filename.endsWith('.xls') || filename.endsWith('.xlsx')) {
                    type = 'excel';
                    iconSvg = '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>';
                } else if (filename.endsWith('.ppt') || filename.endsWith('.pptx')) {
                    type = 'ppt';
                    iconSvg = '<svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>';
                }

                if (type) {
                    fileTypeSelect.value = type;
                }
                
                if (iconSvg) {
                    fileIconPreview.innerHTML = iconSvg;
                } else {
                    fileIconPreview.innerHTML = '<svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                }
                fileIconPreview.classList.remove('hidden');
                fileIconPreview.classList.add('flex');
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>



