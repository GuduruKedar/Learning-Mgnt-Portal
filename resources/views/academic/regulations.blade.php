<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Regulations - LMS</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
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
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 focus:outline-none">Logout</button>
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
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Manage Regulations</h1>
                    <button type="button" id="toggleCreateFormBtn" onclick="toggleRegulationForm()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-all flex items-center gap-2 focus:outline-none cursor-pointer">
                        <span id="toggleBtnText">{{ $errors->any() ? '− Close Form' : '+ Create New Regulation' }}</span>
                        <svg id="toggleIcon" class="w-4 h-4 transform transition-transform duration-200 {{ $errors->any() ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>

                <!-- Total Regulations Stat Card -->
                <div id="stats-container" class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-4 sm:p-5 flex items-center justify-between transition-all hover:shadow-md">
                    <button type="button" onclick="openRegulationsBreakdownModal()" class="flex items-center gap-4 text-left cursor-pointer group focus:outline-none w-full">
                        <div class="p-3 sm:p-3.5 rounded-full bg-indigo-100 text-indigo-600 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="flex-1 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <span class="text-2xl sm:text-3xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $totalRegulations }}</span>
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-0.5 rounded-full group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                        Click to View All Regulations Breakdown
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </span>
                                </div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mt-0.5">Total Regulations Registered</p>
                            </div>
                            <div class="flex items-center gap-2 mt-1 sm:mt-0">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $activeRegulationsCount }} Active
                                </span>
                                @if($totalRegulations - $activeRegulationsCount > 0)
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $totalRegulations - $activeRegulationsCount }} Inactive
                                </span>
                                @endif
                            </div>
                        </div>
                    </button>
                </div>

                <!-- Create Form Container (Full Width) -->
                <div id="createRegulationFormContainer" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 {{ $errors->any() ? '' : 'hidden' }} mb-6 transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Create Regulation</h2>
                        <button type="button" onclick="toggleRegulationForm()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <form action="{{ route('academic.regulations.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Program Type</label>
                            <select name="program_type" id="programTypeSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white" required>
                                <option value="">-- Select Program Type --</option>
                                @foreach($availableProgramTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Regulation Code <span class="text-xs text-gray-400 font-normal">(e.g., R22)</span></label>
                            <input type="text" name="code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. R22" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Curriculum <span class="text-xs text-gray-400 font-normal">(optional, e.g. C22/C24)</span></label>
                            <div class="flex gap-2">
                                <input type="text" name="curriculum" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. C22">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors whitespace-nowrap">
                                    Save
                                </button>
                                <button type="button" onclick="toggleRegulationForm()" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-3 rounded-md shadow-xs transition-colors">
                                    Cancel
                                </button>
                            </div>
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
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex flex-col mb-6 shadow-sm">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-bold">Please correct the following errors:</p>
                        </div>
                        <ul class="list-disc list-inside text-sm ml-8">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <!-- List (Full Width) -->
                    <div class="w-full">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-4 border-b border-gray-100 bg-white">
                                <select id="regulationSearchInput" class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm">
                                    <option value="">-- All Programs --</option>
                                    @foreach($availableProgramTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Regulation Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Curriculum</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Program Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($regulations as $reg)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $reg->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reg->curriculum ?? ($reg->name !== $reg->code ? $reg->name : '-') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="font-medium text-gray-800">{{ $reg->program_type }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($reg->status === 'Active')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-4">
                                                <button type="button" class="text-indigo-600 hover:text-indigo-900 edit-regulation-btn" 
                                                    data-id="{{ $reg->id }}"
                                                    data-code="{{ $reg->code }}"
                                                    data-curriculum="{{ $reg->curriculum ?? ($reg->name !== $reg->code ? $reg->name : '') }}"
                                                    data-status="{{ $reg->status }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('academic.regulations.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Delete this regulation?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No regulations found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $regulations->links() }}
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
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-auto">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm w-full sm:w-auto">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Regulation Modal -->
    <div id="editRegulationModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50">
                <h3 class="text-lg font-bold text-indigo-900">Edit Regulation</h3>
                <button type="button" id="closeEditModalBtn" class="text-indigo-400 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form id="editRegulationForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Regulation Code <span class="text-xs text-gray-400 font-normal">(e.g., R22)</span></label>
                            <input type="text" name="code" id="edit_code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Curriculum <span class="text-xs text-gray-400 font-normal">(optional, e.g. C22/C24)</span></label>
                            <input type="text" name="curriculum" id="edit_curriculum" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="edit_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors">
                                Update Regulation
                            </button>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" id="cancelEditModalBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-auto">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Regulations Breakdown Modal -->
    <div id="regulationsBreakdownModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-100 animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-white border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-indigo-100 text-indigo-700 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">All Registered Regulations</h3>
                        <p class="text-xs text-gray-500">Overview of regulations and curriculum across programs</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                        {{ $totalRegulations }} Total
                    </span>
                    <button type="button" onclick="closeRegulationsBreakdownModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Search Filter -->
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-100">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="modalRegulationSearch" oninput="filterModalRegulations(this.value)" placeholder="Search regulations by code, curriculum or program type..." class="w-full pl-9 pr-4 py-2 text-xs sm:text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Regulations Cards Container -->
            <div class="p-5 sm:p-6 overflow-y-auto max-h-[60vh] bg-gray-50/40 space-y-3" id="modalRegulationsList">
                @forelse($allRegulations as $regItem)
                <div onclick="selectProgramFromModal('{{ $regItem->program_type }}')" 
                     class="modal-reg-item bg-white p-4 rounded-xl border border-indigo-100 hover:border-indigo-400 shadow-xs hover:shadow-md transition-all flex items-center justify-between cursor-pointer group"
                     data-search="{{ strtolower($regItem->code . ' ' . $regItem->curriculum . ' ' . $regItem->program_type . ' ' . $regItem->status) }}">
                    <div class="pr-3">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $regItem->program_type }}
                            </span>
                            @if($regItem->curriculum)
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200">
                                Curriculum: {{ $regItem->curriculum }}
                            </span>
                            @endif
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $regItem->status === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                {{ $regItem->status }}
                            </span>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 group-hover:text-indigo-700 transition-colors flex items-center gap-2">
                            <span>Regulation {{ $regItem->code }}</span>
                            @if(!empty($regItem->curriculum))
                                <span class="text-xs font-semibold text-purple-600">({{ $regItem->curriculum }})</span>
                            @endif
                        </h4>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-xs font-semibold text-indigo-600 group-hover:text-indigo-800 opacity-0 group-hover:opacity-100 transition-opacity hidden sm:inline">Filter in table</span>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">
                    <p class="text-sm font-medium text-gray-900">No regulations are currently registered.</p>
                </div>
                @endforelse
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>Click on any regulation to filter the main table</span>
                <button type="button" onclick="closeRegulationsBreakdownModal()" class="px-3.5 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    @include('partials.bulk_upload_modal')

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Regulations Breakdown Modal Functions
        window.openRegulationsBreakdownModal = function() {
            const modal = document.getElementById('regulationsBreakdownModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            const searchInput = document.getElementById('modalRegulationSearch');
            if (searchInput) {
                searchInput.value = '';
                filterModalRegulations('');
                setTimeout(() => searchInput.focus(), 100);
            }
        };

        window.closeRegulationsBreakdownModal = function() {
            const modal = document.getElementById('regulationsBreakdownModal');
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        window.filterModalRegulations = function(query) {
            const cleanQuery = (query || '').toLowerCase().trim();
            const items = document.querySelectorAll('#modalRegulationsList .modal-reg-item');
            items.forEach(item => {
                const searchData = item.getAttribute('data-search') || '';
                if (cleanQuery === '' || searchData.includes(cleanQuery)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        };

        window.selectProgramFromModal = function(programType) {
            closeRegulationsBreakdownModal();
            const searchSelect = document.getElementById('regulationSearchInput');
            if (searchSelect) {
                searchSelect.value = programType;
                searchSelect.dispatchEvent(new Event('change'));
            }
        };

        // Close breakdown modal on Escape or Backdrop click
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRegulationsBreakdownModal();
            }
        });
        const regModalEl = document.getElementById('regulationsBreakdownModal');
        if (regModalEl) {
            regModalEl.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRegulationsBreakdownModal();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {

            // Edit Regulation Modal Logic
            const editRegulationModal = document.getElementById('editRegulationModal');
            const closeEditModalBtn = document.getElementById('closeEditModalBtn');
            const cancelEditModalBtn = document.getElementById('cancelEditModalBtn');
            const editRegulationForm = document.getElementById('editRegulationForm');
            
            if (editRegulationModal) {
                const closeEditModal = () => {
                    editRegulationModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                };

                if (closeEditModalBtn) closeEditModalBtn.addEventListener('click', closeEditModal);
                if (cancelEditModalBtn) cancelEditModalBtn.addEventListener('click', closeEditModal);

                document.querySelectorAll('.edit-regulation-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const code = this.getAttribute('data-code');
                        const curriculum = this.getAttribute('data-curriculum');
                        const status = this.getAttribute('data-status');
                        
                        document.getElementById('edit_code').value = code;
                        document.getElementById('edit_curriculum').value = curriculum || '';
                        document.getElementById('edit_status').value = status;
                        
                        editRegulationForm.action = `/regulations/${id}`;
                        
                        editRegulationModal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    });
                });
            }

            // Regulation Table Search Filter
            const searchInput = document.getElementById('regulationSearchInput');
            if (searchInput) {
                searchInput.addEventListener('change', function() {
                    const filter = this.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const programTypeCell = row.querySelector('td:nth-child(3)');
                        if (programTypeCell) {
                            const programType = programTypeCell.textContent.toLowerCase().trim();
                            if (filter === '' || programType === filter) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }

            // Create Regulation Toggle function (global for immediate single-click response)
            window.toggleRegulationForm = function() {
                const container = document.getElementById('createRegulationFormContainer');
                const icon = document.getElementById('toggleIcon');
                const btnText = document.getElementById('toggleBtnText');
                if (!container) return;

                const isCurrentlyHidden = container.classList.contains('hidden');
                if (isCurrentlyHidden) {
                    container.classList.remove('hidden');
                    if (icon) icon.classList.add('rotate-180');
                    if (btnText) btnText.textContent = '− Close Form';
                } else {
                    container.classList.add('hidden');
                    if (icon) icon.classList.remove('rotate-180');
                    if (btnText) btnText.textContent = '+ Create New Regulation';
                }
            };
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('select:not(.no-tomselect):not(#edit_status):not(#programTypeSelect):not(#regulationSearchInput)').forEach(function(el) {
                if (!el.classList.contains('tomselected')) {
                    new TomSelect(el, {
                        create: false,
                        maxOptions: null,
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

