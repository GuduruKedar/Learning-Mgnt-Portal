window.initLMSUI = function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-sidebar');
    const navTexts = document.querySelectorAll('.nav-text');
    const logoText = document.getElementById('logo-text');
    const logoContainer = document.getElementById('logo-container');

    if (sidebar && toggleBtn && logoText && logoContainer) {
        let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        if (isCollapsed) {
            sidebar.classList.remove('sidebar-expanded');
            sidebar.classList.add('sidebar-collapsed');
            logoText.classList.add('hidden');
            logoText.classList.remove('text-expanded');
            logoContainer.classList.remove('justify-between');
            logoContainer.classList.add('justify-center');
            navTexts.forEach(el => {
                el.classList.add('text-collapsed');
                el.classList.remove('text-expanded');
            });
        }

        // We use an onclick assignment to avoid attaching multiple listeners if initLMSUI is called twice
        toggleBtn.onclick = () => {
            isCollapsed = !isCollapsed;
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            
            if (isCollapsed) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                logoText.classList.remove('text-expanded');
                logoText.classList.add('hidden');
                logoContainer.classList.remove('justify-between');
                logoContainer.classList.add('justify-center');
                navTexts.forEach(el => {
                    el.classList.remove('text-expanded');
                    el.classList.add('text-collapsed');
                });
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                logoText.classList.remove('hidden');
                logoText.classList.add('text-expanded');
                logoContainer.classList.remove('justify-center');
                logoContainer.classList.add('justify-between');
                navTexts.forEach(el => {
                    el.classList.remove('text-collapsed');
                    el.classList.add('text-expanded');
                });
            }
        };
    }

    const profileBtn = document.getElementById('profileDropdownBtn');
    const profileMenu = document.getElementById('profileDropdownMenu');
    if (profileBtn && profileMenu) {
        profileBtn.onclick = (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        };
        document.addEventListener('click', () => {
            profileMenu.classList.add('hidden');
        });
        profileMenu.onclick = (e) => {
            e.stopPropagation();
        };
    }

    const pwdModal = document.getElementById('passwordModal');
    const openPwdBtn = document.getElementById('openPasswordModalBtn');
    const closePwdBtn = document.getElementById('closePasswordModal');
    const cancelPwdBtn = document.getElementById('cancelPasswordModal');
    
    window.openPwdModal = function() {
        if (pwdModal) {
            pwdModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };
    
    window.closePwdModal = function() {
        if (pwdModal) {
            pwdModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };
    
    if (pwdModal && openPwdBtn) {
        openPwdBtn.onclick = (e) => {
            e.preventDefault();
            window.openPwdModal();
            if(typeof profileMenu !== 'undefined' && profileMenu) profileMenu.classList.add('hidden');
        };
        if(closePwdBtn) closePwdBtn.onclick = window.closePwdModal;
        if(cancelPwdBtn) cancelPwdBtn.onclick = window.closePwdModal;
    }
    // Initialize TomSelect globally for dropdowns, avoiding duplicates
    if (typeof TomSelect !== 'undefined') {
        document.querySelectorAll('select:not(.no-tomselect):not(#edit_status)').forEach(function(el) {
            if (!el.classList.contains('tomselected')) {
                let ts = new TomSelect(el, {
                    create: false,
                    maxOptions: null,
                    sortField: { field: "text", direction: "asc" }
                });
                
                // Safe auto-sync TomSelect when original select options are modified via JS
                let syncTimeout = null;
                const observer = new MutationObserver(() => {
                    if (ts.isOpen) return;
                    clearTimeout(syncTimeout);
                    syncTimeout = setTimeout(() => {
                        if (!ts.isOpen) ts.sync();
                    }, 50);
                });
                observer.observe(el, { childList: true });
            }
        });
    }
    // Mobile sidebar toggle logic
    const mobileToggle = document.getElementById('mobile-toggle');
    const overlay = document.getElementById('sidebar-overlay');
    const header = document.querySelector('header');

    if (mobileToggle && sidebar && overlay) {
        // Move mobile toggle into the header dynamically to prevent overlap
        if (header && window.innerWidth <= 768) {
            mobileToggle.classList.remove('fixed', 'top-3', 'left-4', 'z-[60]');
            mobileToggle.classList.add('ml-1', 'mr-auto', 'my-auto');
            header.classList.remove('justify-end');
            header.classList.add('justify-between');
            header.prepend(mobileToggle);
        }

        function toggleMobileSidebar() {
            sidebar.classList.toggle('open');
            if (sidebar.classList.contains('open')) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                document.body.style.overflow = 'hidden';
            } else {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                document.body.style.overflow = '';
            }
        }

        const mobileClose = document.getElementById('mobile-close-sidebar');
        if (mobileClose) {
            mobileClose.onclick = toggleMobileSidebar;
        }
        mobileToggle.onclick = toggleMobileSidebar;
        overlay.onclick = toggleMobileSidebar;
    }
};
document.addEventListener('DOMContentLoaded', window.initLMSUI);
