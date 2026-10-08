<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }} - {{ $user->first_name }} {{ $user->last_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
</head>
<body class="bg-gray-100 text-gray-800 antialiased min-h-screen py-6 px-4 sm:px-6 lg:px-8">

    <!-- Action Toolbar (Hidden during Print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.assignments.show', $assignment->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Assignment
            </a>
            <a href="{{ route('student.assignments.index', ['course_id' => $assignment->course_id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                {{ $assignment->course->name }} Assignments
            </a>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="downloadAsPDF()" id="downloadBtn" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm hover:shadow transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Save Clean PDF</span>
            </button>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm hover:shadow transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print</span>
            </button>
        </div>
    </div>

    <!-- Printable Report Container -->
    <div id="reportContainer" class="print-container max-w-4xl mx-auto bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
        
        <!-- Clean Header -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-slate-900 to-indigo-950 text-white">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-mono font-bold bg-white/15 text-indigo-200 border border-white/20">
                            {{ $assignment->course->code }}
                        </span>
                        <span class="text-xs text-indigo-200 font-medium">{{ $assignment->course->name }}</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-white">{{ $assignment->title }}</h1>
                    @if($assignment->description)
                        <p class="text-xs text-indigo-200 mt-1">{{ $assignment->description }}</p>
                    @endif
                </div>

                <div class="text-left sm:text-right bg-white/10 backdrop-blur-sm px-5 py-3 rounded-2xl border border-white/15 shrink-0">
                    <span class="text-[10px] uppercase font-bold text-indigo-200 block tracking-wider">Total Score</span>
                    <div class="text-2xl font-black text-white">
                        {{ $submission->marks_awarded }} <span class="text-sm font-normal text-indigo-200">/ {{ $assignment->max_marks }}</span>
                    </div>
                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-400 text-emerald-950">
                        Score: {{ $percentage }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Essential Student & Submission Info -->
        <div class="p-5 sm:p-6 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Student Name</span>
                    <span class="font-bold text-gray-900 mt-0.5 block">{{ $user->first_name }} {{ $user->last_name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Register Number</span>
                    <span class="font-mono font-bold text-indigo-700 mt-0.5 block">{{ $user->username }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Submitted At</span>
                    <span class="font-semibold text-gray-800 mt-0.5 block">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Department</span>
                    <span class="font-semibold text-gray-800 mt-0.5 block truncate">{{ $user->profile?->department?->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Result Overview Stats -->
        <div class="p-5 sm:p-6 border-b border-gray-200">
            <div class="grid grid-cols-3 gap-3 text-center text-xs">
                <div class="bg-emerald-50 border border-emerald-200 p-3.5 rounded-xl">
                    <span class="text-emerald-700 font-bold block text-xl">{{ $correctCount }}</span>
                    <span class="text-emerald-800 font-semibold mt-0.5 block text-[11px]">Correct Answers</span>
                </div>
                <div class="bg-red-50 border border-red-200 p-3.5 rounded-xl">
                    <span class="text-red-600 font-bold block text-xl">{{ $incorrectCount }}</span>
                    <span class="text-red-800 font-semibold mt-0.5 block text-[11px]">Incorrect Answers</span>
                </div>
                <div class="bg-gray-50 border border-gray-200 p-3.5 rounded-xl">
                    <span class="text-gray-600 font-bold block text-xl">{{ $unattemptedCount }}</span>
                    <span class="text-gray-600 font-semibold mt-0.5 block text-[11px]">Unattempted</span>
                </div>
            </div>
        </div>

        <!-- Question-by-Question Review -->
        <div class="p-6 sm:p-8 space-y-5">
            <h2 class="text-sm font-bold text-gray-900 border-b border-gray-200 pb-3">Question-by-Question Review ({{ $assignment->questions->count() }} Questions)</h2>

            <div class="space-y-4">
                @foreach($assignment->questions as $idx => $q)
                    @php
                        $userAns = $userAnswersByQuestionId->get($q->id);
                        $selectedOpt = $userAns ? $userAns->selected_option : null;
                        $isCorrect = $userAns ? $userAns->is_correct : false;
                    @endphp
                    <div class="page-break-inside-avoid p-4 sm:p-5 rounded-xl border {{ $isCorrect ? 'bg-emerald-50/30 border-emerald-300' : ($selectedOpt ? 'bg-red-50/30 border-red-300' : 'bg-gray-50 border-gray-200') }}">
                        <!-- Question Header -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-start gap-2.5">
                                <span class="w-6 h-6 rounded-full {{ $isCorrect ? 'bg-emerald-600 text-white' : ($selectedOpt ? 'bg-red-600 text-white' : 'bg-gray-400 text-white') }} text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                    {{ $idx + 1 }}
                                </span>
                                <h4 class="text-sm font-bold text-gray-900 leading-snug">{{ $q->question_text }}</h4>
                            </div>
                            <span class="text-xs font-black {{ $isCorrect ? 'text-emerald-700' : 'text-red-600' }} shrink-0">
                                {{ $isCorrect ? "+{$q->marks} Marks" : '0 Marks' }}
                            </span>
                        </div>

                        <!-- Options Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs mt-2">
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
                                <div class="p-2.5 rounded-lg border {{ $cardClass }} flex items-center justify-between">
                                    <span class="leading-tight"><strong class="mr-1">{{ $opt }}.</strong> {{ $optText }}</span>
                                    <div class="flex items-center gap-1 shrink-0 ml-2">
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

                        <!-- Explanation if available -->
                        @if($q->explanation)
                            <div class="mt-2.5 text-xs text-gray-700 bg-white p-2.5 rounded-lg border border-gray-200">
                                <strong class="text-indigo-900">Explanation:</strong> {{ $q->explanation }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Direct Clean PDF Export Script -->
    <script>
        function downloadAsPDF() {
            const element = document.getElementById('reportContainer');
            const btn = document.getElementById('downloadBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = `<span>Generating PDF...</span>`;
            
            const opt = {
                margin:       [8, 8, 8, 8],
                filename:     '{{ Str::slug($assignment->course->code . "_" . $assignment->title . "_" . $user->username) }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = originalText;
            }).catch(err => {
                console.error(err);
                btn.innerHTML = originalText;
                window.print();
            });
        }
    </script>

</body>
</html>
