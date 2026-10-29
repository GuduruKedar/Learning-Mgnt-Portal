@php
    $currentUser = Auth::user();
    $userId = $currentUser?->id ?? 'guest';
    $role = $currentUser?->role ?? 'user';
    $roleLabel = ['sa' => 'Super Admin', 'ssh_admin' => 'SSH Admin', 'admin' => 'Department Coordinator', 'civil_admin' => 'Civil Services Admin', 'sta' => 'Faculty Member', 'stu' => 'Student'][$role] ?? 'User';

    // Curate real, meaningful Academic & Portal Notifications (NEVER audit logs like login/logout or server exceptions)
    $notifications = [];

    // 1. Recent Course Materials
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('course_materials')) {
            $materials = \App\Models\CourseMaterial::with('course')->latest()->take(3)->get();
            foreach ($materials as $m) {
                $courseName = $m->course->code ?? $m->course->name ?? 'Course';
                $timeAgo = $m->created_at ? $m->created_at->diffForHumans(null, true, true) : 'recent';
                $notifications[] = [
                    'id' => 'mat_' . $m->id,
                    'title' => 'Course Material Added',
                    'message' => "Material '{$m->title}' uploaded for {$courseName}.",
                    'time' => $timeAgo,
                    'color' => 'blue',
                    'icon' => 'document'
                ];
            }
        }
    } catch (\Throwable $e) {}

    // 2. Published Assignments & Quizzes
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('assignments')) {
            $assignments = \App\Models\Assignment::with('course')->where('status', 'published')->latest()->take(3)->get();
            foreach ($assignments as $a) {
                $courseName = $a->course->code ?? $a->course->name ?? 'Course';
                $timeAgo = $a->created_at ? $a->created_at->diffForHumans(null, true, true) : 'recent';
                $notifications[] = [
                    'id' => 'assign_' . $a->id,
                    'title' => 'Assignment Published',
                    'message' => "'{$a->title}' is active for {$courseName}.",
                    'time' => $timeAgo,
                    'color' => 'emerald',
                    'icon' => 'clipboard'
                ];
            }
        }
    } catch (\Throwable $e) {}

    // 3. Positive Academic Milestones (Only student enrollments or cohort additions)
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('activity_logs')) {
            $academicEvents = \App\Models\ActivityLog::whereIn('action', ['cohort_enrolled', 'student_created'])
                ->latest()
                ->take(2)
                ->get();
            foreach ($academicEvents as $evt) {
                $timeAgo = $evt->created_at ? $evt->created_at->diffForHumans(null, true, true) : 'recent';
                $notifications[] = [
                    'id' => 'evt_' . $evt->id,
                    'title' => $evt->action_title ?: 'Academic Update',
                    'message' => \Illuminate\Support\Str::limit($evt->description, 80),
                    'time' => $timeAgo,
                    'color' => 'indigo',
                    'icon' => 'user-group'
                ];
            }
        }
    } catch (\Throwable $e) {}

    // Fallback pleasant system alerts if database has fewer items
    if (count($notifications) < 2) {
        $notifications[] = [
            'id' => 'sys_academic',
            'title' => 'Academic Term Active',
            'message' => "Logged in as {$roleLabel}. All departmental tools are ready.",
            'time' => '1m',
            'color' => 'emerald',
            'icon' => 'badge-check'
        ];
    }

    // Limit to max 6 clean notifications
    $notifications = array_slice($notifications, 0, 6);
    $initialCount = count($notifications);
@endphp

<!-- Notifications Bell Widget -->
<div class="relative" id="notificationsWidgetContainer">
    <!-- Bell Button -->
    <button 
        type="button" 
        id="notificationsDropdownBtn" 
        onclick="toggleNotificationsDropdown(event)" 
        class="relative p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#1C2430] hover:text-slate-900 dark:hover:text-white transition-all duration-150 focus:outline-none shrink-0 cursor-pointer" 
        title="Notifications" 
        aria-label="View notifications"
        aria-haspopup="true"
        aria-expanded="false"
    >
        <!-- Bell SVG -->
        <svg class="w-5 h-5 text-slate-600 dark:text-[#94A3B8] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Unread Badge Counter -->
        <span 
            id="notificationBadge" 
            class="absolute top-1 right-1 flex items-center justify-center min-w-[17px] h-[17px] px-1 text-[10px] font-bold text-white bg-rose-500 rounded-full shadow-xs transition-transform duration-200"
        >
            <span id="notificationBadgePulse" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-60"></span>
            <span id="notificationBadgeCount" class="relative z-10">{{ $initialCount }}</span>
        </span>
    </button>

    <!-- Dropdown Menu Popover -->
    <div 
        id="notificationsDropdownMenu" 
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-[#151B23] rounded-2xl shadow-2xl border border-slate-200 dark:border-[#273244] py-0 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right transition-all overflow-hidden"
        role="dialog"
        aria-label="Notifications Dropdown"
    >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-slate-100 dark:border-[#273244] bg-slate-50/80 dark:bg-[#1C2430] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-800 dark:text-[#F8FAFC] uppercase tracking-wider">Academic Updates</span>
                <span 
                    id="notifHeaderCountBadge" 
                    class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60"
                >
                    <span id="notifHeaderCount">{{ $initialCount }}</span> new
                </span>
            </div>

            <!-- Clear All Button -->
            <button 
                type="button" 
                id="clearAllNotifsBtn"
                onclick="clearAllNotifications(event)" 
                class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 px-2 py-1 rounded-lg hover:bg-slate-100 dark:hover:bg-[#273244] transition-colors cursor-pointer"
                title="Clear all notifications"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Clear all</span>
            </button>
        </div>

        <!-- Notifications Scrollable Feed -->
        <div id="notificationsList" class="divide-y divide-slate-100 dark:divide-[#273244] max-h-80 overflow-y-auto custom-scrollbar">
            @foreach($notifications as $notif)
                <div 
                    class="notification-item p-3.5 hover:bg-slate-50/80 dark:hover:bg-[#1C2430]/60 transition-colors flex items-start gap-3 group relative" 
                    data-id="{{ $notif['id'] }}"
                >
                    <!-- Icon Indicator -->
                    <div class="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center text-xs font-bold mt-0.5 shadow-2xs 
                        @if($notif['color'] === 'rose') bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60
                        @elseif($notif['color'] === 'amber') bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60
                        @elseif($notif['color'] === 'emerald') bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60
                        @elseif($notif['color'] === 'indigo') bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60
                        @else bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60
                        @endif"
                    >
                        @if($notif['icon'] === 'document')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @elseif($notif['icon'] === 'clipboard')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        @elseif($notif['icon'] === 'user-group')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @elseif($notif['icon'] === 'badge-check')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0 pr-6">
                        <div class="flex items-center justify-between gap-1.5 mb-0.5">
                            <p class="text-xs font-bold text-slate-800 dark:text-[#F8FAFC] truncate">{{ $notif['title'] }}</p>
                            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 shrink-0 font-mono">{{ $notif['time'] }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-[#94A3B8] leading-relaxed break-words">{{ $notif['message'] }}</p>
                    </div>

                    <!-- Single Item Dismiss Button -->
                    <button 
                        type="button" 
                        onclick="dismissSingleNotification('{{ $notif['id'] }}', event)" 
                        class="absolute right-2.5 top-3 p-1 rounded-md text-slate-300 hover:text-slate-600 dark:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-[#273244] opacity-0 group-hover:opacity-100 transition-all cursor-pointer" 
                        title="Dismiss notification"
                        aria-label="Dismiss notification"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Empty State Container -->
        <div id="notificationsEmptyState" class="hidden py-10 px-4 text-center">
            <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-100 dark:bg-[#1C2430] flex items-center justify-center text-slate-400 dark:text-slate-500 shadow-inner">
                <svg class="w-6 h-6 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-800 dark:text-slate-200">No new notifications</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">You're all caught up!</p>
            
            <button 
                type="button" 
                onclick="restoreNotifications(event)" 
                class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/60 transition-colors cursor-pointer"
            >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset notifications</span>
            </button>
        </div>

        <!-- Footer -->
        <div class="px-4 py-2 border-t border-slate-100 dark:border-[#273244] bg-slate-50/50 dark:bg-[#1C2430]/60 flex items-center justify-between text-[11px]">
            <span class="text-slate-400 dark:text-slate-500 font-medium">Academic Notifications</span>
            <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Up to date
            </span>
        </div>
    </div>
</div>

<script>
    (function() {
        const userId = "{{ $userId }}";
        const storageKey = 'lms_cleared_notifs_v2_' + userId;

        function getClearedList() {
            try {
                return JSON.parse(localStorage.getItem(storageKey) || '[]');
            } catch(e) {
                return [];
            }
        }

        function setClearedList(list) {
            try {
                localStorage.setItem(storageKey, JSON.stringify(list));
            } catch(e) {}
        }

        function updateNotificationBadge() {
            const listEl = document.getElementById('notificationsList');
            const badgeEl = document.getElementById('notificationBadge');
            const countEl = document.getElementById('notificationBadgeCount');
            const headerCountEl = document.getElementById('notifHeaderCount');
            const headerBadgeEl = document.getElementById('notifHeaderCountBadge');
            const emptyEl = document.getElementById('notificationsEmptyState');
            const clearBtn = document.getElementById('clearAllNotifsBtn');

            if (!listEl) return;

            const visibleItems = listEl.querySelectorAll('.notification-item:not(.hidden)');
            const count = visibleItems.length;

            if (countEl) countEl.textContent = count;
            if (headerCountEl) headerCountEl.textContent = count;

            if (count === 0) {
                if (badgeEl) badgeEl.classList.add('hidden');
                if (headerBadgeEl) headerBadgeEl.classList.add('hidden');
                if (clearBtn) clearBtn.classList.add('hidden');
                if (emptyEl) emptyEl.classList.remove('hidden');
                if (listEl) listEl.classList.add('hidden');
            } else {
                if (badgeEl) badgeEl.classList.remove('hidden');
                if (headerBadgeEl) headerBadgeEl.classList.remove('hidden');
                if (clearBtn) clearBtn.classList.remove('hidden');
                if (emptyEl) emptyEl.classList.add('hidden');
                if (listEl) listEl.classList.remove('hidden');
            }
        }

        // Apply cleared state on page load
        function initNotifications() {
            const cleared = getClearedList();
            if (cleared.includes('__ALL__')) {
                document.querySelectorAll('#notificationsList .notification-item').forEach(el => el.classList.add('hidden'));
            } else if (cleared.length > 0) {
                cleared.forEach(id => {
                    const el = document.querySelector('#notificationsList .notification-item[data-id="' + id + '"]');
                    if (el) el.classList.add('hidden');
                });
            }
            updateNotificationBadge();
        }

        window.toggleNotificationsDropdown = function(e) {
            e.stopPropagation();
            const notifMenu = document.getElementById('notificationsDropdownMenu');
            const profileMenu = document.getElementById('profileDropdownMenu');
            if (profileMenu) profileMenu.classList.add('hidden');

            if (notifMenu) {
                notifMenu.classList.toggle('hidden');
            }
        };

        window.clearAllNotifications = function(e) {
            if (e) e.stopPropagation();
            const listEl = document.getElementById('notificationsList');
            if (!listEl) return;

            const items = listEl.querySelectorAll('.notification-item');
            items.forEach(el => {
                el.classList.add('transition-all', 'duration-200', 'opacity-0', '-translate-x-2');
                setTimeout(() => el.classList.add('hidden'), 200);
            });

            setClearedList(['__ALL__']);

            setTimeout(() => {
                updateNotificationBadge();
            }, 210);
        };

        window.dismissSingleNotification = function(id, e) {
            if (e) e.stopPropagation();
            const el = document.querySelector('#notificationsList .notification-item[data-id="' + id + '"]');
            if (el) {
                el.classList.add('transition-all', 'duration-200', 'opacity-0', '-translate-x-2');
                setTimeout(() => {
                    el.classList.add('hidden');
                    const cleared = getClearedList();
                    if (!cleared.includes(id)) {
                        cleared.push(id);
                        setClearedList(cleared);
                    }
                    updateNotificationBadge();
                }, 200);
            }
        };

        window.restoreNotifications = function(e) {
            if (e) e.stopPropagation();
            setClearedList([]);
            document.querySelectorAll('#notificationsList .notification-item').forEach(el => {
                el.classList.remove('hidden', 'opacity-0', '-translate-x-2');
            });
            updateNotificationBadge();
        };

        // Close dropdown when clicking anywhere else
        document.addEventListener('click', function(e) {
            const notifWidget = document.getElementById('notificationsWidgetContainer');
            const notifMenu = document.getElementById('notificationsDropdownMenu');
            if (notifMenu && !notifMenu.classList.contains('hidden')) {
                if (notifWidget && !notifWidget.contains(e.target)) {
                    notifMenu.classList.add('hidden');
                }
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const notifMenu = document.getElementById('notificationsDropdownMenu');
                if (notifMenu && !notifMenu.classList.contains('hidden')) {
                    notifMenu.classList.add('hidden');
                }
            }
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNotifications);
        } else {
            initNotifications();
        }
    })();
</script>
