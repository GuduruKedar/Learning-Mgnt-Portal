<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found | LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6 text-gray-800">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center sm:p-12">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 text-blue-600 mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">404</h1>
        <h2 class="mt-2 text-xl font-semibold text-gray-800">Page Not Found</h2>
        <p class="mt-3 text-sm text-gray-500 leading-relaxed">
            The page or resource you are looking for does not exist or has been moved.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                Go Back
            </a>
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition-colors shadow-sm">
                Return to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
