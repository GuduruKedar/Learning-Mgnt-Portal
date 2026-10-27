<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }} - MCQ Management & Results</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
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
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-medium text-emerald-800">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                </div>
                @endif

                <!-- Assignment Overview Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="px-3 py-1 rounded-full text-xs font-bold font-mono tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $assignment->course->code }}
                                </span>
                                <span class="text-xs text-gray-500 font-semibold">{{ $assignment->course->name }}</span>
                                <span class="text-gray-300">&bull;</span>
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800">MCQ Test</span>
                                @if($assignment->isPrivate())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Private Assignment ({{ $totalEnrolled }} Students)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Public Assignment
                                    </span>
                                @endif
                                @if($assignment->status === 'draft')
                                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">Draft</span>
                                @elseif($assignment->isPastDue())
                                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">Past Due</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">Active</span>
                                @endif
                            </div>

                            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $assignment->title }}</h1>

                            @if($assignment->description)
                            <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $assignment->description }}</div>
                            </div>
                            @endif
                        </div>

                        <!-- Assignment Quick Details Badge Box -->
                        <div class="lg:w-72 bg-gradient-to-br from-indigo-50/70 to-slate-50 p-5 rounded-xl border border-indigo-100 space-y-3 shrink-0">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Questions</span>
                                <span class="text-lg font-extrabold text-gray-900">{{ $assignment->questions->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-indigo-100/80 pt-2.5">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Marks</span>
                                <span class="text-lg font-extrabold text-indigo-700">{{ $assignment->max_marks }} <span class="text-xs font-semibold text-gray-500">pts</span></span>
                            </div>
                            <div class="flex items-center justify-between border-t border-indigo-100/80 pt-2.5">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Deadline</span>
                                <span class="text-xs font-bold {{ $assignment->isPastDue() ? 'text-red-600' : 'text-gray-900' }} text-right">
                                    {{ $assignment->due_date->format('M d, Y h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prominent Enrollment & Submissions Metrics Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Card 1: Total Enrolled (Clickable Modal) -->
                    <div onclick="openEnrolledStudentsModal()" class="bg-white hover:bg-slate-50/70 rounded-xl shadow-sm hover:shadow border border-gray-100 hover:border-blue-300 p-5 flex items-center justify-between relative overflow-hidden transition-all cursor-pointer group">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors flex items-center justify-center shrink-0 border border-blue-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">{{ $assignment->isPrivate() ? 'Allowed Students' : 'Total Enrolled' }}</p>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tight mt-0.5">{{ $totalEnrolled }}</h3>
                                <p class="text-[11px] font-medium text-gray-500 truncate mt-0.5">{{ $assignment->isPrivate() ? 'Private Roster in ' . $assignment->course->code : 'Students in ' . $assignment->course->code }}</p>
                            </div>
                        </div>
                        <div class="text-gray-300 group-hover:text-blue-600 transition-colors pr-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                        <div class="absolute right-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                    </div>

                    <!-- Card 2: Submitted (Clickable Tab Filter) -->
                    <div onclick="switchViewTab('results'); filterStudentResults('submitted'); document.getElementById('tab-results-panel').scrollIntoView({behavior: 'smooth'});" class="bg-white hover:bg-slate-50/70 rounded-xl shadow-sm hover:shadow border border-gray-100 hover:border-emerald-300 p-5 flex items-center justify-between relative overflow-hidden transition-all cursor-pointer group">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors flex items-center justify-center shrink-0 border border-emerald-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Submitted</p>
                                <h3 class="text-2xl font-black text-emerald-700 tracking-tight mt-0.5">{{ $totalSubmitted }}</h3>
                                <p class="text-[11px] font-medium text-emerald-600/80 truncate mt-0.5">{{ $submissionRate }}% Submission Rate</p>
                            </div>
                        </div>
                        <div class="text-gray-300 group-hover:text-emerald-600 transition-colors pr-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                        <div class="absolute right-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
                    </div>

                    <!-- Card 3: Pending (Clickable Tab Filter) -->
                    <div onclick="switchViewTab('results'); filterStudentResults('pending'); document.getElementById('tab-results-panel').scrollIntoView({behavior: 'smooth'});" class="bg-white hover:bg-slate-50/70 rounded-xl shadow-sm hover:shadow border border-gray-100 hover:border-amber-300 p-5 flex items-center justify-between relative overflow-hidden transition-all cursor-pointer group">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-xl {{ $totalPending > 0 ? 'bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white' : 'bg-gray-50 text-gray-400' }} transition-colors flex items-center justify-center shrink-0 border {{ $totalPending > 0 ? 'border-amber-100' : 'border-gray-100' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider {{ $totalPending > 0 ? 'text-amber-600' : 'text-gray-400' }}">Pending</p>
                                <h3 class="text-2xl font-black {{ $totalPending > 0 ? 'text-amber-700' : 'text-gray-700' }} tracking-tight mt-0.5">{{ $totalPending }}</h3>
                                <p class="text-[11px] font-medium text-gray-500 truncate mt-0.5">
                                    {{ $totalPending > 0 ? 'Awaiting submissions' : 'All submitted!' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-gray-300 group-hover:text-amber-600 transition-colors pr-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                        <div class="absolute right-0 top-0 bottom-0 w-1 {{ $totalPending > 0 ? 'bg-amber-500' : 'bg-gray-300' }}"></div>
                    </div>

                    <!-- Card 4: Submission Progress Bar -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Completion</span>
                                <span class="text-xs font-extrabold text-indigo-700">{{ $totalSubmitted }} of {{ $totalEnrolled }}</span>
                            </div>
                            <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full rounded-full transition-all duration-500" style="width: {{ $submissionRate }}%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-gray-500 font-medium mt-2">
                            <span>{{ $submissionRate }}% Completed</span>
                        </div>
                        <div class="absolute right-0 top-0 bottom-0 w-1 bg-indigo-500"></div>
                    </div>
                </div>

                <!-- Tabs: Questions Bank / Student Results -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50/50 flex">
                        <button type="button" id="tab-questions-btn" onclick="switchViewTab('questions')" class="px-6 py-3.5 text-sm font-bold border-b-2 border-indigo-600 text-indigo-600 flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            MCQ Question Bank ({{ $assignment->questions->count() }})
                        </button>
                        <button type="button" id="tab-results-btn" onclick="switchViewTab('results')" class="px-6 py-3.5 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Student Results & Submissions ({{ $totalSubmitted }}/{{ $totalEnrolled }})
                        </button>
                    </div>

                    <!-- Panel 1: Questions List -->
                    <div id="tab-questions-panel" class="p-6 sm:p-8 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Questions in this Assignment</h3>
                                <p class="text-xs text-gray-500">Supports Multiple Choice Questions (MCQ).</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="document.getElementById('addQuestionModal').classList.remove('hidden')" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add MCQ Question
                                </button>
                                <button type="button" onclick="document.getElementById('addCodingQuestionModal').classList.remove('hidden')" class="hidden inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4 mr-1.5 text-violet-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                    Add Coding Question (C, Java, Python)
                                </button>
                            </div>
                        </div>

                        @if($assignment->questions->isEmpty())
                            <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 text-center">
                                <p class="text-sm font-medium text-gray-600">No questions added to this assignment yet.</p>
                                <div class="mt-4 flex items-center justify-center gap-3">
                                    <button type="button" onclick="document.getElementById('addQuestionModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-xs">Add MCQ Question</button>
                                    <button type="button" onclick="document.getElementById('addCodingQuestionModal').classList.remove('hidden')" class="hidden inline-flex items-center px-4 py-2 bg-violet-600 text-white text-xs font-bold rounded-xl shadow-xs">Add Coding Question</button>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($assignment->questions as $index => $q)
                                <div class="bg-gray-50/80 rounded-xl p-5 border {{ $q->isCoding() ? 'border-purple-200 bg-purple-50/20' : 'border-gray-200' }}">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-3">
                                            <span class="w-7 h-7 rounded-full {{ $q->isCoding() ? 'bg-purple-600 text-white' : 'bg-indigo-600 text-white' }} text-xs font-bold flex items-center justify-center shrink-0">
                                                Q{{ $index + 1 }}
                                            </span>
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                                    @if($q->isCoding())
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200">
                                                            Coding Problem
                                                        </span>
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 font-mono">
                                                            {{ strtoupper(str_replace(',', ' • ', $q->allowed_languages ?: 'c,java,python')) }}
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">
                                                            MCQ
                                                        </span>
                                                    @endif
                                                </div>
                                                <h4 class="text-sm font-bold text-gray-900 whitespace-pre-line">{{ $q->question_text }}</h4>
                                                <span class="inline-block mt-1 text-xs text-gray-500 font-semibold">{{ $q->marks }} {{ Str::plural('Mark', $q->marks) }}</span>

                                                @if($q->hasImage())
                                                    <div class="mt-3 relative group/qimg inline-block">
                                                        <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xs hover:shadow-md transition-all">
                                                            <img src="{{ $q->image_url }}" alt="Question {{ $index + 1 }} Diagram" class="max-h-56 max-w-full object-contain cursor-pointer rounded-xl bg-gray-50" onclick="openImageViewer('{{ $q->image_url }}', 'Question {{ $index + 1 }} Diagram')">
                                                            <div class="absolute inset-0 bg-slate-950/30 opacity-0 group-hover/qimg:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                                                <span class="px-2.5 py-1 rounded-lg bg-white/95 text-slate-800 text-[11px] font-bold shadow-xs flex items-center gap-1">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                                                    Click to Enlarge
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 flex items-center gap-2.5">
                                                            <button type="button"
                                                                data-question-id="{{ $q->id }}"
                                                                data-question-text="{{ Str::limit($q->question_text, 120) }}"
                                                                data-image-url="{{ $q->image_url ?? '' }}"
                                                                onclick="openUploadImageModal(this)"
                                                                class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 hover:text-indigo-800 cursor-pointer">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                <span>Change Picture</span>
                                                            </button>
                                                            <span class="text-gray-300">&bull;</span>
                                                            <form action="{{ route('staff.assignments.questions.image.delete', $q->id) }}" method="POST" onsubmit="return confirm('Remove picture from Question {{ $index + 1 }}?')" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-500 hover:text-rose-700 cursor-pointer">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    <span>Remove</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Button to attach picture if none exists -->
                                                    <div class="mt-2.5">
                                                        <button type="button"
                                                            data-question-id="{{ $q->id }}"
                                                            data-question-text="{{ Str::limit($q->question_text, 120) }}"
                                                            data-image-url=""
                                                            onclick="openUploadImageModal(this)"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-dashed border-indigo-300 hover:border-indigo-500 bg-indigo-50/50 hover:bg-indigo-50 text-[11px] font-bold text-indigo-700 transition-all cursor-pointer shadow-2xs hover:shadow-xs">
                                                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                            <span>+ Attach Diagram / Picture</span>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <button type="button"
                                            onclick="window.openUniversalDeleteModal({
                                                title: 'Delete Assessment Question',
                                                itemName: 'Question {{ $index + 1 }}: {{ addslashes(Str::limit($q->question_text, 40)) }}',
                                                itemType: '{{ $q->isCoding() ? 'Coding Question' : 'MCQ Question' }} ({{ $q->marks }} {{ Str::plural('Mark', $q->marks) }})',
                                                deleteUrl: '{{ route('staff.assignments.questions.destroy', $q->id) }}',
                                                warningMessage: 'Deleting this question will remove it from the assessment and adjust the total assignment maximum marks accordingly.',
                                                cascadeItems: [
                                                    { label: 'Question Config & Test Cases', count: '{{ $q->isCoding() ? count($q->test_cases ?? []) . ' Test Cases' : '4 Choices' }}' },
                                                    { label: 'Student Submitted Answers for Q{{ $index + 1 }}', count: 'All Students' }
                                                ]
                                            })"
                                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-rose-50/80 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5 shrink-0"
                                            title="Delete Question">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>

                                    @if($q->isCoding())
                                        @php
                                            $testCases = is_array($q->test_cases) ? $q->test_cases : [];
                                        @endphp
                                        <div class="mt-4 pt-3 border-t border-purple-100 space-y-3">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="font-bold text-gray-700">Automated Test Cases ({{ count($testCases) }})</span>
                                                <span class="text-gray-400 text-[11px]">Evaluates student code with input & expected output</span>
                                            </div>

                                            @if(empty($testCases))
                                                <p class="text-xs text-gray-400 italic">No automated test cases configured. Output will be evaluated upon submission.</p>
                                            @else
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                                                    @foreach($testCases as $tIdx => $tc)
                                                        <div class="p-3 bg-white rounded-lg border border-gray-200 space-y-1">
                                                            <div class="flex items-center justify-between">
                                                                <span class="font-bold text-gray-800">Test Case {{ $tIdx + 1 }}</span>
                                                                @if(!empty($tc['is_hidden']))
                                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Hidden</span>
                                                                @else
                                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Public Sample</span>
                                                                @endif
                                                            </div>
                                                            <div class="font-mono text-[11px] text-gray-600 bg-gray-50 p-2 rounded border border-gray-100 space-y-1">
                                                                <div><span class="text-gray-400">Stdin:</span> {{ $tc['input'] ?: '(None)' }}</div>
                                                                <div><span class="text-gray-400">Stdout:</span> {{ $tc['expected_output'] ?: '(None)' }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- 4 Options Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mt-4 text-xs">
                                            <div class="p-2.5 rounded-lg border {{ $q->correct_option === 'A' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-white border-gray-200 text-gray-700' }} flex items-center justify-between">
                                                <span><strong>A.</strong> {{ $q->option_a }}</span>
                                                @if($q->correct_option === 'A')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-200 text-emerald-800">Correct</span>
                                                @endif
                                            </div>
                                            <div class="p-2.5 rounded-lg border {{ $q->correct_option === 'B' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-white border-gray-200 text-gray-700' }} flex items-center justify-between">
                                                <span><strong>B.</strong> {{ $q->option_b }}</span>
                                                @if($q->correct_option === 'B')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-200 text-emerald-800">Correct</span>
                                                @endif
                                            </div>
                                            <div class="p-2.5 rounded-lg border {{ $q->correct_option === 'C' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-white border-gray-200 text-gray-700' }} flex items-center justify-between">
                                                <span><strong>C.</strong> {{ $q->option_c }}</span>
                                                @if($q->correct_option === 'C')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-200 text-emerald-800">Correct</span>
                                                @endif
                                            </div>
                                            <div class="p-2.5 rounded-lg border {{ $q->correct_option === 'D' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-white border-gray-200 text-gray-700' }} flex items-center justify-between">
                                                <span><strong>D.</strong> {{ $q->option_d }}</span>
                                                @if($q->correct_option === 'D')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-200 text-emerald-800">Correct</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($q->explanation)
                                        <div class="mt-3 text-xs text-gray-500 bg-white p-2.5 rounded-lg border border-gray-100">
                                            <span class="font-bold text-gray-700">Explanation:</span> {{ $q->explanation }}
                                        </div>
                                        @endif
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Panel 2: Student Results -->
                    <div id="tab-results-panel" class="p-6 sm:p-8 hidden space-y-5">
                        <!-- Top filter & counter toolbar -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-100">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Enrolled Students Submission Status</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Showing all students enrolled in {{ $assignment->course->code }} - {{ $assignment->course->name }}</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" onclick="filterStudentResults('all')" id="filter-btn-all" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition-colors">
                                    All Enrolled ({{ $totalEnrolled }})
                                </button>
                                <button type="button" onclick="filterStudentResults('submitted')" id="filter-btn-submitted" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                    Submitted ({{ $totalSubmitted }})
                                </button>
                                <button type="button" onclick="filterStudentResults('pending')" id="filter-btn-pending" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                    Pending ({{ $totalPending }})
                                </button>
                                <a href="{{ route('staff.assignments.export', $assignment->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-colors ml-auto sm:ml-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Export to Excel
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm" id="studentResultsTable">
                                <thead class="bg-gray-50/80 text-xs uppercase font-bold text-gray-500 border-b border-gray-200">
                                    <tr>
                                        <th class="py-3.5 px-6">Student</th>
                                        <th class="py-3.5 px-4">Submission Status</th>
                                        <th class="py-3.5 px-4">Score</th>
                                        <th class="py-3.5 px-4">Percentage</th>
                                        <th class="py-3.5 px-4">Submitted At</th>
                                        <th class="py-3.5 px-6 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($enrolledStudents as $student)
                                        @php
                                            $submission = $submissionsByStudent->get($student->id);
                                            $hasSubmitted = !is_null($submission);
                                            $percentage = ($hasSubmitted && $assignment->max_marks > 0) ? round(($submission->marks_awarded / $assignment->max_marks) * 100) : 0;
                                        @endphp
                                        <tr class="student-result-row hover:bg-gray-50/70 transition-colors" data-status="{{ $hasSubmitted ? 'submitted' : 'pending' }}">
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full {{ $hasSubmitted ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center font-bold text-xs shrink-0">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                        <div class="text-xs text-gray-500 font-mono">{{ $student->username }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if(!$hasSubmitted)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                        Pending Submission
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Submitted & Graded
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($hasSubmitted)
                                                    <span class="font-black text-base text-indigo-700">{{ $submission->marks_awarded }}</span>
                                                    <span class="text-xs font-semibold text-gray-400">/ {{ $assignment->max_marks }}</span>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Not graded</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($hasSubmitted)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $percentage >= 70 ? 'bg-emerald-100 text-emerald-800' : ($percentage >= 40 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $percentage }}%
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                @if($hasSubmitted)
                                                    <div class="flex items-center gap-1.5 text-xs">
                                                        <span class="font-bold text-gray-900">{{ $submission->submitted_at->format('M d, Y') }}</span>
                                                        <span class="text-gray-300">&bull;</span>
                                                        <span class="font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded text-xs">{{ $submission->submitted_at->format('h:i A') }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Awaiting submission</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                @if($hasSubmitted)
                                                    <button type="button" onclick="openStudentReviewModal('{{ addslashes($student->first_name . ' ' . $student->last_name) }}', '{{ $submission->marks_awarded }}', '{{ $assignment->max_marks }}', '{{ $percentage }}', {{ json_encode($submission->answers->keyBy('question_id')) }})" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors border border-indigo-100">
                                                        View Responses &rarr;
                                                    </button>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-400 bg-gray-50 rounded-lg border border-gray-200/60">
                                                        No submission
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-12 px-6 text-center text-gray-500 text-sm">
                                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                <p class="font-semibold text-gray-700">No students are currently enrolled in this course.</p>
                                                <p class="text-xs text-gray-400 mt-1">Once students are enrolled, their submission statuses will appear here.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <!-- Add Question Modal -->
    <div id="addQuestionModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50">
                <h3 class="text-base font-bold text-indigo-950">Add MCQ Question</h3>
                <button type="button" onclick="document.getElementById('addQuestionModal').classList.add('hidden')" class="text-indigo-400 hover:text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('staff.assignments.questions.store', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Question Statement <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="2" required placeholder="Type the question text here..." class="w-full text-sm border border-gray-300 rounded-lg p-2.5"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Question Diagram / Picture <span class="text-xs font-normal text-gray-500 lowercase">(optional - jpg, png, webp, svg)</span>
                    </label>
                    <input type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml,image/gif" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg p-1.5 bg-gray-50/50">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Option A <span class="text-red-500">*</span></label>
                        <input type="text" name="option_a" required placeholder="Option A text" class="w-full text-sm border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Option B <span class="text-red-500">*</span></label>
                        <input type="text" name="option_b" required placeholder="Option B text" class="w-full text-sm border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Option C <span class="text-red-500">*</span></label>
                        <input type="text" name="option_c" required placeholder="Option C text" class="w-full text-sm border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Option D <span class="text-red-500">*</span></label>
                        <input type="text" name="option_d" required placeholder="Option D text" class="w-full text-sm border border-gray-300 rounded-lg p-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-emerald-700 mb-1">Correct Answer <span class="text-red-500">*</span></label>
                        <select name="correct_option" required class="w-full text-sm border border-gray-300 rounded-lg p-2 font-bold bg-emerald-50">
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Marks</label>
                        <input type="number" name="marks" value="1" min="1" max="100" required class="w-full text-sm border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Explanation</label>
                        <input type="text" name="explanation" placeholder="Explanation for students" class="w-full text-sm border border-gray-300 rounded-lg p-2">
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addQuestionModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">Save Question</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Add Coding Question Modal -->
    <div id="addCodingQuestionModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto overflow-hidden max-h-[92vh] flex flex-col border border-purple-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-purple-100 flex justify-between items-center bg-gradient-to-r from-violet-50 to-indigo-50 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-purple-600 text-white flex items-center justify-center font-mono font-bold shadow-xs">
                        &lt;/&gt;
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-indigo-950">Add Coding Problem</h3>
                        <p class="text-xs text-purple-700 font-medium">Supports interactive C, Java, and Python compilation with test cases</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('addCodingQuestionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('staff.assignments.questions.store', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 overflow-y-auto flex-1 text-xs">
                @csrf
                <input type="hidden" name="type" value="code">

                <!-- Problem Statement -->
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Problem Statement & Requirements <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="3" required placeholder="Describe the programming problem, input/output specifications, constraints, and instructions..." class="w-full text-xs sm:text-sm border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                </div>

                <!-- Problem Diagram / Image (Optional) -->
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Problem Diagram / Graphic <span class="text-xs font-normal text-gray-500 lowercase">(optional)</span></label>
                    <input type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml,image/gif" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-300 rounded-xl p-2 bg-gray-50/50">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Marks -->
                    <div>
                        <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Marks Allocated <span class="text-red-500">*</span></label>
                        <input type="number" name="marks" value="10" min="1" max="100" required class="w-full text-xs sm:text-sm border border-gray-300 rounded-xl p-2.5 font-bold text-indigo-900">
                    </div>

                    <!-- Allowed Languages -->
                    <div>
                        <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Permitted Languages</label>
                        <div class="flex items-center gap-3 pt-1">
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" name="allowed_languages[]" value="c" checked class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="font-bold text-gray-800">C (GCC 13)</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" name="allowed_languages[]" value="java" checked class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="font-bold text-gray-800">Java (OpenJDK 21)</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" name="allowed_languages[]" value="python" checked class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="font-bold text-gray-800">Python (3.14)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Starter Code Tabs -->
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/60 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-800 uppercase tracking-wider">Starter Code Boilerplates (Optional)</span>
                        <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-gray-200 text-[11px] font-bold">
                            <button type="button" onclick="switchStarterTab('python')" id="starter-tab-python-btn" class="px-2.5 py-1 rounded bg-purple-600 text-white">Python</button>
                            <button type="button" onclick="switchStarterTab('c')" id="starter-tab-c-btn" class="px-2.5 py-1 rounded text-gray-600 hover:text-gray-900">C</button>
                            <button type="button" onclick="switchStarterTab('java')" id="starter-tab-java-btn" class="px-2.5 py-1 rounded text-gray-600 hover:text-gray-900">Java</button>
                        </div>
                    </div>

                    <div id="starter-tab-python">
                        <textarea name="starter_code_python" rows="5" class="w-full font-mono text-xs p-3 bg-gray-900 text-emerald-400 rounded-xl border border-gray-700" placeholder="# Python starter code">def solve():
    # Read input and solve
    pass

if __name__ == '__main__':
    solve()</textarea>
                    </div>

                    <div id="starter-tab-c" class="hidden">
                        <textarea name="starter_code_c" rows="5" class="w-full font-mono text-xs p-3 bg-gray-900 text-blue-300 rounded-xl border border-gray-700" placeholder="// C starter code">#include &lt;stdio.h&gt;

int main() {
    // Write your C code here
    return 0;
}</textarea>
                    </div>

                    <div id="starter-tab-java" class="hidden">
                        <textarea name="starter_code_java" rows="5" class="w-full font-mono text-xs p-3 bg-gray-900 text-amber-300 rounded-xl border border-gray-700" placeholder="// Java starter code">import java.util.Scanner;

public class Solution {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        // Write your solution here
    }
}</textarea>
                    </div>
                </div>

                <!-- Test Cases Builder -->
                <div class="border border-purple-200 rounded-xl p-4 bg-purple-50/30 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-bold text-gray-900 uppercase tracking-wider block">Automated Test Cases</span>
                            <span class="text-gray-500 text-[11px]">Inputs sent via stdin and compared against expected stdout</span>
                        </div>
                        <button type="button" onclick="addTestCaseRow()" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold flex items-center gap-1 shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Test Case
                        </button>
                    </div>

                    <div id="testCasesContainer" class="space-y-3">
                        <!-- Test Case 1 (Default) -->
                        <div class="test-case-row p-3.5 bg-white rounded-xl border border-gray-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-purple-950">Test Case #1</span>
                                <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-600 cursor-pointer">
                                    <input type="checkbox" name="test_cases[0][is_hidden]" value="1" class="rounded text-purple-600">
                                    <span>Hidden Test Case (graded on submission only)</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Standard Input (stdin)</label>
                                    <textarea name="test_cases[0][input]" rows="2" placeholder="e.g. 5" class="w-full font-mono text-xs border border-gray-300 rounded-lg p-2"></textarea>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Expected Output (stdout) <span class="text-red-500">*</span></label>
                                    <textarea name="test_cases[0][expected_output]" rows="2" required placeholder="e.g. 10" class="w-full font-mono text-xs border border-gray-300 rounded-lg p-2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="pt-3 border-t border-gray-100 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="document.getElementById('addCodingQuestionModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-sm hover:shadow transition-all">Save Coding Question</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Student Response Review Modal -->
    <div id="studentReviewModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden max-h-[90vh] flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50 shrink-0">
                <div>
                    <h3 class="text-base font-bold text-indigo-950">Student MCQ Response Sheet</h3>
                    <p id="reviewStudentMeta" class="text-xs text-indigo-700 font-semibold"></p>
                </div>
                <button type="button" onclick="document.getElementById('studentReviewModal').classList.add('hidden')" class="text-indigo-400 hover:text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 space-y-4" id="reviewQuestionsContainer">
                <!-- Injected via Javascript -->
            </div>
            <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end shrink-0">
                <button type="button" onclick="document.getElementById('studentReviewModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Close</button>
            </div>
        </div>
    </div>

    <!-- Enrolled Students Directory Modal -->
    <div id="enrolledStudentsModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden max-h-[85vh] flex flex-col border border-gray-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/80 shrink-0">
                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $assignment->isPrivate() ? 'Allowed Students (Private Access)' : 'Enrolled Students' }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <span class="font-mono font-bold text-indigo-700">{{ $assignment->course->code }}</span> &bull; {{ $assignment->course->name }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $totalEnrolled }} {{ $assignment->isPrivate() ? 'Allowed' : 'Enrolled' }}
                    </span>
                    <button type="button" onclick="closeEnrolledStudentsModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="px-6 py-3 bg-white border-b border-gray-100 shrink-0">
                <div class="relative">
                    <input type="text" id="enrolledModalSearchInput" oninput="filterEnrolledModalList(this.value)" placeholder="Search by Register Number or Name..." class="w-full text-xs bg-gray-50 border border-gray-200 rounded-lg pl-9 pr-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Student List Table -->
            <div class="p-6 overflow-y-auto flex-1">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="py-2.5 px-4 w-12 text-center">#</th>
                            <th class="py-2.5 px-4">Register Number</th>
                            <th class="py-2.5 px-4">Student Name</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($enrolledStudents as $index => $student)
                            @php
                                $searchableText = strtolower($student->username . ' ' . $student->first_name . ' ' . $student->last_name);
                            @endphp
                            <tr class="enrolled-student-item hover:bg-gray-50/80 transition-colors" data-search="{{ $searchableText }}">
                                <td class="py-3 px-4 text-center text-xs font-semibold text-gray-400">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-indigo-700 text-xs">
                                    {{ $student->username }}
                                </td>
                                <td class="py-3 px-4 font-semibold text-gray-900 text-sm">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-400 text-xs">
                                    {{ $assignment->isPrivate() ? 'No students assigned to this private assignment.' : 'No students enrolled in this course.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div id="enrolledModalNoResults" class="hidden py-8 text-center text-gray-400 text-xs font-medium">
                    No student found matching your search.
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/80 flex items-center justify-between shrink-0">
                <span class="text-xs text-gray-500 font-medium">Total: <strong class="text-gray-900">{{ $totalEnrolled }}</strong> Students</span>
                <button type="button" onclick="closeEnrolledStudentsModal()" class="px-4 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm cursor-pointer">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Upload Question Picture Modal -->
    <div id="uploadQuestionImageModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all" onclick="closeUploadImageModal()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100" onclick="event.stopPropagation()">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-indigo-50/80 to-slate-50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900" id="uploadModalTitle">Attach Question Picture</h3>
                        <p class="text-[11px] text-gray-500">Diagram, circuit, graph, or formula image for this question</p>
                    </div>
                </div>
                <button type="button" onclick="closeUploadImageModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="uploadQuestionImageForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4" onsubmit="handleImageUploadSubmit(event)">
                @csrf
                
                <!-- Target Question Preview -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-0.5">Selected Question</span>
                    <p id="uploadModalQuestionText" class="text-xs font-semibold text-slate-800 line-clamp-2"></p>
                </div>

                <!-- Existing Image Preview if present -->
                <div id="existingImagePreviewContainer" class="hidden">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Current Picture</span>
                    <div class="p-2 bg-gray-50 rounded-xl border border-gray-200 text-center">
                        <img id="existingImagePreview" src="" alt="Current Picture" class="max-h-36 max-w-full mx-auto object-contain rounded-lg">
                    </div>
                </div>

                <!-- File Dropzone -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Choose Picture File <span class="text-red-500">*</span>
                    </label>
                    <div id="imageDropZone" class="relative border-2 border-dashed border-gray-300 hover:border-indigo-500 rounded-xl p-4 text-center bg-gray-50/50 hover:bg-indigo-50/30 transition-all cursor-pointer group"
                        onclick="document.getElementById('question_image_input').click()"
                        ondragover="event.preventDefault(); this.classList.add('border-indigo-500', 'bg-indigo-50/50');"
                        ondragleave="event.preventDefault(); this.classList.remove('border-indigo-500', 'bg-indigo-50/50');"
                        ondrop="handleImageDrop(event, this)">
                        <input type="file" id="question_image_input" name="image" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml,image/gif" class="hidden" onchange="previewSelectedQuestionImage(this)">
                        <div id="fileUploadPrompt" class="space-y-1">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-indigo-600 mx-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs font-bold text-gray-700 group-hover:text-indigo-700">Click to browse or drag & drop picture</p>
                            <p class="text-[11px] text-gray-400">PNG, JPG, WEBP, SVG up to 5MB</p>
                        </div>
                        <div id="newFilePreviewContainer" class="hidden space-y-2">
                            <img id="newFilePreviewImg" src="" alt="Selected Preview" class="max-h-40 max-w-full mx-auto object-contain rounded-lg border border-gray-200 bg-white">
                            <p id="newFileName" class="text-xs font-mono font-bold text-indigo-700"></p>
                            <span class="text-[11px] text-slate-500 underline cursor-pointer">Click to change selection</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeUploadImageModal()" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" id="uploadImageSubmitBtn" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Save Picture</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lightbox Image Viewer Modal -->
    <div id="imageViewerModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-black/80 backdrop-blur-md p-4 transition-all" onclick="closeImageViewer()">
        <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center justify-center" onclick="event.stopPropagation()">
            <button type="button" onclick="closeImageViewer()" class="absolute -top-10 right-0 text-white/80 hover:text-white p-1 rounded-full cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <img id="imageViewerImg" src="" alt="Enlarged Diagram" class="max-w-full max-h-[82vh] object-contain rounded-xl shadow-2xl bg-white p-1">
            <p id="imageViewerCaption" class="mt-2 text-xs font-medium text-white/90 text-center"></p>
        </div>
    </div>

    <!-- Global Questions Data for JS modal -->
    <script>
        const assignmentQuestions = @json($assignment->questions->map(function($q) {
            return array_merge($q->toArray(), ['image_url' => $q->image_url]);
        }));

        function switchViewTab(tab) {
            const qBtn = document.getElementById('tab-questions-btn');
            const rBtn = document.getElementById('tab-results-btn');
            const qPanel = document.getElementById('tab-questions-panel');
            const rPanel = document.getElementById('tab-results-panel');

            if (tab === 'questions') {
                qBtn.classList.add('border-indigo-600', 'text-indigo-600');
                qBtn.classList.remove('border-transparent', 'text-gray-500');
                rBtn.classList.remove('border-indigo-600', 'text-indigo-600');
                rBtn.classList.add('border-transparent', 'text-gray-500');
                qPanel.classList.remove('hidden');
                rPanel.classList.add('hidden');
            } else {
                rBtn.classList.add('border-indigo-600', 'text-indigo-600');
                rBtn.classList.remove('border-transparent', 'text-gray-500');
                qBtn.classList.remove('border-indigo-600', 'text-indigo-600');
                qBtn.classList.add('border-transparent', 'text-gray-500');
                rPanel.classList.remove('hidden');
                qPanel.classList.add('hidden');
            }
        }

        function openEnrolledStudentsModal() {
            const modal = document.getElementById('enrolledStudentsModal');
            if (modal) {
                modal.classList.remove('hidden');
                const searchInput = document.getElementById('enrolledModalSearchInput');
                if (searchInput) {
                    searchInput.value = '';
                    filterEnrolledModalList('');
                    setTimeout(() => searchInput.focus(), 50);
                }
            }
        }

        function closeEnrolledStudentsModal() {
            const modal = document.getElementById('enrolledStudentsModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function closeEnrolledModalAndSwitchTab(tab) {
            closeEnrolledStudentsModal();
            switchViewTab(tab);
            filterStudentResults('all');
            const panel = document.getElementById('tab-results-panel');
            if (panel) {
                panel.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function filterEnrolledModalList(query) {
            const term = (query || '').toLowerCase().trim();
            const items = document.querySelectorAll('.enrolled-student-item');
            let visibleCount = 0;

            items.forEach(item => {
                const text = item.getAttribute('data-search') || '';
                if (!term || text.includes(term)) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            const noResults = document.getElementById('enrolledModalNoResults');
            if (noResults) {
                if (visibleCount === 0 && items.length > 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        }

        function filterStudentResults(status) {
            const rows = document.querySelectorAll('.student-result-row');
            const allBtn = document.getElementById('filter-btn-all');
            const subBtn = document.getElementById('filter-btn-submitted');
            const pendBtn = document.getElementById('filter-btn-pending');

            [allBtn, subBtn, pendBtn].forEach(b => {
                if (b) b.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors';
            });

            if (status === 'all') {
                if (allBtn) allBtn.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white transition-colors';
                rows.forEach(r => r.classList.remove('hidden'));
            } else if (status === 'submitted') {
                if (subBtn) subBtn.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white transition-colors';
                rows.forEach(r => {
                    if (r.getAttribute('data-status') === 'submitted') {
                        r.classList.remove('hidden');
                    } else {
                        r.classList.add('hidden');
                    }
                });
            } else if (status === 'pending') {
                if (pendBtn) pendBtn.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-600 text-white transition-colors';
                rows.forEach(r => {
                    if (r.getAttribute('data-status') === 'pending') {
                        r.classList.remove('hidden');
                    } else {
                        r.classList.add('hidden');
                    }
                });
            }
        }

        function switchStarterTab(lang) {
            ['python', 'c', 'java'].forEach(l => {
                const tab = document.getElementById(`starter-tab-${l}`);
                const btn = document.getElementById(`starter-tab-${l}-btn`);
                if (tab && btn) {
                    if (l === lang) {
                        tab.classList.remove('hidden');
                        btn.className = 'px-2.5 py-1 rounded bg-purple-600 text-white';
                    } else {
                        tab.classList.add('hidden');
                        btn.className = 'px-2.5 py-1 rounded text-gray-600 hover:text-gray-900';
                    }
                }
            });
        }

        let testCaseIndex = 1;
        function addTestCaseRow() {
            const container = document.getElementById('testCasesContainer');
            if (!container) return;
            const idx = testCaseIndex++;
            const div = document.createElement('div');
            div.className = 'test-case-row p-3.5 bg-white rounded-xl border border-gray-200 space-y-2';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="font-bold text-purple-950">Test Case #${idx + 1}</span>
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-600 cursor-pointer">
                            <input type="checkbox" name="test_cases[${idx}][is_hidden]" value="1" class="rounded text-purple-600">
                            <span>Hidden Test Case</span>
                        </label>
                        <button type="button" onclick="this.closest('.test-case-row').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold">&times; Remove</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Standard Input (stdin)</label>
                        <textarea name="test_cases[${idx}][input]" rows="2" placeholder="stdin input" class="w-full font-mono text-xs border border-gray-300 rounded-lg p-2"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Expected Output (stdout) <span class="text-red-500">*</span></label>
                        <textarea name="test_cases[${idx}][expected_output]" rows="2" required placeholder="expected output" class="w-full font-mono text-xs border border-gray-300 rounded-lg p-2"></textarea>
                    </div>
                </div>
            `;
            container.appendChild(div);
        }

        function openUploadImageModal(target, questionText, currentImageUrl) {
            let questionId = target;
            if (typeof target === 'object' && target !== null && target.dataset) {
                questionId = target.dataset.questionId;
                questionText = target.dataset.questionText || '';
                currentImageUrl = target.dataset.imageUrl || null;
            }

            const modal = document.getElementById('uploadQuestionImageModal');
            const form = document.getElementById('uploadQuestionImageForm');
            const title = document.getElementById('uploadModalTitle');
            const qTextEl = document.getElementById('uploadModalQuestionText');
            const existingContainer = document.getElementById('existingImagePreviewContainer');
            const existingImg = document.getElementById('existingImagePreview');
            const fileInput = document.getElementById('question_image_input');
            const filePrompt = document.getElementById('fileUploadPrompt');
            const newPreviewContainer = document.getElementById('newFilePreviewContainer');

            if (!modal || !form) {
                console.error('Modal or form element not found');
                return;
            }

            form.action = `/staff/assignments/questions/${questionId}/image`;
            if (qTextEl) qTextEl.textContent = questionText || '';
            if (fileInput) fileInput.value = '';
            if (filePrompt) filePrompt.classList.remove('hidden');
            if (newPreviewContainer) newPreviewContainer.classList.add('hidden');

            const submitBtn = document.getElementById('uploadImageSubmitBtn');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Save Picture</span>
                `;
            }

            if (currentImageUrl) {
                if (title) title.textContent = 'Change Question Picture';
                if (existingImg) existingImg.src = currentImageUrl;
                if (existingContainer) existingContainer.classList.remove('hidden');
            } else {
                if (title) title.textContent = 'Attach Question Picture';
                if (existingContainer) existingContainer.classList.add('hidden');
            }

            modal.classList.remove('hidden');
        }

        function closeUploadImageModal() {
            const modal = document.getElementById('uploadQuestionImageModal');
            if (modal) modal.classList.add('hidden');
        }

        function handleImageDrop(e, container) {
            e.preventDefault();
            container.classList.remove('border-indigo-500', 'bg-indigo-50/50');
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                const input = document.getElementById('question_image_input');
                input.files = e.dataTransfer.files;
                previewSelectedQuestionImage(input);
            }
        }

        function handleImageUploadSubmit(e) {
            const fileInput = document.getElementById('question_image_input');
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Please select a picture file to attach first.');
                return false;
            }
            const btn = document.getElementById('uploadImageSubmitBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span>Saving Picture...</span>
                `;
            }
            return true;
        }

        function previewSelectedQuestionImage(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('newFilePreviewImg').src = e.target.result;
                    document.getElementById('newFileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                    document.getElementById('fileUploadPrompt').classList.add('hidden');
                    document.getElementById('newFilePreviewContainer').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function openImageViewer(imgUrl, caption) {
            const modal = document.getElementById('imageViewerModal');
            const img = document.getElementById('imageViewerImg');
            const cap = document.getElementById('imageViewerCaption');
            if (modal && img) {
                img.src = imgUrl;
                if (cap) cap.textContent = caption || '';
                modal.classList.remove('hidden');
            }
        }

        function closeImageViewer() {
            const modal = document.getElementById('imageViewerModal');
            if (modal) modal.classList.add('hidden');
        }

        function openStudentReviewModal(studentName, marks, maxMarks, percentage, answers) {
            document.getElementById('reviewStudentMeta').textContent = `${studentName} — Score: ${marks} / ${maxMarks} (${percentage}%)`;
            const container = document.getElementById('reviewQuestionsContainer');
            container.innerHTML = '';

            assignmentQuestions.forEach((q, idx) => {
                const ans = answers[q.id];
                const isCoding = (q.type === 'code');
                const isCorrect = ans ? ans.is_correct : false;
                const marksAwarded = ans ? ans.marks_awarded : 0;

                const card = document.createElement('div');
                card.className = `p-4 rounded-xl border ${isCorrect ? 'bg-emerald-50/40 border-emerald-200' : (ans ? 'bg-amber-50/40 border-amber-200' : 'bg-gray-50 border-gray-200')}`;

                const imageHtml = q.image_url ? `
                    <div class="my-2.5">
                        <img src="${q.image_url}" alt="Question Diagram" class="max-h-48 max-w-full rounded-lg border border-gray-200 cursor-pointer object-contain bg-white shadow-2xs hover:shadow" onclick="openImageViewer('${q.image_url}', 'Question ${idx + 1} Diagram')">
                    </div>
                ` : '';

                if (isCoding) {
                    const submittedCode = (ans && ans.code) ? ans.code : '';
                    const lang = (ans && ans.language) ? ans.language.toUpperCase() : 'N/A';
                    const testResults = (ans && ans.test_case_results) ? ans.test_case_results : [];
                    const statusText = ans ? (ans.code_output || `${marksAwarded} / ${q.marks} Marks`) : 'Unattempted';

                    let testCaseHtml = '';
                    if (Array.isArray(testResults) && testResults.length > 0) {
                        testCaseHtml = `
                            <div class="mt-3 pt-2 border-t border-gray-200 space-y-1">
                                <div class="font-bold text-[11px] text-gray-700">Test Case Execution Details:</div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                    ${testResults.map(tc => `
                                        <div class="p-2 rounded bg-white border ${tc.passed ? 'border-emerald-300 text-emerald-900' : 'border-rose-300 text-rose-900'} text-[11px] flex items-center justify-between">
                                            <span>Test #${tc.test_case_id} ${tc.is_hidden ? '(Hidden)' : '(Sample)'}</span>
                                            <span class="font-bold px-1.5 py-0.5 rounded text-[10px] ${tc.passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}">${tc.passed ? 'PASSED' : 'FAILED'}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    card.innerHTML = `
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 mr-1.5 uppercase font-mono">Coding</span>
                                <span class="text-xs font-bold text-gray-900">Q${idx + 1}. ${q.question_text}</span>
                            </div>
                            <span class="text-xs font-bold ${isCorrect ? 'text-emerald-700' : 'text-gray-700'}">
                                ${marksAwarded} / ${q.marks} Marks
                            </span>
                        </div>
                        ${imageHtml}
                        <div class="flex items-center gap-3 text-xs mb-2">
                            <span class="text-gray-500">Language: <strong class="text-purple-700 font-mono">${lang}</strong></span>
                            <span class="text-gray-400">&bull;</span>
                            <span class="font-semibold text-gray-700">${statusText}</span>
                        </div>
                        ${submittedCode ? `
                            <pre class="bg-gray-900 text-gray-100 p-3 rounded-lg font-mono text-[11px] overflow-x-auto whitespace-pre-wrap">${submittedCode.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</pre>
                        ` : `<div class="p-3 bg-gray-100 text-gray-500 rounded-lg text-xs italic">No code submitted for this question.</div>`}
                        ${testCaseHtml}
                    `;
                } else {
                    const selectedOpt = ans ? ans.selected_option : null;
                    let optionsHtml = '';
                    ['A', 'B', 'C', 'D'].forEach(opt => {
                        const optText = q[`option_${opt.toLowerCase()}`];
                        const isStudentPick = selectedOpt === opt;
                        const isActualCorrect = q.correct_option === opt;

                        let optClass = 'bg-white border-gray-200 text-gray-700';
                        let badge = '';

                        if (isActualCorrect) {
                            optClass = 'bg-emerald-100 border-emerald-400 font-bold text-emerald-900';
                            badge = '<span class="text-[10px] bg-emerald-600 text-white px-1.5 py-0.5 rounded font-bold ml-2">Correct Answer</span>';
                        }
                        if (isStudentPick && !isActualCorrect) {
                            optClass = 'bg-red-100 border-red-400 font-bold text-red-900';
                            badge = '<span class="text-[10px] bg-red-600 text-white px-1.5 py-0.5 rounded font-bold ml-2">Student Picked</span>';
                        } else if (isStudentPick && isActualCorrect) {
                            badge += '<span class="text-[10px] bg-emerald-700 text-white px-1.5 py-0.5 rounded font-bold ml-2">Student Picked</span>';
                        }

                        optionsHtml += `
                            <div class="p-2 rounded-lg border ${optClass} text-xs flex items-center justify-between">
                                <span><strong>${opt}.</strong> ${optText}</span>
                                <div>${badge}</div>
                            </div>
                        `;
                    });

                    card.innerHTML = `
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-xs font-bold text-gray-900">Q${idx + 1}. ${q.question_text}</span>
                            <span class="text-xs font-bold ${isCorrect ? 'text-emerald-700' : 'text-red-600'}">
                                ${isCorrect ? `+${q.marks} Marks` : '0 Marks'}
                            </span>
                        </div>
                        ${imageHtml}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                            ${optionsHtml}
                        </div>
                        ${q.explanation ? `<div class="mt-2 text-[11px] text-gray-500 italic">Explanation: ${q.explanation}</div>` : ''}
                    `;
                }

                container.appendChild(card);
            });

            document.getElementById('studentReviewModal').classList.remove('hidden');
        }

        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
    </script>
</body>
</html>
