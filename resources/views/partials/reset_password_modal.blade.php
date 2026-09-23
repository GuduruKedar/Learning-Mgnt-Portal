<!-- Universal Reset User Password Modal -->
<div id="resetUserPasswordModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-all duration-200" onclick="if(event.target===this) closeResetUserPasswordModal()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 animate-scale-up" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-amber-500/10 via-amber-50 to-indigo-50/50 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-600 flex items-center justify-center font-bold shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Reset User Password</h3>
                    <p class="text-xs text-slate-500" id="resetModalSubtext">Enter a new manual password or use default</p>
                </div>
            </div>
            <button type="button" onclick="closeResetUserPasswordModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-white/80 transition-colors focus:outline-none">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Form Body -->
        <form id="resetUserPasswordForm" action="" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Target User Info (if available) -->
            <div id="resetModalTargetBanner" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between hidden">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Target Account</span>
                    <span class="text-sm font-bold text-slate-800" id="resetModalUserName">User</span>
                </div>
                <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100" id="resetModalUserRegNo"></span>
            </div>

            <!-- New Password Input Field -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="resetModalPasswordInput" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        New Password <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" id="resetModalDefaultBtn" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                        Use Default
                    </button>
                </div>
                <div class="relative">
                    <input type="text" id="resetModalPasswordInput" name="new_password" required minlength="4" placeholder="Enter new password manually" class="w-full text-sm font-medium py-2.5 pl-3.5 pr-20 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-100 bg-white hover:border-slate-300 transition-all text-slate-800 placeholder-slate-400">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 gap-1">
                        <button type="button" onclick="generateResetModalPassword()" class="p-1.5 text-xs text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-colors" title="Generate strong random password">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Type any custom password, click default, or generate a random one.</span>
                </p>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" onclick="closeResetUserPasswordModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md shadow-amber-200 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Update Password</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let globalDefaultPass = 'Student#963';

    function openResetUserPasswordModal(formAction, regNo, fullName, defaultPass) {
        const modal = document.getElementById('resetUserPasswordModal');
        const form = document.getElementById('resetUserPasswordForm');
        const passInput = document.getElementById('resetModalPasswordInput');
        const banner = document.getElementById('resetModalTargetBanner');
        const userNameEl = document.getElementById('resetModalUserName');
        const userRegNoEl = document.getElementById('resetModalUserRegNo');
        const subtext = document.getElementById('resetModalSubtext');
        const defaultBtn = document.getElementById('resetModalDefaultBtn');

        if (!modal || !form) return;

        form.action = formAction;
        globalDefaultPass = defaultPass || 'Student#963';

        if (regNo || fullName) {
            if (banner) banner.classList.remove('hidden');
            if (userNameEl) userNameEl.textContent = fullName || regNo;
            if (userRegNoEl) userRegNoEl.textContent = regNo || '';
            if (subtext) subtext.textContent = `Set new password for ${regNo || fullName}`;
        } else {
            if (banner) banner.classList.add('hidden');
            if (subtext) subtext.textContent = 'Enter a new password for the account';
        }

        if (defaultBtn) {
            defaultBtn.textContent = `Default (${globalDefaultPass})`;
        }

        if (passInput) {
            passInput.value = '';
            setTimeout(() => passInput.focus(), 50);
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Alias for backwards/cross compatibility
    window.openManualResetPasswordModal = openResetUserPasswordModal;

    function closeResetUserPasswordModal() {
        const modal = document.getElementById('resetUserPasswordModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
    window.closeManualResetPasswordModal = closeResetUserPasswordModal;

    function generateResetModalPassword() {
        const passInput = document.getElementById('resetModalPasswordInput');
        if (!passInput) return;
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%&*';
        let res = '';
        for (let i = 0; i < 10; i++) {
            res += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        passInput.value = res;
    }
    window.generateRandomPassword = generateResetModalPassword;

    document.addEventListener('DOMContentLoaded', function() {
        const defaultBtn = document.getElementById('resetModalDefaultBtn');
        const passInput = document.getElementById('resetModalPasswordInput');
        if (defaultBtn && passInput) {
            defaultBtn.onclick = function() {
                passInput.value = globalDefaultPass;
                passInput.focus();
            };
        }
    });
</script>
