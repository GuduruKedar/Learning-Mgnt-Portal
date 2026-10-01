<!-- Cascade Delete Faculty Confirmation Modal -->
<div id="deleteFacultyModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity p-4" onclick="closeDeleteFacultyModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-slate-200" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4.5 bg-gradient-to-r from-rose-50 to-orange-50 border-b border-rose-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 shadow-xs border border-rose-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Delete Faculty & Linked Data</h3>
                    <p class="text-xs text-slate-500 font-medium">Confirm permanent cascade deletion</p>
                </div>
            </div>
            <button type="button" onclick="closeDeleteFacultyModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-white/60 transition-colors focus:outline-none cursor-pointer" title="Close Modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-5">
            <!-- Faculty Target Preview Card -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-2">
                        <span id="delModalFacultyUsername" class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100">ID: --</span>
                        <span id="delModalFacultyDesignation" class="text-xs font-semibold text-slate-600 bg-white px-2.5 py-0.5 rounded-md border border-slate-200">Faculty</span>
                    </div>
                    <span id="delModalFacultyDept" class="text-xs font-medium text-slate-500 truncate max-w-[200px]">Department</span>
                </div>
                <h4 id="delModalFacultyName" class="text-base font-bold text-slate-900">Faculty Name</h4>
                <p id="delModalFacultyMeta" class="text-xs text-slate-500 font-medium"></p>
            </div>

            <!-- Linked Child Records Breakdown -->
            <div>
                <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Linked Records That Will Be Cascadingly Removed:</span>
                </h5>
                
                <div class="grid grid-cols-2 gap-2.5">
                    <!-- Teaching Course Allocations -->
                    <div class="p-3 rounded-xl bg-indigo-50/70 border border-indigo-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <div id="delModalFacultyCoursesCount" class="text-sm font-bold text-indigo-900">0 Courses</div>
                            <div class="text-[11px] text-indigo-600 font-medium">Assigned Courses</div>
                        </div>
                    </div>

                    <!-- Uploaded Materials & Notes -->
                    <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <div id="delModalFacultyMaterialsCount" class="text-sm font-bold text-blue-900">0 Materials</div>
                            <div class="text-[11px] text-blue-600 font-medium">Notes & Files</div>
                        </div>
                    </div>

                    <!-- Assignments & Quizzes -->
                    <div class="p-3 rounded-xl bg-purple-50/70 border border-purple-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div>
                            <div id="delModalFacultyAssignmentsCount" class="text-sm font-bold text-purple-900">0 Assignments</div>
                            <div class="text-[11px] text-purple-600 font-medium">Quizzes & Questions</div>
                        </div>
                    </div>

                    <!-- Submissions & Evaluations -->
                    <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-amber-900">All Submissions</div>
                            <div class="text-[11px] text-amber-600 font-medium">Student Attempts</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning Callout -->
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                <p class="font-bold flex items-center gap-1.5 text-rose-900">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Are you sure you want to delete this faculty member?</span>
                </p>
                <p class="text-rose-700 leading-relaxed">
                    This action will permanently delete this faculty member and automatically clean up all associated teaching course allocations, uploaded notes, study materials, assignments, questions, and student submission records from the system.
                </p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="button" onclick="closeDeleteFacultyModal()" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors cursor-pointer">
                Cancel / Keep Faculty
            </button>
            <form id="deleteFacultyForm" method="POST" action="" class="inline m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Yes, Delete Faculty & All Linked Data</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteFacultyModal(name, username, email, designation, dept, school, coursesCount, materialsCount, assignmentsCount, delUrl) {
        document.getElementById('delModalFacultyName').textContent = name || 'Faculty Member';
        document.getElementById('delModalFacultyUsername').textContent = 'ID: ' + (username || '--');
        document.getElementById('delModalFacultyDesignation').textContent = designation || 'Faculty';
        document.getElementById('delModalFacultyDept').textContent = dept || 'Department';
        
        let metaParts = [];
        if (email && email !== 'N/A') metaParts.push(email);
        if (school && school !== 'N/A') metaParts.push(school);
        document.getElementById('delModalFacultyMeta').textContent = metaParts.join(' • ');

        document.getElementById('delModalFacultyCoursesCount').textContent = (coursesCount || 0) + ' ' + (coursesCount == 1 ? 'Course' : 'Courses');
        document.getElementById('delModalFacultyMaterialsCount').textContent = (materialsCount || 0) + ' ' + (materialsCount == 1 ? 'Material' : 'Materials');
        document.getElementById('delModalFacultyAssignmentsCount').textContent = (assignmentsCount || 0) + ' ' + (assignmentsCount == 1 ? 'Assignment' : 'Assignments');

        const form = document.getElementById('deleteFacultyForm');
        if (form) {
            form.action = delUrl;
        }

        const modal = document.getElementById('deleteFacultyModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeDeleteFacultyModal() {
        const modal = document.getElementById('deleteFacultyModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteFacultyModal();
        }
    });
</script>
