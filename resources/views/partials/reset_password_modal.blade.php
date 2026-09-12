<!-- Reset User Password Modal -->
<div id="resetUserPasswordModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-orange-50">
            <h3 class="text-lg font-bold text-orange-900">Reset User Password</h3>
            <button type="button" onclick="closeResetUserPasswordModal()" class="text-orange-400 hover:text-orange-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">Leave the password field blank to reset to the user's default password.</p>
            <form id="resetUserPasswordForm" action="" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password (Optional)</label>
                        <input type="text" name="new_password" placeholder="Enter new password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeResetUserPasswordModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 shadow-sm">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openResetUserPasswordModal(formAction) {
        document.getElementById('resetUserPasswordForm').action = formAction;
        document.getElementById('resetUserPasswordModal').classList.remove('hidden');
    }
    function closeResetUserPasswordModal() {
        document.getElementById('resetUserPasswordModal').classList.add('hidden');
    }
</script>
