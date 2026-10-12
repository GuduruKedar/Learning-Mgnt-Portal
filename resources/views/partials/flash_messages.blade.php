@if(session('success') || session('error') || session('warning') || $errors->any())
<div id="globalFlashMessageContainer" class="fixed top-5 right-5 z-[999] max-w-md w-full space-y-3 pointer-events-none">
    @if(session('success'))
    <div class="toast-item pointer-events-auto relative overflow-hidden flex items-start gap-3.5 p-4 bg-white/95 dark:bg-[#151B23]/95 backdrop-blur-md border border-emerald-200/80 dark:border-emerald-500/30 rounded-2xl shadow-[0_12px_36px_-6px_rgba(0,0,0,0.12),0_4px_16px_rgba(0,0,0,0.04)] dark:shadow-2xl text-slate-800 dark:text-[#F8FAFC] toast-slide-in">
        <div class="p-2 bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-500/30 rounded-xl shrink-0 mt-0.5 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC]">Success</h4>
            <p class="text-xs text-slate-600 dark:text-[#94A3B8] mt-1 font-medium leading-relaxed">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-700 dark:hover:text-[#F8FAFC] p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-[#1C2430] transition-colors shrink-0" aria-label="Close notification">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="toast-progress absolute bottom-0 left-0 right-0 h-1 bg-emerald-500/30 dark:bg-emerald-500/40">
            <div class="toast-progress-bar h-full bg-emerald-500 rounded-full" style="animation: toastTimer 5s linear forwards;"></div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="toast-item pointer-events-auto relative overflow-hidden flex items-start gap-3.5 p-4 bg-white/95 dark:bg-[#151B23]/95 backdrop-blur-md border border-rose-200/80 dark:border-rose-500/30 rounded-2xl shadow-[0_12px_36px_-6px_rgba(0,0,0,0.12),0_4px_16px_rgba(0,0,0,0.04)] dark:shadow-2xl text-slate-800 dark:text-[#F8FAFC] toast-slide-in">
        <div class="p-2 bg-rose-50 dark:bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-500/30 rounded-xl shrink-0 mt-0.5 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC]">Notice / Error</h4>
            <p class="text-xs text-slate-600 dark:text-[#94A3B8] mt-1 font-medium leading-relaxed">{{ session('error') }}</p>
        </div>
        <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-700 dark:hover:text-[#F8FAFC] p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-[#1C2430] transition-colors shrink-0" aria-label="Close notification">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="toast-progress absolute bottom-0 left-0 right-0 h-1 bg-rose-500/30 dark:bg-rose-500/40">
            <div class="toast-progress-bar h-full bg-rose-500 rounded-full" style="animation: toastTimer 5s linear forwards;"></div>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="toast-item pointer-events-auto relative overflow-hidden flex items-start gap-3.5 p-4 bg-white/95 dark:bg-[#151B23]/95 backdrop-blur-md border border-amber-200/80 dark:border-amber-500/30 rounded-2xl shadow-[0_12px_36px_-6px_rgba(0,0,0,0.12),0_4px_16px_rgba(0,0,0,0.04)] dark:shadow-2xl text-slate-800 dark:text-[#F8FAFC] toast-slide-in">
        <div class="p-2 bg-amber-50 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-500/30 rounded-xl shrink-0 mt-0.5 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC]">Warning</h4>
            <p class="text-xs text-slate-600 dark:text-[#94A3B8] mt-1 font-medium leading-relaxed">{{ session('warning') }}</p>
        </div>
        <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-700 dark:hover:text-[#F8FAFC] p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-[#1C2430] transition-colors shrink-0" aria-label="Close notification">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="toast-progress absolute bottom-0 left-0 right-0 h-1 bg-amber-500/30 dark:bg-amber-500/40">
            <div class="toast-progress-bar h-full bg-amber-500 rounded-full" style="animation: toastTimer 5s linear forwards;"></div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="toast-item pointer-events-auto relative overflow-hidden flex items-start gap-3.5 p-4 bg-white/95 dark:bg-[#151B23]/95 backdrop-blur-md border border-rose-200/80 dark:border-rose-500/30 rounded-2xl shadow-[0_12px_36px_-6px_rgba(0,0,0,0.12),0_4px_16px_rgba(0,0,0,0.04)] dark:shadow-2xl text-slate-800 dark:text-[#F8FAFC] toast-slide-in">
        <div class="p-2 bg-rose-50 dark:bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-500/30 rounded-xl shrink-0 mt-0.5 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC]">Validation Notice</h4>
            <div class="text-xs text-slate-600 dark:text-[#94A3B8] mt-1.5 space-y-1 font-medium">
                @foreach($errors->all() as $err)
                    <div class="flex items-start gap-1.5 leading-relaxed">
                        <span class="text-rose-500 mt-0.5 font-bold shrink-0">•</span>
                        <span>{{ $err }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-700 dark:hover:text-[#F8FAFC] p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-[#1C2430] transition-colors shrink-0" aria-label="Close notification">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="toast-progress absolute bottom-0 left-0 right-0 h-1 bg-rose-500/30 dark:bg-rose-500/40">
            <div class="toast-progress-bar h-full bg-rose-500 rounded-full" style="animation: toastTimer 6s linear forwards;"></div>
        </div>
    </div>
    @endif
</div>
@endif

<script>
    function dismissToast(el) {
        if (!el) return;
        el.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-12px) scale(0.95)';
        setTimeout(() => {
            el.remove();
            const container = document.getElementById('globalFlashMessageContainer');
            if (container && container.children.length === 0) {
                container.remove();
            }
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss each toast after its animation timer
        document.querySelectorAll('.toast-item').forEach(toast => {
            setTimeout(() => {
                dismissToast(toast);
            }, 6000);
        });

        // Check for one-time client flash message (e.g. from bulk upload)
        const clientSuccessMsg = sessionStorage.getItem('bulk_upload_toast_success');
        if (clientSuccessMsg) {
            sessionStorage.removeItem('bulk_upload_toast_success');
            
            let container = document.getElementById('globalFlashMessageContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'globalFlashMessageContainer';
                container.className = 'fixed top-5 right-5 z-[999] max-w-md w-full space-y-3 pointer-events-none';
                document.body.appendChild(container);
            }
            
            const toast = document.createElement('div');
            toast.className = 'toast-item pointer-events-auto relative overflow-hidden flex items-start gap-3.5 p-4 bg-white/95 dark:bg-[#151B23]/95 backdrop-blur-md border border-emerald-200/80 dark:border-emerald-500/30 rounded-2xl shadow-[0_12px_36px_-6px_rgba(0,0,0,0.12),0_4px_16px_rgba(0,0,0,0.04)] dark:shadow-2xl text-slate-800 dark:text-[#F8FAFC] toast-slide-in';
            toast.innerHTML = `
                <div class="p-2 bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-500/30 rounded-xl shrink-0 mt-0.5 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC]">Success</h4>
                    <p class="text-xs text-slate-600 dark:text-[#94A3B8] mt-1 font-medium leading-relaxed">${clientSuccessMsg}</p>
                </div>
                <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-700 dark:hover:text-[#F8FAFC] p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-[#1C2430] transition-colors shrink-0" aria-label="Close notification">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="toast-progress absolute bottom-0 left-0 right-0 h-1 bg-emerald-500/30 dark:bg-emerald-500/40">
                    <div class="toast-progress-bar h-full bg-emerald-500 rounded-full" style="animation: toastTimer 5s linear forwards;"></div>
                </div>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                dismissToast(toast);
            }, 5000);
        }

        // Clean up any stale query parameters from URL
        if (window.location.search.includes('upload_success')) {
            const cleanUrl = window.location.pathname + window.location.search.replace(/[\?&]upload_success=[^&]+/, '').replace(/^&/, '?');
            window.history.replaceState({}, document.title, cleanUrl || window.location.pathname);
        }
    });
</script>
