<!-- Universal Cascade Deletion Confirmation Modal -->
<div id="universalDeleteModal" class="fixed inset-0 z-[130] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity p-4" onclick="closeUniversalDeleteModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-slate-200" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="px-6 py-4.5 bg-gradient-to-r from-rose-50 to-orange-50 border-b border-rose-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 shadow-xs border border-rose-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <div>
                    <h3 id="univDelTitle" class="text-base font-bold text-slate-900">Delete Item & Linked Data</h3>
                    <p id="univDelSubtitle" class="text-xs text-slate-500 font-medium">Confirm permanent cascade deletion</p>
                </div>
            </div>
            <button type="button" onclick="closeUniversalDeleteModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-white/60 transition-colors focus:outline-none cursor-pointer" title="Close Modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-5">
            <!-- Target Item Details Preview Card -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-2">
                        <span id="univDelItemCode" class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100">--</span>
                        <span id="univDelItemBadge" class="text-xs font-semibold text-slate-600 bg-white px-2.5 py-0.5 rounded-md border border-slate-200">Item</span>
                    </div>
                    <span id="univDelItemMetaRight" class="text-xs font-medium text-slate-500 truncate max-w-[200px]"></span>
                </div>
                <h4 id="univDelItemName" class="text-base font-bold text-slate-900"></h4>
                <p id="univDelItemMeta" class="text-xs text-slate-500 font-medium"></p>
            </div>

            <!-- Cascade Records Breakdown Section -->
            <div id="univDelCascadeSection">
                <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Linked Records That Will Be Cascadingly Removed:</span>
                </h5>
                
                <div id="univDelCascadeGrid" class="grid grid-cols-2 gap-2.5">
                    <!-- Dynamic Tiles inserted by JS -->
                </div>
            </div>

            <!-- Warning Callout -->
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                <p class="font-bold flex items-center gap-1.5 text-rose-900">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span id="univDelWarningTitle">Are you sure you want to delete this item?</span>
                </p>
                <p id="univDelWarningBody" class="text-rose-700 leading-relaxed">
                    This action will permanently delete this record and automatically remove all linked child data, submissions, files, and relationships from the LMS portal.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="button" onclick="closeUniversalDeleteModal()" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors cursor-pointer">
                Cancel / Keep Item
            </button>
            <form id="universalDeleteForm" method="POST" action="" class="inline m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit" id="univDelSubmitBtn" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span id="univDelSubmitBtnText">Yes, Delete & All Linked Data</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    window.openUniversalDeleteModal = function(opts) {
        opts = opts || {};
        
        document.getElementById('univDelTitle').textContent = opts.title || 'Delete Record & Linked Data';
        document.getElementById('univDelSubtitle').textContent = opts.subtitle || 'Confirm permanent cascade deletion';

        const codeEl = document.getElementById('univDelItemCode');
        if (opts.itemCode) {
            codeEl.textContent = opts.itemCode;
            codeEl.classList.remove('hidden');
        } else {
            codeEl.classList.add('hidden');
        }

        const badgeEl = document.getElementById('univDelItemBadge');
        if (opts.itemBadge) {
            badgeEl.textContent = opts.itemBadge;
            badgeEl.classList.remove('hidden');
        } else {
            badgeEl.classList.add('hidden');
        }

        document.getElementById('univDelItemMetaRight').textContent = opts.itemMetaRight || '';
        document.getElementById('univDelItemName').textContent = opts.itemName || 'Selected Item';
        document.getElementById('univDelItemMeta').textContent = opts.itemMeta || '';

        // Cascading Grid
        const grid = document.getElementById('univDelCascadeGrid');
        grid.innerHTML = '';
        const cascadeItems = opts.cascadeItems || [];

        if (cascadeItems.length > 0) {
            document.getElementById('univDelCascadeSection').classList.remove('hidden');
            cascadeItems.forEach(item => {
                const tile = document.createElement('div');
                tile.className = 'p-3 rounded-xl bg-slate-100/80 border border-slate-200/90 flex items-center gap-2.5';
                
                let iconSvg = `<svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>`;
                
                if (item.icon === 'book' || item.icon === 'course') {
                    iconSvg = `<svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>`;
                } else if (item.icon === 'user' || item.icon === 'staff' || item.icon === 'student') {
                    iconSvg = `<svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>`;
                } else if (item.icon === 'file' || item.icon === 'material') {
                    iconSvg = `<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>`;
                } else if (item.icon === 'assignment' || item.icon === 'task' || item.icon === 'quiz') {
                    iconSvg = `<svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>`;
                } else if (item.icon === 'submission' || item.icon === 'check') {
                    iconSvg = `<svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>`;
                }

                tile.innerHTML = `
                    <div class="w-7 h-7 rounded-lg bg-white border border-slate-200/80 flex items-center justify-center shrink-0 shadow-2xs">
                        ${iconSvg}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-xs font-bold text-slate-800 truncate">${item.count || item.value || '--'}</div>
                        <div class="text-[10px] text-slate-500 font-medium truncate">${item.title || item.label || ''}</div>
                    </div>
                `;
                grid.appendChild(tile);
            });
        } else {
            document.getElementById('univDelCascadeSection').classList.add('hidden');
        }

        // Warning messages
        document.getElementById('univDelWarningTitle').textContent = opts.warningTitle || 'Are you sure you want to permanently delete this?';
        document.getElementById('univDelWarningBody').textContent = opts.warningBody || 'This action will permanently delete this record and automatically clean up all associated data from the portal.';

        // Form action & submit text
        const form = document.getElementById('universalDeleteForm');
        if (form) {
            form.action = opts.deleteUrl || '';
        }

        document.getElementById('univDelSubmitBtnText').textContent = opts.submitBtnText || 'Yes, Delete & All Linked Data';

        // Show modal
        const modal = document.getElementById('universalDeleteModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    };

    window.closeUniversalDeleteModal = function() {
        const modal = document.getElementById('universalDeleteModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeUniversalDeleteModal();
        }
    });
</script>
