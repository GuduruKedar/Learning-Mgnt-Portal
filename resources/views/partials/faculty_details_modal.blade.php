<!-- Faculty Details Quick View Modal -->
<div id="facultyDetailsModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-950/60 backdrop-blur-sm transition-all duration-300 p-4" onclick="closeFacultyModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all border border-slate-200/80 animate-in fade-in zoom-in duration-200" onclick="event.stopPropagation()">
        <!-- Top Profile Banner -->
        <div class="relative bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 p-6 text-white overflow-hidden">
            <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-10 h-32 w-32 rounded-full bg-blue-500/20 blur-2xl pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <div class="flex items-center gap-4">
                    <div id="facultyModalPhotoContainer" class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-indigo-950/40 ring-4 ring-white/20 shrink-0 overflow-hidden">
                        <span id="facultyModalInitial">F</span>
                        <img id="facultyModalPhoto" src="" alt="" class="w-full h-full object-cover hidden">
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 id="facultyModalName" class="text-xl font-bold text-white tracking-tight">Faculty Name</h3>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Faculty
                            </span>
                        </div>
                        <p id="facultyModalDesignation" class="text-xs text-indigo-200/90 mt-0.5 font-medium">Faculty Member</p>
                        <p class="text-xs text-indigo-300/80 mt-1 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span id="facultyModalDept">Department</span>
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeFacultyModal()" class="text-indigo-200 hover:text-white hover:bg-white/10 p-2 rounded-xl transition-colors focus:outline-none" title="Close Modal">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Body Details -->
        <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
            <!-- Current Allocated Course Banner -->
            <div id="facultyModalCourseBanner" class="p-3 bg-indigo-50/70 border border-indigo-100/80 rounded-xl flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Allocated Course</p>
                        <p id="facultyModalCourseName" class="text-xs font-bold text-slate-800">--</p>
                    </div>
                </div>
            </div>

            <!-- Profile & Contact Details -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile & Contact Information
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Employee ID / Username -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex items-center justify-between hover:bg-indigo-50/30 transition-colors group">
                        <div>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Employee ID</p>
                            <p id="facultyModalCode" class="text-sm font-bold text-slate-800 font-mono mt-0.5">--</p>
                        </div>
                        <button type="button" onclick="copyFacultyText('facultyModalCode')" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-none hover:shadow-xs" title="Copy Employee ID">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>

                    <!-- Email Address -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex items-center justify-between hover:bg-indigo-50/30 transition-colors group">
                        <div class="truncate mr-2">
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Email Address</p>
                            <div id="facultyModalEmailContainer" class="mt-0.5">
                                <a id="facultyModalEmailLink" href="javascript:void(0)" class="text-sm font-bold text-indigo-600 hover:underline truncate block hidden">
                                    <span id="facultyModalEmail">--</span>
                                </a>
                                <span id="facultyModalEmailText" class="text-sm font-bold text-slate-500 block">N/A</span>
                            </div>
                        </div>
                        <button type="button" id="facultyModalEmailCopyBtn" onclick="copyFacultyText('facultyModalEmail')" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-none hover:shadow-xs shrink-0 hidden" title="Copy Email">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>

                    <!-- Phone Number -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex items-center justify-between hover:bg-indigo-50/30 transition-colors group">
                        <div class="truncate mr-2">
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Phone Number</p>
                            <div id="facultyModalPhoneContainer" class="mt-0.5">
                                <a id="facultyModalPhoneLink" href="javascript:void(0)" class="text-sm font-bold text-indigo-600 hover:underline block hidden">
                                    <span id="facultyModalPhone">--</span>
                                </a>
                                <span id="facultyModalPhoneText" class="text-sm font-bold text-slate-500 block">N/A</span>
                            </div>
                        </div>
                        <button type="button" id="facultyModalPhoneCopyBtn" onclick="copyFacultyText('facultyModalPhone')" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-none hover:shadow-xs shrink-0 hidden" title="Copy Phone">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>

                    <!-- School Affiliation -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">School / Division</p>
                        <p id="facultyModalSchool" class="text-sm font-bold text-slate-800 mt-0.5">--</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-between items-center border-t border-slate-100 gap-2.5">
            <form id="facultyModalDeleteForm" method="POST" class="w-full sm:w-auto inline-flex m-0" onsubmit="return confirm('Are you sure you want to remove this faculty from the course?');">
                @csrf
                @method('DELETE')
                <button type="submit" id="facultyModalDeleteBtn" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 rounded-xl text-xs font-bold transition-all focus:outline-none">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Remove from this Course</span>
                </button>
            </form>
            <button type="button" onclick="closeFacultyModal()" class="w-full sm:w-auto px-5 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all shadow-xs focus:outline-none">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function showFacultyDetails(name, code, email, phone, designation, dept, school, photo, deleteUrl, courseInfo) {
        document.getElementById('facultyModalName').innerText = name || 'Faculty Details';
        document.getElementById('facultyModalCode').innerText = code || 'N/A';
        document.getElementById('facultyModalDesignation').innerText = designation || 'Faculty';
        document.getElementById('facultyModalDept').innerText = dept || 'General';
        document.getElementById('facultyModalSchool').innerText = school || 'Social Sciences & Humanities';

        // Course Info Banner
        const courseBanner = document.getElementById('facultyModalCourseBanner');
        const courseNameEl = document.getElementById('facultyModalCourseName');
        if (courseInfo && courseInfo.trim() !== '') {
            courseNameEl.innerText = courseInfo;
            courseBanner.classList.remove('hidden');
        } else {
            courseBanner.classList.add('hidden');
        }

        // Email handling
        const emailEl = document.getElementById('facultyModalEmail');
        const emailLink = document.getElementById('facultyModalEmailLink');
        const emailText = document.getElementById('facultyModalEmailText');
        const emailCopyBtn = document.getElementById('facultyModalEmailCopyBtn');
        const rawEmail = (email || '').trim();
        const hasValidEmail = rawEmail && rawEmail !== 'N/A' && rawEmail !== '--';

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

        // Phone handling
        const phoneEl = document.getElementById('facultyModalPhone');
        const phoneLink = document.getElementById('facultyModalPhoneLink');
        const phoneText = document.getElementById('facultyModalPhoneText');
        const phoneCopyBtn = document.getElementById('facultyModalPhoneCopyBtn');
        const rawPhone = (phone || '').trim();
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

        // Photo / Initial
        const photoEl = document.getElementById('facultyModalPhoto');
        const initialEl = document.getElementById('facultyModalInitial');
        if (photo && photo.trim() !== '' && photo !== 'null') {
            photoEl.src = photo;
            photoEl.classList.remove('hidden');
            initialEl.classList.add('hidden');
        } else {
            photoEl.classList.add('hidden');
            initialEl.innerText = (name && name.length > 0) ? name.charAt(0).toUpperCase() : 'F';
            initialEl.classList.remove('hidden');
        }

        // Unallocate action
        const deleteForm = document.getElementById('facultyModalDeleteForm');
        if (deleteUrl && deleteUrl.trim() !== '') {
            deleteForm.action = deleteUrl;
            deleteForm.classList.remove('hidden');
        } else {
            deleteForm.classList.add('hidden');
        }

        const modal = document.getElementById('facultyDetailsModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeFacultyModal() {
        const modal = document.getElementById('facultyDetailsModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function copyFacultyText(elementId) {
        const el = document.getElementById(elementId);
        if (!el) return;
        const text = el.innerText ? el.innerText.trim() : '';
        if (text && text !== '--' && text !== 'N/A') {
            navigator.clipboard.writeText(text).then(() => {
                const orig = el.innerText;
                el.innerText = 'Copied!';
                setTimeout(() => { el.innerText = orig; }, 1200);
            }).catch(() => {});
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFacultyModal();
        }
    });
</script>
