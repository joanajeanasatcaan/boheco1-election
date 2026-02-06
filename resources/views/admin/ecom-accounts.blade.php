<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('eccom-accounts') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Manage authetication credentials for election committee members across all districts.
                </p>
            </div>
            <div class="text-sm text-gray-500">
                Last updated: {{ now()->format('M j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8 relative">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-green-500/20 to-transparent rounded-full -translate-y-32 translate-x-32"></div>
                        <div class="relative">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-6 md:mb-0">
                                    <h1 class="text-lg md:text-4xl font-bold text-white mb-2 drop-shadow">
                                        {{ __('Ecom Accounts') }}
                                    </h1>
                                    <p class="text-sm text-green-100/90 max-w-2xl">
                                        {{ __('Manage authetication credentials for election committee members across all districts.') }}
                                    </p>
                                </div>
                                <button onclick="openAddAccountModal()" class="group text-sm 
                                inline-flex items-center justify-center px-6 py-3 bg-white 
                                text-green-700 font-semibold rounded-xl hover:bg-green-50 
                                active:scale-[0.98] transition-all duration-200 shadow-lg 
                                hover:shadow-xl focus:ring-none focus:ring-2 focus:ring-white 
                                focus:ring-offset-2 focus:ring-offset-green-700">
                                    <x-plus-logo class="h-5 w-5 mr-2 group-hover:rotate-90 
                                    transition-transform"/>
                                    Add Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Election Committee Accounts</h3>
                            <p class="text-sm text-gray-500">Authentication credentials for district election officials</p>
                        </div>
                        <div class="text-sm text-gray-500" id="account-count">
                            Showing 0 accounts
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-green-600 to-emerald-600">
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Account Details
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        District & Access
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Status
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white" id="accounts-table-body">
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-20 w-20 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-700 mb-2">No accounts found</h4>
                                        <p class="text-gray-500 mb-4">Add your first ECOM account to get started</p>
                                        <button onclick="openAddAccountModal()"
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                                            <x-plus-logo class="h-5 w-5 mr-2" />
                                            Add First Account
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="addAccountModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeAddAccountModal()"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                                <x-plus-logo class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-white">Create New Account</h3>
                        </div>
                        <button onclick="closeAddAccountModal()" 
                            class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-white/20 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form id="addAccountForm" class="px-6 py-6">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="username" required
                                    class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                    placeholder="Enter username">
                                <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Username must be unique</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" required
                                    class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                    placeholder="Enter password">
                                <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eye-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                Password must contain at least 8 characters with uppercase, lowercase, and numbers
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="confirm_password" required
                                    class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                    placeholder="Confirm password">
                                <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                District <span class="text-red-500">*</span>
                            </label>
                            <select id="district" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 bg-white">
                                <option value="">Select District</option>
                                <option value="District 1">District 1</option>
                                <option value="District 2">District 2</option>
                                <option value="District 3">District 3</option>
                                <option value="District 4">District 4</option>
                                <option value="District 5">District 5</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Account Status
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="active" checked
                                        class="peer sr-only">
                                    <div class="flex items-center justify-center p-4 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all duration-200">
                                        <div class="flex items-center">
                                            <div class="h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 transition-colors"></div>
                                            <div>
                                                <span class="font-medium text-gray-700 peer-checked:text-green-700">Active</span>
                                                <p class="text-xs text-gray-500">Account can login</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="inactive"
                                        class="peer sr-only">
                                    <div class="flex items-center justify-center p-4 rounded-xl border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all duration-200">
                                        <div class="flex items-center">
                                            <div class="h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:border-red-500 peer-checked:bg-red-500 mr-3 transition-colors"></div>
                                            <div>
                                                <span class="font-medium text-gray-700 peer-checked:text-red-700">Inactive</span>
                                                <p class="text-xs text-gray-500">Account disabled</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" onclick="closeAddAccountModal()"
                            class="flex-1 px-4 py-3 text-gray-700 font-medium border border-gray-300 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all duration-200">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>

    function editAccount(id) {
        const account = ecomAccounts.find(acc => acc.id === id);
        if (!account) return;
        
        document.getElementById('username').value = account.username;
        document.getElementById('password').value = account.password;
        document.getElementById('confirm_password').value = account.password;
        document.getElementById('district').value = account.district;
        document.querySelector(`input[name="status"][value="${account.status}"]`).checked = true;
        
        document.querySelector('#addAccountModal h3').textContent = 'Edit Account';
        document.querySelector('#addAccountModal button[type="submit"]').textContent = 'Update Account';
        
        const form = document.getElementById('addAccountForm');
        form.onsubmit = function(e) {
            e.preventDefault();
            updateAccount(id);
        };
        
        openAddAccountModal();
    }

    function updateAccount(id) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const district = document.getElementById('district').value;
        const status = document.querySelector('input[name="status"]:checked').value;
        
        if (!username || !password || !confirmPassword || !district) {
            alert('Please fill in all required fields.');
            return;
        }
        
        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }
        
        const accountIndex = ecomAccounts.findIndex(acc => acc.id === id);
        if (accountIndex !== -1) {
            ecomAccounts[accountIndex] = {
                ...ecomAccounts[accountIndex],
                username,
                password,
                district,
                status
            };
            
            alert('Account updated successfully!');
            closeAddAccountModal();
            filterAccounts();
        }
    }

    function deleteAccount(id) {
        if (confirm('Are you sure you want to delete this account?')) {
            const index = ecomAccounts.findIndex(acc => acc.id === id);
            if (index !== -1) {
                ecomAccounts.splice(index, 1);
                filterAccounts();
                alert('Account deleted successfully!');
            }
        }
    }

    function addNewAccount() {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const district = document.getElementById('district').value;
        const status = document.querySelector('input[name="status"]:checked').value;
        
        if (!username || !password || !confirmPassword || !district) {
            alert('Please fill in all required fields.');
            return;
        }
        
        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }
        
        if (ecomAccounts.some(acc => acc.username === username)) {
            alert('Username already exists. Please choose a different one.');
            return;
        }
        
        const newAccount = {
            id: ecomAccounts.length + 1,
            username,
            password,
            district,
            status,
            lastLogin: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + ' ' + 
                      new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
            createdDate: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
        };
        
        ecomAccounts.push(newAccount);
        alert('Account created successfully!');
        closeAddAccountModal();
        filterAccounts();
    }

    function openAddAccountModal() {

        document.getElementById('addAccountForm').reset();
        document.querySelector('#addAccountModal h3').textContent = 'Create New Account';
        document.querySelector('#addAccountModal button[type="submit"]').textContent = 'Create Account';
        
        const form = document.getElementById('addAccountForm');
        form.onsubmit = function(e) {
            e.preventDefault();
            addNewAccount();
        };
        
        document.getElementById('addAccountModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeAddAccountModal() {
        document.getElementById('addAccountModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderAccountsTable();
        
        document.getElementById('search-input').addEventListener('input', filterAccounts);
        document.getElementById('status-filter').addEventListener('change', filterAccounts);
        document.getElementById('district-filter').addEventListener('change', filterAccounts);
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddAccountModal();
            }
        });
        
        document.getElementById('addAccountModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeAddAccountModal();
            }
        });
    });
</script>