<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LMS</title>
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
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-2.5 ml-auto">
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/70">
            <div class="w-full space-y-6">
                
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-br from-blue-900 via-indigo-900 to-blue-800 rounded-2xl p-6 sm:p-7 shadow-xl relative overflow-hidden border border-blue-700/50">
                    <!-- Abstract modern background elements -->
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-blue-500 opacity-20 blur-3xl pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-56 h-56 rounded-full bg-indigo-500 opacity-20 blur-2xl pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col gap-6">
                        <div>
                            <div class="flex items-center gap-4 mb-4">
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-white/10 text-white backdrop-blur-md border border-white/20 shadow-sm shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-tight">Welcome back,<br class="sm:hidden"/><span class="text-blue-200"> {{ $admin->first_name }}!</span></h2>
                            </div>
                            <p class="text-blue-100/90 text-base sm:text-lg max-w-2xl font-normal leading-relaxed">
                                Coordinate and manage your department's staff, students, and academic programs seamlessly.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                            <!-- School Card -->
                            <div class="flex items-start gap-4 bg-white/5 backdrop-blur-md p-4 sm:p-5 rounded-xl border border-white/10 hover:bg-white/10 transition-colors shadow-inner">
                                <div class="p-2.5 rounded-lg bg-blue-400/20 text-blue-200 shrink-0 border border-blue-400/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div class="flex flex-col min-w-0 pt-0.5">
                                    <span class="text-[11px] font-semibold text-blue-300/80 uppercase tracking-wider mb-1">School</span>
                                    <span class="text-sm sm:text-base font-semibold text-white whitespace-normal leading-snug break-words">{{ $admin->school->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <!-- Department Card -->
                            <div class="flex items-start gap-4 bg-white/5 backdrop-blur-md p-4 sm:p-5 rounded-xl border border-white/10 hover:bg-white/10 transition-colors shadow-inner">
                                <div class="p-2.5 rounded-lg bg-indigo-400/20 text-indigo-200 shrink-0 border border-indigo-400/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="flex flex-col min-w-0 pt-0.5">
                                    <span class="text-[11px] font-semibold text-indigo-300/80 uppercase tracking-wider mb-1">Department</span>
                                    <span class="text-sm sm:text-base font-semibold text-white whitespace-normal leading-snug break-words">{{ $admin->department->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-3">Dashboard Overview</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-5">
                        <!-- Regulations Card -->
                        <button type="button" id="openRegulationsModalBtn" class="group bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col hover:shadow-lg hover:border-indigo-300 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer text-left w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 overflow-hidden relative z-0">
                            <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-indigo-50/50 rounded-bl-full -z-10 group-hover:scale-125 transition-transform duration-500"></div>
                            <div class="flex items-start justify-between w-full mb-2 sm:mb-3">
                                <div class="p-2 sm:p-3 rounded-xl bg-indigo-50 text-indigo-600 shadow-xs border border-indigo-100/50 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-200">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-slate-50 text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                            <div class="w-full mt-auto">
                                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 group-hover:text-indigo-500 transition-colors leading-tight">Total Regulations</p>
                                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 group-hover:text-indigo-700 transition-colors">{{ $totalRegulations ?? 0 }}</p>
                            </div>
                        </button>

                        <!-- Faculty / Staff Card -->
                        <a href="{{ route('staff.index') }}" class="group bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col hover:shadow-lg hover:border-teal-300 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer text-left w-full focus:outline-none overflow-hidden relative z-0">
                            <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-teal-50/50 rounded-bl-full -z-10 group-hover:scale-125 transition-transform duration-500"></div>
                            <div class="flex items-start justify-between w-full mb-2 sm:mb-3">
                                <div class="p-2 sm:p-3 rounded-xl bg-teal-50 text-teal-600 shadow-xs border border-teal-100/50 group-hover:bg-teal-500 group-hover:text-white transition-colors duration-200">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-slate-50 text-slate-400 group-hover:bg-teal-100 group-hover:text-teal-600 transition-colors">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                            <div class="w-full mt-auto">
                                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 group-hover:text-teal-500 transition-colors leading-tight">Dept. Faculty</p>
                                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 group-hover:text-teal-700 transition-colors">{{ $totalStaff ?? 0 }}</p>
                            </div>
                        </a>

                        <!-- Students Card -->
                        <a href="{{ route('students.index') }}" class="group bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex flex-col hover:shadow-lg hover:border-orange-300 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer text-left w-full focus:outline-none overflow-hidden relative z-0">
                            <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-orange-50/50 rounded-bl-full -z-10 group-hover:scale-125 transition-transform duration-500"></div>
                            <div class="flex items-start justify-between w-full mb-2 sm:mb-3">
                                <div class="p-2 sm:p-3 rounded-xl bg-orange-50 text-orange-600 shadow-xs border border-orange-100/50 group-hover:bg-orange-500 group-hover:text-white transition-colors duration-200">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                                </div>
                                <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-slate-50 text-slate-400 group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                            <div class="w-full mt-auto">
                                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 group-hover:text-orange-500 transition-colors leading-tight">Dept. Students</p>
                                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 group-hover:text-orange-700 transition-colors">{{ $totalStudents ?? 0 }}</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Department Programs Section -->
                <div>
                    <div class="flex items-center justify-between mb-3.5">
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Department Programs <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2.5 py-0.5 rounded-full ml-1.5 border border-indigo-100">{{ $departmentPrograms->count() ?? 0 }}</span></h3>
                    </div>
                    
                    @if(isset($departmentPrograms) && $departmentPrograms->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($departmentPrograms as $program)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all hover:-translate-y-1 group flex flex-col justify-between">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-base font-bold text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors">{{ $program->name }}</h4>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700 uppercase tracking-wide">
                                                    {{ $program->code }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 uppercase tracking-wide">
                                                    {{ $program->level }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 uppercase tracking-wide">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ $program->duration_years }} Years
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $programRegulations = isset($allRegulations) ? $allRegulations->filter(function($reg) use ($program) {
                                            return str_contains($program->name, $reg->program_type);
                                        }) : collect();
                                        $programRegulationsCount = $programRegulations->count();
                                    @endphp
                                    <button type="button" onclick="openProgramRegulationsModal('{{ $program->id }}')" class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between w-full focus:outline-none hover:bg-gray-50 transition-colors -mx-5 px-5 pb-5 -mb-5 rounded-b-xl group/btn">
                                        <span class="text-xs font-medium text-gray-500 group-hover/btn:text-indigo-600 transition-colors">Regulations Available</span>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $programRegulationsCount > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">
                                                {{ $programRegulationsCount }}
                                            </span>
                                            <svg class="w-4 h-4 text-gray-300 group-hover/btn:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center flex flex-col items-center justify-center">
                            <div class="bg-gray-50 rounded-full p-4 mb-3">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900">No Programs Found</h3>
                            <p class="mt-1 text-sm text-gray-500 max-w-sm">There are currently no programs assigned to your department. Contact the superadmin if this is an error.</p>
                        </div>
                    @endif
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

    <!-- Regulations Modal -->
    <div id="regulationsModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all flex flex-col max-h-[80vh]">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50 shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <h3 class="text-lg font-bold text-indigo-900">Active Regulations</h3>
                </div>
                <button type="button" id="closeRegulationsModal" class="text-indigo-400 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-0 overflow-y-auto bg-gray-50 flex-1">
                <ul class="divide-y divide-gray-100">
                    @php
                        $groupedRegulations = collect($allRegulations ?? [])->groupBy('program_type');
                    @endphp
                    @forelse($groupedRegulations as $programType => $regs)
                        <li class="bg-gray-100/50 px-4 py-2.5 border-y border-gray-100 first:border-t-0 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $programType }} Programs</h4>
                        </li>
                        @foreach($regs as $reg)
                            <li class="p-4 hover:bg-white transition-colors flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-sm font-bold font-mono bg-indigo-100 text-indigo-800 border border-indigo-200 shadow-2xs shrink-0 min-w-[3.5rem]">
                                        {{ $reg->code }}
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-bold text-gray-900">{{ $reg->name }}</span>
                                            @if(!empty($reg->curriculum))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 shadow-2xs">
                                                    Curriculum: {{ $reg->curriculum }}
                                                </span>
                                            @endif
                                        </div>
                                        @if(!empty($reg->program_type))
                                        <div class="text-xs text-gray-500 mt-0.5 font-medium">
                                            <span>{{ $reg->program_type }} Program</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold shrink-0 ml-3 {{ strtolower($reg->status) == 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                    {{ $reg->status }}
                                </span>
                            </li>
                        @endforeach
                    @empty
                        <li class="p-6 text-center text-sm text-gray-500 italic">No regulations available.</li>
                    @endforelse
                </ul>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end">
                <button type="button" id="closeRegulationsModalBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Close</button>
            </div>
        </div>
    </div>

    <!-- Per-Program Regulations Modals -->
    @if(isset($departmentPrograms))
        @foreach($departmentPrograms as $program)
            @php
                $programRegulations = isset($allRegulations) ? $allRegulations->filter(function($reg) use ($program) {
                    return str_contains($program->name, $reg->program_type);
                }) : collect();
            @endphp
            <div id="programRegulationsModal-{{ $program->id }}" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity program-reg-modal">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all flex flex-col max-h-[80vh]">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50 shrink-0">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <h3 class="text-lg font-bold text-indigo-900 line-clamp-1" title="{{ $program->name }}">Regulations for {{ $program->name }}</h3>
                        </div>
                        <button type="button" onclick="closeProgramRegulationsModal('{{ $program->id }}')" class="text-indigo-400 hover:text-indigo-600 focus:outline-none shrink-0 ml-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-0 overflow-y-auto bg-gray-50 flex-1">
                        <ul class="divide-y divide-gray-100">
                            @forelse($programRegulations as $reg)
                                <li class="p-4 hover:bg-white transition-colors flex items-center justify-between">
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <div class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-sm font-bold font-mono bg-indigo-100 text-indigo-800 border border-indigo-200 shadow-2xs shrink-0 min-w-[3.5rem]">
                                            {{ $reg->code }}
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-sm font-bold text-gray-900">{{ $reg->name }}</span>
                                                @if(!empty($reg->curriculum))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 shadow-2xs">
                                                        Curriculum: {{ $reg->curriculum }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if(!empty($reg->program_type))
                                            <div class="text-xs text-gray-500 mt-0.5 font-medium">
                                                <span>{{ $reg->program_type }} Program</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold shrink-0 ml-3 {{ strtolower($reg->status) == 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                        {{ $reg->status }}
                                    </span>
                                </li>
                            @empty
                                <li class="p-6 text-center text-sm text-gray-500 italic">No regulations assigned to this program yet.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end">
                        <button type="button" onclick="closeProgramRegulationsModal('{{ $program->id }}')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Close</button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif



    <script>        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Regulations Modal Logic
        const regModal = document.getElementById('regulationsModal');
        const openRegBtn = document.getElementById('openRegulationsModalBtn');
                const closeRegBtn = document.getElementById('closeRegulationsModal');
        const closeRegBtn2 = document.getElementById('closeRegulationsModalBtn');

        if (regModal && openRegBtn) {
            const openReg = () => {
                regModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };
            const closeReg = () => {
                regModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            };

            openRegBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openReg();
            });
            if (closeRegBtn) closeRegBtn.addEventListener('click', closeReg);
            if (closeRegBtn2) closeRegBtn2.addEventListener('click', closeReg);
        }

        // Per-Program Regulations Modals Logic
        window.openProgramRegulationsModal = function(programId) {
            const modal = document.getElementById('programRegulationsModal-' + programId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeProgramRegulationsModal = function(programId) {
            const modal = document.getElementById('programRegulationsModal-' + programId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>

