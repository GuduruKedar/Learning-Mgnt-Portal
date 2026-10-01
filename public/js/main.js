// Helper: Check if sidebar is currently in collapsed state
window.isSidebarCollapsed = function() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return false;
    return (
        sidebar.classList.contains('sidebar-collapsed') ||
        document.documentElement.classList.contains('sidebar-is-collapsed')
    ) && window.innerWidth > 768;
};

// Global active flyout state tracker
let activeFlyoutTimeout = null;
let activeFlyoutElement = null;
let activeFlyoutTriggerBtn = null;

window.closeActiveFlyout = function() {
    if (activeFlyoutTimeout) {
        clearTimeout(activeFlyoutTimeout);
        activeFlyoutTimeout = null;
    }
    if (activeFlyoutElement) {
        activeFlyoutElement.remove();
        activeFlyoutElement = null;
    }
    if (activeFlyoutTriggerBtn) {
        activeFlyoutTriggerBtn.classList.remove('flyout-active');
        activeFlyoutTriggerBtn = null;
    }
    document.querySelectorAll('.flyout-active').forEach(b => b.classList.remove('flyout-active'));
};

window.openSidebarFlyout = function(menuId, triggerBtn, focusFirstItem = false) {
    if (!window.isSidebarCollapsed()) return;

    const menu = document.getElementById(menuId);
    if (!menu) return;

    const btn = triggerBtn || menu.previousElementSibling;
    if (!btn) return;

    // If this flyout is already open, cancel any close timeouts and keep it
    if (activeFlyoutElement && activeFlyoutElement.dataset.activeMenu === menuId) {
        if (activeFlyoutTimeout) {
            clearTimeout(activeFlyoutTimeout);
            activeFlyoutTimeout = null;
        }
        if (focusFirstItem) {
            const firstLink = activeFlyoutElement.querySelector('a');
            if (firstLink) firstLink.focus();
        }
        return;
    }

    // Close any previous flyout
    window.closeActiveFlyout();

    btn.classList.add('flyout-active');
    btn.setAttribute('aria-expanded', 'true');
    activeFlyoutTriggerBtn = btn;

    const flyout = document.createElement('div');
    flyout.id = 'sidebar-collapsed-flyout';
    flyout.dataset.activeMenu = menuId;
    flyout.className = 'sidebar-flyout-popover';
    flyout.setAttribute('role', 'menu');
    flyout.setAttribute('aria-label', (btn.querySelector('.nav-text') ? btn.querySelector('.nav-text').textContent.trim() : 'Navigation') + ' Menu');

    const btnRect = btn.getBoundingClientRect();
    const topPos = Math.max(12, Math.min(btnRect.top, window.innerHeight - 320));
    const leftPos = Math.round(btnRect.right + 10);
    flyout.style.top = topPos + 'px';
    flyout.style.left = leftPos + 'px';

    const sectionTitle = (btn && btn.querySelector('.nav-text')) ? btn.querySelector('.nav-text').textContent.trim() : 'Navigation';

    const linksHtml = Array.from(menu.querySelectorAll('a')).map((a, idx) => {
        const isActive = a.classList.contains('active') ? 'active' : '';
        const svgEl = a.querySelector('svg');
        const svgHtml = svgEl ? svgEl.outerHTML : '';
        const textEl = a.querySelector('.nav-text');
        const textContent = textEl ? textEl.textContent.trim() : a.textContent.trim();
        return `
            <a href="${a.href}" class="sidebar-flyout-item ${isActive}" role="menuitem" tabindex="0" data-index="${idx}">
                ${svgHtml}
                <span>${textContent}</span>
            </a>
        `;
    }).join('');

    flyout.innerHTML = `
        <div class="sidebar-flyout-header">
            <span>${sectionTitle}</span>
            <span class="text-[9px] text-slate-400 font-normal">Quick Jump</span>
        </div>
        <div class="sidebar-flyout-body">
            ${linksHtml}
        </div>
    `;

    // Keep flyout open when cursor is hovering inside the flyout
    flyout.addEventListener('mouseenter', () => {
        if (activeFlyoutTimeout) {
            clearTimeout(activeFlyoutTimeout);
            activeFlyoutTimeout = null;
        }
    });

    flyout.addEventListener('mouseleave', () => {
        activeFlyoutTimeout = setTimeout(() => {
            window.closeActiveFlyout();
        }, 180);
    });

    // Keyboard navigation inside flyout
    flyout.addEventListener('keydown', (e) => {
        const items = Array.from(flyout.querySelectorAll('.sidebar-flyout-item'));
        const currentIndex = items.indexOf(document.activeElement);

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const nextIndex = (currentIndex + 1) % items.length;
            items[nextIndex]?.focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const prevIndex = (currentIndex - 1 + items.length) % items.length;
            items[prevIndex]?.focus();
        } else if (e.key === 'ArrowLeft' || e.key === 'Escape') {
            e.preventDefault();
            window.closeActiveFlyout();
            btn.focus();
        } else if (e.key === 'Tab') {
            window.closeActiveFlyout();
        }
    });

    document.body.appendChild(flyout);
    activeFlyoutElement = flyout;

    if (focusFirstItem) {
        const firstLink = flyout.querySelector('a');
        if (firstLink) firstLink.focus();
    }
};

window.scheduleCloseSidebarFlyout = function() {
    if (activeFlyoutTimeout) clearTimeout(activeFlyoutTimeout);
    activeFlyoutTimeout = setTimeout(() => {
        window.closeActiveFlyout();
    }, 180);
};

window.toggleSidebarSection = function(menuId, triggerBtn, event) {
    if (event && event.stopPropagation) {
        event.stopPropagation();
    }
    const isCollapsed = window.isSidebarCollapsed();
    const menu = document.getElementById(menuId);
    if (!menu) return;

    if (isCollapsed) {
        const btn = triggerBtn || menu.previousElementSibling;
        if (activeFlyoutElement && activeFlyoutElement.dataset.activeMenu === menuId) {
            window.closeActiveFlyout();
        } else {
            const isKeyboard = event && (event.type === 'keydown' || event.detail === 0);
            window.openSidebarFlyout(menuId, btn, isKeyboard);
        }
        return;
    }

    // In Expanded Mode: standard inline accordion toggle
    const isCurrentlyHidden = menu.classList.contains('hidden');
    if (isCurrentlyHidden) {
        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
    }

    const btn = triggerBtn || menu.previousElementSibling;
    if (btn) {
        btn.setAttribute('aria-expanded', isCurrentlyHidden ? 'true' : 'false');
        const icon = btn.querySelector('.section-toggle-icon');
        if (icon) {
            icon.classList.toggle('rotate-180', isCurrentlyHidden);
        }
    }
};

window.initLMSUI = function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-sidebar');

    if (sidebar) {
        const updateSidebarStateUI = (collapsed) => {
            if (collapsed) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                document.documentElement.classList.add('sidebar-is-collapsed');
                if (toggleBtn) {
                    toggleBtn.setAttribute('aria-expanded', 'false');
                    toggleBtn.setAttribute('title', 'Expand sidebar (Ctrl+B)');
                    toggleBtn.setAttribute('aria-label', 'Expand navigation');
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                document.documentElement.classList.remove('sidebar-is-collapsed');
                if (toggleBtn) {
                    toggleBtn.setAttribute('aria-expanded', 'true');
                    toggleBtn.setAttribute('title', 'Collapse sidebar (Ctrl+B)');
                    toggleBtn.setAttribute('aria-label', 'Collapse navigation');
                }
            }
        };

        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 768;
        updateSidebarStateUI(isCollapsed);

        // Attach hover listeners for section groups in collapsed mode
        const sectionGroups = sidebar.querySelectorAll('.sidebar-section-group');
        sectionGroups.forEach(group => {
            const btn = group.querySelector('button');
            const menu = group.querySelector('.submenu-container');
            if (btn && menu) {
                const menuId = menu.id;

                group.addEventListener('mouseenter', () => {
                    if (window.isSidebarCollapsed()) {
                        if (activeFlyoutTimeout) clearTimeout(activeFlyoutTimeout);
                        window.openSidebarFlyout(menuId, btn);
                    }
                });

                group.addEventListener('mouseleave', () => {
                    if (window.isSidebarCollapsed()) {
                        window.scheduleCloseSidebarFlyout();
                    }
                });

                // Keyboard trigger on button
                btn.addEventListener('keydown', (e) => {
                    if (window.isSidebarCollapsed()) {
                        if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowRight') {
                            e.preventDefault();
                            window.openSidebarFlyout(menuId, btn, true);
                        }
                    }
                });
            }
        });

        // Click handler for desktop sidebar collapse/expand
        const toggleSidebarAction = (e) => {
            if (e) e.stopPropagation();
            window.closeActiveFlyout();
            
            const currentlyCollapsed = window.isSidebarCollapsed();
            const shouldCollapse = !currentlyCollapsed;
            localStorage.setItem('sidebarCollapsed', shouldCollapse ? 'true' : 'false');
            updateSidebarStateUI(shouldCollapse);
        };

        if (toggleBtn) {
            toggleBtn.onclick = toggleSidebarAction;
        }

        // Global hotkey Ctrl+B / Cmd+B for collapsing/expanding sidebar
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b' && !e.shiftKey && !e.altKey) {
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName)) return;
                e.preventDefault();
                toggleSidebarAction(e);
            }
        });

        // Close flyouts on Escape key or outside click
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                window.closeActiveFlyout();
            }
        });

        document.addEventListener('click', (e) => {
            if (activeFlyoutElement && !activeFlyoutElement.contains(e.target) && (!activeFlyoutTriggerBtn || !activeFlyoutTriggerBtn.contains(e.target))) {
                window.closeActiveFlyout();
            }
        });

        window.addEventListener('resize', () => {
            window.closeActiveFlyout();
        });
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

// Global Theme Management Engine (Light & Dark Theme Switcher)
window.initTheme = function() {
    const savedTheme = localStorage.getItem('themeMode') || 'light';
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

window.toggleThemeMode = function() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('themeMode', isDark ? 'dark' : 'light');
    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { isDark } }));
};

// Color Palette Theme Engine (1. Blue, 2. Green, 3. Violet, 4. Orange, 5. Rose, 6. Indigo)
window.initColorTheme = function() {
    const savedColor = localStorage.getItem('colorTheme') || 'blue';
    document.documentElement.setAttribute('data-theme-color', savedColor);
    window.updateColorThemeUI(savedColor);
};

window.setColorTheme = function(color) {
    const validColors = ['blue', 'green', 'violet', 'orange', 'rose', 'indigo'];
    if (!validColors.includes(color)) color = 'blue';
    document.documentElement.setAttribute('data-theme-color', color);
    localStorage.setItem('colorTheme', color);
    window.updateColorThemeUI(color);
    window.dispatchEvent(new CustomEvent('colorThemeChanged', { detail: { color } }));
};

window.updateColorThemeUI = function(color) {
    const ringClasses = [
        'ring-blue-500', 
        'ring-emerald-500', 
        'ring-purple-500', 
        'ring-orange-500', 
        'ring-rose-500', 
        'ring-indigo-500'
    ];
    document.querySelectorAll('.color-theme-btn').forEach(btn => {
        const btnColor = btn.getAttribute('data-color');
        const checkIcon = btn.querySelector('.select-check-icon');
        btn.classList.remove('ring-2', 'ring-offset-2', 'scale-110', 'shadow-md', ...ringClasses);
        
        if (btnColor === color) {
            btn.classList.add('ring-2', 'ring-offset-2', 'scale-110', 'shadow-md');
            if (color === 'blue') btn.classList.add('ring-blue-500');
            else if (color === 'green') btn.classList.add('ring-emerald-500');
            else if (color === 'violet') btn.classList.add('ring-purple-500');
            else if (color === 'orange') btn.classList.add('ring-orange-500');
            else if (color === 'rose') btn.classList.add('ring-rose-500');
            else if (color === 'indigo') btn.classList.add('ring-indigo-500');
            btn.setAttribute('aria-pressed', 'true');
            if (checkIcon) checkIcon.classList.remove('hidden');
        } else {
            btn.setAttribute('aria-pressed', 'false');
            if (checkIcon) checkIcon.classList.add('hidden');
        }
    });
};

// Execute theme checks immediately on script execution
window.initTheme();
window.initColorTheme();

document.addEventListener('DOMContentLoaded', function() {
    window.initTheme();
    window.initColorTheme();
    window.initLMSUI();
});
