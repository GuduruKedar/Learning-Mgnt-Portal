<!-- Universal Protected Student Deletion Modal -->
<div id="protectedStudentModal" 
     class="fixed inset-0 z-[100] items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 transition-all duration-200" 
     style="display: none;" 
     onclick="if(event.target===this) closeProtectedStudentModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden border border-amber-200/80 animate-in fade-in zoom-in-95 duration-200" 
         onclick="event.stopPropagation()">
        
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-amber-500/10 via-amber-50 to-orange-50/40 border-b border-amber-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold shadow-xs shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Protected Student Record</h3>
                    <p class="text-xs text-amber-700 font-semibold">Active Enrollments Detected</p>
                </div>
            </div>
            <button type="button" onclick="closeProtectedStudentModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-white/80 transition-colors focus:outline-none">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4">
            <p class="text-sm text-slate-600 leading-relaxed">
                Student <span id="protectedStudentNameBadge" class="font-bold text-slate-900 px-2 py-0.5 bg-slate-100 rounded-md border border-slate-200"></span> (<span id="protectedStudentRegBadge" class="font-mono text-xs font-bold text-indigo-700"></span>) cannot be deleted because they are actively enrolled in:
            </p>

            <ul class="space-y-2 bg-slate-50 p-3.5 rounded-xl border border-slate-200/70 text-xs" id="protectedStudentEnrollmentList">
            </ul>

            <div class="bg-amber-50/80 border border-amber-200/80 rounded-xl p-3.5 text-xs text-amber-900 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-amber-800">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Required Action:
                </div>
                <p class="leading-relaxed">Please un-enroll the student from all enrolled courses and Civil Services training first before removing this student account.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5">
            <button type="button" onclick="closeProtectedStudentModal()" class="w-full sm:w-auto px-4 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 focus:outline-none transition-colors cursor-pointer">
                Close
            </button>
            <a id="protectedStudentManageBtn" href="#" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/20 hover:shadow-lg transition-all focus:outline-none cursor-pointer">
                <span id="protectedStudentManageText">Manage Enrollments</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</div>

<script>
    window.openProtectedStudentModal = function(name, regNo, regularCount, civilCount, manageUrl) {
        const modal = document.getElementById('protectedStudentModal');
        const nameEl = document.getElementById('protectedStudentNameBadge');
        const regEl = document.getElementById('protectedStudentRegBadge');
        const listEl = document.getElementById('protectedStudentEnrollmentList');
        const manageBtn = document.getElementById('protectedStudentManageBtn');
        const manageText = document.getElementById('protectedStudentManageText');

        if (!modal) {
            let reason = [];
            if (regularCount > 0) reason.push(`${regularCount} academic course(s)`);
            if (civilCount > 0) reason.push(`Civil Services training`);
            alert(`Cannot delete student ${name} (${regNo}). The student is actively enrolled in ${reason.join(' and ')}. Please un-enroll them first before deleting.`);
            if (manageUrl) window.location.href = manageUrl;
            return;
        }

        if (nameEl) nameEl.textContent = name || regNo;
        if (regEl) regEl.textContent = regNo;
        
        if (listEl) {
            listEl.innerHTML = '';
            if (regularCount > 0) {
                const li = document.createElement('li');
                li.className = 'flex items-center gap-2 font-semibold text-indigo-900';
                li.innerHTML = '<span class="w-2 h-2 rounded-full bg-indigo-600 shrink-0"></span> ' + regularCount + ' Academic Course(s)';
                listEl.appendChild(li);
            }
            if (civilCount > 0) {
                const li = document.createElement('li');
                li.className = 'flex items-center gap-2 font-semibold text-amber-900';
                li.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-600 shrink-0"></span> Civil Services Training (Active Enrollment)';
                listEl.appendChild(li);
            }
        }

        if (manageBtn) {
            manageBtn.href = manageUrl || '#';
        }
        if (manageText) {
            manageText.textContent = civilCount > 0 ? 'Go to Civil Services' : 'Manage Course Enrollments';
        }

        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeProtectedStudentModal = function() {
        const modal = document.getElementById('protectedStudentModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProtectedStudentModal();
        }
    });
</script>
