<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }} - MCQ Online Test</title>
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
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center space-x-2">
                <a href="{{ route('student.assignments.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">Assignments</a>
                <span class="text-gray-300">/</span>
                <span class="text-sm font-semibold text-indigo-700 truncate max-w-xs sm:max-w-md">{{ $assignment->title }}</span>
            </div>
            <div class="flex items-center">
                <a href="{{ route('student.assignments.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Assignments
                </a>
            </div>
        </header>

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
                            <div class="text-right">
                                <div class="text-3xl font-black text-emerald-700">{{ $submission->marks_awarded }} <span class="text-sm font-semibold text-gray-600">/ {{ $assignment->max_marks }}</span></div>
                                <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-bold {{ $percentage >= 70 ? 'bg-emerald-200 text-emerald-900' : ($percentage >= 40 ? 'bg-amber-200 text-amber-900' : 'bg-red-200 text-red-900') }}">
                                    Score: {{ $percentage }}%
                                </span>
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
                                        $selectedOpt = $userAns ? $userAns->selected_option : null;
                                        $isCorrect = $userAns ? $userAns->is_correct : false;
                                    @endphp
                                    <div class="p-5 rounded-xl border {{ $isCorrect ? 'bg-emerald-50/30 border-emerald-200' : ($selectedOpt ? 'bg-red-50/30 border-red-200' : 'bg-gray-50 border-gray-200') }}">
                                        <div class="flex items-start justify-between gap-3 mb-3">
                                            <div class="flex items-start gap-2">
                                                <span class="w-6 h-6 rounded-full {{ $isCorrect ? 'bg-emerald-600 text-white' : ($selectedOpt ? 'bg-red-600 text-white' : 'bg-gray-400 text-white') }} text-xs font-bold flex items-center justify-center shrink-0">
                                                    {{ $idx + 1 }}
                                                </span>
                                                <h4 class="text-sm font-bold text-gray-900">{{ $q->question_text }}</h4>
                                            </div>
                                            <span class="text-xs font-bold {{ $isCorrect ? 'text-emerald-700' : 'text-red-600' }} shrink-0">
                                                {{ $isCorrect ? "+{$q->marks} Marks" : '0 Marks' }}
                                            </span>
                                        </div>

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
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                    <div class="flex items-start justify-between gap-3 mb-4">
                                        <div class="flex items-start gap-3">
                                            <span class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                                Q{{ $idx + 1 }}
                                            </span>
                                            <h3 class="text-base font-bold text-gray-900">{{ $q->question_text }}</h3>
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
        </main>
    </div>

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
    </script>
</body>
</html>
