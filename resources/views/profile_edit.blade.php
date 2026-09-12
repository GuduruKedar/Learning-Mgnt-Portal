<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - LMS</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar logic        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif
        });
    </script>

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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 bg-gray-50/50 flex flex-col">
            <div class="max-w-4xl mx-auto w-full flex-1">
                
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Dashboard
                    </a>
                </div>

                <!-- Page Header with Gradient Banner -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="h-32 sm:h-40 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600"></div>
                    <div class="px-6 sm:px-10 pb-8">
                        <div class="relative flex justify-between items-end -mt-12 sm:-mt-16 mb-6">
                            <div class="relative">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="h-24 w-24 sm:h-32 sm:w-32 object-cover rounded-full border-4 border-white shadow-lg bg-white">
                                @else
                                    <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-full bg-gradient-to-br from-indigo-100 to-white flex items-center justify-center text-indigo-600 font-bold text-3xl sm:text-5xl shadow-lg border-4 border-white">
                                        {{ substr($user->first_name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 bg-green-500 w-5 h-5 sm:w-6 sm:h-6 rounded-full border-4 border-white" title="Active"></div>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ $user->first_name }} {{ $user->last_name }}</h1>
                            <p class="text-sm sm:text-base text-gray-500 mt-1 font-medium">{{ $user->profile->designation ?? 'Super Admin' }} &bull; {{ $user->department->name ?? 'System' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-10">
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-gray-900">Personal Information</h2>
                        <p class="text-sm text-gray-500">Update your details and how others see you on the platform.</p>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                            
                            <!-- First Name -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">First Name <span class="text-indigo-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900" required>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $user->profile->middle_name ?? '') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900">
                                @error('middle_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Last Name <span class="text-indigo-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900" required>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->profile->phone ?? '') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="10-digit number" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900">
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Photo Upload -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Profile Photo</label>
                                <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                @error('photo')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-1 md:col-span-2 mt-4 pt-6 border-t border-gray-100">
                                <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">System Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                                        <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Username</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $user->username }}</p>
                                    </div>
                                    <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                                        <p class="text-xs text-gray-500 font-semibold uppercase mb-1">School</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $user->school->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                                        <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Department</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $user->department->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3 px-8 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 w-full sm:w-auto">
                                Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>
            
            </div>
            
            <footer class="mt-12 pt-4 pb-2">
                <p class="text-center text-sm text-gray-400 font-medium">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
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
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-auto">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm w-full sm:w-auto">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>




