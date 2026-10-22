<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses & Assignments - LMS Faculty</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Page Header with Summary Stats -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Courses & Academic Hub</h1>
                        <p class="mt-1 text-sm text-gray-600">Seamlessly access your assigned courses, learning materials, and student assignments.</p>
                    </div>
                    
                    <!-- Quick Stats Badges -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-500 leading-none">Courses to Teach</p>
                                <p class="text-base font-black text-gray-900 leading-tight mt-0.5">{{ $courses->count() }}</p>
                            </div>
                        </div>

                        <div class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mr-2.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-500 leading-none">Materials Uploaded</p>
                                <p class="text-base font-black text-gray-900 leading-tight mt-0.5">{{ $totalMaterialsCount }}</p>
                            </div>
                        </div>

                        <div class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mr-2.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-500 leading-none">Assessments Given</p>
                                <p class="text-base font-black text-gray-900 leading-tight mt-0.5">{{ $totalAssignmentsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Navigation Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-6 sm:space-x-8" role="tablist" aria-label="Course Sections">
                        <button type="button" id="tab-btn-materials" onclick="switchSectionTab('materials')" role="tab" aria-selected="true" aria-controls="section-materials" class="group inline-flex items-center py-3.5 px-1 border-b-2 font-bold text-sm text-indigo-600 border-indigo-600 transition-all focus:outline-none">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Course Learning Materials</span>
                            <span class="ml-2.5 py-0.5 px-2.5 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-700">{{ $courses->count() }}</span>
                        </button>

                        <button type="button" id="tab-btn-assignments" onclick="switchSectionTab('assignments')" role="tab" aria-selected="false" aria-controls="section-assignments" class="group inline-flex items-center py-3.5 px-1 border-b-2 font-semibold text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 transition-all focus:outline-none">
                            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span>Course Assignments & Assessments</span>
                            <span class="ml-2.5 py-0.5 px-2.5 rounded-full text-xs font-extrabold bg-gray-100 text-gray-600 group-hover:bg-purple-100 group-hover:text-purple-700">{{ $totalAssignmentsCount }}</span>
                        </button>
                    </nav>
                </div>

                <!-- SECTION 1: COURSE LEARNING MATERIALS (ENHANCED SECTION-WISE DIRECTORY) -->
                <div id="section-materials" role="tabpanel" aria-labelledby="tab-btn-materials" class="space-y-6">
                    
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

                        $groupedAllMats = [];
                        foreach ($subfolderCategories as $k => $c) {
                            $groupedAllMats[$k] = collect();
                        }
                        if (isset($allMaterials)) {
                            foreach ($allMaterials as $mat) {
                                $cat = $mat->effective_division;
                                if (!isset($groupedAllMats[$cat])) {
                                    $groupedAllMats['Additional material']->push($mat);
                                } else {
                                    $groupedAllMats[$cat]->push($mat);
                                }
                            }
                        }
                    @endphp

                    <!-- Materials Hub Master Card (Section-Wise) -->
                    <div class="bg-white rounded-2xl shadow-2xs border border-gray-200/90 overflow-hidden flex flex-col">
                        <!-- Top Header & Filter Controls -->
                        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <h3 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">All Courses & Section Directory</h3>
                                    <span id="coursesCountBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $courses->count() }} {{ Str::plural('Course', $courses->count()) }} &bull; {{ $totalMaterialsCount }} Materials
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">Section-wise organization across regulations, courses, and study materials</p>
                            </div>

                            <!-- Live Search & View Mode Switcher -->
                            <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                                <!-- Search Input -->
                                <div class="relative w-full sm:w-60">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" id="courseMaterialSearch" oninput="filterCourseCards(this.value)" placeholder="Search course, code, or format..." class="block w-full pl-8 pr-7 py-2 border border-gray-300 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    <button type="button" id="courseSearchClearBtn" onclick="clearCourseSearch()" class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-400 hover:text-gray-600 hidden cursor-pointer" title="Clear Search">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <!-- View Mode Selector: View All Courses vs View as Sections -->
                                <div class="inline-flex p-1 bg-gray-100 rounded-xl border border-gray-200 shadow-2xs">
                                    <button type="button" onclick="switchAllCoursesMode('courses')" id="mode-btn-courses" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5" title="View all courses organized by regulation">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <span>View All Courses</span>
                                    </button>
                                    <button type="button" onclick="switchAllCoursesMode('materials')" id="mode-btn-materials" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-indigo-700 shadow-2xs cursor-pointer flex items-center gap-1.5" title="View materials partitioned section-wise across divisions">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                        <span>View as Sections</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Folder Navigation Bar (Pill Tabs) -->
                        <div class="px-5 sm:px-6 py-3 bg-slate-50/80 border-b border-gray-200/80 flex items-center justify-between gap-3 overflow-x-auto no-scrollbar">
                            <!-- Course Regulation Tabs (shown in Courses Mode) -->
                            <div class="hidden items-center gap-2 shrink-0" id="courseRegulationTabs">
                                <button type="button" 
                                    onclick="selectRegulationTab('all')" 
                                    id="reg-tab-all"
                                    data-reg-target="all"
                                    class="reg-tab-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-2xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                    <span>All Regulations</span>
                                    <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white">{{ $courses->count() }}</span>
                                </button>

                                @foreach($groupedCourses as $regId => $coursesList)
                                    @php
                                        $fCourse = $coursesList->first();
                                        $rObj = $fCourse ? $fCourse->regulation : null;
                                        $rCode = $rObj ? ($rObj->code ?: $rObj->name) : 'Standard';
                                    @endphp
                                    <button type="button" 
                                        onclick="selectRegulationTab('{{ $regId }}')" 
                                        id="reg-tab-{{ $regId }}"
                                        data-reg-target="{{ $regId }}"
                                        class="reg-tab-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 hover:text-gray-900 bg-white border border-gray-200 hover:border-gray-300 transition-all shadow-2xs cursor-pointer">
                                        <span>Regulation {{ $rCode }}</span>
                                        <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">{{ $coursesList->count() }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Combined Materials Category Tabs (shown in Materials Mode by default) -->
                            <div class="flex items-center gap-2 shrink-0" id="combinedMaterialTabs">
                                <button type="button" 
                                    onclick="selectCombinedFolderTab('all')" 
                                    id="comb-tab-all"
                                    data-folder-target="all"
                                    class="comb-tab-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-2xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                    <span>All Divisions</span>
                                    <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white">{{ $totalMaterialsCount }}</span>
                                </button>

                                @foreach($subfolderCategories as $catKey => $catMeta)
                                    @php 
                                        $catCount = $groupedAllMats[$catKey]->count(); 
                                        $catSlug = Str::slug($catKey);
                                    @endphp
                                    @if($catCount > 0)
                                    <button type="button" 
                                        onclick="selectCombinedFolderTab('{{ $catSlug }}')" 
                                        id="comb-tab-{{ $catSlug }}"
                                        data-folder-target="{{ $catSlug }}"
                                        class="comb-tab-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 hover:text-gray-900 bg-white border border-gray-200 hover:border-gray-300 transition-all shadow-2xs cursor-pointer">
                                        <span class="text-xs">{{ $catMeta['icon'] }}</span>
                                        <span>{{ $catMeta['short_name'] }}</span>
                                        <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">{{ $catCount }}</span>
                                    </button>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Controls on the right (Collapse all and Table/Cards layout switcher) -->
                            <div class="flex items-center gap-2.5 shrink-0 flex-wrap sm:flex-nowrap">
                                <!-- Cards vs Table Toggle (shown when in View All Courses mode) -->
                                <div class="hidden items-center gap-1.5 shrink-0" id="courseLayoutSwitcherWrap">
                                    <div class="inline-flex p-1 bg-white rounded-xl border border-gray-200 shadow-2xs">
                                        <button type="button" onclick="setCourseLayout('cards')" id="layout-btn-cards" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 text-indigo-700 border border-indigo-200 cursor-pointer flex items-center gap-1.5" title="Grid / Cards View">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                            <span>Cards</span>
                                        </button>
                                        <button type="button" onclick="setCourseLayout('table')" id="layout-btn-table" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5" title="Data Table View">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            <span>Table</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Expand / Collapse All Toggle -->
                                <button type="button" onclick="toggleAllMasterSections()" id="toggleAllMasterBtn" class="text-xs font-bold text-gray-500 hover:text-indigo-600 px-3 py-1.5 rounded-xl hover:bg-white border border-transparent hover:border-gray-200 transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs">
                                    <span id="toggleAllMasterText">Collapse All</span>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" id="toggleAllMasterIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="p-5 sm:p-6 bg-gray-50/50 space-y-6">
                            
                            <!-- ============================================== -->
                            <!-- VIEW MODE A: COURSES GROUPED SECTION-WISE     -->
                            <!-- ============================================== -->
                            <div id="viewModeCoursesContainer" class="hidden space-y-6">
                                @if($groupedCourses->isEmpty())
                                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto text-gray-400 mb-4">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">No Assigned Courses</h3>
                                        <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">You do not have any courses assigned to your staff profile yet.</p>
                                    </div>
                                @else
                                    @foreach($groupedCourses as $regId => $coursesList)
                                        @php
                                            $firstCourse = $coursesList->first();
                                            $regulation = $firstCourse ? $firstCourse->regulation : null;
                                            $regCode = $regulation ? ($regulation->code ?: $regulation->name) : 'Standard';
                                            $curriculum = $regulation ? $regulation->curriculum : null;
                                            $programType = $regulation ? $regulation->program_type : null;
                                        @endphp
                                        <div class="master-regulation-section bg-white dark:bg-[#151B23] rounded-2xl border border-gray-200 dark:border-[#273244] shadow-2xs overflow-hidden transition-all duration-200"
                                             id="master-section-reg-{{ $regId }}"
                                             data-reg-id="{{ $regId }}">
                                            
                                            <!-- Section Header Banner -->
                                            <div onclick="toggleRegulationSection('{{ $regId }}')"
                                                 class="px-5 py-4 bg-gray-50/70 dark:bg-[#1C2128] border-b border-gray-100 dark:border-[#273244] flex items-center justify-between cursor-pointer select-none group transition-colors">
                                                <div class="flex items-center gap-3.5 min-w-0">
                                                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white tracking-tight">Regulation {{ $regCode }}</h3>
                                                            @if(!empty($curriculum))
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/40">
                                                                    Curriculum: {{ $curriculum }}
                                                                </span>
                                                            @endif
                                                            @if(!empty($programType))
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                                                                    {{ $programType }}
                                                                </span>
                                                            @endif
                                                            <span class="reg-folder-count-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/40">
                                                                {{ $coursesList->count() }} {{ Str::plural('Course', $coursesList->count()) }}
                                                            </span>
                                                        </div>
                                                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Faculty course curriculum and learning materials section</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2.5 shrink-0 ml-3">
                                                    <span class="master-reg-toggle-text text-xs font-semibold text-gray-400 dark:text-slate-400 hidden md:inline">Collapse</span>
                                                    <div class="master-reg-chevron w-7 h-7 rounded-lg bg-white dark:bg-[#273244] border border-gray-200 dark:border-[#384457] flex items-center justify-center text-gray-500 dark:text-slate-300 group-hover:border-indigo-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-all duration-200 shadow-2xs">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Section Body: Course Cards Grid -->
                                            <div class="master-reg-cards master-reg-body p-5 sm:p-6 bg-slate-50/30 dark:bg-[#151B23]">
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                                    @foreach($coursesList as $course)
                                                        @php
                                                            $cMaterials = $course->materials ?? collect();
                                                            $cByDiv = $cMaterials->groupBy(fn($m) => $m->effective_division);
                                                        @endphp
                                                        <div class="course-card bg-white rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-indigo-300 transition-all duration-200 flex flex-col justify-between overflow-hidden group"
                                                             data-search="{{ strtolower($course->name . ' ' . $course->code . ' ' . ($course->department->name ?? '')) }}"
                                                             data-reg="{{ $regId }}">
                                                            
                                                            <!-- Subtle Accent Bar -->
                                                            <div class="h-1 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-500"></div>

                                                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                                                <div>
                                                                    <!-- Badges Header -->
                                                                    <div class="flex items-center justify-between gap-2 mb-2.5">
                                                                        <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold font-mono tracking-wide bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                                                {{ $course->code }}
                                                                            </span>
                                                                            @if($course->regulation)
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                                                                    {{ $course->regulation->code ?: $course->regulation->name }}
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        @if($course->year || $course->semester)
                                                                            <span class="inline-flex items-center text-[10px] font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md shrink-0">
                                                                                @if($course->year)Year {{ $course->year }}@endif
                                                                                @if($course->year && $course->semester), @endif
                                                                                @if($course->semester)Sem {{ $course->semester }}@endif
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <!-- Course Name & Department -->
                                                                    <h4 class="text-base font-extrabold text-gray-900 group-hover:text-indigo-600 transition-colors leading-snug mb-1">
                                                                        <a href="{{ route('staff.courses.materials', $course->id) }}" class="focus:outline-none">
                                                                            {{ $course->name }}
                                                                        </a>
                                                                    </h4>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ $course->department->name ?? 'Academic Department' }}
                                                                    </p>

                                                                    <!-- Material Division Breakdown Pills -->
                                                                    <div class="mt-3.5 pt-3 border-t border-gray-100 flex flex-wrap gap-1.5 items-center">
                                                                        @php $shownPills = 0; @endphp
                                                                        @foreach($subfolderCategories as $dKey => $dMeta)
                                                                            @if(isset($cByDiv[$dKey]) && $cByDiv[$dKey]->count() > 0)
                                                                                @php $shownPills++; @endphp
                                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold {{ $dMeta['badge_class'] }} border" title="{{ $dKey }}">
                                                                                    <span>{{ $dMeta['icon'] }}</span> {{ $cByDiv[$dKey]->count() }} {{ $dMeta['short_name'] }}
                                                                                </span>
                                                                            @endif
                                                                        @endforeach
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                                                                            {{ $course->materials_count }} Total
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <!-- Card Actions -->
                                                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                                                    <a href="{{ route('staff.courses.materials', $course->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors shadow-2xs">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                                        <span>Open Section View</span>
                                                                    </a>
                                                                    <a href="{{ route('staff.assignments.index', ['course_id' => $course->id]) }}" class="inline-flex items-center justify-center px-3 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-xl text-xs font-bold transition-colors border border-purple-200" title="Course Assessments">
                                                                        <span>Assessments ({{ $course->assignments_count }})</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Section Body: Course Table View -->
                                            <div class="master-reg-table master-reg-body hidden overflow-x-auto bg-white border-t border-gray-100">
                                                <table class="w-full divide-y divide-gray-200 text-left">
                                                    <thead class="bg-gray-50/90 text-[11px] font-bold text-gray-600 uppercase tracking-wider">
                                                        <tr>
                                                            <th class="px-5 py-3.5">Course Code & Title</th>
                                                            <th class="px-5 py-3.5">Year / Sem</th>
                                                            <th class="px-5 py-3.5">Department</th>
                                                            <th class="px-5 py-3.5">Materials Breakdown</th>
                                                            <th class="px-5 py-3.5 text-center">Assessments</th>
                                                            <th class="px-5 py-3.5 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-100 bg-white text-xs">
                                                        @foreach($coursesList as $course)
                                                            @php
                                                                $cMaterials = $course->materials ?? collect();
                                                                $cByDiv = $cMaterials->groupBy(fn($m) => $m->effective_division);
                                                            @endphp
                                                            <tr class="hover:bg-indigo-50/40 transition-colors course-table-row" 
                                                                data-search="{{ strtolower($course->name . ' ' . $course->code . ' ' . ($course->department->name ?? '')) }}"
                                                                data-reg="{{ $regId }}">
                                                                <td class="px-5 py-3.5">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold font-mono bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                                            {{ $course->code }}
                                                                        </span>
                                                                        <a href="{{ route('staff.courses.materials', $course->id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition-colors">
                                                                            {{ $course->name }}
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td class="px-5 py-3.5 whitespace-nowrap">
                                                                    @if($course->year || $course->semester)
                                                                        <span class="inline-flex items-center text-[11px] font-semibold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-md">
                                                                            @if($course->year)Yr {{ $course->year }}@endif
                                                                            @if($course->year && $course->semester), @endif
                                                                            @if($course->semester)Sem {{ $course->semester }}@endif
                                                                        </span>
                                                                    @else
                                                                        <span class="text-gray-400">&mdash;</span>
                                                                    @endif
                                                                </td>
                                                                <td class="px-5 py-3.5 text-gray-600">
                                                                    {{ $course->department->name ?? 'Academic Dept' }}
                                                                </td>
                                                                <td class="px-5 py-3.5">
                                                                    <div class="flex flex-wrap items-center gap-1.5 max-w-md">
                                                                        @foreach($subfolderCategories as $dKey => $dMeta)
                                                                            @if(isset($cByDiv[$dKey]) && $cByDiv[$dKey]->count() > 0)
                                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $dMeta['badge_class'] }} border" title="{{ $dKey }}">
                                                                                    <span>{{ $dMeta['icon'] }}</span> {{ $cByDiv[$dKey]->count() }}
                                                                                </span>
                                                                            @endif
                                                                        @endforeach
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-slate-100 text-slate-700">
                                                                            {{ $course->materials_count }} Total
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="px-5 py-3.5 text-center">
                                                                    <a href="{{ route('staff.assignments.index', ['course_id' => $course->id]) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 transition-colors">
                                                                        {{ $course->assignments_count }}
                                                                    </a>
                                                                </td>
                                                                <td class="px-5 py-3.5 text-center">
                                                                    <div class="flex items-center justify-center gap-2">
                                                                        <a href="{{ route('staff.courses.materials', $course->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-2xs">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                                            <span>Section View</span>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- ============================================== -->
                            <!-- VIEW MODE B: COMBINED ALL MATERIALS FOLDERS    -->
                            <!-- ============================================== -->
                            <div id="viewModeMaterialsContainer" class="space-y-6">
                                @if(!isset($allMaterials) || $allMaterials->isEmpty())
                                    <div class="py-14 px-6 text-center bg-white border border-gray-100 rounded-2xl shadow-sm">
                                        <div class="mx-auto w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-3">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        </div>
                                        <h3 class="text-base font-bold text-gray-900">No Learning Materials Across Courses</h3>
                                        <p class="text-xs text-gray-500 mt-1">Upload study materials in individual courses to view them in this combined directory.</p>
                                    </div>
                                @else
                                    @foreach($subfolderCategories as $catKey => $catMeta)
                                        @php 
                                            $catItems = $groupedAllMats[$catKey]; 
                                            $catSlug = Str::slug($catKey);
                                        @endphp
                                        @if($catItems->count() > 0)
                                        <div class="comb-subfolder-section bg-white dark:bg-[#151B23] rounded-2xl border border-gray-200 dark:border-[#273244] shadow-2xs overflow-hidden transition-all duration-200"
                                             id="comb-section-{{ $catSlug }}"
                                             data-folder-key="{{ $catSlug }}">
                                            
                                            <!-- Subfolder Header Banner -->
                                            <div onclick="toggleCombinedSubfolder('{{ $catSlug }}')"
                                                 class="px-5 py-4 bg-gray-50/70 dark:bg-[#1C2128] border-b border-gray-100 dark:border-[#273244] flex items-center justify-between cursor-pointer select-none group transition-colors">
                                                <div class="flex items-center gap-3.5 min-w-0">
                                                    <div class="w-10 h-10 rounded-xl {{ $catMeta['icon_bg'] }} dark:bg-[#273244] dark:border-[#384457] flex items-center justify-center shadow-2xs shrink-0 group-hover:scale-105 transition-transform text-lg">
                                                        <span>{{ $catMeta['icon'] }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <h3 class="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $catMeta['name'] }}</h3>
                                                            <span class="comb-folder-count-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $catMeta['badge_class'] }}">
                                                                {{ $catItems->count() }} {{ $catItems->count() === 1 ? 'Material' : 'Materials' }}
                                                            </span>
                                                        </div>
                                                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ $catMeta['description'] }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2.5 shrink-0 ml-3">
                                                    <span class="comb-folder-toggle-text text-xs font-semibold text-gray-400 dark:text-slate-400 hidden md:inline">Collapse</span>
                                                    <div class="comb-folder-chevron w-7 h-7 rounded-lg bg-white dark:bg-[#273244] border border-gray-200 dark:border-[#384457] flex items-center justify-center text-gray-500 dark:text-slate-300 group-hover:border-indigo-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-all duration-200 shadow-2xs">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Subfolder Content: Materials Grid -->
                                            <div class="comb-subfolder-body p-5 sm:p-6 bg-slate-50/30">
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                                                    @foreach($catItems as $material)
                                                        @php
                                                            $rawExt = strtolower(pathinfo($material->url_or_path, PATHINFO_EXTENSION));
                                                            $allocData = [
                                                                'id' => $material->id,
                                                                'title' => $material->title,
                                                                'division' => $material->effective_division,
                                                                'platform' => $material->platform,
                                                                'type' => $material->type,
                                                                'updateUrl' => route('staff.courses.materials.update', $material->id)
                                                            ];
                                                        @endphp
                                                        <div class="comb-material-card bg-white rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-indigo-200 transition-all duration-200 flex flex-col justify-between overflow-hidden group"
                                                             data-search="{{ strtolower($material->title . ' ' . ($material->course->code ?? '') . ' ' . ($material->course->name ?? '') . ' ' . $material->platform . ' ' . $material->effective_division) }}"
                                                             data-folder="{{ $catSlug }}"
                                                             data-division="{{ strtolower($material->effective_division) }}">
                                                            
                                                            <div class="p-5 flex-1 relative flex flex-col justify-between">
                                                                <div>
                                                                    <!-- Course Tag Badge & Format Pill -->
                                                                    <div class="flex items-center justify-between gap-2 mb-3">
                                                                        <a href="{{ route('staff.courses.materials', $material->course_id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-extrabold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/80 transition-colors truncate max-w-[170px]" title="{{ $material->course->name ?? 'Course' }}">
                                                                            <span>🎯 {{ $material->course->code ?? 'CRS' }}</span>
                                                                        </a>

                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-extrabold uppercase font-mono tracking-wider {{ in_array($rawExt, ['ppt','pptx','pps']) ? 'bg-amber-100 text-amber-800 border border-amber-200' : (in_array($rawExt, ['xls','xlsx','csv']) ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($rawExt === 'pdf' ? 'bg-rose-100 text-rose-800 border border-rose-200' : (in_array($rawExt, ['doc','docx']) ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-slate-100 text-slate-700 border border-slate-200'))) }}">
                                                                            {{ $rawExt ? '.' . $rawExt : ($material->platform ?: $material->type) }}
                                                                        </span>
                                                                    </div>

                                                                    <!-- Title -->
                                                                    <a href="{{ $material->type === 'link' ? $material->url_or_path : route('materials.view', $material->id) }}" target="_blank" class="text-[14px] font-bold text-gray-900 leading-snug break-words tracking-tight group-hover:text-indigo-600 hover:underline transition-colors block" title="Open {{ $material->title }}">
                                                                        {{ $material->title }}
                                                                    </a>
                                                                    <p class="text-[11px] text-gray-500 mt-1 truncate">{{ $material->course->name ?? '' }}</p>
                                                                    <div class="mt-2 flex items-center justify-between gap-2 flex-wrap">
                                                                        <button type="button"
                                                                            data-material='@json($allocData)'
                                                                            onclick="openAllocateDivisionModal(JSON.parse(this.getAttribute('data-material')))"
                                                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 border border-transparent hover:border-indigo-200 transition-all cursor-pointer"
                                                                            title="Click to allocate / change division">
                                                                            <span>{{ $catMeta['icon'] }}</span>
                                                                            <span>{{ $material->effective_division }}</span>
                                                                            <svg class="w-2.5 h-2.5 opacity-60 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                                        </button>

                                                                        <div class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500" title="Created By">
                                                                            <span class="text-gray-400">By:</span>
                                                                            <span class="font-semibold text-gray-700 truncate max-w-[120px]">{{ trim(($material->staff->profile->first_name ?? '') . ' ' . ($material->staff->profile->last_name ?? '')) ?: ($material->staff->username ?? 'Faculty') }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="bg-gray-50/80 px-4 py-2.5 border-t border-gray-100 flex items-center justify-between gap-2">
                                                                @if($material->type === 'link')
                                                                    <a href="{{ $material->url_or_path }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors gap-1.5">
                                                                        Open Link <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors gap-1">
                                                                        View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                                    </a>
                                                                    <div class="w-px h-3.5 bg-gray-200"></div>
                                                                    <a href="{{ route('materials.download', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors gap-1">
                                                                        Download <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                                    </a>
                                                                @endif

                                                                <button type="button"
                                                                    data-material='@json($allocData)'
                                                                    onclick="openAllocateDivisionModal(JSON.parse(this.getAttribute('data-material')))"
                                                                    class="w-7 h-7 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-600 hover:text-white transition-colors cursor-pointer shrink-0 shadow-2xs"
                                                                    title="Allocate / Edit Division">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            <!-- No Matches State -->
                            <div id="masterNoMatchBox" class="py-14 px-6 text-center bg-white border border-dashed border-gray-200 rounded-2xl hidden">
                                <div class="mx-auto w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">No Matching Courses or Learning Materials</h3>
                                <p class="text-xs text-gray-500 mt-1">Try adjusting your search terms or filter selection.</p>
                                <button type="button" onclick="clearCourseSearch()" class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">
                                    <span>Reset Search</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- SECTION 2: COURSE ASSIGNMENTS -->
                <div id="section-assignments" role="tabpanel" aria-labelledby="tab-btn-assignments" class="hidden space-y-6">
                    
                    <!-- Assignments Filter & Actions Header -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                        <div class="flex flex-col sm:flex-row items-center gap-3 flex-1">
                            <!-- Live Search -->
                            <div class="relative w-full sm:w-72">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="assignmentSearchInput" oninput="filterAssignmentCards(this.value)" placeholder="Search assignments..." class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            </div>

                            <!-- Course Filter Dropdown -->
                            <div class="w-full sm:w-60">
                                <select id="assignmentCourseSelect" onchange="filterAssignmentByCourse(this.value)" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                                    <option value="">All Assigned Courses</option>
                                    @foreach($courses as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('staff.assignments.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs text-sm font-bold transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Create New Assignment
                            </a>
                        </div>
                    </div>

                    <!-- Assignments Cards Grid -->
                    @if($assignments->isEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto text-purple-600 mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">No Assignments Created Yet</h3>
                            <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">You haven't created any assignments for your assigned courses yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('staff.assignments.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Create Your First Assignment
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="assignmentsGrid">
                            @foreach($assignments as $assignment)
                                <div class="assignment-card bg-white dark:bg-[#151B23] rounded-xl border border-slate-200/90 dark:border-slate-800 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-md hover:border-slate-300 dark:hover:border-slate-700 transition-all flex flex-col justify-between overflow-hidden"
                                     data-course-id="{{ $assignment->course_id }}"
                                     data-search="{{ strtolower($assignment->title . ' ' . ($assignment->course->name ?? '') . ' ' . ($assignment->course->code ?? '')) }}">
                                    <!-- Subtle Top Accent Line -->
                                    <div class="h-0.5 bg-slate-200 dark:bg-slate-700 group-hover:bg-purple-500 transition-colors duration-200"></div>

                                    <div class="p-5 flex-1 flex flex-col justify-between">
                                        <div>
                                            <!-- Course Code & Due Date Badge -->
                                            <div class="flex items-center justify-between gap-2 mb-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                    {{ $assignment->course->code ?? 'Course' }}
                                                </span>
                                                @if($assignment->due_date)
                                                    <span class="inline-flex items-center text-[11px] font-medium {{ $assignment->isPastDue() ? 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60' : 'text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700' }} px-2.5 py-0.5 rounded-md">
                                                        <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Due: {{ $assignment->due_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Assignment Title -->
                                            <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors leading-snug mb-1">
                                                {{ $assignment->title }}
                                            </h3>

                                            <!-- Course Name -->
                                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mb-3">
                                                {{ $assignment->course->name ?? 'Course' }}
                                            </p>

                                            @if($assignment->description)
                                                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 mb-4 bg-slate-50 dark:bg-slate-800/40 p-2.5 rounded-lg border border-slate-100 dark:border-slate-800">
                                                    {{ $assignment->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Metrics Row -->
                                        <div class="grid grid-cols-3 divide-x divide-slate-100 dark:divide-slate-800 bg-slate-50/70 dark:bg-slate-800/40 rounded-lg p-2 border border-slate-100 dark:border-slate-800 text-center">
                                            <div class="p-1">
                                                <p class="text-sm font-extrabold text-slate-800 dark:text-slate-200">{{ $assignment->max_marks }}</p>
                                                <p class="text-[10px] uppercase font-semibold text-slate-400 dark:text-slate-500 mt-0.5">Max Marks</p>
                                            </div>
                                            <div class="p-1">
                                                <p class="text-sm font-extrabold text-slate-800 dark:text-slate-200">{{ $assignment->questions->count() }}</p>
                                                <p class="text-[10px] uppercase font-semibold text-slate-400 dark:text-slate-500 mt-0.5">Questions</p>
                                            </div>
                                            <div class="p-1">
                                                <p class="text-sm font-extrabold text-purple-600 dark:text-purple-400">{{ $assignment->submissions_count }}</p>
                                                <p class="text-[10px] uppercase font-semibold text-slate-400 dark:text-slate-500 mt-0.5">Submitted</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Actions -->
                                    <div class="bg-slate-50/50 dark:bg-slate-850/40 px-5 py-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                                        <a href="{{ route('staff.assignments.show', $assignment->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white rounded-lg shadow-2xs text-xs font-semibold transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            View / Grade
                                        </a>
                                        <a href="{{ route('staff.assignments.edit', $assignment->id) }}" class="inline-flex justify-center items-center p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold transition-colors" title="Edit Assignment">
                                            <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
                <button type="button" id="closePasswordModal" class="text-indigo-400 hover:text-indigo-600 focus:outline-none" aria-label="Close Password Modal">
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

        // Accessible Tab Switcher (Materials vs Assignments)
        window.switchSectionTab = function(tabKey) {
            const materialsSection = document.getElementById('section-materials');
            const assignmentsSection = document.getElementById('section-assignments');
            const materialsTabBtn = document.getElementById('tab-btn-materials');
            const assignmentsTabBtn = document.getElementById('tab-btn-assignments');

            if (tabKey === 'materials') {
                materialsSection.classList.remove('hidden');
                assignmentsSection.classList.add('hidden');

                materialsTabBtn.setAttribute('aria-selected', 'true');
                materialsTabBtn.className = 'group inline-flex items-center py-3.5 px-1 border-b-2 font-bold text-sm text-indigo-600 border-indigo-600 transition-all focus:outline-none';
                
                assignmentsTabBtn.setAttribute('aria-selected', 'false');
                assignmentsTabBtn.className = 'group inline-flex items-center py-3.5 px-1 border-b-2 font-semibold text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 transition-all focus:outline-none';
                
                window.history.replaceState(null, null, '#materials');
            } else {
                materialsSection.classList.add('hidden');
                assignmentsSection.classList.remove('hidden');

                assignmentsTabBtn.setAttribute('aria-selected', 'true');
                assignmentsTabBtn.className = 'group inline-flex items-center py-3.5 px-1 border-b-2 font-bold text-sm text-purple-600 border-purple-600 transition-all focus:outline-none';
                
                materialsTabBtn.setAttribute('aria-selected', 'false');
                materialsTabBtn.className = 'group inline-flex items-center py-3.5 px-1 border-b-2 font-semibold text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 transition-all focus:outline-none';
                
                window.history.replaceState(null, null, '#assignments');
            }
        };

        // Current state for section views (default to section-wise view)
        let currentViewMode = 'materials';
        let currentCourseLayout = 'cards'; // 'cards' | 'table'
        let activeRegFilter = 'all';
        let activeFolderFilter = 'all';

        // Switch between View All Courses vs View as Sections
        window.switchAllCoursesMode = function(mode) {
            currentViewMode = mode;
            const coursesContainer = document.getElementById('viewModeCoursesContainer');
            const materialsContainer = document.getElementById('viewModeMaterialsContainer');
            const btnCourses = document.getElementById('mode-btn-courses');
            const btnMaterials = document.getElementById('mode-btn-materials');
            const regTabs = document.getElementById('courseRegulationTabs');
            const folderTabs = document.getElementById('combinedMaterialTabs');
            const layoutSwitcherWrap = document.getElementById('courseLayoutSwitcherWrap');
            const iconCourses = btnCourses?.querySelector('svg');
            const iconMaterials = btnMaterials?.querySelector('svg');

            if (mode === 'courses') {
                coursesContainer?.classList.remove('hidden');
                materialsContainer?.classList.add('hidden');

                if (btnCourses) btnCourses.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-indigo-700 shadow-2xs cursor-pointer flex items-center gap-1.5';
                if (btnMaterials) btnMaterials.className = 'px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5';
                if (iconCourses) iconCourses.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600');
                if (iconMaterials) iconMaterials.setAttribute('class', 'w-3.5 h-3.5 text-gray-400');

                regTabs?.classList.remove('hidden');
                regTabs?.classList.add('flex');
                folderTabs?.classList.add('hidden');
                folderTabs?.classList.remove('flex');

                layoutSwitcherWrap?.classList.remove('hidden');
                layoutSwitcherWrap?.classList.add('flex');

                window.history.replaceState(null, null, '#courses');
            } else {
                coursesContainer?.classList.add('hidden');
                materialsContainer?.classList.remove('hidden');

                if (btnCourses) btnCourses.className = 'px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5';
                if (btnMaterials) btnMaterials.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-indigo-700 shadow-2xs cursor-pointer flex items-center gap-1.5';
                if (iconCourses) iconCourses.setAttribute('class', 'w-3.5 h-3.5 text-gray-400');
                if (iconMaterials) iconMaterials.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600');

                regTabs?.classList.add('hidden');
                regTabs?.classList.remove('flex');
                folderTabs?.classList.remove('hidden');
                folderTabs?.classList.add('flex');

                layoutSwitcherWrap?.classList.add('hidden');
                layoutSwitcherWrap?.classList.remove('flex');

                window.history.replaceState(null, null, '#sections');
            }

            // Re-apply filter and search in the active mode
            const searchInput = document.getElementById('courseMaterialSearch');
            window.filterCourseCards(searchInput ? searchInput.value : '');
        };

        // Switch layout inside View All Courses (Cards vs Table)
        window.setCourseLayout = function(layout) {
            currentCourseLayout = layout;
            const btnCards = document.getElementById('layout-btn-cards');
            const btnTable = document.getElementById('layout-btn-table');
            const iconCards = btnCards?.querySelector('svg');
            const iconTable = btnTable?.querySelector('svg');

            if (layout === 'cards') {
                if (btnCards) btnCards.className = 'px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 text-indigo-700 border border-indigo-200 cursor-pointer flex items-center gap-1.5';
                if (btnTable) btnTable.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5';
                if (iconCards) iconCards.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600');
                if (iconTable) iconTable.setAttribute('class', 'w-3.5 h-3.5 text-gray-400');

                document.querySelectorAll('.master-regulation-section').forEach(sec => {
                    const cardsView = sec.querySelector('.master-reg-cards');
                    const tableView = sec.querySelector('.master-reg-table');
                    if (cardsView) cardsView.classList.remove('hidden');
                    if (tableView) tableView.classList.add('hidden');
                });
            } else {
                if (btnCards) btnCards.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5';
                if (btnTable) btnTable.className = 'px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 text-indigo-700 border border-indigo-200 cursor-pointer flex items-center gap-1.5';
                if (iconCards) iconCards.setAttribute('class', 'w-3.5 h-3.5 text-gray-400');
                if (iconTable) iconTable.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600');

                document.querySelectorAll('.master-regulation-section').forEach(sec => {
                    const cardsView = sec.querySelector('.master-reg-cards');
                    const tableView = sec.querySelector('.master-reg-table');
                    if (cardsView) cardsView.classList.add('hidden');
                    if (tableView) tableView.classList.remove('hidden');
                });
            }

            const searchInput = document.getElementById('courseMaterialSearch');
            window.filterCourseCards(searchInput ? searchInput.value : '');
        };

        // Filter courses by Regulation tab
        window.selectRegulationTab = function(regId) {
            activeRegFilter = regId;
            document.querySelectorAll('.reg-tab-btn').forEach(btn => {
                const target = btn.getAttribute('data-reg-target');
                const badge = btn.querySelector('.tab-badge');
                if (target === regId) {
                    btn.className = 'reg-tab-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-2xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white';
                } else {
                    btn.className = 'reg-tab-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 hover:text-gray-900 bg-white border border-gray-200 hover:border-gray-300 transition-all shadow-2xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700';
                }
            });

            // Automatically open / expand the selected regulation section directly!
            if (regId !== 'all') {
                const sec = document.getElementById('master-section-reg-' + regId);
                if (sec) {
                    const activeBody = currentCourseLayout === 'table' ? sec.querySelector('.master-reg-table') : sec.querySelector('.master-reg-cards');
                    const otherBody = currentCourseLayout === 'table' ? sec.querySelector('.master-reg-cards') : sec.querySelector('.master-reg-table');
                    const chevron = sec.querySelector('.master-reg-chevron svg');
                    const toggleText = sec.querySelector('.master-reg-toggle-text');
                    if (otherBody) otherBody.classList.add('hidden');
                    if (activeBody) activeBody.classList.remove('hidden');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                    if (toggleText) toggleText.textContent = 'Collapse';
                }
                allSectionsExpanded = true;
                const toggleBtnText = document.getElementById('toggleAllMasterText');
                const toggleBtnIcon = document.getElementById('toggleAllMasterIcon');
                if (toggleBtnText) toggleBtnText.textContent = 'Collapse All';
                if (toggleBtnIcon) toggleBtnIcon.style.transform = 'rotate(0deg)';
            } else {
                allSectionsExpanded = true;
                const toggleBtnText = document.getElementById('toggleAllMasterText');
                const toggleBtnIcon = document.getElementById('toggleAllMasterIcon');
                if (toggleBtnText) toggleBtnText.textContent = 'Collapse All';
                if (toggleBtnIcon) toggleBtnIcon.style.transform = 'rotate(0deg)';
                document.querySelectorAll('.master-regulation-section').forEach(sec => {
                    const activeBody = currentCourseLayout === 'table' ? sec.querySelector('.master-reg-table') : sec.querySelector('.master-reg-cards');
                    const otherBody = currentCourseLayout === 'table' ? sec.querySelector('.master-reg-cards') : sec.querySelector('.master-reg-table');
                    const chevron = sec.querySelector('.master-reg-chevron svg');
                    const toggleText = sec.querySelector('.master-reg-toggle-text');
                    if (otherBody) otherBody.classList.add('hidden');
                    if (activeBody) activeBody.classList.remove('hidden');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                    if (toggleText) toggleText.textContent = 'Collapse';
                });
            }

            const searchInput = document.getElementById('courseMaterialSearch');
            window.filterCourseCards(searchInput ? searchInput.value : '');
        };

        // Filter materials by Subfolder Category tab
        window.selectCombinedFolderTab = function(folderKey) {
            activeFolderFilter = folderKey;
            document.querySelectorAll('.comb-tab-btn').forEach(btn => {
                const target = btn.getAttribute('data-folder-target');
                const badge = btn.querySelector('.tab-badge');
                if (target === folderKey) {
                    btn.className = 'comb-tab-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-2xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white';
                } else {
                    btn.className = 'comb-tab-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 hover:text-gray-900 bg-white border border-gray-200 hover:border-gray-300 transition-all shadow-2xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700';
                }
            });

            // Automatically open / expand the selected combined folder directly!
            if (folderKey !== 'all') {
                const sec = document.getElementById('comb-section-' + folderKey);
                if (sec) {
                    const body = sec.querySelector('.comb-subfolder-body');
                    const chevron = sec.querySelector('.comb-folder-chevron svg');
                    const toggleText = sec.querySelector('.comb-folder-toggle-text');
                    if (body) body.classList.remove('hidden');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                    if (toggleText) toggleText.textContent = 'Collapse';
                }
                allSectionsExpanded = true;
                const toggleBtnText = document.getElementById('toggleAllMasterText');
                const toggleBtnIcon = document.getElementById('toggleAllMasterIcon');
                if (toggleBtnText) toggleBtnText.textContent = 'Collapse All';
                if (toggleBtnIcon) toggleBtnIcon.style.transform = 'rotate(0deg)';
            } else {
                allSectionsExpanded = true;
                const toggleBtnText = document.getElementById('toggleAllMasterText');
                const toggleBtnIcon = document.getElementById('toggleAllMasterIcon');
                if (toggleBtnText) toggleBtnText.textContent = 'Collapse All';
                if (toggleBtnIcon) toggleBtnIcon.style.transform = 'rotate(0deg)';
                document.querySelectorAll('.comb-subfolder-section').forEach(sec => {
                    const body = sec.querySelector('.comb-subfolder-body');
                    const chevron = sec.querySelector('.comb-folder-chevron svg');
                    const toggleText = sec.querySelector('.comb-folder-toggle-text');
                    if (body) body.classList.remove('hidden');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                    if (toggleText) toggleText.textContent = 'Collapse';
                });
            }

            const searchInput = document.getElementById('courseMaterialSearch');
            window.filterCourseCards(searchInput ? searchInput.value : '');
        };

        // Toggle individual Regulation Section Accordion
        window.toggleRegulationSection = function(regId) {
            const section = document.getElementById('master-section-reg-' + regId);
            if (!section) return;
            const activeBody = currentCourseLayout === 'table'
                ? section.querySelector('.master-reg-table')
                : section.querySelector('.master-reg-cards');
            const otherBody = currentCourseLayout === 'table'
                ? section.querySelector('.master-reg-cards')
                : section.querySelector('.master-reg-table');
            const chevron = section.querySelector('.master-reg-chevron svg');
            const toggleText = section.querySelector('.master-reg-toggle-text');

            if (!activeBody) return;
            if (otherBody) otherBody.classList.add('hidden');

            const isHidden = activeBody.classList.contains('hidden');
            if (isHidden) {
                activeBody.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                if (toggleText) toggleText.textContent = 'Collapse';
            } else {
                activeBody.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(-90deg)';
                if (toggleText) toggleText.textContent = 'Expand';
            }
        };

        // Toggle individual Combined Subfolder Accordion
        window.toggleCombinedSubfolder = function(folderKey) {
            const section = document.getElementById('comb-section-' + folderKey);
            if (!section) return;
            const body = section.querySelector('.comb-subfolder-body');
            const chevron = section.querySelector('.comb-folder-chevron svg');
            const toggleText = section.querySelector('.comb-folder-toggle-text');

            if (!body) return;
            const isHidden = body.classList.contains('hidden');
            if (isHidden) {
                body.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                if (toggleText) toggleText.textContent = 'Collapse';
            } else {
                body.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(-90deg)';
                if (toggleText) toggleText.textContent = 'Expand';
            }
        };

        // Expand/Collapse All Master Sections
        let allSectionsExpanded = true;
        window.toggleAllMasterSections = function() {
            allSectionsExpanded = !allSectionsExpanded;
            const toggleBtnText = document.getElementById('toggleAllMasterText');
            const toggleBtnIcon = document.getElementById('toggleAllMasterIcon');

            if (toggleBtnText) toggleBtnText.textContent = allSectionsExpanded ? 'Collapse All' : 'Expand All';
            if (toggleBtnIcon) toggleBtnIcon.style.transform = allSectionsExpanded ? 'rotate(0deg)' : 'rotate(-90deg)';

            if (currentViewMode === 'courses') {
                document.querySelectorAll('.master-regulation-section').forEach(sec => {
                    const activeBody = currentCourseLayout === 'table'
                        ? sec.querySelector('.master-reg-table')
                        : sec.querySelector('.master-reg-cards');
                    const otherBody = currentCourseLayout === 'table'
                        ? sec.querySelector('.master-reg-cards')
                        : sec.querySelector('.master-reg-table');
                    const chevron = sec.querySelector('.master-reg-chevron svg');
                    const toggleText = sec.querySelector('.master-reg-toggle-text');

                    if (otherBody) otherBody.classList.add('hidden');

                    if (activeBody) {
                        if (allSectionsExpanded) {
                            activeBody.classList.remove('hidden');
                            if (chevron) chevron.style.transform = 'rotate(0deg)';
                            if (toggleText) toggleText.textContent = 'Collapse';
                        } else {
                            activeBody.classList.add('hidden');
                            if (chevron) chevron.style.transform = 'rotate(-90deg)';
                            if (toggleText) toggleText.textContent = 'Expand';
                        }
                    }
                });
            } else {
                document.querySelectorAll('.comb-subfolder-section').forEach(sec => {
                    const body = sec.querySelector('.comb-subfolder-body');
                    const chevron = sec.querySelector('.comb-folder-chevron svg');
                    const toggleText = sec.querySelector('.comb-folder-toggle-text');
                    if (body) {
                        if (allSectionsExpanded) {
                            body.classList.remove('hidden');
                            if (chevron) chevron.style.transform = 'rotate(0deg)';
                            if (toggleText) toggleText.textContent = 'Collapse';
                        } else {
                            body.classList.add('hidden');
                            if (chevron) chevron.style.transform = 'rotate(-90deg)';
                            if (toggleText) toggleText.textContent = 'Expand';
                        }
                    }
                });
            }
        };

        // Live Search across courses or materials based on current view mode
        window.filterCourseCards = function(query) {
            const cleanQuery = (query || '').toLowerCase().trim();
            const clearBtn = document.getElementById('courseSearchClearBtn');
            const noMatchBox = document.getElementById('masterNoMatchBox');

            if (clearBtn) {
                if (cleanQuery.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            let totalMatches = 0;

            if (currentViewMode === 'courses') {
                const regSections = document.querySelectorAll('.master-regulation-section');
                regSections.forEach(section => {
                    const sectionRegId = section.getAttribute('data-reg-id');
                    const isRegMatch = (activeRegFilter === 'all' || activeRegFilter === sectionRegId);

                    if (!isRegMatch) {
                        section.style.display = 'none';
                        return;
                    }

                    const cards = section.querySelectorAll('.course-card');
                    const tableRows = section.querySelectorAll('.course-table-row');
                    let visibleCardsInSection = 0;

                    cards.forEach(card => {
                        const searchData = (card.getAttribute('data-search') || '').toLowerCase();
                        if (cleanQuery === '' || searchData.includes(cleanQuery)) {
                            card.style.display = '';
                            visibleCardsInSection++;
                            totalMatches++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    tableRows.forEach(row => {
                        const searchData = (row.getAttribute('data-search') || '').toLowerCase();
                        if (cleanQuery === '' || searchData.includes(cleanQuery)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    section.style.display = visibleCardsInSection > 0 ? '' : 'none';
                });
            } else {
                const folderSections = document.querySelectorAll('.comb-subfolder-section');
                folderSections.forEach(section => {
                    const folderKey = section.getAttribute('data-folder-key');
                    const isFolderMatch = (activeFolderFilter === 'all' || activeFolderFilter === folderKey);

                    if (!isFolderMatch) {
                        section.style.display = 'none';
                        return;
                    }

                    const cards = section.querySelectorAll('.comb-material-card');
                    let visibleCardsInFolder = 0;

                    cards.forEach(card => {
                        const searchData = (card.getAttribute('data-search') || '').toLowerCase();
                        if (cleanQuery === '' || searchData.includes(cleanQuery)) {
                            card.style.display = '';
                            visibleCardsInFolder++;
                            totalMatches++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    section.style.display = visibleCardsInFolder > 0 ? '' : 'none';
                });
            }

            if (noMatchBox) {
                noMatchBox.classList.toggle('hidden', totalMatches > 0);
            }
        };

        // Reset course/material search
        window.clearCourseSearch = function() {
            const searchInput = document.getElementById('courseMaterialSearch');
            if (searchInput) {
                searchInput.value = '';
                window.filterCourseCards('');
                searchInput.focus();
            }
        };

        // Live Search for Assignments
        window.filterAssignmentCards = function(query) {
            const cleanQuery = (query || '').toLowerCase().trim();
            const courseFilter = (document.getElementById('assignmentCourseSelect')?.value || '').trim();
            const cards = document.querySelectorAll('.assignment-card');

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                const cardCourseId = card.getAttribute('data-course-id') || '';

                const matchesQuery = cleanQuery === '' || searchData.includes(cleanQuery);
                const matchesCourse = courseFilter === '' || cardCourseId === courseFilter;

                if (matchesQuery && matchesCourse) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        };

        // Course filter for Assignments
        window.filterAssignmentByCourse = function(courseId) {
            const searchQuery = document.getElementById('assignmentSearchInput')?.value || '';
            window.filterAssignmentCards(searchQuery);
        };

        // Check URL hash or query on load
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash === '#assignments') {
                window.switchSectionTab('assignments');
            } else if (window.location.hash === '#courses' || window.location.search.includes('view=courses')) {
                window.switchAllCoursesMode('courses');
            }
        });

        // Allocate Division Modal Open / Close Handlers
        window.openAllocateDivisionModal = function(data) {
            const modal = document.getElementById('allocateDivisionModal');
            const form = document.getElementById('allocateDivisionForm');
            const titleInput = document.getElementById('allocate-title-input');
            const divisionSelect = document.getElementById('allocate-division-select');
            const platformInput = document.getElementById('allocate-platform-select');
            const idInput = document.getElementById('allocate-material-id');
            const subtitleEl = document.getElementById('allocate-material-subtitle');

            if (!modal || !form) return;

            if (data.updateUrl) {
                form.action = data.updateUrl;
            }
            if (idInput && data.id) idInput.value = data.id;
            if (titleInput && data.title) titleInput.value = data.title;
            if (divisionSelect && data.division) divisionSelect.value = data.division;
            if (platformInput) platformInput.value = data.platform || '';
            if (subtitleEl) {
                subtitleEl.textContent = `Type: ${(data.type || 'Material').toUpperCase()} • Format: ${data.platform || 'Standard'}`;
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                if (divisionSelect) divisionSelect.focus();
            }, 50);
        };

        window.closeAllocateDivisionModal = function() {
            const modal = document.getElementById('allocateDivisionModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        };

        // Close Allocate Modal on backdrop click
        document.getElementById('allocateDivisionModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeAllocateDivisionModal();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeAllocateDivisionModal();
            }
        });
    </script>

    <!-- Allocate / Edit Division Modal -->
    <div id="allocateDivisionModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 overflow-y-auto transition-opacity" role="dialog" aria-labelledby="allocateDivisionModalTitle" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 transform transition-all my-8">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50/90 via-white to-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 id="allocateDivisionModalTitle" class="text-base font-extrabold text-gray-900">Allocate / Reallocate Material</h3>
                        <p id="allocate-material-subtitle" class="text-xs text-gray-500 font-medium">Assign or update the division section for this material</p>
                    </div>
                </div>
                <button type="button" onclick="closeAllocateDivisionModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center transition-colors focus:outline-none" aria-label="Close modal">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body Form -->
            <form id="allocateDivisionForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="allocate-material-id" name="id" value="">

                <div class="p-6 space-y-4">
                    <!-- Material Title Input -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Material Title <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <input type="text" id="allocate-title-input" name="title" class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium text-gray-800" required>
                        </div>
                    </div>

                    <!-- Material Division Select -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Material Division / Section <span class="text-red-500">*</span></label>
                        <select id="allocate-division-select" name="division" class="w-full px-3 py-2.5 text-xs sm:text-sm border border-gray-300 bg-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium text-gray-800 shadow-2xs" required>
                            <option value="">-- Select Division --</option>
                            <option value="Course objective and outcomes">🎯 Course objective and outcomes</option>
                            <option value="Syllabus document">📜 Syllabus document</option>
                            <option value="PPTs">📊 PPTs</option>
                            <option value="Lecture notes">📝 Lecture notes</option>
                            <option value="Lab manual">🧪 Lab manual</option>
                            <option value="Assigment Questions">📋 Assigment Questions</option>
                            <option value="Question Bank">🗂️ Question Bank</option>
                            <option value="Gate Questions">🎓 Gate Questions</option>
                            <option value="Previous Question Papers">📑 Previous Question Papers</option>
                            <option value="Videos">🎥 Videos</option>
                            <option value="Additional material">📁 Additional material</option>
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1">Select which section this resource belongs to so students and faculty can find it organized properly.</p>
                    </div>

                    <!-- Platform / Source Selection (Optional) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Platform / Format</label>
                        <input type="text" id="allocate-platform-select" name="platform" placeholder="e.g. pdf, ppt, youtube, website" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium text-gray-700 bg-gray-50/50">
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeAllocateDivisionModal()" class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none transition-colors">Cancel</button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-xs hover:shadow transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Save Allocation</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
