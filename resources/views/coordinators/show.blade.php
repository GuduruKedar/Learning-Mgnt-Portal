<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Coordinator - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>

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
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="mb-6 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Coordinator Profile: {{ $coordinator->first_name }} {{ $coordinator->last_name }}</h1>
                    <div class="flex gap-3">
                        <a href="{{ route('coordinators.departments_list') }}" class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-lg shadow-sm transition-all duration-200 ease-in-out text-sm">
                            &larr; Back
                        </a>
                        <a href="{{ route('coordinators.edit', $coordinator->id) }}" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-all duration-200 ease-in-out text-sm">
                            Edit Coordinator
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">First Name</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-800 font-medium">
                                {{ $coordinator->first_name }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Name</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-800 font-medium">
                                {{ $coordinator->last_name }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Employee ID / Username</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-800 font-medium">
                                {{ $coordinator->username }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-800 font-medium">
                                {{ $coordinator->email ?: 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-800 font-medium">
                                {{ $coordinator->phone_number ?: 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">School</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-800 font-medium">
                                {{ $coordinator->profile->school->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-800 font-medium">
                                {{ $coordinator->profile->department->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <div class="w-full px-3 py-2 border border-transparent rounded-md flex items-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-700">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700">TD</span>.</p>
            </footer>
        </main>
    </div>

    <script>        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI(); }
    </script>
</body>
</html>



