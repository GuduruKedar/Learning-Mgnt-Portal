<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1st Year Learning Materials - SSH Department</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-slate-50 text-slate-800 font-sans">

    @include('partials.sidebar')

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-3">
                <a href="{{ route('ssh.dashboard') }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-base sm:text-lg font-bold text-slate-800">1st Year Learning Materials & Notes</h1>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button" onclick="document.getElementById('upload-material-modal').classList.remove('hidden')" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Upload Material
                </button>
                <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Search Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <form method="GET" action="{{ route('ssh.materials.index') }}" class="flex gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by topic title or course code..." class="flex-1 text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4">
                        <button type="submit" class="py-2.5 px-5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl transition-colors">
                            Search
                        </button>
                        <a href="{{ route('ssh.materials.index') }}" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors">
                            Reset
                        </a>
                    </form>
                </div>

                <!-- Materials Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($materials as $material)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $material->course->code ?? '1st Year' }}
                                </span>
                                <span class="text-xs font-medium text-slate-400">
                                    {{ $material->created_at ? $material->created_at->format('M d, Y') : '' }}
                                </span>
                            </div>

                            <div>
                                <h3 class="font-bold text-slate-900 text-sm leading-snug line-clamp-2">{{ $material->title }}</h3>
                                <p class="text-xs text-slate-500 mt-1">{{ $material->course->name ?? 'Foundational Subject' }}</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium truncate max-w-[120px]">
                                {{ $material->staff->first_name ?? 'SSH' }} {{ $material->staff->last_name ?? 'Admin' }}
                            </span>
                            <div class="flex items-center gap-2">
                                @if($material->type === 'link' || !empty($material->url_or_path))
                                    <a href="{{ $material->url_or_path }}" target="_blank" class="font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                        View &rarr;
                                    </a>
                                @else
                                    <a href="{{ route('materials.download', $material->id) }}" class="font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                        Download &rarr;
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('ssh.materials.destroy', $material->id) }}" onsubmit="return confirm('Remove material {{ $material->title }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-1" title="Delete Material">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="sm:col-span-2 lg:col-span-3 bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200">
                        No materials uploaded for 1st year foundational courses yet.
                    </div>
                    @endforelse
                </div>

                @if($materials->hasPages())
                <div class="bg-white rounded-2xl p-4 border border-slate-200">
                    {{ $materials->links() }}
                </div>
                @endif

            </div>
        </main>
    </div>

    <!-- Upload Material Modal -->
    <div id="upload-material-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Upload 1st Year Study Material</h3>
                <button type="button" onclick="document.getElementById('upload-material-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('ssh.materials.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target 1st Year Course *</label>
                    <select name="course_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        <option value="">Select Foundational Course</option>
                        @foreach($firstYearCourses as $c)
                        <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }} (Sem {{ $c->semester }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Material / Topic Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Unit 1: Matrices & Eigenvalues Notes" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Resource Type *</label>
                    <select name="type" id="material-type-select" onchange="toggleMaterialInputs()" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        <option value="file">Document / File (PDF, PPT, Word)</option>
                        <option value="link">Web / Video Resource Link</option>
                    </select>
                </div>

                <div id="file-input-wrapper">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Upload File (Max 25MB)</label>
                    <input type="file" name="file" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div id="url-input-wrapper" class="hidden">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">URL / Link Address</label>
                    <input type="url" name="url" placeholder="https://..." class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('upload-material-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-xs text-slate-700 hover:bg-slate-100">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md">
                        Upload Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMaterialInputs() {
            const type = document.getElementById('material-type-select').value;
            const fileWrapper = document.getElementById('file-input-wrapper');
            const urlWrapper = document.getElementById('url-input-wrapper');
            if (type === 'link') {
                fileWrapper.classList.add('hidden');
                urlWrapper.classList.remove('hidden');
            } else {
                fileWrapper.classList.remove('hidden');
                urlWrapper.classList.add('hidden');
            }
        }
    </script>

</body>
</html>
