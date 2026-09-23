<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Allocations (Faculty Assignment) - LMS</title>
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
            
            <div class="flex items-center ml-auto">
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
            <div class="w-full space-y-6">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-4">
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Course Allocations (Faculty Assignment)</h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/80 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                            {{ $courses->total() }} {{ Str::plural('Course', $courses->total()) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('academic.courses') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold py-2 px-3.5 rounded-xl shadow-xs transition-all flex items-center gap-1.5 text-xs">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Courses Catalog</span>
                        </a>
                        <a href="{{ route('academic.courses.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm shadow-indigo-200 transition-all flex items-center gap-1.5 text-xs focus:outline-none">
                            <span>+ Add New Course</span>
                        </a>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 transition-all hover:shadow-md">
                    <form action="{{ route('academic.courses.allocations') }}" method="GET" class="flex flex-row flex-wrap items-center gap-3 w-full" id="filterForm">
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

                        @if(in_array(Auth::user()->role, ['sa', 'ssh_admin']))
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
                                @if(Auth::user()->role === 'ssh_admin')
                                    <option value="1">Year 1</option>
                                @else
                                    <option value="">All Years</option>
                                    @for($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                                    @endfor
                                @endif
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
                            <a href="{{ route('academic.courses.allocations') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-xs" title="Reset all filters">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Reset</span>
                            </a>
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

                <div><!-- List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider rounded-tl-xl">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Program & Reg</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Year/Sem</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Dept</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider rounded-tr-xl">Assigned Staff</th>
                                        
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
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-3 min-w-[220px]">
                                                <!-- Assigned Staff Badges -->
                                                <div class="flex flex-wrap gap-1.5">
                                                    @forelse($course->staff as $s)
                                                        @php
                                                            $sDeptName = $s->profile->department->name ?? ($s->profile->departments_id ?? 'Dept');
                                                            $sDeptShort = $s->profile->department->code ?? (strlen($sDeptName) > 12 ? substr($sDeptName, 0, 10).'..' : $sDeptName);
                                                        @endphp
                                                        <div onclick="showFacultyDetails('{{ addslashes($s->first_name . ' ' . $s->last_name) }}', '{{ addslashes($s->username ?? 'N/A') }}', '{{ addslashes($s->profile->email ?? 'N/A') }}', '{{ addslashes($s->profile->designation ?? 'N/A') }}', '{{ addslashes($s->profile->department->name ?? 'N/A') }}', '{{ route('academic.courses.unallocate', [$course->id, $s->id]) }}')" class="cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-medium shadow-sm transition-all hover:shadow hover:bg-indigo-100" title="View Details">
                                                            <span>{{ $s->first_name }} {{ $s->last_name }}</span>
                                                            <span class="text-[10px] text-indigo-500 font-normal">({{ $sDeptShort }})</span>
                                                        </div>
                                                    @empty
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-50 text-gray-500 border border-gray-100 text-xs italic">
                                                            No staff assigned
                                                        </span>
                                                    @endforelse
                                                </div>
                                                
                                                <!-- Allocation Form -->
                                                <form action="{{ route('academic.courses.allocate', $course->id) }}" method="POST" class="flex items-center gap-2 w-full max-w-[280px]">
                                                    @csrf
                                                    <div class="relative flex-1">
                                                        <select name="staff_id" class="tom-select-staff block w-full text-sm" required placeholder="Assign Staff...">
                                                            <option value=""></option>
                                                            @php
                                                                $groupedStaff = $availableStaff->groupBy(function($st) {
                                                                    return $st->profile->department->name ?? ($st->profile->school->name ?? 'General / S&H');
                                                                });
                                                            @endphp
                                                            @foreach($groupedStaff as $deptName => $staffList)
                                                                <optgroup label="{{ $deptName }}">
                                                                    @foreach($staffList as $staff)
                                                                        @if(!$course->staff->contains('id', $staff->id))
                                                                            <option value="{{ $staff->id }}">{{ $staff->username }} - {{ $staff->first_name }} {{ $staff->last_name }} ({{ $staff->profile->designation ?? 'Faculty' }})</option>
                                                                        @endif
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="shrink-0 flex items-center justify-center w-[34px] h-[34px] rounded-md bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1" title="Assign">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No courses found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    <div class="px-6 py-4 border-t border-gray-100 bg-white rounded-b-xl">
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

        // Dynamic Program to Regulation, Year, and Semester Filtering
        document.addEventListener('DOMContentLoaded', function() {
            const programSelect = document.getElementById('program_type_select');
            const regulationSelect = document.getElementById('regulation_id_select');
            const yearSelect = document.getElementById('year_select');
            const semesterSelect = document.getElementById('semester_select');
            
            if (programSelect && regulationSelect) {
                const allRegulations = Array.from(regulationSelect.options).filter(opt => opt.value !== '');
                const defaultRegOption = regulationSelect.options[0];
                
                function formatOrdinal(n) {
                    const s = ["th", "st", "nd", "rd"];
                    const v = n % 100;
                    return n + (s[(v - 20) % 10] || s[v] || s[0]);
                }
                
                function filterOptions() {
                    const selectedProgram = programSelect.value;
                    let currentRegValue = regulationSelect.value;
                    let regStillValid = false;

                    regulationSelect.innerHTML = '';
                    regulationSelect.appendChild(defaultRegOption);

                    allRegulations.forEach(option => {
                        if (!selectedProgram || option.getAttribute('data-program') === selectedProgram) {
                            regulationSelect.appendChild(option);
                            if (option.value === currentRegValue) {
                                regStillValid = true;
                            }
                        }
                    });

                    if (!regStillValid && currentRegValue !== '') {
                        regulationSelect.value = '';
                    }
                    
                    // Update Year and Semester dropdowns based on program
                    let maxYear = 4, maxSem = 8; // Default B.Tech
                    if (selectedProgram === 'M.Tech' || selectedProgram === 'Ph.D') {
                        maxYear = 2; maxSem = 4;
                    } else if (selectedProgram === 'Degree' || selectedProgram === 'Diploma') {
                        maxYear = 3; maxSem = 6;
                    }
                    
                    let currentYearValue = yearSelect.value;
                    let currentSemValue = semesterSelect.value;
                    
                    yearSelect.innerHTML = '<option value="">Select Year</option>';
                    for(let i=1; i<=maxYear; i++) {
                        let opt = new Option(formatOrdinal(i) + ' Year', i + ' Year');
                        yearSelect.add(opt);
                    }
                    if(currentYearValue && currentYearValue.match(/^\d+/)) {
                        yearSelect.value = currentYearValue;
                    }
                    
                    semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                    for(let i=1; i<=2; i++) {
                        let opt = new Option(formatOrdinal(i) + ' Semester', i);
                        semesterSelect.add(opt);
                    }
                    if(currentSemValue && (currentSemValue == '1' || currentSemValue == '2')) {
                        semesterSelect.value = currentSemValue;
                    }
                }
                
                programSelect.addEventListener('change', filterOptions);
                filterOptions();
            }
        });

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
                        yearSelect.innerHTML = '<option value="">Select Year</option>';
                        semesterSelect.innerHTML = '<option value="">Select Semester</option>';
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

                    yearSelect.innerHTML = '<option value="">Select Year</option>';
                    for (let i = 1; i <= maxYear; i++) {
                        let suffix = 'th';
                        if (i === 1) suffix = 'st';
                        else if (i === 2) suffix = 'nd';
                        else if (i === 3) suffix = 'rd';
                        yearSelect.innerHTML += `<option value="${i}" ${currentYear == i ? 'selected' : ''}>${i}${suffix} Year</option>`;
                    }

                    // Reset semester if it's not valid for the new year
                    semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                    updateSemestersForYear();
                };

                const updateSemestersForYear = function() {
                    const selectedYear = parseInt(yearSelect.value);
                    const currentSem = semesterSelect.value;
                    const selectedProg = progSelect.value;
                    
                    semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                    
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
                            <input type="text" name="code[]" placeholder="Code" class="w-1/3 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" required>
                            <input type="text" name="name[]" placeholder="Name" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" required>
                        </div>`;
                    }
                    dynamicFieldsContainer.innerHTML = html;
                });
            }
        });
    </script>
    

    <!-- Faculty Details Modal -->
    <div id="facultyDetailsModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeFacultyModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="facultyModalName">
                                Faculty Name
                            </h3>
                            <div class="mt-5 space-y-3 bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-100">
                                <div class="grid grid-cols-1 sm:grid-cols-[130px_1fr] gap-1 sm:gap-4 border-b border-gray-200 pb-2.5">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-0.5">Employee Code</span>
                                    <span class="text-sm font-medium text-gray-900 break-words" id="facultyModalCode"></span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-[130px_1fr] gap-1 sm:gap-4 border-b border-gray-200 pb-2.5">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-0.5">Email</span>
                                    <span class="text-sm font-medium text-gray-900 break-all" id="facultyModalEmail"></span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-[130px_1fr] gap-1 sm:gap-4 border-b border-gray-200 pb-2.5">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-0.5">Designation</span>
                                    <span class="text-sm font-medium text-gray-900 break-words" id="facultyModalDesignation"></span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-[130px_1fr] gap-1 sm:gap-4 pt-0.5">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-0.5">Department</span>
                                    <span class="text-sm font-medium text-gray-900 break-words leading-snug" id="facultyModalDept"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse border-t border-gray-200 gap-2">
                    <button type="button" onclick="closeFacultyModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                        Close
                    </button>
                    <form id="facultyModalDeleteForm" method="POST" class="w-full sm:w-auto inline-flex m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                            Remove Staff
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showFacultyDetails(name, code, email, designation, dept, deleteUrl) {
            document.getElementById('facultyModalName').textContent = name;
            document.getElementById('facultyModalCode').textContent = code;
            document.getElementById('facultyModalEmail').textContent = email;
            document.getElementById('facultyModalDesignation').textContent = designation;
            document.getElementById('facultyModalDept').textContent = dept;
            
            // Update delete form action
            document.getElementById('facultyModalDeleteForm').action = deleteUrl;
            
            document.getElementById('facultyDetailsModal').classList.remove('hidden');
        }
        
        function closeFacultyModal() {
            document.getElementById('facultyDetailsModal').classList.add('hidden');
        }
    </script>

    @if(in_array(Auth::user()->role, ['admin', 'sa', 'ssh_admin']))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.tom-select-staff').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    dropdownParent: "body",
                    placeholder: 'Search & Assign...',
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            });
        });
    </script>
    @endif

    @include('partials.reset_password_modal')
    
    <!-- Restore TomSelect default CSS override to make it visible -->
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <style>
        .ts-control {
            border: 1px solid #d1d5db !important;
            background-color: #ffffff !important;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05) !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.375rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            min-height: unset !important;
            cursor: text !important;
        }
        .ts-wrapper.single .ts-control {
            background-color: #ffffff !important;
        }
    </style>
</body>
</html>

