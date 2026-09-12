<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
<script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="min-h-screen bg-gray-900 flex flex-col items-center justify-center p-4 sm:p-8 bg-image relative">

    <div class="absolute inset-0 bg-black/50 z-0"></div>

    <div class="relative z-10 w-full max-w-md flex flex-col items-center mt-12 sm:mt-0">
        
        <h1 class="text-3xl sm:text-4xl font-bold text-white tracking-tight text-center mb-16 sm:mb-20 drop-shadow-lg">
            Learning Management
        </h1>

        <div class="glass-panel p-6 sm:p-10 rounded-3xl w-full relative pt-12">

            <div class="mb-8 text-center mt-4">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome Back</h2>
                <p class="text-gray-500 mt-2 font-medium text-sm">Please enter your credentials to continue.</p>
            </div>

            <form method="POST" action="{{ route('authenticate') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="emp_id" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <input type="text" id="emp_id" name="emp_id" class="input-animated w-full px-5 py-3.5 rounded-xl border {{ $errors->has('emp_id') ? 'border-red-500' : 'border-gray-200' }} focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none bg-white/90 text-gray-900" value="{{ old('emp_id') }}" required autofocus autocomplete="off">
                    @error('emp_id')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="input-animated w-full px-5 py-3.5 pr-12 rounded-xl border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-200' }} focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none bg-white/90 text-gray-900" placeholder="••••••••" required autocomplete="off">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none transition-colors">
                            <svg id="eyeOpen" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosed" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-animated w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-4 px-6 rounded-xl shadow-lg">
                        Sign In
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500 font-medium">
                    &copy; {{ date('Y') }} Learning Management System
                </p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>

