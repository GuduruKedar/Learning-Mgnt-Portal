<!-- Coordinator Details Quick View Modal -->
<div id="coordinatorDetailsModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-950/60 backdrop-blur-sm transition-all duration-300 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all border border-slate-200/80 animate-in fade-in zoom-in duration-200" onclick="event.stopPropagation()">
        <!-- Top Profile Banner -->
        <div class="relative bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 p-6 text-white overflow-hidden">
            <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-10 h-32 w-32 rounded-full bg-blue-500/20 blur-2xl pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <div class="flex items-center gap-4">
                    <div id="coordModalPhotoContainer" class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-indigo-950/40 ring-4 ring-white/20 shrink-0 overflow-hidden">
                        <span id="coordModalInitial">C</span>
                        <img id="coordModalPhoto" src="" alt="" class="w-full h-full object-cover hidden">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 id="coordModalName" class="text-xl font-bold text-white tracking-tight">Coordinator Profile</h3>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Active
                            </span>
                        </div>
                        <p id="coordModalDesignation" class="text-xs text-indigo-200/90 mt-0.5 font-medium">Department Coordinator</p>
                        <p class="text-xs text-indigo-300/80 mt-1 font-mono flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span id="coordModalDeptName">Department Name</span>
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeCoordinatorDetailsModal()" class="text-indigo-200 hover:text-white hover:bg-white/10 p-2 rounded-xl transition-colors focus:outline-none" title="Close Modal">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Body Details -->
        <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
            <!-- Profile & Contact Details -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile & Contact Details
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Employee ID / Username -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex items-center justify-between hover:bg-indigo-50/30 transition-colors group">
                        <div>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Employee ID / Username</p>
                            <p id="coordModalUsername" class="text-sm font-bold text-slate-800 font-mono mt-0.5">--</p>
                        </div>
                        <button type="button" id="coordModalUsernameCopyBtn" onclick="copyCoordText('coordModalUsername')" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-none hover:shadow-sm" title="Copy Username">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>

                    <!-- Email Address -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex items-center justify-between hover:bg-indigo-50/30 transition-colors group">
                        <div class="truncate mr-2">
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Email Address</p>
                            <div id="coordModalEmailContainer" class="mt-0.5">
                                <a id="coordModalEmailLink" href="javascript:void(0)" class="text-sm font-bold text-indigo-600 hover:underline truncate block hidden">
                                    <span id="coordModalEmail">--</span>
                                </a>
                                <span id="coordModalEmailText" class="text-sm font-bold text-slate-500 block">N/A</span>
                            </div>
                        </div>
                        <button type="button" id="coordModalEmailCopyBtn" onclick="copyCoordText('coordModalEmail')" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-none hover:shadow-sm shrink-0 hidden" title="Copy Email">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>

                    <!-- Phone Number -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex items-center justify-between hover:bg-indigo-50/30 transition-colors group">
                        <div class="truncate mr-2">
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Phone Number</p>
                            <div id="coordModalPhoneContainer" class="mt-0.5">
                                <a id="coordModalPhoneLink" href="javascript:void(0)" class="text-sm font-bold text-indigo-600 hover:underline block hidden">
                                    <span id="coordModalPhone">--</span>
                                </a>
                                <span id="coordModalPhoneText" class="text-sm font-bold text-slate-500 block">N/A</span>
                            </div>
                        </div>
                        <button type="button" id="coordModalPhoneCopyBtn" onclick="copyCoordText('coordModalPhone')" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-none hover:shadow-sm shrink-0 hidden" title="Copy Phone">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>

                    <!-- Role Access -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">System Role</p>
                        <p class="text-sm font-bold text-slate-800 mt-0.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                            Department Coordinator (Admin)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Academic Affiliation Section -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Academic Affiliation
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">School / Faculty</p>
                        <p id="coordModalSchool" class="text-sm font-bold text-slate-800 mt-0.5">--</p>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Department</p>
                        <p id="coordModalDepartment" class="text-sm font-bold text-slate-800 mt-0.5">--</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
            <button type="button" onclick="closeCoordinatorDetailsModal()" class="px-5 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 rounded-xl transition-colors shadow-sm focus:outline-none">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openCoordinatorDetailsModal(data) {
        if (!data) return;
        document.getElementById('coordModalName').innerText = data.name || 'Coordinator Profile';
        document.getElementById('coordModalDesignation').innerText = data.designation || 'Department Coordinator';
        document.getElementById('coordModalDeptName').innerText = data.department || 'N/A';
        document.getElementById('coordModalUsername').innerText = data.username || 'N/A';
        
        // Email handling: only make clickable mailto if a valid email exists
        const emailEl = document.getElementById('coordModalEmail');
        const emailLink = document.getElementById('coordModalEmailLink');
        const emailText = document.getElementById('coordModalEmailText');
        const emailCopyBtn = document.getElementById('coordModalEmailCopyBtn');
        
        const rawEmail = (data.email || '').trim();
        const hasValidEmail = rawEmail && rawEmail !== 'N/A' && rawEmail !== 'No email provided' && rawEmail !== '--';
        
        if (hasValidEmail) {
            emailEl.innerText = rawEmail;
            emailLink.href = 'mailto:' + rawEmail;
            emailLink.classList.remove('hidden');
            emailText.classList.add('hidden');
            if (emailCopyBtn) emailCopyBtn.classList.remove('hidden');
        } else {
            emailText.innerText = 'N/A';
            emailLink.href = 'javascript:void(0)';
            emailLink.classList.add('hidden');
            emailText.classList.remove('hidden');
            if (emailCopyBtn) emailCopyBtn.classList.add('hidden');
        }

        // Phone handling: only make clickable tel if a valid phone exists
        const phoneEl = document.getElementById('coordModalPhone');
        const phoneLink = document.getElementById('coordModalPhoneLink');
        const phoneText = document.getElementById('coordModalPhoneText');
        const phoneCopyBtn = document.getElementById('coordModalPhoneCopyBtn');
        
        const rawPhone = (data.phone || '').trim();
        const hasValidPhone = rawPhone && rawPhone !== 'N/A' && rawPhone !== '--';
        
        if (hasValidPhone) {
            phoneEl.innerText = rawPhone;
            phoneLink.href = 'tel:' + rawPhone;
            phoneLink.classList.remove('hidden');
            phoneText.classList.add('hidden');
            if (phoneCopyBtn) phoneCopyBtn.classList.remove('hidden');
        } else {
            phoneText.innerText = 'N/A';
            phoneLink.href = 'javascript:void(0)';
            phoneLink.classList.add('hidden');
            phoneText.classList.remove('hidden');
            if (phoneCopyBtn) phoneCopyBtn.classList.add('hidden');
        }

        document.getElementById('coordModalSchool').innerText = data.school || 'N/A';
        document.getElementById('coordModalDepartment').innerText = data.department + (data.deptCode ? ' (' + data.deptCode + ')' : '');
        
        const photoEl = document.getElementById('coordModalPhoto');
        const initialEl = document.getElementById('coordModalInitial');
        if (data.photo) {
            photoEl.src = data.photo;
            photoEl.classList.remove('hidden');
            initialEl.classList.add('hidden');
        } else {
            photoEl.classList.add('hidden');
            initialEl.innerText = data.initial || 'C';
            initialEl.classList.remove('hidden');
        }

        const modal = document.getElementById('coordinatorDetailsModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeCoordinatorDetailsModal() {
        const modal = document.getElementById('coordinatorDetailsModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function copyCoordText(elementId) {
        const el = document.getElementById(elementId);
        if (!el) return;
        const text = el.innerText ? el.innerText.trim() : '';
        if (text && text !== '--' && text !== 'N/A' && text !== 'No email provided') {
            navigator.clipboard.writeText(text).catch(() => {});
        }
    }

    // Close on Escape or click outside
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCoordinatorDetailsModal();
        }
    });

    const coordModalElement = document.getElementById('coordinatorDetailsModal');
    if (coordModalElement) {
        coordModalElement.addEventListener('click', function(e) {
            if (e.target === coordModalElement) {
                closeCoordinatorDetailsModal();
            }
        });
    }
</script>
