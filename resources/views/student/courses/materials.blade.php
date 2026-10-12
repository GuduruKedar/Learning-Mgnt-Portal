<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Materials - {{ $course->name }} - LMS</title>
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
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $course->name }}</h1>
                        @if($course->department_id === 'dep_cs')
                            <p class="text-sm text-blue-600 font-medium mt-1">{{ $course->code }} &bull; {{ $course->semester ?? 'General Studies' }} &bull; Civil Services Academy</p>
                        @else
                            <p class="text-sm text-gray-500 mt-1">{{ $course->code }} | Year {{ $course->year }}, Sem {{ $course->semester }}</p>
                        @endif
                    </div>
                </div>

                @php
                    $subfolderCategories = [
                        'Course objective and outcomes' => [
                            'name' => 'Course Objective & Outcomes (CO/PO)',
                            'badge' => 'Course Objectives & Outcomes',
                            'short_name' => 'Objective & Outcomes',
                            'description' => 'Course learning objectives, expected student outcomes, and mapping matrix',
                            'color' => 'blue',
                            'border_accent' => 'border-blue-200/90',
                            'header_gradient' => 'from-blue-50/90 via-blue-50/40 to-white',
                            'icon_bg' => 'bg-blue-100 text-blue-700 border border-blue-200',
                            'badge_class' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'icon' => '🎯',
                        ],
                        'Syllabus document' => [
                            'name' => 'Syllabus Document & Curriculum Guide',
                            'badge' => 'Syllabus Document',
                            'short_name' => 'Syllabus Document',
                            'description' => 'Official curriculum syllabus document, unit distribution, textbooks, and grading policy',
                            'color' => 'purple',
                            'border_accent' => 'border-purple-200/90',
                            'header_gradient' => 'from-purple-50/90 via-purple-50/40 to-white',
                            'icon_bg' => 'bg-purple-100 text-purple-700 border border-purple-200',
                            'badge_class' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'icon' => '📜',
                        ],
                        'PPTs' => [
                            'name' => 'PowerPoint Presentations (PPTs / Slides)',
                            'badge' => 'PPTs / Slides',
                            'short_name' => 'PPTs',
                            'description' => 'Classroom lecture presentation decks, slides, and animated visual aids',
                            'color' => 'amber',
                            'border_accent' => 'border-amber-200/90',
                            'header_gradient' => 'from-amber-50/90 via-amber-50/40 to-white',
                            'icon_bg' => 'bg-amber-100 text-amber-700 border border-amber-200',
                            'badge_class' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'icon' => '📊',
                        ],
                        'Lecture notes' => [
                            'name' => 'Lecture Notes & Study Material',
                            'badge' => 'Lecture Notes',
                            'short_name' => 'Lecture Notes',
                            'description' => 'Comprehensive class reading notes, module summaries, and study handouts',
                            'color' => 'emerald',
                            'border_accent' => 'border-emerald-200/90',
                            'header_gradient' => 'from-emerald-50/90 via-emerald-50/40 to-white',
                            'icon_bg' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                            'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'icon' => '📝',
                        ],
                        'Lab manual' => [
                            'name' => 'Lab Manual & Practical Guides',
                            'badge' => 'Lab Manual',
                            'short_name' => 'Lab Manual',
                            'description' => 'Laboratory instructions, experiment procedures, code guides, and viva reference sheets',
                            'color' => 'teal',
                            'border_accent' => 'border-teal-200/90',
                            'header_gradient' => 'from-teal-50/90 via-teal-50/40 to-white',
                            'icon_bg' => 'bg-teal-100 text-teal-700 border border-teal-200',
                            'badge_class' => 'bg-teal-100 text-teal-800 border-teal-200',
                            'icon' => '🧪',
                        ],
                        'Assigment Questions' => [
                            'name' => 'Assignment Questions',
                            'badge' => 'Assignment Questions',
                            'short_name' => 'Assigment Questions',
                            'description' => 'Homework tasks, practice assignments, problem sets, and case study briefs',
                            'color' => 'indigo',
                            'border_accent' => 'border-indigo-200/90',
                            'header_gradient' => 'from-indigo-50/90 via-indigo-50/40 to-white',
                            'icon_bg' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
                            'badge_class' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            'icon' => '📋',
                        ],
                        'Question Bank' => [
                            'name' => 'Question Bank & Solved Problems',
                            'badge' => 'Question Bank',
                            'short_name' => 'Question Bank',
                            'description' => 'Unit-wise question pools, model answers, 2-mark & 16-mark compilations',
                            'color' => 'violet',
                            'border_accent' => 'border-violet-200/90',
                            'header_gradient' => 'from-violet-50/90 via-violet-50/40 to-white',
                            'icon_bg' => 'bg-violet-100 text-violet-700 border border-violet-200',
                            'badge_class' => 'bg-violet-100 text-violet-800 border-violet-200',
                            'icon' => '🗂️',
                        ],
                        'Gate Questions' => [
                            'name' => 'GATE Questions & Competitive Prep',
                            'badge' => 'GATE Questions',
                            'short_name' => 'Gate Questions',
                            'description' => 'GATE examination previous year questions, solutions, and competitive mock drills',
                            'color' => 'orange',
                            'border_accent' => 'border-orange-200/90',
                            'header_gradient' => 'from-orange-50/90 via-orange-50/40 to-white',
                            'icon_bg' => 'bg-orange-100 text-orange-700 border border-orange-200',
                            'badge_class' => 'bg-orange-100 text-orange-800 border-orange-200',
                            'icon' => '🎓',
                        ],
                        'Previous Question Papers' => [
                            'name' => 'Previous Question Papers (QP)',
                            'badge' => 'Previous Question Papers',
                            'short_name' => 'Previous Question Papers',
                            'description' => 'University semester end examination papers, mid-term tests, and archival papers',
                            'color' => 'cyan',
                            'border_accent' => 'border-cyan-200/90',
                            'header_gradient' => 'from-cyan-50/90 via-cyan-50/40 to-white',
                            'icon_bg' => 'bg-cyan-100 text-cyan-700 border border-cyan-200',
                            'badge_class' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                            'icon' => '📑',
                        ],
                        'Videos' => [
                            'name' => 'Recorded Lectures & Videos',
                            'badge' => 'Videos',
                            'short_name' => 'Videos',
                            'description' => 'Video recordings, YouTube playlist links, webinar clips, and visual demonstrations',
                            'color' => 'red',
                            'border_accent' => 'border-red-200/90',
                            'header_gradient' => 'from-red-50/90 via-red-50/40 to-white',
                            'icon_bg' => 'bg-red-100 text-red-700 border border-red-200',
                            'badge_class' => 'bg-red-100 text-red-800 border-red-200',
                            'icon' => '🎥',
                        ],
                        'Additional material' => [
                            'name' => 'Additional Material & References',
                            'badge' => 'Additional Material',
                            'short_name' => 'Additional Material',
                            'description' => 'Web links, cloud drives, reference documents, journal articles, and extra notes',
                            'color' => 'slate',
                            'border_accent' => 'border-slate-200/90',
                            'header_gradient' => 'from-slate-50/90 via-slate-50/40 to-white',
                            'icon_bg' => 'bg-slate-100 text-slate-700 border border-slate-200',
                            'badge_class' => 'bg-slate-100 text-slate-800 border-slate-200',
                            'icon' => '📁',
                        ],
                    ];

                    $groupedMaterials = [];
                    foreach ($subfolderCategories as $k => $c) {
                        $groupedMaterials[$k] = collect();
                    }
                    foreach ($materials as $mat) {
                        $cat = $mat->effective_division;
                        if (!isset($groupedMaterials[$cat])) {
                            $groupedMaterials['Additional material']->push($mat);
                        } else {
                            $groupedMaterials[$cat]->push($mat);
                        }
                    }
                    $totalMaterialsCount = $materials->count();
                @endphp

                <!-- Materials List -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/90 to-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-2.5">
                            <h3 class="text-lg font-bold text-gray-900 tracking-tight">Study Materials Directory</h3>
                            <span id="studentMaterialsCountBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $totalMaterialsCount }} Total
                            </span>
                        </div>
                        
                        <!-- Action Bar: Live Search & View Mode Separation -->
                        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                            <!-- Search Input -->
                            <div class="relative w-full sm:w-56">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="studentMaterialsSearch" oninput="filterStudentMaterialsLive()" placeholder="Search title or division..." value="{{ request('search') }}" class="block w-full pl-8 pr-7 py-2 border border-gray-300 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <button type="button" id="studentSearchClearBtn" onclick="clearStudentSearch()" class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-400 hover:text-gray-600 hidden cursor-pointer" title="Clear">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>




                        </div>
                    </div>

                    <!-- Sub-Folder Navigation Bar (Pill Tabs) -->
                    <div class="px-5 sm:px-6 py-3 bg-slate-50/80 border-b border-gray-200/80 flex items-center justify-between gap-3 overflow-x-auto no-scrollbar">
                        <div class="flex items-center gap-2 shrink-0" id="studentSubfolderTabsContainer">
                            <button type="button" 
                                onclick="selectStudentSubfolder('all')" 
                                id="student-tab-all"
                                data-folder-target="all"
                                class="student-folder-tab inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-2xs cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                <span>All Divisions</span>
                                <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white">{{ $totalMaterialsCount }}</span>
                            </button>

                            @foreach($subfolderCategories as $catKey => $catMeta)
                                @php 
                                    $catCount = $groupedMaterials[$catKey]->count(); 
                                    $catSlug = Str::slug($catKey);
                                @endphp
                                @if($catCount > 0)
                                <button type="button" 
                                    onclick="selectStudentSubfolder('{{ $catSlug }}')" 
                                    id="student-tab-{{ $catSlug }}"
                                    data-folder-target="{{ $catSlug }}"
                                    class="student-folder-tab inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 hover:text-gray-900 bg-white border border-gray-200 hover:border-gray-300 transition-all shadow-2xs cursor-pointer">
                                    <span>{{ $catMeta['icon'] }}</span>
                                    <span>{{ $catMeta['short_name'] }}</span>
                                    <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">{{ $catCount }}</span>
                                </button>
                                @endif
                            @endforeach
                        </div>

                        <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap">
                            <!-- Cards vs Table Layout Switcher -->
                            <div class="inline-flex p-1 bg-white rounded-xl border border-gray-200 shadow-2xs">
                                <button type="button" onclick="setStudentMaterialsLayout('cards')" id="student-mat-btn-cards" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 text-indigo-700 border border-indigo-200 cursor-pointer flex items-center gap-1.5" title="Cards View">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    <span>Cards</span>
                                </button>
                                <button type="button" onclick="setStudentMaterialsLayout('table')" id="student-mat-btn-table" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5" title="Table View">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <span>Table</span>
                                </button>
                            </div>

                            <button type="button" onclick="toggleAllStudentSubfolders()" id="studentToggleAllBtn" class="text-xs font-bold text-gray-500 hover:text-indigo-600 px-3 py-1.5 rounded-xl hover:bg-white border border-transparent hover:border-gray-200 transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs">
                                <span id="studentToggleAllText">Collapse All</span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" id="studentToggleAllIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6 bg-gray-50/50 space-y-6">
                        @if($totalMaterialsCount === 0)
                            <div class="py-16 px-6 text-center bg-white border border-gray-100 rounded-2xl shadow-sm">
                                <div class="mx-auto w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">No Materials Found</h3>
                                <p class="text-sm text-gray-500">There are currently no learning materials added to this course.</p>
                            </div>
                        @else
                            <!-- Layout 1: Segregated Sub-Folder Sections (Cards View) -->
                            <div id="studentMaterialsCardsWrap" class="space-y-6">
                                {{-- Segregated Sub-Folder Sections --}}
                                @foreach($subfolderCategories as $catKey => $catMeta)
                                @php 
                                    $catItems = $groupedMaterials[$catKey]; 
                                    $catSlug = Str::slug($catKey);
                                @endphp
                                @if($catItems->count() > 0)
                                <div class="student-subfolder-section bg-white rounded-2xl border {{ $catMeta['border_accent'] }} shadow-2xs overflow-hidden transition-all duration-200"
                                     id="student-section-{{ $catSlug }}"
                                     data-folder-key="{{ $catSlug }}">
                                    
                                    <!-- Subfolder Header Banner -->
                                    <div onclick="toggleStudentSubfolder('{{ $catSlug }}')" 
                                         class="px-5 py-4 bg-gradient-to-r {{ $catMeta['header_gradient'] }} border-b border-gray-100 flex items-center justify-between cursor-pointer select-none group">
                                        <div class="flex items-center gap-3.5 min-w-0">
                                            <div class="w-10 h-10 rounded-xl {{ $catMeta['icon_bg'] }} flex items-center justify-center shadow-2xs shrink-0 group-hover:scale-105 transition-transform text-lg">
                                                <span>{{ $catMeta['icon'] }}</span>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h4 class="text-sm sm:text-base font-extrabold text-gray-900 tracking-tight truncate">{{ $catMeta['name'] }}</h4>
                                                    <span class="student-folder-count-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $catMeta['badge_class'] }} transition-all">
                                                        {{ $catItems->count() }} {{ $catItems->count() === 1 ? 'Material' : 'Materials' }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $catMeta['description'] }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2.5 shrink-0 ml-3">
                                            <span class="student-folder-toggle-text text-xs font-semibold text-gray-400 hidden md:inline">Collapse</span>
                                            <div class="student-folder-chevron w-7 h-7 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:border-indigo-300 group-hover:text-indigo-600 transition-all duration-200 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subfolder Content Grid -->
                                    <div class="student-subfolder-body p-5 sm:p-6 bg-slate-50/30">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                                            @foreach($catItems as $material)
                                            <div class="student-material-card bg-white rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-indigo-200 transition-all flex flex-col group h-full justify-between overflow-hidden"
                                                 data-title="{{ strtolower($material->title) }}"
                                                 data-platform="{{ strtolower($material->platform) }}"
                                                 data-division="{{ strtolower($material->effective_division) }}"
                                                 data-folder="{{ $catSlug }}">
                                                <div class="p-5 flex-1 relative flex flex-col justify-between">
                                                    <div>
                                                        @php
                                                            $matOpenUrl = $material->type === 'link' ? $material->url_or_path : route('materials.view', $material->id);
                                                        @endphp
                                                        <div class="flex items-center justify-between gap-3 mb-3.5">
                                                            <div class="flex items-center gap-2.5 min-w-0">
                                                                <a href="{{ $matOpenUrl }}" target="_blank" class="shrink-0 group/icon block" title="Open {{ $material->title }}">
                                                                    @if($material->platform === 'youtube')
                                                                        <div class="h-10 w-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'drive' || $material->platform === 'onedrive')
                                                                        <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'pdf')
                                                                        <div class="h-10 w-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-500 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'word')
                                                                        <div class="h-10 w-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'excel')
                                                                        <div class="h-10 w-10 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center text-green-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'ppt')
                                                                        <div class="h-10 w-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-500 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'txt')
                                                                        <div class="h-10 w-10 rounded-xl bg-cyan-50 border border-cyan-100 flex items-center justify-center text-cyan-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                        </div>
                                                                    @else
                                                                        <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 shadow-2xs border border-gray-200 group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold text-slate-700 bg-slate-100 border border-slate-200 capitalize">
                                                                    {{ $material->platform ?: $material->type }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <a href="{{ $matOpenUrl }}" target="_blank" class="text-[15px] font-bold text-gray-900 leading-snug break-words tracking-tight hover:text-indigo-600 hover:underline transition-colors block" title="Open {{ $material->title }}">
                                                            {{ $material->title }}
                                                        </a>
                                                        
                                                        <div class="mt-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-700">
                                                            <span>{{ $catMeta['icon'] }}</span>
                                                            <span>{{ $material->effective_division }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="bg-gray-50/80 px-4 py-2.5 border-t border-gray-100 mt-auto">
                                                    @if($material->type === 'link')
                                                        <a href="{{ $material->url_or_path }}" target="_blank" class="flex items-center justify-center w-full text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors gap-1.5">
                                                            Open Link <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                        </a>
                                                    @else
                                                        <div class="flex items-center justify-between w-full gap-2">
                                                            <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors gap-1">
                                                                View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            </a>
                                                            <div class="w-px h-3.5 bg-gray-200"></div>
                                                            <a href="{{ route('materials.download', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors gap-1">
                                                                Download <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Layout 2: Structured Tabular Directory (Table View) -->
                            <div id="studentMaterialsTableWrap" class="hidden overflow-hidden bg-white rounded-2xl border border-gray-200 shadow-2xs">
                                <div class="overflow-x-auto">
                                    <table class="w-full divide-y divide-gray-200 text-left">
                                        <thead class="bg-gray-50/90 text-[11px] font-bold text-gray-600 uppercase tracking-wider">
                                            <tr>
                                                <th class="px-5 py-3.5">Material Title & Format</th>
                                                <th class="px-5 py-3.5">Division / Section</th>
                                                <th class="px-5 py-3.5">Type</th>
                                                <th class="px-5 py-3.5">Uploaded By</th>
                                                <th class="px-5 py-3.5">Date</th>
                                                <th class="px-5 py-3.5 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white text-xs">
                                            @foreach($materials as $material)
                                                @php
                                                    $rawExt = strtolower(pathinfo($material->url_or_path, PATHINFO_EXTENSION));
                                                    $matCatKey = $material->effective_division;
                                                    $matMeta = $subfolderCategories[$matCatKey] ?? $subfolderCategories['Additional material'];
                                                    $matSlug = Str::slug($matCatKey);
                                                @endphp
                                                <tr class="hover:bg-indigo-50/40 transition-colors student-material-table-row"
                                                    data-title="{{ strtolower($material->title) }}"
                                                    data-platform="{{ strtolower($material->platform) }}"
                                                    data-division="{{ strtolower($material->effective_division) }}"
                                                    data-folder="{{ $matSlug }}">
                                                    <td class="px-5 py-3.5">
                                                        <div class="flex items-center gap-3">
                                                            <div class="shrink-0">
                                                                @if($material->platform === 'youtube')
                                                                    <div class="h-8 w-8 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center text-red-600">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                    </div>
                                                                @elseif($material->platform === 'pdf')
                                                                    <div class="h-8 w-8 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                    </div>
                                                                @elseif(in_array($rawExt, ['ppt','pptx']))
                                                                    <div class="h-8 w-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                                                    </div>
                                                                @else
                                                                    <div class="h-8 w-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="min-w-0">
                                                                <a href="{{ $material->type === 'link' ? $material->url_or_path : route('materials.view', $material->id) }}" target="_blank" class="font-bold text-gray-900 hover:text-indigo-600 hover:underline transition-colors break-words block" title="Open {{ $material->title }}">
                                                                    {{ $material->title }}
                                                                </a>
                                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-slate-100 text-slate-700">
                                                                        {{ $rawExt ? '.' . $rawExt : ($material->platform ?: $material->type) }}
                                                                    </span>
                                                                    <span class="text-[10px] text-gray-400 capitalize">{{ $material->platform ?: $material->type }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-extrabold {{ $matMeta['badge_class'] }} border">
                                                            <span>{{ $matMeta['icon'] }}</span>
                                                            <span>{{ $matMeta['short_name'] }}</span>
                                                        </span>
                                                    </td>
                                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                                        @if($material->type === 'link')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                                🔗 Web Link
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                📄 File
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3.5 whitespace-nowrap text-gray-600">
                                                        <span class="font-medium text-gray-800">{{ $material->staff->profile->first_name ?? 'Faculty' }}</span>
                                                    </td>
                                                    <td class="px-5 py-3.5 whitespace-nowrap text-gray-500 font-medium">
                                                        {{ $material->created_at ? $material->created_at->format('M d, Y') : '&mdash;' }}
                                                    </td>
                                                    <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                                        <div class="flex items-center justify-center gap-1.5">
                                                            @if($material->type === 'link')
                                                                <a href="{{ $material->url_or_path }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-2xs">
                                                                    <span>Open Link</span>
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-2xs">
                                                                    <span>View</span>
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                                </a>
                                                                <a href="{{ route('materials.download', $material->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-colors">
                                                                    <span>Download</span>
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- No Match State for Search / Folder Filter -->
                        <div id="studentNoMatchBox" class="py-14 px-6 text-center bg-white border border-dashed border-gray-200 rounded-2xl hidden">
                            <div class="mx-auto w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900">No Matching Learning Materials</h3>
                            <p class="text-xs text-gray-500 mt-1">No learning materials match your current search query or selected sub-folder.</p>
                            <button type="button" onclick="clearStudentSearch()" class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">
                                <span>Reset Search</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700">Technology Development (TD)</span>.</p>
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

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        let activeStudentSubfolder = 'all';

        window.selectStudentSubfolder = function(folderKey) {
            activeStudentSubfolder = folderKey;

            document.querySelectorAll('.student-folder-tab').forEach(btn => {
                const target = btn.getAttribute('data-folder-target');
                const badge = btn.querySelector('.tab-badge');
                if (target === folderKey) {
                    btn.className = 'student-folder-tab inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white';
                } else {
                    btn.className = 'student-folder-tab inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 hover:text-gray-900 bg-white border border-gray-200 hover:border-gray-300 transition-all shadow-2xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700';
                }
            });

            // Automatically open / expand the selected section directly!
            if (folderKey !== 'all') {
                const section = document.getElementById('student-section-' + folderKey);
                if (section) {
                    const body = section.querySelector('.student-subfolder-body');
                    const chevron = section.querySelector('.student-folder-chevron');
                    const toggleText = section.querySelector('.student-folder-toggle-text');
                    if (body) body.classList.remove('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                    if (toggleText) toggleText.textContent = 'Collapse';
                }
                studentAllExpanded = true;
                const btnText = document.getElementById('studentToggleAllText');
                const btnIcon = document.getElementById('studentToggleAllIcon');
                if (btnText) btnText.textContent = 'Collapse All';
                if (btnIcon) btnIcon.classList.remove('rotate-180');
            } else {
                studentAllExpanded = true;
                const btnText = document.getElementById('studentToggleAllText');
                const btnIcon = document.getElementById('studentToggleAllIcon');
                if (btnText) btnText.textContent = 'Collapse All';
                if (btnIcon) btnIcon.classList.remove('rotate-180');
                document.querySelectorAll('.student-subfolder-section').forEach(sec => {
                    const body = sec.querySelector('.student-subfolder-body');
                    const chevron = sec.querySelector('.student-folder-chevron');
                    const toggleText = sec.querySelector('.student-folder-toggle-text');
                    if (body) body.classList.remove('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                    if (toggleText) toggleText.textContent = 'Collapse';
                });
            }

            window.filterStudentMaterialsLive();
        };

        window.toggleStudentSubfolder = function(folderKey) {
            const section = document.getElementById('student-section-' + folderKey);
            if (!section) return;
            const body = section.querySelector('.student-subfolder-body');
            const chevron = section.querySelector('.student-folder-chevron');
            const toggleText = section.querySelector('.student-folder-toggle-text');

            if (body.classList.contains('hidden')) {
                body.classList.remove('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
                if (toggleText) toggleText.textContent = 'Collapse';
            } else {
                body.classList.add('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                if (toggleText) toggleText.textContent = 'Expand';
            }
        };

        let studentAllExpanded = true;
        window.toggleAllStudentSubfolders = function() {
            studentAllExpanded = !studentAllExpanded;
            const btnText = document.getElementById('studentToggleAllText');
            const btnIcon = document.getElementById('studentToggleAllIcon');
            if (btnText) btnText.textContent = studentAllExpanded ? 'Collapse All' : 'Expand All';
            if (btnIcon) {
                if (studentAllExpanded) btnIcon.classList.remove('rotate-180');
                else btnIcon.classList.add('rotate-180');
            }

            document.querySelectorAll('.student-subfolder-section').forEach(sec => {
                const body = sec.querySelector('.student-subfolder-body');
                const chevron = sec.querySelector('.student-folder-chevron');
                const toggleText = sec.querySelector('.student-folder-toggle-text');
                if (body && chevron && toggleText) {
                    if (studentAllExpanded) {
                        body.classList.remove('hidden');
                        chevron.classList.remove('rotate-180');
                        toggleText.textContent = 'Collapse';
                    } else {
                        body.classList.add('hidden');
                        chevron.classList.add('rotate-180');
                        toggleText.textContent = 'Expand';
                    }
                }
            });
        };

        let currentStudentMaterialsLayout = 'cards';

        window.setStudentMaterialsLayout = function(layout) {
            currentStudentMaterialsLayout = layout;
            const btnCards = document.getElementById('student-mat-btn-cards');
            const btnTable = document.getElementById('student-mat-btn-table');
            const cardsWrap = document.getElementById('studentMaterialsCardsWrap');
            const tableWrap = document.getElementById('studentMaterialsTableWrap');
            const collapseBtn = document.getElementById('studentToggleAllBtn');
            const iconCards = btnCards?.querySelector('svg');
            const iconTable = btnTable?.querySelector('svg');

            if (layout === 'cards') {
                if (btnCards) btnCards.className = 'px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 text-indigo-700 border border-indigo-200 cursor-pointer flex items-center gap-1.5';
                if (btnTable) btnTable.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5';
                if (iconCards) iconCards.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600');
                if (iconTable) iconTable.setAttribute('class', 'w-3.5 h-3.5 text-gray-400');

                if (cardsWrap) cardsWrap.classList.remove('hidden');
                if (tableWrap) tableWrap.classList.add('hidden');
                if (collapseBtn) collapseBtn.classList.remove('hidden');
            } else {
                if (btnCards) btnCards.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5';
                if (btnTable) btnTable.className = 'px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 text-indigo-700 border border-indigo-200 cursor-pointer flex items-center gap-1.5';
                if (iconCards) iconCards.setAttribute('class', 'w-3.5 h-3.5 text-gray-400');
                if (iconTable) iconTable.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600');

                if (cardsWrap) cardsWrap.classList.add('hidden');
                if (tableWrap) tableWrap.classList.remove('hidden');
                if (collapseBtn) collapseBtn.classList.add('hidden');
            }

            window.filterStudentMaterialsLive();
        };

        window.filterStudentMaterialsLive = function() {
            const searchInput = document.getElementById('studentMaterialsSearch');
            const clearBtn = document.getElementById('studentSearchClearBtn');
            const noMatchBox = document.getElementById('studentNoMatchBox');
            const countBadge = document.getElementById('studentMaterialsCountBadge');

            const query = (searchInput?.value || '').toLowerCase().trim();

            if (clearBtn) {
                if (query.length > 0) clearBtn.classList.remove('hidden');
                else clearBtn.classList.add('hidden');
            }

            const cards = document.querySelectorAll('.student-material-card');
            let overallCount = 0;
            const totalCount = cards.length;

            cards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const platform = card.getAttribute('data-platform') || '';
                const folder = card.getAttribute('data-folder') || '';

                const division = card.getAttribute('data-division') || '';

                const matchesQuery = query === '' || title.includes(query) || platform.includes(query) || division.includes(query);
                const matchesFolder = activeStudentSubfolder === 'all' || folder === activeStudentSubfolder;

                if (matchesQuery && matchesFolder) {
                    card.style.display = '';
                    overallCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.querySelectorAll('.student-material-table-row').forEach(row => {
                const title = row.getAttribute('data-title') || '';
                const platform = row.getAttribute('data-platform') || '';
                const folder = row.getAttribute('data-folder') || '';
                const division = row.getAttribute('data-division') || '';

                const matchesQuery = query === '' || title.includes(query) || platform.includes(query) || division.includes(query);
                const matchesFolder = activeStudentSubfolder === 'all' || folder === activeStudentSubfolder;

                if (matchesQuery && matchesFolder) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            document.querySelectorAll('.student-subfolder-section').forEach(sec => {
                const secKey = sec.getAttribute('data-folder-key');
                if (activeStudentSubfolder !== 'all' && activeStudentSubfolder !== secKey) {
                    sec.style.display = 'none';
                    return;
                }

                const secCards = sec.querySelectorAll('.student-material-card');
                let secVisible = 0;
                secCards.forEach(c => {
                    if (c.style.display !== 'none') secVisible++;
                });

                const badge = sec.querySelector('.student-folder-count-badge');
                if (badge) {
                    if (query !== '') {
                        badge.textContent = `${secVisible} of ${secCards.length} Shown`;
                    } else {
                        badge.textContent = `${secCards.length} ${secCards.length === 1 ? 'Material' : 'Materials'}`;
                    }
                }

                if (secVisible > 0) {
                    sec.style.display = '';
                } else {
                    sec.style.display = 'none';
                }
            });

            if (noMatchBox) {
                if (overallCount === 0 && totalCount > 0) {
                    noMatchBox.classList.remove('hidden');
                } else {
                    noMatchBox.classList.add('hidden');
                }
            }

            if (countBadge) {
                if (query !== '' || activeStudentSubfolder !== 'all') {
                    countBadge.textContent = `${overallCount} of ${totalCount} Shown`;
                } else {
                    countBadge.textContent = `${totalCount} Total`;
                }
            }
        };

        window.clearStudentSearch = function() {
            const searchInput = document.getElementById('studentMaterialsSearch');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
            window.selectStudentSubfolder('all');
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>

