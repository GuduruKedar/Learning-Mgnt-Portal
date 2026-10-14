<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Materials - {{ $course->name }} - LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ filemtime(public_path('css/main.css')) }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-[#0D1117]">
            <div class="w-full space-y-6">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $course->name }}</h1>
                        @if($course->department_id === 'dep_cs')
                            <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mt-1">{{ $course->code }} &bull; {{ $course->semester ?? 'General Studies' }} &bull; Civil Services Academy</p>
                        @else
                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ $course->code }} | Year {{ $course->year }}, Sem {{ $course->semester }}</p>
                        @endif
                    </div>
                </div>

                @php
                    $subfolderCategories = [
                        'Course objective and outcomes' => [
                            'name' => 'Course Objectives & Outcomes (CO / PO)',
                            'badge' => 'Course Objectives',
                            'short_name' => 'Course Objectives & Outcomes',
                            'description' => 'Course educational goals, course outcomes (COs), program outcomes (POs), and learning taxonomies',
                            'color' => 'purple',
                            'border_accent' => 'border-purple-200/90',
                            'header_gradient' => 'from-purple-50/90 via-purple-50/40 to-white',
                            'icon_bg' => 'bg-purple-100 text-purple-700 border border-purple-200',
                            'badge_class' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'icon' => '🎯',
                        ],
                        'Syllabus document' => [
                            'name' => 'Syllabus Document',
                            'badge' => 'Syllabus',
                            'short_name' => 'Syllabus Document',
                            'description' => 'Official curriculum breakdown, lecture schedule, evaluation schemes, and textbook references',
                            'color' => 'blue',
                            'border_accent' => 'border-blue-200/90',
                            'header_gradient' => 'from-blue-50/90 via-blue-50/40 to-white',
                            'icon_bg' => 'bg-blue-100 text-blue-700 border border-blue-200',
                            'badge_class' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'icon' => '📜',
                        ],
                        'PPTs' => [
                            'name' => 'PowerPoint Presentations (PPTs)',
                            'badge' => 'PPTs / Slides',
                            'short_name' => 'PPTs',
                            'description' => 'Lecture slide decks, interactive visual presentations, and module slides',
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
                            'short_name' => 'Lecture notes',
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
                            'short_name' => 'Lab manual',
                            'description' => 'Laboratory instructions, experiment code, and viva reference sheets',
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
                            'description' => 'Unit homework tasks, practice problems, and continuous evaluation assignments',
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
                            'description' => 'GATE previous year questions, solutions, and competitive mock drills',
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
                            'short_name' => 'Additional material',
                            'description' => 'Supplementary reference documents, spreadsheets, external web links, and extra materials',
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

                <!-- Materials Directory (Full Width & Professional - Exactly Matching Faculty UI) -->
                <div class="course-materials-directory bg-white dark:bg-[#151B23] rounded-2xl shadow-2xs border border-gray-200/90 dark:border-[#273244] overflow-hidden flex flex-col">
                    <div class="course-materials-header px-5 sm:px-6 py-3.5 border-b border-gray-100 dark:border-[#273244] bg-gray-50/80 dark:bg-[#151B23] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="shrink-0">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-gray-900 dark:text-white tracking-tight">Study Materials Directory</h3>
                                <span id="materialsCountBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/60 transition-all">
                                    {{ $totalMaterialsCount }} Total
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Direct learning access for enrolled students</p>
                        </div>
                        
                        <!-- Sleek Unified Filter Toolbar -->
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <!-- Live Search Input -->
                            <div class="relative flex-1 sm:w-64 min-w-[170px]">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" 
                                       id="materialsSearchInput" 
                                       oninput="filterMaterialsLive()" 
                                       placeholder="Search title, format, division..." 
                                       class="h-10 block w-full pl-9 pr-8 border border-gray-200 dark:border-[#273244] hover:border-gray-300 dark:hover:border-slate-600 rounded-xl text-xs bg-white dark:bg-[#1C2430] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-2xs">
                                <button type="button" 
                                        id="materialsSearchClearBtn" 
                                        onclick="clearMaterialsSearch()" 
                                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 dark:text-slate-400 hover:text-gray-600 dark:hover:text-slate-200 hidden cursor-pointer" 
                                        title="Clear Search">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <!-- Live Type Filter Dropdown (Custom styled pill) -->
                            <div class="relative shrink-0">
                                <select id="materialsTypeSelect" 
                                        onchange="filterMaterialsLive()" 
                                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;"
                                        class="h-10 appearance-none pl-3 pr-8 border border-gray-200 dark:border-[#273244] hover:border-gray-300 dark:hover:border-slate-600 bg-white dark:bg-[#1C2430] rounded-xl text-xs font-semibold text-gray-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-2xs cursor-pointer">
                                    <option value="">All Types</option>
                                    <option value="file">📄 Files</option>
                                    <option value="link">🔗 Links</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 dark:text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>

                            <!-- Reset Filters Button -->
                            <button type="button" 
                                    id="materialsResetFilterBtn" 
                                    onclick="resetAllMaterialFilters()" 
                                    class="hidden h-10 shrink-0 inline-flex items-center gap-1.5 px-3 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 border border-rose-200/80 dark:border-rose-800/60 rounded-xl text-xs font-bold transition-all shadow-2xs cursor-pointer" 
                                    title="Reset all filters">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Reset</span>
                            </button>
                        </div>
                    </div>

                    <!-- Sub-Folder Navigation Bar (Segregation Tabs) -->
                    <div class="px-5 sm:px-6 py-3 bg-slate-50/80 dark:bg-[#1C2430]/70 border-b border-gray-200/80 dark:border-[#273244] flex items-center justify-between gap-3 overflow-x-auto no-scrollbar">
                        <div class="flex items-center gap-2 shrink-0" id="subfolderTabsContainer">
                            <button type="button" 
                                onclick="selectSubfolder('all')" 
                                id="subfolder-tab-all"
                                data-subfolder-target="all"
                                class="subfolder-tab-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-2xs cursor-pointer">
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
                                    onclick="selectSubfolder('{{ $catSlug }}')" 
                                    id="subfolder-tab-{{ $catSlug }}"
                                    data-subfolder-target="{{ $catSlug }}"
                                    class="subfolder-tab-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-[#151B23] border border-gray-200 dark:border-[#273244] hover:border-gray-300 dark:hover:border-slate-600 transition-all shadow-2xs cursor-pointer">
                                    <span class="text-xs">{{ $catMeta['icon'] }}</span>
                                    <span>{{ $catMeta['short_name'] }}</span>
                                    <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-[#1C2430] text-gray-700 dark:text-slate-300 border border-transparent dark:border-[#273244]">{{ $catCount }}</span>
                                </button>
                                @endif
                            @endforeach
                        </div>

                        <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap">
                            <!-- Cards vs Table Layout Switcher -->
                            <div class="inline-flex p-1 bg-white dark:bg-[#151B23] rounded-xl border border-gray-200 dark:border-[#273244] shadow-2xs">
                                <button type="button" onclick="setCourseMaterialsLayout('cards')" id="course-mat-btn-cards" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 cursor-pointer flex items-center gap-1.5" title="Cards View">
                                    <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    <span>Cards</span>
                                </button>
                                <button type="button" onclick="setCourseMaterialsLayout('table')" id="course-mat-btn-table" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-all cursor-pointer flex items-center gap-1.5" title="Table View">
                                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <span>Table</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6 bg-gray-50/50 dark:bg-[#0D1117]/60 flex-1 space-y-6" id="materialsCardsContainer">
                        @if($totalMaterialsCount === 0)
                            <div class="py-16 px-6 text-center bg-white dark:bg-[#151B23] border border-gray-100 dark:border-[#273244] rounded-2xl shadow-2xs">
                                <div class="mx-auto w-16 h-16 bg-gradient-to-tr from-indigo-100 to-indigo-50 dark:from-indigo-950/60 dark:to-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-4 ring-8 ring-indigo-50/60 dark:ring-indigo-950/40 shadow-xs">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <h3 class="text-base font-extrabold text-gray-900 dark:text-white mb-1">No Materials Found</h3>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                                    There are currently no learning materials uploaded for this course yet.
                                </p>
                            </div>
                        @else
                            <!-- Layout 1: Segregated Academic Division Sections (Cards View) -->
                            <div id="courseMaterialsCardsWrap" class="space-y-6">
                                @foreach($subfolderCategories as $catKey => $catMeta)
                                @php 
                                    $catItems = $groupedMaterials[$catKey]; 
                                    $catSlug = Str::slug($catKey);
                                @endphp
                                @if($catItems->count() > 0)
                                <div class="subfolder-section bg-white dark:bg-[#151B23] rounded-2xl border {{ $catMeta['border_accent'] }} dark:border-[#273244] shadow-2xs overflow-hidden transition-all duration-200" 
                                     id="subfolder-section-{{ $catSlug }}"
                                     data-folder-key="{{ $catSlug }}">
                                    
                                    <!-- Subfolder Header Banner -->
                                    <div onclick="toggleSubfolder('{{ $catSlug }}')" 
                                         class="px-5 py-4 bg-gray-50/70 dark:bg-[#1C2128] border-b border-gray-100 dark:border-[#273244] flex items-center justify-between cursor-pointer select-none group transition-colors">
                                        <div class="flex items-center gap-3.5 min-w-0">
                                            <div class="w-10 h-10 rounded-xl {{ $catMeta['icon_bg'] }} flex items-center justify-center shadow-2xs shrink-0 group-hover:scale-105 transition-transform text-lg">
                                                <span>{{ $catMeta['icon'] }}</span>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h4 class="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white tracking-tight truncate">{{ $catMeta['name'] }}</h4>
                                                    <span class="subfolder-count-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $catMeta['badge_class'] }} transition-all">
                                                        {{ $catItems->count() }} {{ $catItems->count() === 1 ? 'Material' : 'Materials' }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 truncate">{{ $catMeta['description'] }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2.5 shrink-0 ml-3">
                                            <span class="subfolder-toggle-text text-xs font-semibold text-gray-400 dark:text-slate-400 hidden md:inline">Collapse</span>
                                            <div class="subfolder-chevron w-7 h-7 rounded-lg bg-white dark:bg-[#1C2430] border border-gray-200 dark:border-[#273244] flex items-center justify-center text-gray-500 dark:text-slate-400 group-hover:border-indigo-300 group-hover:text-indigo-600 transition-all duration-200 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subfolder Content Grid -->
                                    <div class="subfolder-body p-5 sm:p-6 bg-slate-50/30 dark:bg-[#0D1117]/30">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                                            @foreach($catItems as $material)
                                            @php
                                                $matOpenUrl = $material->type === 'link' ? $material->url_or_path : route('materials.view', $material->id);
                                                $rawExt = strtolower(pathinfo($material->url_or_path, PATHINFO_EXTENSION));
                                            @endphp
                                            <div class="material-card bg-white dark:bg-[#151B23] rounded-2xl border border-gray-200 dark:border-[#273244] shadow-2xs hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-500/50 transition-all duration-200 flex flex-col group h-full justify-between overflow-hidden"
                                                 data-material-title="{{ strtolower($material->title) }}"
                                                 data-material-type="{{ strtolower($material->type) }}"
                                                 data-material-platform="{{ strtolower($material->platform) }}"
                                                 data-material-division="{{ strtolower($material->effective_division) }}"
                                                 data-material-category="{{ strtolower(($catMeta['name'] ?? '') . ' ' . ($catMeta['short_name'] ?? '') . ' ' . $catSlug . ' ' . $catKey) }}"
                                                 data-material-ext="{{ $rawExt }}"
                                                 data-material-staff="{{ strtolower($material->staff->profile->first_name ?? '') }}"
                                                 data-subfolder="{{ $catSlug }}">
                                                
                                                <div class="p-5 flex-1 relative flex flex-col justify-between">
                                                    <div>
                                                        <div class="flex items-center justify-between gap-3 mb-3.5">
                                                            <div class="flex items-center gap-2.5 min-w-0">
                                                                <a href="{{ $matOpenUrl }}" target="_blank" class="shrink-0 group/icon block" title="Open {{ $material->title }}">
                                                                    @if($material->platform === 'youtube')
                                                                        <div class="h-10 w-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'drive' || $material->platform === 'onedrive')
                                                                        <div class="h-10 w-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'pdf')
                                                                        <div class="h-10 w-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'word')
                                                                        <div class="h-10 w-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'excel')
                                                                        <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'ppt')
                                                                        <div class="h-10 w-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'txt')
                                                                        <div class="h-10 w-10 rounded-xl bg-cyan-50 border border-cyan-100 flex items-center justify-center text-cyan-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                        </div>
                                                                    @else
                                                                        <div class="h-10 w-10 rounded-xl bg-slate-100 border border-slate-200/70 flex items-center justify-center text-slate-500 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                                
                                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-extrabold uppercase font-mono tracking-wider {{ in_array($rawExt, ['ppt','pptx','pps']) ? 'bg-amber-100 text-amber-800 border border-amber-200' : (in_array($rawExt, ['xls','xlsx','csv']) ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($rawExt === 'pdf' ? 'bg-rose-100 text-rose-800 border border-rose-200' : (in_array($rawExt, ['doc','docx']) ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-slate-100 text-slate-700 border border-slate-200'))) }}">
                                                                        {{ $rawExt ? '.' . $rawExt : ($material->platform ?: $material->type) }}
                                                                    </span>
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold text-slate-600 bg-slate-50 border border-slate-200 capitalize">
                                                                        {{ $material->platform ?: $material->type }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Full-width Title: Directly clickable to open material --}}
                                                        <a href="{{ $matOpenUrl }}" target="_blank" class="text-[15px] font-bold text-gray-900 dark:text-white leading-snug break-words tracking-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors hover:underline block" title="Open {{ $material->title }}">
                                                            {{ $material->title }}
                                                        </a>

                                                        {{-- Division Tag Badge --}}
                                                        <div class="mt-2.5">
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $catMeta['badge_class'] }} border shadow-2xs">
                                                                <span>{{ $catMeta['icon'] }}</span>
                                                                <span>{{ $material->effective_division }}</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="bg-gray-50/80 dark:bg-[#1C2430]/70 px-4 py-2.5 border-t border-gray-100 dark:border-[#273244] flex items-center justify-between gap-2">
                                                    @if($material->type === 'link')
                                                        <a href="{{ $material->url_or_path }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 py-1.5 rounded-lg transition-colors gap-1.5">
                                                            Open Link <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 py-1.5 rounded-lg transition-colors gap-1">
                                                            View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                        </a>
                                                        <div class="w-px h-3.5 bg-gray-200 dark:bg-[#273244]"></div>
                                                        <a href="{{ route('materials.download', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 py-1.5 rounded-lg transition-colors gap-1">
                                                            Download <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>

                            <!-- Layout 2: Structured Tabular Directory (Table View) -->
                            <div id="courseMaterialsTableWrap" class="hidden overflow-hidden bg-white dark:bg-[#151B23] rounded-2xl border border-gray-200 dark:border-[#273244] shadow-2xs">
                                <div class="overflow-x-auto">
                                    <table class="w-full divide-y divide-gray-200 dark:divide-[#273244] text-left">
                                        <thead class="bg-gray-50/90 dark:bg-[#1C2430] text-[11px] font-bold text-gray-600 dark:text-slate-300 uppercase tracking-wider">
                                            <tr>
                                                <th class="px-5 py-3.5">Material Title & Format</th>
                                                <th class="px-5 py-3.5">Division / Section</th>
                                                <th class="px-5 py-3.5">Type</th>
                                                <th class="px-5 py-3.5">Date</th>
                                                <th class="px-5 py-3.5 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-[#273244] bg-white dark:bg-[#151B23] text-xs">
                                            @foreach($materials as $material)
                                                @php
                                                    $rawExt = strtolower(pathinfo($material->url_or_path, PATHINFO_EXTENSION));
                                                    $matCatKey = $material->effective_division;
                                                    $matMeta = $subfolderCategories[$matCatKey] ?? $subfolderCategories['Additional material'];
                                                    $matSlug = Str::slug($matCatKey);
                                                @endphp
                                                <tr class="hover:bg-indigo-50/40 dark:hover:bg-[#1C2430]/50 transition-colors material-table-row"
                                                    data-material-title="{{ strtolower($material->title) }}"
                                                    data-material-type="{{ strtolower($material->type) }}"
                                                    data-material-platform="{{ strtolower($material->platform) }}"
                                                    data-material-division="{{ strtolower($material->effective_division) }}"
                                                    data-material-category="{{ strtolower(($matMeta['name'] ?? '') . ' ' . ($matMeta['short_name'] ?? '') . ' ' . $matSlug . ' ' . $matCatKey) }}"
                                                    data-material-ext="{{ $rawExt }}"
                                                    data-material-staff="{{ strtolower($material->staff->profile->first_name ?? '') }}"
                                                    data-subfolder="{{ $matSlug }}">
                                                    <td class="px-5 py-3.5">
                                                        <div class="flex items-center gap-3">
                                                            <div class="shrink-0">
                                                                @if($material->platform === 'youtube')
                                                                    <div class="h-8 w-8 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                    </div>
                                                                @elseif($material->platform === 'pdf')
                                                                    <div class="h-8 w-8 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-900/50 flex items-center justify-center text-rose-600 dark:text-rose-400">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                    </div>
                                                                @elseif(in_array($rawExt, ['ppt','pptx']))
                                                                    <div class="h-8 w-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                                                    </div>
                                                                @else
                                                                    <div class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="min-w-0">
                                                                <a href="{{ $material->type === 'link' ? $material->url_or_path : route('materials.view', $material->id) }}" target="_blank" class="font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline transition-colors break-words block" title="Open {{ $material->title }}">
                                                                    {{ $material->title }}
                                                                </a>
                                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-slate-100 dark:bg-[#1C2430] text-slate-700 dark:text-slate-300 border border-transparent dark:border-[#273244]">
                                                                        {{ $rawExt ? '.' . $rawExt : ($material->platform ?: $material->type) }}
                                                                    </span>
                                                                    <span class="text-[10px] text-gray-400 dark:text-slate-400 capitalize">{{ $material->platform ?: $material->type }}</span>
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
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60">
                                                                🔗 Web Link
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                                                📄 File Resource
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3.5 whitespace-nowrap text-gray-500 dark:text-slate-400 font-medium">
                                                        {{ $material->created_at ? $material->created_at->format('M d, Y') : '&mdash;' }}
                                                    </td>
                                                    <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                                        <div class="flex items-center justify-center gap-1.5">
                                                            @if($material->type === 'link')
                                                                <a href="{{ $material->url_or_path }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-2xs">
                                                                    <span>Open</span>
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-2xs">
                                                                    <span>View</span>
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                                </a>
                                                                <a href="{{ route('materials.download', $material->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-[#1C2430] dark:hover:bg-[#273244] text-gray-700 dark:text-slate-200 border border-transparent dark:border-[#273244] rounded-lg text-xs font-bold transition-colors">
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
                        <div id="materialsNoMatchState" class="py-14 px-6 text-center bg-white dark:bg-[#151B23] border border-dashed border-gray-200 dark:border-[#273244] rounded-2xl hidden">
                            <div class="mx-auto w-12 h-12 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-500 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No Matching Learning Materials</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">No learning materials match your current search query or filter selection.</p>
                            <button type="button" onclick="resetAllMaterialFilters()" class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-xl transition-colors cursor-pointer border border-transparent dark:border-indigo-800/60">
                                <span>Reset Filters</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="mt-8 border-t border-gray-200 dark:border-[#273244] pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-slate-300">Technology Development (TD)</span>.</p>
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

    <!-- Scripts -->
    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Active Subfolder Filter State
        let activeSubfolderFilter = 'all';

        // Select Subfolder Handler
        window.selectSubfolder = function(folderKey) {
            activeSubfolderFilter = folderKey;

            // Highlight subfolder tab
            document.querySelectorAll('.subfolder-tab-btn').forEach(btn => {
                const target = btn.getAttribute('data-subfolder-target');
                const badge = btn.querySelector('.tab-badge');
                if (target === folderKey) {
                    btn.className = 'subfolder-tab-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white';
                } else {
                    btn.className = 'subfolder-tab-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-[#151B23] border border-gray-200 dark:border-[#273244] hover:border-gray-300 dark:hover:border-slate-600 transition-all shadow-2xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-[#1C2430] text-gray-700 dark:text-slate-300 border border-transparent dark:border-[#273244]';
                }
            });

            // If subfolder selected, auto expand and scroll
            if (folderKey !== 'all') {
                const section = document.getElementById('subfolder-section-' + folderKey);
                if (section) {
                    const body = section.querySelector('.subfolder-body');
                    const chevron = section.querySelector('.subfolder-chevron');
                    const toggleText = section.querySelector('.subfolder-toggle-text');
                    if (body) body.classList.remove('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                    if (toggleText) toggleText.textContent = 'Collapse';
                }
                allSubfoldersExpanded = true;
                const btnText = document.getElementById('toggleAllSubfoldersText');
                const btnIcon = document.getElementById('toggleAllSubfoldersIcon');
                if (btnText) btnText.textContent = 'Collapse All';
                if (btnIcon) btnIcon.classList.remove('rotate-180');
            } else {
                allSubfoldersExpanded = true;
                const btnText = document.getElementById('toggleAllSubfoldersText');
                const btnIcon = document.getElementById('toggleAllSubfoldersIcon');
                if (btnText) btnText.textContent = 'Collapse All';
                if (btnIcon) btnIcon.classList.remove('rotate-180');
                document.querySelectorAll('.subfolder-section').forEach(sec => {
                    const body = sec.querySelector('.subfolder-body');
                    const chevron = sec.querySelector('.subfolder-chevron');
                    const toggleText = sec.querySelector('.subfolder-toggle-text');
                    if (body) body.classList.remove('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                    if (toggleText) toggleText.textContent = 'Collapse';
                });
            }

            window.filterMaterialsLive();
        };

        // Individual Subfolder Toggle (Expand / Collapse)
        window.toggleSubfolder = function(folderKey) {
            const section = document.getElementById('subfolder-section-' + folderKey);
            if (!section) return;
            const body = section.querySelector('.subfolder-body');
            const chevron = section.querySelector('.subfolder-chevron');
            const toggleText = section.querySelector('.subfolder-toggle-text');

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

        // Global Collapse / Expand All Subfolders
        let allSubfoldersExpanded = true;
        window.toggleAllSubfolders = function() {
            allSubfoldersExpanded = !allSubfoldersExpanded;
            const btnText = document.getElementById('toggleAllSubfoldersText');
            const btnIcon = document.getElementById('toggleAllSubfoldersIcon');
            if (btnText) btnText.textContent = allSubfoldersExpanded ? 'Collapse All' : 'Expand All';
            if (btnIcon) {
                if (allSubfoldersExpanded) btnIcon.classList.remove('rotate-180');
                else btnIcon.classList.add('rotate-180');
            }

            document.querySelectorAll('.subfolder-section').forEach(section => {
                const body = section.querySelector('.subfolder-body');
                const chevron = section.querySelector('.subfolder-chevron');
                const toggleText = section.querySelector('.subfolder-toggle-text');
                if (body && chevron && toggleText) {
                    if (allSubfoldersExpanded) {
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

        // Course Materials Layout State (Cards vs Table)
        let currentCourseMaterialsLayout = 'cards';

        window.setCourseMaterialsLayout = function(layout) {
            currentCourseMaterialsLayout = layout;
            const btnCards = document.getElementById('course-mat-btn-cards');
            const btnTable = document.getElementById('course-mat-btn-table');
            const cardsWrap = document.getElementById('courseMaterialsCardsWrap');
            const tableWrap = document.getElementById('courseMaterialsTableWrap');
            const collapseBtn = document.getElementById('toggleAllSubfoldersBtn');
            const iconCards = btnCards?.querySelector('svg');
            const iconTable = btnTable?.querySelector('svg');

            if (layout === 'cards') {
                if (btnCards) btnCards.className = 'px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 cursor-pointer flex items-center gap-1.5';
                if (btnTable) btnTable.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-all cursor-pointer flex items-center gap-1.5';
                if (iconCards) iconCards.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400');
                if (iconTable) iconTable.setAttribute('class', 'w-3.5 h-3.5 text-gray-400 dark:text-slate-500');

                if (cardsWrap) cardsWrap.classList.remove('hidden');
                if (tableWrap) tableWrap.classList.add('hidden');
                if (collapseBtn) collapseBtn.classList.remove('hidden');
            } else {
                if (btnCards) btnCards.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-all cursor-pointer flex items-center gap-1.5';
                if (btnTable) btnTable.className = 'px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 cursor-pointer flex items-center gap-1.5';
                if (iconCards) iconCards.setAttribute('class', 'w-3.5 h-3.5 text-gray-400 dark:text-slate-500');
                if (iconTable) iconTable.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400');

                if (cardsWrap) cardsWrap.classList.add('hidden');
                if (tableWrap) tableWrap.classList.remove('hidden');
                if (collapseBtn) collapseBtn.classList.add('hidden');
            }

            window.filterMaterialsLive();
        };

        // Live Real-Time Course Materials Filter
        window.filterMaterialsLive = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            const typeSelect = document.getElementById('materialsTypeSelect');
            const clearBtn = document.getElementById('materialsSearchClearBtn');
            const resetBtn = document.getElementById('materialsResetFilterBtn');
            const noMatchBox = document.getElementById('materialsNoMatchState');
            const countBadge = document.getElementById('materialsCountBadge');

            const searchQuery = (searchInput?.value || '').toLowerCase().trim();
            const selectedType = (typeSelect?.value || '').toLowerCase().trim();

            if (clearBtn) {
                if (searchQuery.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            if (resetBtn) {
                if (searchQuery.length > 0 || selectedType !== '' || activeSubfolderFilter !== 'all') {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }
            }

            const cards = document.querySelectorAll('.material-card');
            let overallVisibleCount = 0;
            const totalCount = cards.length;

            // 1. Filter each individual card
            cards.forEach(card => {
                const title = card.getAttribute('data-material-title') || '';
                const type = card.getAttribute('data-material-type') || '';
                const platform = card.getAttribute('data-material-platform') || '';
                const staff = card.getAttribute('data-material-staff') || '';
                const cardFolder = card.getAttribute('data-subfolder') || '';
                const division = card.getAttribute('data-material-division') || '';
                const category = card.getAttribute('data-material-category') || '';
                const ext = card.getAttribute('data-material-ext') || '';

                const matchesSearch = searchQuery === '' || 
                                      title.includes(searchQuery) || 
                                      platform.includes(searchQuery) || 
                                      division.includes(searchQuery) || 
                                      category.includes(searchQuery) || 
                                      ext.includes(searchQuery) || 
                                      staff.includes(searchQuery);

                const matchesType = selectedType === '' || type === selectedType;
                const matchesFolder = (activeSubfolderFilter === 'all') || (cardFolder === activeSubfolderFilter);

                if (matchesSearch && matchesType && matchesFolder) {
                    card.style.display = '';
                    overallVisibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Filter each table row
            document.querySelectorAll('.material-table-row').forEach(row => {
                const title = row.getAttribute('data-material-title') || '';
                const type = row.getAttribute('data-material-type') || '';
                const platform = row.getAttribute('data-material-platform') || '';
                const staff = row.getAttribute('data-material-staff') || '';
                const cardFolder = row.getAttribute('data-subfolder') || '';
                const division = row.getAttribute('data-material-division') || '';
                const category = row.getAttribute('data-material-category') || '';
                const ext = row.getAttribute('data-material-ext') || '';

                const matchesSearch = searchQuery === '' || 
                                      title.includes(searchQuery) || 
                                      platform.includes(searchQuery) || 
                                      division.includes(searchQuery) || 
                                      category.includes(searchQuery) || 
                                      ext.includes(searchQuery) || 
                                      staff.includes(searchQuery);

                const matchesType = selectedType === '' || type === selectedType;
                const matchesFolder = (activeSubfolderFilter === 'all') || (cardFolder === activeSubfolderFilter);

                if (matchesSearch && matchesType && matchesFolder) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // 2. Update each subfolder section visibility and badge
            document.querySelectorAll('.subfolder-section').forEach(section => {
                const sectionFolderKey = section.getAttribute('data-folder-key');
                if (activeSubfolderFilter !== 'all' && activeSubfolderFilter !== sectionFolderKey) {
                    section.style.display = 'none';
                    return;
                }

                const sectionCards = section.querySelectorAll('.material-card');
                let sectionVisibleCount = 0;
                sectionCards.forEach(c => {
                    if (c.style.display !== 'none') sectionVisibleCount++;
                });

                const badgeEl = section.querySelector('.subfolder-count-badge');
                if (badgeEl) {
                    if (searchQuery !== '' || selectedType !== '') {
                        badgeEl.textContent = `${sectionVisibleCount} of ${sectionCards.length} Shown`;
                    } else {
                        badgeEl.textContent = `${sectionCards.length} ${sectionCards.length === 1 ? 'Material' : 'Materials'}`;
                    }
                }

                if (sectionVisibleCount > 0) {
                    section.style.display = '';
                } else {
                    section.style.display = 'none';
                }
            });

            if (noMatchBox) {
                if (overallVisibleCount === 0 && totalCount > 0) {
                    noMatchBox.classList.remove('hidden');
                } else {
                    noMatchBox.classList.add('hidden');
                }
            }

            if (countBadge) {
                if (searchQuery !== '' || selectedType !== '' || activeSubfolderFilter !== 'all') {
                    countBadge.textContent = `${overallVisibleCount} of ${totalCount} Shown`;
                } else {
                    countBadge.textContent = `${totalCount} Total`;
                }
            }
        };

        window.clearMaterialsSearch = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
            window.filterMaterialsLive();
        };

        window.resetAllMaterialFilters = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            const typeSelect = document.getElementById('materialsTypeSelect');
            if (searchInput) searchInput.value = '';
            if (typeSelect) typeSelect.value = '';
            window.selectSubfolder('all');
        };

        // Initialize state on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.filterMaterialsLive === 'function') {
                window.filterMaterialsLive();
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>
