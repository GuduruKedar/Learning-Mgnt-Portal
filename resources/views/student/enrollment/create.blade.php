<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in Courses - LMS</title>
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
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden main-content-wrapper transition-all duration-300">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-2.5 ml-auto">
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/70">
            <div class="w-full space-y-6">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Course Enrollment</h2>
                    <p class="mt-1 text-sm text-slate-500">Select your regulation, year, and semester to view and enroll in available courses.</p>
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
                
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="ml-3">
                                <ul class="list-disc pl-5 space-y-1 text-sm text-red-700">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Selection Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 bg-indigo-50 border-b border-indigo-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <h3 class="text-lg font-semibold text-indigo-900">Academic Selection</h3>
                        <span class="px-3 py-1 bg-white text-indigo-600 text-xs font-semibold rounded-full shadow-sm border border-indigo-100">{{ $department->name ?? 'N/A' }}</span>
                    </div>
                    <div class="p-6">
                        @php
                            $studentProgramCode = Auth::user()->profile?->programs_id;
                            $studentProgram = isset($programs) ? $programs->where('code', $studentProgramCode)->first() : null;
                        @endphp
                        @if($studentProgram)
                            <input type="hidden" id="program" value="{{ $studentProgram->id }}">
                        @endif

                        <div class="space-y-8">
                            <!-- 1. Regulation -->
                            <div id="regulationSection" class="transition-opacity duration-500">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Regulation</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach($regulations as $reg)
                                        <label class="cursor-pointer transition-all duration-300">
                                            <input type="radio" name="regulation_id" value="{{ $reg->id }}" class="peer hidden regulation-radio">
                                            <div class="px-4 py-3 border border-gray-200 rounded-xl text-center text-sm font-medium text-gray-600 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:border-indigo-300 transition-all shadow-sm flex items-center justify-center gap-2">
                                                <span>{{ $reg->display_name }}</span>
                                                <svg class="w-4 h-4 hidden peer-checked:block opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 2. Year -->
                            <div id="yearSection" class="hidden opacity-0 transition-opacity duration-500">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Year</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                    @foreach(['1' => '1st Year', '2' => '2nd Year', '3' => '3rd Year', '4' => '4th Year'] as $val => $label)
                                        <label class="cursor-pointer transition-all duration-300">
                                            <input type="radio" name="year" value="{{ $val }}" class="peer hidden year-radio">
                                            <div class="px-4 py-3 border border-gray-200 rounded-xl text-center text-sm font-medium text-gray-600 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:border-indigo-300 transition-all shadow-sm flex items-center justify-center gap-2">
                                                <span>{{ $label }}</span>
                                                <svg class="w-4 h-4 hidden peer-checked:block opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 3. Semester -->
                            <div id="semesterSection" class="hidden opacity-0 transition-opacity duration-500">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Semester</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                    @foreach(['1' => '1st Semester', '2' => '2nd Semester'] as $val => $label)
                                        <label class="cursor-pointer transition-all duration-300">
                                            <input type="radio" name="semester" value="{{ $val }}" class="peer hidden semester-radio">
                                            <div class="px-4 py-3 border border-gray-200 rounded-xl text-center text-sm font-medium text-gray-600 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:border-indigo-300 transition-all shadow-sm flex items-center justify-center gap-2">
                                                <span>{{ $label }}</span>
                                                <svg class="w-4 h-4 hidden peer-checked:block opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Breadcrumbs (so they know what they picked) -->
                <div id="selectionBreadcrumbs" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 px-4 sm:px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center text-sm font-medium text-gray-600 gap-2" id="breadcrumbText">
                        <!-- Filled via JS -->
                    </div>
                    <button type="button" onclick="location.reload()" class="shrink-0 text-sm text-indigo-600 hover:text-indigo-800 font-semibold underline mt-1 sm:mt-0">Reset Selection</button>
                </div>

                <!-- Courses List Area -->
                <div id="coursesArea" class="hidden">
                    <form action="{{ route('student.enrollment.store') }}" method="POST">
                        @csrf
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 mt-4 gap-4">
                            <h3 class="text-xl font-bold text-gray-900">Available Courses</h3>
                            <button type="submit" id="enrollBtn" class="hidden w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors transform hover:scale-105">
                                Enroll Selected
                            </button>
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="hidden bg-white border border-gray-200 rounded-xl p-12 text-center shadow-sm">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No courses found</h3>
                            <p class="mt-1 text-sm text-gray-500">No courses are available for the selected criteria in your department.</p>
                        </div>

                        <!-- Courses Grid -->
                        <div id="coursesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
                    </form>
                </div>
            </div>

            <footer class="mt-12 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
            </footer>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }

        const regulationRadios = document.querySelectorAll('.regulation-radio');
        const yearRadios = document.querySelectorAll('.year-radio');
        const semesterRadios = document.querySelectorAll('.semester-radio');
        
        const regulationSection = document.getElementById('regulationSection');
        const yearSection = document.getElementById('yearSection');
        const semesterSection = document.getElementById('semesterSection');
        
        const selectionBreadcrumbs = document.getElementById('selectionBreadcrumbs');
        const breadcrumbText = document.getElementById('breadcrumbText');
        const coursesArea = document.getElementById('coursesArea');
        const coursesGrid = document.getElementById('coursesGrid');
        const enrollBtn = document.getElementById('enrollBtn');
        const emptyState = document.getElementById('emptyState');

        function hideSection(section) {
            if (section) section.classList.add('hidden', 'opacity-0');
        }

        function showSection(section) {
            if (!section) return;
            section.classList.remove('hidden');
            setTimeout(() => {
                section.classList.remove('opacity-0');
            }, 50);
        }

        function updateBreadcrumbs() {
            let parts = [];
            const reg = document.querySelector('input[name="regulation_id"]:checked')?.closest('label')?.querySelector('span')?.innerText;
            const yr = document.querySelector('input[name="year"]:checked')?.closest('label')?.querySelector('span')?.innerText;
            const sem = document.querySelector('input[name="semester"]:checked')?.closest('label')?.querySelector('span')?.innerText;

            if (reg) parts.push(`<button type="button" onclick="goToStep('regulation')" class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition-colors">${reg}</button>`);
            if (yr) parts.push(`<button type="button" onclick="goToStep('year')" class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition-colors">${yr}</button>`);
            if (sem) parts.push(`<button type="button" onclick="goToStep('semester')" class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition-colors">${sem}</button>`);

            if (parts.length > 0) {
                breadcrumbText.innerHTML = parts.join('<svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>');
                showSection(selectionBreadcrumbs);
            } else {
                hideSection(selectionBreadcrumbs);
            }
        }

        function goToStep(step) {
            if (coursesArea) coursesArea.classList.add('hidden');
            const selectionCard = document.querySelector('.bg-white.rounded-xl.shadow-sm.border.border-gray-200.overflow-hidden');
            if (selectionCard) selectionCard.classList.remove('hidden');
            
            if (step === 'regulation') {
                document.querySelectorAll('.regulation-radio, .year-radio, .semester-radio').forEach(r => r.checked = false);
                hideSection(yearSection);
                hideSection(semesterSection);
                showSection(regulationSection);
            } else if (step === 'year') {
                document.querySelectorAll('.year-radio, .semester-radio').forEach(r => r.checked = false);
                hideSection(semesterSection);
                showSection(yearSection);
            } else if (step === 'semester') {
                document.querySelectorAll('.semester-radio').forEach(r => r.checked = false);
                showSection(semesterSection);
            }
            updateBreadcrumbs();
        }

        regulationRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                hideSection(regulationSection);
                showSection(yearSection);
                updateBreadcrumbs();
            });
        });

        yearRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                hideSection(yearSection);
                showSection(semesterSection);
                updateBreadcrumbs();
            });
        });

        semesterRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                hideSection(semesterSection);
                const selectionCard = document.querySelector('.bg-white.rounded-xl.shadow-sm.border.border-gray-200.overflow-hidden');
                if (selectionCard) selectionCard.classList.add('hidden');
                updateBreadcrumbs();
                fetchCourses();
            });
        });

        function fetchCourses() {
            let regulationId = document.querySelector('input[name="regulation_id"]:checked')?.value;
            const year = document.querySelector('input[name="year"]:checked')?.value;
            const semester = document.querySelector('input[name="semester"]:checked')?.value;

            if(!regulationId || !year || !semester) {
                return;
            }

            fetch(`{{ route('student.enrollment.fetchCourses') }}?regulation_id=${regulationId}&year=${year}&semester=${semester}`)
                .then(response => response.json())
                .then(data => {
                    coursesGrid.innerHTML = '';
                    coursesArea.classList.remove('hidden');

                    if(data.courses.length === 0) {
                        emptyState.classList.remove('hidden');
                        coursesGrid.classList.add('hidden');
                        enrollBtn.classList.add('hidden');
                    } else {
                        emptyState.classList.add('hidden');
                        coursesGrid.classList.remove('hidden');
                        enrollBtn.classList.remove('hidden');

                        data.courses.forEach(course => {
                            const isEnrolled = data.enrolled_course_ids.includes(course.id);
                            const card = document.createElement('div');
                            card.className = `course-card relative rounded-xl border p-5 flex flex-col justify-between h-full bg-white shadow-sm ${isEnrolled ? 'border-green-300 ring-1 ring-green-300' : 'border-gray-200 hover:border-indigo-300'}`;
                            
                            let checkMarkup = isEnrolled ? 
                                `<div class="absolute top-4 right-4 bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-semibold flex items-center shadow-sm"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>Enrolled</div>` : 
                                `<div class="absolute top-4 right-4">
                                    <input type="checkbox" name="courses[]" value="${course.id}" class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                </div>`;

                            card.innerHTML = `
                                ${checkMarkup}
                                <div>
                                    <div class="inline-flex p-2 rounded-lg bg-indigo-50 text-indigo-600 mb-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 leading-tight pr-8">${course.name}</h4>
                                    <p class="text-sm font-medium text-indigo-600 mt-1">${course.code}</p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-xs text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Year ${course.year}, Sem ${course.semester}
                                    </p>
                                </div>
                            `;
                            coursesGrid.appendChild(card);
                        });
                        
                        // Disable enroll btn if all enrolled
                        if(data.courses.length === data.enrolled_course_ids.length) {
                             enrollBtn.classList.add('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching courses:', error);
                    alert('Error fetching courses. Please try again.');
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>
