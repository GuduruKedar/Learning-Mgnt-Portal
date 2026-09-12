<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
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
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full">
            
            <div class="flex items-center">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                    @if(Auth::user()->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Admin' }}</span>
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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-4">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Manage Courses</h1>
                    <a href="{{ route('academic.courses.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2 focus:outline-none">
                        <span>+ Add New Course</span>
                    </a>
                </div>

                <!-- Filters Section -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 transition-all hover:shadow-md">
                    <form action="{{ route('academic.courses') }}" method="GET" class="flex flex-row flex-wrap items-center gap-3 w-full" id="filterForm">
                        <div class="flex-1 min-w-[200px]">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" placeholder="Search by course code or name...">
                            </div>
                        </div>
                        
                        <div class="w-full sm:w-48 shrink-0">
                            <label for="regulation_id" class="sr-only">Regulation</label>
                            <select name="regulation_id" id="regulation_id" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-all duration-200 cursor-pointer appearance-none">
                                <option value="">All Regulations</option>
                                @foreach($regulations as $reg)
                                    <option value="{{ $reg->id }}" {{ request('regulation_id') == $reg->id ? 'selected' : '' }}>{{ $reg->code }}{{ !empty($reg->curriculum) ? ' - ' . $reg->curriculum : ($reg->name && $reg->name !== $reg->code ? ' - ' . $reg->name : '') }} ({{ $reg->program_type }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        @if(Auth::user()->role === 'sa')
                        <div class="w-full sm:w-48 shrink-0">
                            <label for="department" class="sr-only">Department</label>
                            <select name="department" id="department" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-all duration-200 cursor-pointer appearance-none">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <div class="w-full sm:w-32 shrink-0">
                            <label for="year" class="sr-only">Year</label>
                            <select name="year" id="year" onchange="this.form.submit()" class="no-tomselect block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-all duration-200 cursor-pointer appearance-none">
                                <option value="">All Years</option>
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="w-full sm:w-32 shrink-0">
                            <label for="semester" class="sr-only">Semester</label>
                            <select name="semester" id="semester" onchange="this.form.submit()" class="no-tomselect block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-all duration-200 cursor-pointer appearance-none">
                                <option value="">All Sems</option>
                                @for($i = 1; $i <= 2; $i++)
                                    <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Sem {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        

                        
                        <div class="flex items-center space-x-3 shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                                Apply Filters
                            </button>
                            @if(request()->hasAny(['search', 'regulation_id', 'department', 'year', 'semester']) && (request('search') != '' || request('regulation_id') != '' || request('department') != '' || request('year') != '' || request('semester') != ''))
                            <a href="{{ route('academic.courses') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Clear
                            </a>
                            @endif
                        </div>
                    </form>
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

                <div>
                    <!-- List (Full Width) -->
                    <div class="w-full">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Program & Reg</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Year/Sem</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Dept</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($courses as $course)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $course->code }}</div>
                                            <div class="text-gray-500">{{ $course->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="font-medium">{{ $course->regulation->program_type ?? 'N/A' }}</div>
                                            <div class="text-gray-500">{{ $course->regulation->code ?? 'N/A' }}{{ !empty($course->regulation->curriculum) ? ' - ' . $course->regulation->curriculum : ($course->regulation && $course->regulation->name && $course->regulation->name !== $course->regulation->code ? ' - ' . $course->regulation->name : '') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="font-medium">{{ $course->year ?? 'N/A' }}</div>
                                            <div class="text-gray-500">{{ $course->semester ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $course->department->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('academic.courses.edit', $course->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('academic.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Delete this course?');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No courses found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $courses->links() }}
                    </div>
                        </div>
                    </div>
                </div>
            </div>
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


    @include('partials.bulk_upload_modal')

    <script>
        const availableStaff = @json($availableStaff->map(function($s) {
            return ['id' => $s->id, 'name' => $s->first_name . ' ' . $s->last_name];
        }));
        const csrfToken = '{{ csrf_token() }}';        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Dynamic Department Filtering based on School Selection
        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelect = document.getElementById('school');
            const departmentSelect = document.getElementById('department');
            
            if (schoolSelect && departmentSelect) {
                // Store all original department options (excluding the default "All Departments" option)
                const allDepartments = Array.from(departmentSelect.options).filter(opt => opt.value !== '');
                const defaultOption = departmentSelect.options[0];
                
                function filterDepartments() {
                    const selectedSchoolCode = schoolSelect.value;
                    let currentDeptValue = departmentSelect.value;
                    let deptStillValid = false;

                    // Clear current options except the default one
                    departmentSelect.innerHTML = '';
                    departmentSelect.appendChild(defaultOption);

                    allDepartments.forEach(option => {
                        if (!selectedSchoolCode || option.getAttribute('data-school-id') === selectedSchoolCode) {
                            departmentSelect.appendChild(option);
                            if (option.value === currentDeptValue) {
                                deptStillValid = true;
                            }
                        }
                    });

                    // If the previously selected department is no longer in the list, reset to default
                    if (!deptStillValid && currentDeptValue !== '') {
                        departmentSelect.value = '';
                    }
                }
                
                // Listen for school changes
                schoolSelect.addEventListener('change', filterDepartments);
                
                // Run on initial load
                filterDepartments();
                
                // Ensure the selected department from the request is maintained if it's valid
                const requestedDept = "{{ request('department') }}";
                if(requestedDept) {
                     departmentSelect.value = requestedDept;
                }
            }
        });

        // Dynamic Regulation Filtering and Year/Sem populating based on Program Type
        document.addEventListener('DOMContentLoaded', function() {
            const progSelect = document.getElementById('program_type_select');
            const regSelect = document.getElementById('regulation_id_select');
            const yearSelect = document.getElementById('year_select');
            const semesterSelect = document.getElementById('semester_select');
            
            if (progSelect && regSelect && yearSelect && semesterSelect) {
                const allRegs = Array.from(regSelect.options).filter(opt => opt.value !== '');
                const defRegOpt = regSelect.options[0];
                
                const updateYearSem = function() {
                    const selectedProg = progSelect.value;
                    if (!selectedProg) {
                        yearSelect.innerHTML = '<option value="">-- Year --</option>';
                        semesterSelect.innerHTML = '<option value="">-- Sem --</option>';
                        return;
                    }

                    let maxYear = 4;
                    let maxSem = 8;
                    
                    if (selectedProg === 'M.Tech' || selectedProg === 'Ph.D') {
                        maxYear = 2;
                        maxSem = 4;
                    } else if (selectedProg === 'Degree' || selectedProg === 'Diploma') {
                        maxYear = 3;
                        maxSem = 6;
                    }

                    const currentYear = yearSelect.value;
                    const currentSem = semesterSelect.value;

                    yearSelect.innerHTML = '<option value="">-- Year --</option>';
                    for (let i = 1; i <= maxYear; i++) {
                        let suffix = 'th';
                        if (i === 1) suffix = 'st';
                        else if (i === 2) suffix = 'nd';
                        else if (i === 3) suffix = 'rd';
                        yearSelect.innerHTML += `<option value="${i}" ${currentYear == i ? 'selected' : ''}>${i}${suffix} Year</option>`;
                    }

                    // Reset semester if it's not valid for the new year
                    semesterSelect.innerHTML = '<option value="">-- Sem --</option>';
                    updateSemestersForYear();
                };

                const updateSemestersForYear = function() {
                    const selectedYear = parseInt(yearSelect.value);
                    const currentSem = semesterSelect.value;
                    const selectedProg = progSelect.value;
                    
                    semesterSelect.innerHTML = '<option value="">-- Sem --</option>';
                    
                    if (selectedYear) {
                        for (let i = 1; i <= 2; i++) {
                            let suffix = i === 1 ? 'st' : 'nd';
                            semesterSelect.innerHTML += `<option value="${i}" ${currentSem == i ? 'selected' : ''}>${i}${suffix} Semester</option>`;
                        }
                    }
                };

                yearSelect.addEventListener('change', updateSemestersForYear);

                progSelect.addEventListener('change', function() {
                    const selectedProg = this.value;
                    let currentReg = regSelect.value;
                    let regStillValid = false;
                    
                    regSelect.innerHTML = '';
                    regSelect.appendChild(defRegOpt);
                    
                    allRegs.forEach(opt => {
                        if (!selectedProg || opt.getAttribute('data-program') === selectedProg) {
                            regSelect.appendChild(opt);
                            if (opt.value === currentReg) {
                                regStillValid = true;
                            }
                        }
                    });
                    
                    if (!regStillValid && currentReg !== '') {
                        regSelect.value = '';
                    }

                    updateYearSem();
                });

                // Run on initial load
                updateYearSem();
            }
        });

        // Dynamic generation of course fields
        document.addEventListener('DOMContentLoaded', function() {
            const numCoursesInput = document.getElementById('no_of_courses');
            const dynamicFieldsContainer = document.getElementById('dynamic_course_fields');
            
            if (numCoursesInput && dynamicFieldsContainer) {
                numCoursesInput.addEventListener('input', function() {
                    let num = parseInt(this.value);
                    if (isNaN(num) || num < 1) num = 1;
                    if (num > 9) {
                        num = 9;
                        this.value = 9;
                    }
                    
                    let html = '';
                    for (let i = 1; i <= num; i++) {
                        html += `
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-500 w-8 text-center">${i}</span>
                            <input type="text" name="code[]" placeholder="Code" class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" required>
                            <input type="text" name="name[]" placeholder="Name" class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" required>
                        </div>`;
                    }
                    dynamicFieldsContainer.innerHTML = html;
                });
            }
        });
    </script>
    

    @include('partials.reset_password_modal')
</body>
</html>


