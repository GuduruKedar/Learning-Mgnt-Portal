<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }} - MCQ Management & Results</title>
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
                <a href="{{ route('staff.assignments.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">Assignments</a>
                <span class="text-gray-300">/</span>
                <span class="text-sm font-semibold text-indigo-700 truncate max-w-xs sm:max-w-md">{{ $assignment->title }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('staff.assignments.edit', $assignment->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Details
                </a>
                <a href="{{ route('staff.assignments.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back
                </a>
            </div>
        </header>

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
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $assignment->course->code }}
                                </span>
                                <span class="text-xs text-gray-500 font-medium">{{ $assignment->course->name }}</span>
                                <span class="text-gray-300">&bull;</span>
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800">MCQ Test</span>
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

                        <!-- Assignment Metadata Sidebar -->
                        <div class="lg:w-80 bg-gray-50 p-5 rounded-xl border border-gray-200/80 space-y-4 shrink-0">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Questions</span>
                                    <p class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $assignment->questions->count() }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Marks</span>
                                    <p class="text-2xl font-extrabold text-indigo-700 mt-0.5">{{ $assignment->max_marks }} <span class="text-xs font-semibold text-gray-500">pts</span></p>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Deadline</span>
                                <p class="text-sm font-bold {{ $assignment->isPastDue() ? 'text-red-600' : 'text-gray-900' }} mt-0.5">
                                    {{ $assignment->due_date->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Submissions</span>
                                <div class="flex items-center justify-between text-xs mt-1 font-semibold text-gray-700">
                                    <span>{{ $assignment->submissions->count() }} of {{ $enrolledStudents->count() }} Enrolled</span>
                                    <span>{{ $enrolledStudents->count() > 0 ? round(($assignment->submissions->count() / $enrolledStudents->count()) * 100) : 0 }}%</span>
                                </div>
                            </div>
                        </div>
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
                            Student Results ({{ $assignment->submissions->count() }})
                        </button>
                    </div>

                    <!-- Panel 1: Questions List -->
                    <div id="tab-questions-panel" class="p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-bold text-gray-900">Questions in this Assignment</h3>
                            <button type="button" onclick="document.getElementById('addQuestionModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add MCQ Question
                            </button>
                        </div>

                        @if($assignment->questions->isEmpty())
                            <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 text-center">
                                <p class="text-sm font-medium text-gray-600">No questions added to this assignment yet.</p>
                                <button type="button" onclick="document.getElementById('addQuestionModal').classList.remove('hidden')" class="mt-3 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg">Add First Question</button>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($assignment->questions as $index => $q)
                                <div class="bg-gray-50/80 rounded-xl p-5 border border-gray-200">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-3">
                                            <span class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                                Q{{ $index + 1 }}
                                            </span>
                                            <div>
                                                <h4 class="text-sm font-bold text-gray-900">{{ $q->question_text }}</h4>
                                                <span class="inline-block mt-1 text-xs text-gray-500 font-semibold">{{ $q->marks }} {{ Str::plural('Mark', $q->marks) }}</span>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('staff.assignments.questions.destroy', $q->id) }}" onsubmit="return confirm('Delete this question?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="Delete Question">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>

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
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Panel 2: Student Results -->
                    <div id="tab-results-panel" class="p-6 sm:p-8 hidden space-y-4">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead class="bg-gray-50/80 text-xs uppercase font-bold text-gray-500 border-b border-gray-200">
                                    <tr>
                                        <th class="py-3.5 px-6">Student</th>
                                        <th class="py-3.5 px-4">Status</th>
                                        <th class="py-3.5 px-4">Score</th>
                                        <th class="py-3.5 px-4">Percentage</th>
                                        <th class="py-3.5 px-4">Submitted At</th>
                                        <th class="py-3.5 px-6 text-right">Answer Sheet</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($enrolledStudents as $student)
                                        @php
                                            $submission = $submissionsByStudent->get($student->id);
                                            $percentage = ($submission && $assignment->max_marks > 0) ? round(($submission->marks_awarded / $assignment->max_marks) * 100) : 0;
                                        @endphp
                                        <tr class="hover:bg-gray-50/70 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="font-bold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                <div class="text-xs text-gray-500 font-mono">{{ $student->username }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if(!$submission)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">Pending</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">Completed & Graded</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($submission)
                                                    <span class="font-bold text-base text-indigo-700">{{ $submission->marks_awarded }}</span>
                                                    <span class="text-xs text-gray-500">/ {{ $assignment->max_marks }}</span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($submission)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $percentage >= 70 ? 'bg-emerald-100 text-emerald-800' : ($percentage >= 40 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $percentage }}%
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 text-xs text-gray-600">
                                                @if($submission)
                                                    {{ $submission->submitted_at->format('M d, Y h:i A') }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                @if($submission)
                                                    <button type="button" onclick="openStudentReviewModal('{{ addslashes($student->first_name . ' ' . $student->last_name) }}', '{{ $submission->marks_awarded }}', '{{ $assignment->max_marks }}', '{{ $percentage }}', {{ json_encode($submission->answers->keyBy('question_id')) }})" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                                                        View Responses &rarr;
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">No responses</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 px-6 text-center text-gray-500 text-sm">
                                                No students are currently enrolled in this course.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
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
            <form action="{{ route('staff.assignments.questions.store', $assignment->id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Question Statement <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="2" required placeholder="Type the question text here..." class="w-full text-sm border border-gray-300 rounded-lg p-2.5"></textarea>
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

    <!-- Global Questions Data for JS modal -->
    <script>
        const assignmentQuestions = @json($assignment->questions);

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

        function openStudentReviewModal(studentName, marks, maxMarks, percentage, answers) {
            document.getElementById('reviewStudentMeta').textContent = `${studentName} — Score: ${marks} / ${maxMarks} (${percentage}%)`;
            const container = document.getElementById('reviewQuestionsContainer');
            container.innerHTML = '';

            assignmentQuestions.forEach((q, idx) => {
                const ans = answers[q.id];
                const selectedOpt = ans ? ans.selected_option : null;
                const isCorrect = ans ? ans.is_correct : false;

                const card = document.createElement('div');
                card.className = `p-4 rounded-xl border ${isCorrect ? 'bg-emerald-50/40 border-emerald-200' : (selectedOpt ? 'bg-red-50/40 border-red-200' : 'bg-gray-50 border-gray-200')}`;

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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                        ${optionsHtml}
                    </div>
                    ${q.explanation ? `<div class="mt-2 text-[11px] text-gray-500 italic">Explanation: ${q.explanation}</div>` : ''}
                `;

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
