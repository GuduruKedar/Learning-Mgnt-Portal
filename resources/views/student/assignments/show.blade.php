<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }} - Assessment</title>
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
            <div class="max-w-4xl mx-auto space-y-6">

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

                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
                </div>
                @endif

                <!-- Assignment Overview Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $assignment->course->code }}
                                </span>
                                <span class="text-xs text-gray-500 font-medium">{{ $assignment->course->name }}</span>
                                @if($assignment->staff)
                                    <span class="text-gray-300">&bull;</span>
                                    <span class="text-xs text-gray-500 font-medium">Faculty: {{ $assignment->staff->first_name }} {{ $assignment->staff->last_name }}</span>
                                    @if($assignment->isPrivate())
                                        <span class="text-gray-300">&bull;</span>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            Private Assignment
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $assignment->title }}</h1>
                            @if($assignment->description)
                                <p class="text-sm text-gray-600 mt-2">{{ $assignment->description }}</p>
                            @endif
                        </div>

                        <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 sm:border-l border-gray-100 pt-3 sm:pt-0 sm:pl-6 shrink-0">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block text-right">Total Score</span>
                                <span class="text-2xl font-black text-indigo-700">{{ $assignment->max_marks }} <span class="text-xs font-semibold text-gray-500">Points</span></span>
                            </div>
                            <div class="mt-2 text-right">
                                <span class="text-xs text-gray-500">Due: {{ $assignment->due_date->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($submission)
                    <!-- RESULT SCORECARD & BREAKDOWN -->
                    @php
                        $percentage = ($assignment->max_marks > 0) ? round(($submission->marks_awarded / $assignment->max_marks) * 100) : 0;
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-emerald-100 overflow-hidden">
                        <div class="p-6 bg-emerald-50/60 border-b border-emerald-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-emerald-950">MCQ Test Evaluated</h3>
                                    <p class="text-xs text-emerald-800">Submitted on {{ $submission->submitted_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="text-3xl font-black text-emerald-700">{{ $submission->marks_awarded }} <span class="text-sm font-semibold text-gray-600">/ {{ $assignment->max_marks }}</span></div>
                                    <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-bold {{ $percentage >= 70 ? 'bg-emerald-200 text-emerald-900' : ($percentage >= 40 ? 'bg-amber-200 text-amber-900' : 'bg-red-200 text-red-900') }}">
                                        Score: {{ $percentage }}%
                                    </span>
                                </div>
                                <div class="no-print border-l border-emerald-200 pl-4">
                                    <a href="{{ route('student.assignments.download', $assignment->id) }}" class="inline-flex flex-col items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow transition-all group cursor-pointer">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-100 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            <span>Download</span>
                                        </div>
                                        <span class="text-[10px] text-emerald-100 font-normal">PDF Report</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Stat Badges -->
                        <div class="grid grid-cols-3 gap-3 p-4 bg-gray-50/50 border-b border-gray-100 text-center text-xs">
                            <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                <span class="text-emerald-700 font-bold block text-lg">{{ $correctCount }}</span>
                                <span class="text-gray-500 font-semibold">Correct Answers</span>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                <span class="text-red-600 font-bold block text-lg">{{ $incorrectCount }}</span>
                                <span class="text-gray-500 font-semibold">Incorrect Answers</span>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                <span class="text-gray-600 font-bold block text-lg">{{ $unattemptedCount }}</span>
                                <span class="text-gray-500 font-semibold">Unattempted</span>
                            </div>
                        </div>

                        <!-- Question by Question Review -->
                        <div class="p-6 sm:p-8 space-y-6">
                            <h3 class="text-base font-bold text-gray-900">Question-by-Question Review</h3>

                            <div class="space-y-6">
                                @foreach($assignment->questions as $idx => $q)
                                    @php
                                        $userAns = $userAnswersByQuestionId->get($q->id);
                                        $isCodingQ = $q->isCoding();
                                        $selectedOpt = $userAns ? $userAns->selected_option : null;
                                        $hasSubmittedCode = $isCodingQ && $userAns && !empty(trim((string)$userAns->code));
                                        $isCorrect = $userAns ? $userAns->is_correct : false;
                                        $marksAwarded = $userAns ? $userAns->marks_awarded : 0;
                                        $cardBg = $isCorrect ? 'bg-emerald-50/30 border-emerald-200' : (($selectedOpt || $hasSubmittedCode) ? 'bg-amber-50/30 border-amber-200' : 'bg-gray-50 border-gray-200');
                                    @endphp
                                    <div class="p-5 rounded-xl border {{ $cardBg }}">
                                        <div class="flex items-start justify-between gap-3 mb-3">
                                            <div class="flex items-start gap-2.5">
                                                <span class="w-6 h-6 rounded-full {{ $isCorrect ? 'bg-emerald-600 text-white' : (($selectedOpt || $hasSubmittedCode) ? 'bg-amber-600 text-white' : 'bg-gray-400 text-white') }} text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                                    {{ $idx + 1 }}
                                                </span>
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h4 class="text-sm font-bold text-gray-900 leading-snug">{{ $q->question_text }}</h4>
                                                        @if($isCodingQ)
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200 font-mono">Coding</span>
                                                        @endif
                                                    </div>
                                                    @if($q->hasImage())
                                                        <div class="my-2.5">
                                                            <img src="{{ $q->image_url }}" alt="Question {{ $idx + 1 }} Diagram" class="max-h-56 max-w-full rounded-xl border border-gray-200 cursor-pointer object-contain bg-white p-1 shadow-2xs hover:shadow transition-shadow" onclick="openImageViewer('{{ $q->image_url }}', 'Question {{ $idx + 1 }} Diagram')">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="text-xs font-bold {{ $isCorrect ? 'text-emerald-700' : ($marksAwarded > 0 ? 'text-amber-700' : 'text-red-600') }} shrink-0">
                                                {{ $marksAwarded }} / {{ $q->marks }} Marks
                                            </span>
                                        </div>

                                        @if($isCodingQ)
                                            <!-- Coding Question Review -->
                                            <div class="mt-3 space-y-3 text-xs">
                                                <div class="flex flex-wrap items-center justify-between gap-2 bg-white p-3 rounded-xl border border-gray-200">
                                                    <span class="text-gray-600 font-semibold">Language Used: <strong class="text-purple-700 font-mono uppercase">{{ $userAns->language ?? 'Python' }}</strong></span>
                                                    <span class="font-bold {{ $isCorrect ? 'text-emerald-700' : 'text-amber-700' }}">
                                                        {{ $userAns->code_output ?? ($isCorrect ? 'All Test Cases Passed' : 'Evaluated') }}
                                                    </span>
                                                </div>

                                                @if($hasSubmittedCode)
                                                    <div>
                                                        <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Submitted Program Code:</div>
                                                        <pre class="bg-gray-950 text-gray-100 p-4 rounded-xl font-mono text-xs overflow-x-auto whitespace-pre-wrap border border-gray-800 leading-relaxed">{{ $userAns->code }}</pre>
                                                    </div>
                                                @else
                                                    <div class="p-4 bg-gray-100 text-gray-500 rounded-xl text-xs italic">
                                                        No code submitted for this question.
                                                    </div>
                                                @endif

                                                @php
                                                    $testCaseResults = is_array($userAns?->test_case_results) ? $userAns->test_case_results : [];
                                                @endphp
                                                @if(!empty($testCaseResults))
                                                    <div class="pt-2 border-t border-gray-100">
                                                        <span class="font-bold text-[11px] text-gray-700 block mb-2">Automated Test Case Breakdown:</span>
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                            @foreach($testCaseResults as $tc)
                                                                <div class="p-2.5 rounded-lg border {{ $tc['passed'] ? 'bg-emerald-50/60 border-emerald-300 text-emerald-950' : 'bg-rose-50/60 border-rose-300 text-rose-950' }} flex items-center justify-between text-xs">
                                                                    <span>Test #{{ $tc['test_case_id'] }} {{ !empty($tc['is_hidden']) ? '(Hidden)' : '(Sample)' }}</span>
                                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $tc['passed'] ? 'bg-emerald-200 text-emerald-800' : 'bg-rose-200 text-rose-800' }}">
                                                                        {{ $tc['passed'] ? 'PASSED' : 'FAILED' }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Options Review Grid -->
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                                @foreach(['A', 'B', 'C', 'D'] as $opt)
                                                    @php
                                                        $optText = $q->{'option_' . strtolower($opt)};
                                                        $isStudentPick = ($selectedOpt === $opt);
                                                        $isActualCorrect = ($q->correct_option === $opt);

                                                        $cardClass = 'bg-white border-gray-200 text-gray-700';
                                                        if ($isActualCorrect) {
                                                            $cardClass = 'bg-emerald-100 border-emerald-400 font-bold text-emerald-950';
                                                        }
                                                        if ($isStudentPick && !$isActualCorrect) {
                                                            $cardClass = 'bg-red-100 border-red-400 font-bold text-red-950';
                                                        }
                                                    @endphp
                                                    <div class="p-3 rounded-lg border {{ $cardClass }} flex items-center justify-between">
                                                        <span><strong>{{ $opt }}.</strong> {{ $optText }}</span>
                                                        <div class="flex items-center gap-1">
                                                            @if($isActualCorrect)
                                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-600 text-white">Correct Answer</span>
                                                            @endif
                                                            @if($isStudentPick)
                                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $isActualCorrect ? 'bg-emerald-800 text-white' : 'bg-red-600 text-white' }}">Your Answer</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if($q->explanation)
                                                <div class="mt-3 text-xs text-gray-600 bg-white p-3 rounded-lg border border-gray-200">
                                                    <strong class="text-gray-800">Explanation:</strong> {{ $q->explanation }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                @else
                    <!-- INTERACTIVE MCQ QUIZ FORM -->
                    <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to submit your answers? This test will be evaluated immediately.')" class="space-y-6">
                        @csrf

                        <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 flex items-center justify-between">
                            <div class="flex items-center text-xs text-indigo-900 font-medium">
                                <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Select one option for each question. Your score will be auto-calculated upon submission.
                            </div>
                            <span class="text-xs font-bold text-indigo-700 bg-white px-2.5 py-1 rounded shadow-sm">
                                {{ $assignment->questions->count() }} Questions
                            </span>
                        </div>

                        @if($assignment->questions->isEmpty())
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                                <p class="text-sm font-medium text-gray-600">No questions have been published for this assignment yet.</p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach($assignment->questions as $idx => $q)
                                    @if($q->isCoding())
                                        <!-- CODING QUESTION WORKSPACE -->
                                        @php
                                            $allowedLangs = $q->getAllowedLanguagesArray();
                                            $defaultLang = in_array('python', $allowedLangs) ? 'python' : ($allowedLangs[0] ?? 'c');
                                            $starters = is_array($q->starter_code) ? $q->starter_code : [];
                                            $initialCode = $starters[$defaultLang] ?? \App\Services\CodeExecutionService::getDefaultStarterCode($defaultLang);
                                            $testCases = is_array($q->test_cases) ? $q->test_cases : [];
                                            $publicTestCases = array_values(array_filter($testCases, fn($tc) => empty($tc['is_hidden'])));
                                        @endphp
                                        <div class="bg-white rounded-2xl shadow-sm border border-purple-200/80 p-6 space-y-4 coding-question-container" data-question-id="{{ $q->id }}">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-start gap-3">
                                                    <span class="w-8 h-8 rounded-xl bg-purple-600 text-white text-xs font-black flex items-center justify-center shrink-0 shadow-xs">
                                                        Q{{ $idx + 1 }}
                                                    </span>
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200 font-mono">
                                                                Coding Problem
                                                            </span>
                                                            <span class="text-xs text-gray-500 font-medium">
                                                                Languages: {{ strtoupper(implode(', ', $allowedLangs)) }}
                                                            </span>
                                                        </div>
                                                        <h3 class="text-base font-bold text-gray-900 whitespace-pre-line leading-relaxed">{{ $q->question_text }}</h3>
                                                        @if($q->hasImage())
                                                            <div class="mt-3">
                                                                <img src="{{ $q->image_url }}" alt="Question {{ $idx + 1 }} Diagram" class="max-h-64 max-w-full rounded-xl border border-gray-200 cursor-pointer object-contain bg-white p-1 shadow-2xs hover:shadow transition-shadow" onclick="openImageViewer('{{ $q->image_url }}', 'Question {{ $idx + 1 }} Diagram')">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="text-xs font-bold text-purple-700 bg-purple-50 border border-purple-100 px-3 py-1 rounded-xl shrink-0">
                                                    {{ $q->marks }} {{ Str::plural('Mark', $q->marks) }}
                                                </span>
                                            </div>

                                            <!-- Interactive Code Editor -->
                                            <div class="border border-gray-800 rounded-2xl overflow-hidden shadow-md bg-gray-950">
                                                <!-- Editor Toolbar -->
                                                <div class="bg-gray-900 px-4 py-2.5 border-b border-gray-800 flex flex-wrap items-center justify-between gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Language:</span>
                                                        <select id="lang_select_{{ $q->id }}" onchange="changeLanguage({{ $q->id }}, this.value)" class="text-xs bg-gray-800 text-gray-100 font-semibold rounded-lg px-2.5 py-1 border border-gray-700 focus:outline-none focus:ring-1 focus:ring-purple-500">
                                                            @if(in_array('c', $allowedLangs))
                                                                <option value="c" {{ $defaultLang === 'c' ? 'selected' : '' }}>C (GCC 13)</option>
                                                            @endif
                                                            @if(in_array('java', $allowedLangs))
                                                                <option value="java" {{ $defaultLang === 'java' ? 'selected' : '' }}>Java (OpenJDK 21)</option>
                                                            @endif
                                                            @if(in_array('python', $allowedLangs))
                                                                <option value="python" {{ $defaultLang === 'python' ? 'selected' : '' }}>Python (3.14)</option>
                                                            @endif
                                                        </select>
                                                    </div>

                                                    <div class="flex items-center gap-2">
                                                        <button type="button" onclick="resetStarterCode({{ $q->id }})" class="text-[11px] text-gray-400 hover:text-white px-2.5 py-1 rounded hover:bg-gray-800 transition-colors">
                                                            Reset Starter Code
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Code Textarea -->
                                                <textarea id="editor_{{ $q->id }}" oninput="syncCode({{ $q->id }})" onkeydown="handleEditorKeyDown(event, this)" rows="10" class="w-full font-mono text-xs sm:text-sm bg-gray-950 text-gray-100 p-4 focus:outline-none leading-relaxed resize-y border-none" spellcheck="false" placeholder="Write your code here...">{{ $initialCode }}</textarea>

                                                <!-- Hidden fields submitted with quiz form -->
                                                <textarea name="code_answers[{{ $q->id }}][code]" id="code_submit_{{ $q->id }}" class="hidden">{{ $initialCode }}</textarea>
                                                <input type="hidden" name="code_answers[{{ $q->id }}][language]" id="lang_submit_{{ $q->id }}" value="{{ $defaultLang }}">
                                            </div>

                                            <!-- Interactive Test & Console Panel (Hidden from UI while retaining underlying structure) -->
                                            <div class="hidden bg-gray-50 rounded-2xl border border-gray-200 p-4 space-y-3">
                                                <!-- Action Buttons -->
                                                <div class="flex flex-wrap items-center justify-between gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" onclick="runPublicTestCases({{ $q->id }})" id="btn_run_tests_{{ $q->id }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center gap-1.5 cursor-pointer">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                                            <span>Run Test Cases ({{ count($publicTestCases) }})</span>
                                                        </button>
                                                        <button type="button" onclick="runCustomInput({{ $q->id }})" id="btn_run_custom_{{ $q->id }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center gap-1.5 cursor-pointer">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                            <span>Run with Custom Input</span>
                                                        </button>
                                                    </div>

                                                    <span class="text-[11px] text-gray-500 font-medium">Test before submitting</span>
                                                </div>

                                                <!-- Custom Input Box (Collapsible) -->
                                                <div id="custom_input_box_{{ $q->id }}" class="space-y-1.5">
                                                    <label class="block text-[11px] font-bold text-gray-600 uppercase">Standard Input (stdin) for Custom Run</label>
                                                    <textarea id="custom_stdin_{{ $q->id }}" rows="2" placeholder="Enter standard input if required by your program..." class="w-full text-xs font-mono border border-gray-300 rounded-xl p-2.5 bg-white"></textarea>
                                                </div>

                                                <!-- Console / Terminal Output Panel -->
                                                <div id="console_output_{{ $q->id }}" class="hidden space-y-2">
                                                    <div class="flex items-center justify-between text-xs">
                                                        <span class="font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                                            Execution Output
                                                        </span>
                                                        <span id="exec_time_{{ $q->id }}" class="text-gray-400 text-[11px] font-mono"></span>
                                                    </div>
                                                    <pre id="output_text_{{ $q->id }}" class="bg-gray-900 text-emerald-400 p-3.5 rounded-xl font-mono text-xs overflow-x-auto whitespace-pre-wrap border border-gray-800 min-h-[50px]"></pre>
                                                </div>

                                                <!-- Test Cases Feedback Panel -->
                                                <div id="test_results_{{ $q->id }}" class="hidden space-y-2 pt-2 border-t border-gray-200">
                                                    <div class="font-bold text-xs text-gray-800">Sample Test Results:</div>
                                                    <div id="test_cards_{{ $q->id }}" class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- MCQ QUESTION CARD -->
                                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start gap-3">
                                                        <span class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                                            Q{{ $idx + 1 }}
                                                        </span>
                                                        <div class="flex-1">
                                                            <h3 class="text-base font-bold text-gray-900">{{ $q->question_text }}</h3>
                                                            @if($q->hasImage())
                                                                <div class="mt-3">
                                                                    <img src="{{ $q->image_url }}" alt="Question {{ $idx + 1 }} Diagram" class="max-h-64 max-w-full rounded-xl border border-gray-200 cursor-pointer object-contain bg-white p-1 shadow-2xs hover:shadow transition-shadow" onclick="openImageViewer('{{ $q->image_url }}', 'Question {{ $idx + 1 }} Diagram')">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded shrink-0">
                                                    {{ $q->marks }} {{ Str::plural('Mark', $q->marks) }}
                                                </span>
                                            </div>

                                            <!-- Option Selection Radio Buttons -->
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                                @foreach(['A', 'B', 'C', 'D'] as $opt)
                                                    @php
                                                        $optText = $q->{'option_' . strtolower($opt)};
                                                    @endphp
                                                    <label class="relative flex items-center p-3.5 rounded-xl border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50/40 cursor-pointer transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:ring-2 has-[:checked]:ring-indigo-500">
                                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                        <span class="ml-3 text-sm text-gray-800 font-medium">
                                                            <strong class="text-gray-900 mr-1">{{ $opt }}.</strong> {{ $optText }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Submit Button -->
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Ensure you have selected all your answers before clicking submit.</span>
                                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition-colors flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Submit & Grade Test
                                </button>
                            </div>
                        @endif
                    </form>
                @endif

            </div>
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
    </script>

    <!-- Code Runner and Editor Scripts -->
    <script>
        const assignmentQuestionsData = @json($assignment->questions);
        const starterBoilerplates = {
            c: "#include <stdio.h>\n\nint main() {\n    // Write your C code here\n    return 0;\n}\n",
            java: "import java.util.Scanner;\n\npublic class Solution {\n    public static void main(String[] args) {\n        Scanner scanner = new Scanner(System.in);\n        // Write your Java code here\n    }\n}\n",
            python: "import sys\n\ndef main():\n    # Write your Python code here\n    pass\n\nif __name__ == '__main__':\n    main()\n"
        };

        function syncCode(questionId) {
            const editor = document.getElementById(`editor_${questionId}`);
            const submitField = document.getElementById(`code_submit_${questionId}`);
            if (editor && submitField) {
                submitField.value = editor.value;
            }
        }

        function handleEditorKeyDown(e, textarea) {
            if (e.key === 'Tab') {
                e.preventDefault();
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                textarea.value = textarea.value.substring(0, start) + "    " + textarea.value.substring(end);
                textarea.selectionStart = textarea.selectionEnd = start + 4;
                const container = textarea.closest('.coding-question-container');
                if (container) {
                    const qId = container.getAttribute('data-question-id');
                    syncCode(qId);
                }
            }
        }

        function changeLanguage(questionId, lang) {
            const langSubmit = document.getElementById(`lang_submit_${questionId}`);
            if (langSubmit) langSubmit.value = lang;

            const q = assignmentQuestionsData.find(item => item.id == questionId);
            const editor = document.getElementById(`editor_${questionId}`);
            if (!editor) return;

            let codeToSet = '';
            if (q && q.starter_code && q.starter_code[lang]) {
                codeToSet = q.starter_code[lang];
            } else if (starterBoilerplates[lang]) {
                codeToSet = starterBoilerplates[lang];
            }

            if (confirm(`Switch editor to ${lang.toUpperCase()} and load starter template? Any unsaved edits will be replaced.`)) {
                editor.value = codeToSet;
                syncCode(questionId);
            }
        }

        function resetStarterCode(questionId) {
            const langSelect = document.getElementById(`lang_select_${questionId}`);
            const lang = langSelect ? langSelect.value : 'python';
            changeLanguage(questionId, lang);
        }

        async function runCustomInput(questionId) {
            const btn = document.getElementById(`btn_run_custom_${questionId}`);
            const editor = document.getElementById(`editor_${questionId}`);
            const langSelect = document.getElementById(`lang_select_${questionId}`);
            const stdin = document.getElementById(`custom_stdin_${questionId}`);
            const consoleBox = document.getElementById(`console_output_${questionId}`);
            const outputText = document.getElementById(`output_text_${questionId}`);
            const execTime = document.getElementById(`exec_time_${questionId}`);

            if (!editor || !btn) return;
            const code = editor.value;
            const lang = langSelect ? langSelect.value : 'python';

            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin h-3.5 w-3.5 mr-1" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Compiling...`;

            consoleBox.classList.remove('hidden');
            outputText.textContent = 'Compiling and executing code...';
            outputText.className = 'bg-gray-900 text-gray-300 p-3.5 rounded-xl font-mono text-xs overflow-x-auto whitespace-pre-wrap border border-gray-800';

            try {
                const response = await fetch("{{ route('student.assignments.run-code') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        language: lang,
                        code: code,
                        stdin: stdin ? stdin.value : ''
                    })
                });

                const data = await response.json();
                execTime.textContent = data.execution_time ? `${data.execution_time}s` : '';

                if (data.success) {
                    outputText.textContent = data.output || '(Execution completed with no output)';
                    outputText.className = 'bg-gray-900 text-emerald-400 p-3.5 rounded-xl font-mono text-xs overflow-x-auto whitespace-pre-wrap border border-gray-800';
                } else {
                    outputText.textContent = (data.error || 'Execution failed') + (data.output ? "\n\nOutput:\n" + data.output : '');
                    outputText.className = 'bg-gray-900 text-rose-400 p-3.5 rounded-xl font-mono text-xs overflow-x-auto whitespace-pre-wrap border border-gray-800';
                }
            } catch (err) {
                outputText.textContent = 'Network or server error while running code: ' + err.message;
                outputText.className = 'bg-gray-900 text-rose-400 p-3.5 rounded-xl font-mono text-xs overflow-x-auto whitespace-pre-wrap border border-gray-800';
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><span>Run with Custom Input</span>`;
            }
        }

        async function runPublicTestCases(questionId) {
            const btn = document.getElementById(`btn_run_tests_${questionId}`);
            const editor = document.getElementById(`editor_${questionId}`);
            const langSelect = document.getElementById(`lang_select_${questionId}`);
            const testResultsBox = document.getElementById(`test_results_${questionId}`);
            const testCards = document.getElementById(`test_cards_${questionId}`);

            const q = assignmentQuestionsData.find(item => item.id == questionId);
            if (!q || !editor || !btn) return;

            const code = editor.value;
            const lang = langSelect ? langSelect.value : 'python';
            const publicTests = (q.test_cases || []).filter(tc => !tc.is_hidden);

            if (publicTests.length === 0) {
                alert('No public test cases are configured for this question. You can test your code using Custom Input.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin h-3.5 w-3.5 mr-1" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Evaluating...`;

            testResultsBox.classList.remove('hidden');
            testCards.innerHTML = '<div class="col-span-2 text-xs text-gray-500 py-2">Running test cases against compiler...</div>';

            try {
                const response = await fetch("{{ route('student.assignments.run-code') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        language: lang,
                        code: code,
                        test_cases: publicTests
                    })
                });

                const data = await response.json();
                testCards.innerHTML = '';

                if (Array.isArray(data.results)) {
                    data.results.forEach(tc => {
                        const card = document.createElement('div');
                        const isPassed = tc.passed || tc.status === 'passed';
                        const isInfra = tc.status === 'infrastructure_error';
                        const isCompile = tc.status === 'compilation_error';
                        const isTimeout = tc.status === 'time_limit_exceeded';
                        const isRuntime = tc.status === 'runtime_error';

                        let badgeColor = 'bg-rose-600 text-white';
                        let badgeText = 'WRONG ANSWER ❌';
                        let cardBg = 'bg-rose-50/70 border-rose-300';

                        if (isPassed) {
                            badgeColor = 'bg-emerald-600 text-white';
                            badgeText = 'PASSED ✅';
                            cardBg = 'bg-emerald-50/70 border-emerald-300';
                        } else if (isInfra) {
                            badgeColor = 'bg-amber-600 text-white';
                            badgeText = 'SANDBOX BUSY ⚠️';
                            cardBg = 'bg-amber-50/80 border-amber-300';
                        } else if (isCompile) {
                            badgeColor = 'bg-purple-600 text-white';
                            badgeText = 'COMPILE ERROR ⚠️';
                            cardBg = 'bg-purple-50/80 border-purple-300';
                        } else if (isTimeout) {
                            badgeColor = 'bg-orange-600 text-white';
                            badgeText = 'TIMEOUT ⏱️';
                            cardBg = 'bg-orange-50/80 border-orange-300';
                        } else if (isRuntime) {
                            badgeColor = 'bg-rose-600 text-white';
                            badgeText = 'RUNTIME ERROR ❌';
                        }

                        let actualDisplay = '(None)';
                        if (tc.actual_output !== null && tc.actual_output !== undefined && tc.actual_output !== '') {
                            actualDisplay = tc.actual_output;
                        } else if (isInfra) {
                            actualDisplay = '(Execution service temporarily unavailable)';
                        } else if (isCompile) {
                            actualDisplay = '(Compilation failed - no output generated)';
                        } else if (isTimeout) {
                            actualDisplay = '(Time Limit Exceeded)';
                        } else if (isRuntime) {
                            actualDisplay = '(Runtime exception before output)';
                        } else {
                            actualDisplay = '(No output produced)';
                        }

                        card.className = `p-3 rounded-xl border ${cardBg} text-xs space-y-1.5`;
                        card.innerHTML = `
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-900">Sample Test Case #${tc.test_case_id}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold ${badgeColor}">
                                    ${badgeText}
                                </span>
                            </div>
                            <div class="font-mono text-[11px] space-y-0.5 text-gray-700 bg-white/70 p-2 rounded">
                                <div><span class="text-gray-400">Input:</span> ${tc.input !== '' && tc.input !== null ? tc.input : '(None)'}</div>
                                <div><span class="text-gray-400">Expected:</span> ${tc.expected_output || tc.expectedOutput || '(None)'}</div>
                                <div><span class="text-gray-400">Actual:</span> ${actualDisplay}</div>
                            </div>
                            ${tc.error ? `<div class="text-[10px] ${isInfra ? 'text-amber-800' : 'text-rose-700'} font-mono whitespace-pre-wrap">${tc.error}</div>` : ''}
                        `;
                        testCards.appendChild(card);
                    });
                }
            } catch (err) {
                testCards.innerHTML = `<div class="col-span-2 text-xs text-rose-600 py-2">Error running test cases: ${err.message}</div>`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg><span>Run Test Cases (${publicTests.length})</span>`;
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
    </script>

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

</body>
</html>
