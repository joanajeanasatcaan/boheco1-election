let ecomAccounts = [];

async function loadEcomAccounts() {
    try {
        const response = await fetch('/api/ecom-profile', {
            credentials: 'include'
        });
        const json = await response.json();


        ecomAccounts = (json.data ?? []).map(user => ({
            id: user.id,
            name: user.name,
            email: user.email,
            password: user.ecom_password,
            district: user.district != null ? "District " + user.district : 'N/A',
            status: user.status ?? 'Active',
            createdAt: user.created_at
        }));

        renderAccountsTable();
    } catch (error) {
        console.error('Failed to load ECOM accounts:', error);
    }
}

function renderAccountsTable() {
    const tbody = document.getElementById('accounts-table-body');
    const counter = document.getElementById('account-count');

    tbody.innerHTML = '';

    if (ecomAccounts.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="h-20 w-20 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">
                        No accounts found
                        </h4>
                        <p class="text-gray-500 m-4">
                        Add your first ECOM account
                        </p>
                        <button onclick="openAddAccountModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all>
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add First Account
                        </button>
                    </div>
                </td>
            </tr>
        `;
        if (counter) counter.textContent = 'Showing 0 accounts';
        return;
    }

    if (counter) counter.textContent = `Showing ${ecomAccounts.length} accounts`;

    ecomAccounts.forEach(acc => {
        const statusClass = acc.status?.toLowerCase() === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700';

        tbody.innerHTML += `
            <tr onclick="viewAccountDetails('${acc.id}')" 
                class="hover:bg-gray-50 transition-colors cursor-pointer group"
                style="cursor: pointer;">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div>
                            <div class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                ${acc.name || 'N/A'}
                            </div>
                            <div class="text-sm text-gray-500">${acc.email || 'No email'}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="font-mono text-gray-600">••••••••</div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700">
                        ${acc.district}
                    </div>
                </td>

                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold ${statusClass}">
                        <span class="h-2 w-2 rounded-full ${acc.status?.toLowerCase() === 'active' ? 'bg-green-500' : 'bg-gray-500'} mr-2"></span>
                        ${acc.status?.charAt(0).toUpperCase() + acc.status?.slice(1) || 'Unknown'}
                    </span>
                </td>

                <td class="px-6 py-4" onclick="event.stopPropagation()">
                    <div class="flex items-center gap-2">
                        <button onclick="editAccount('${acc.id}')" 
                            class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all duration-200 group/edit"
                            title="Edit Account">
                            <svg class="h-5 w-5 group-hover/edit:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
        
                        <button onclick="deleteAccount('${acc.id}')" 
                            class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all duration-200 group/delete"
                            title="Delete Account">
                            <svg class="h-5 w-5 group-hover/delete:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

function viewAccountDetails(id) {
    if (!ecomAccounts || !Array.isArray(ecomAccounts)) {
        console.error('ecomAccounts is not defined or not an array');
        return;
    }

    const account = ecomAccounts.find(acc => String(acc.id) === String(id));
    if (!account) {
        console.error('Account not found with id:', id);
        return;
    }

    const existingModal = document.getElementById('accountDetailsModal');
    if (existingModal) {
        existingModal.remove();
    }

    const hasPassword = account.password && account.password !== 'N/A';
    const passwordDisplay = hasPassword ? '*******' : 'No password set';

    const modalHtml = `
        <div id="accountDetailsModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeAccountDetailsModal()"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div>
                                    <h3 class="text-xl font-bold text-black">Account Details</h3>
                                </div>
                            </div>
                            <button onclick="closeAccountDetailsModal()" 
                                class="h-10 w-10 rounded-lg flex items-center justify-center hover:bg-black/20 active:scale-95 transition-all duration-200">
                                <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="space-y-5">
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Username with Status -->
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex">
                                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Username</p>
                                                    <p class="text-lg font-semibold text-gray-900">${account.name || 'N/A'}</p>
                                                </div>
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold 
                                                    ${account.status?.toLowerCase() === 'active' 
                                                        ? 'bg-green-100 text-green-700' 
                                                        : 'bg-gray-100 text-gray-700'}">
                                                    <span class="h-2.5 w-2.5 rounded-full 
                                                        ${account.status?.toLowerCase() === 'active' ? 'bg-green-500 animate-pulse' : 'bg-gray-500'} 
                                                        mr-2.5">
                                                    </span>
                                                    ${account.status || 'Unknown'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                ${account.email ? `
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex">
                                        <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</p>
                                            <p class="text-lg text-gray-900">${account.email}</p>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}

                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex">
                                        <div class="h-10 w-10 rounded-lg bg-amber-100 flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Password</p>
                                            <div class="flex items-start ml-8">
                                                <span class="text-lg font-mono text-gray-900" id="password-display-${account.id}">${passwordDisplay}</span>
                                                ${hasPassword ? `
                                                <button onclick="togglePasswordDisplay('${account.id}', '${account.password}')" 
                                                    class="ml-2 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-lg transition-all"
                                                    style="display: inline-flex; align-items: center; justify-content: center;"
                                                    title="Show/Hide Password">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eye-icon-${account.id}">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex">
                                        <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">District</p>
                                            <p class="text-lg font-semibold text-gray-900">${account.district}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex">
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Created At</p>
                                            <p class="text-base text-gray-900">
                                                ${account.createdAt ? new Date(account.createdAt).toLocaleString('en-US', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit'
                                                }) : 'Not available'}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                        <button onclick="closeAccountDetailsModal()" 
                            class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 active:scale-[0.98] transition-all duration-200">
                            Close
                        </button>
                        <button onclick="editAccount('${account.id}'); closeAccountDetailsModal();" 
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all duration-200 flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    document.body.classList.add('overflow-hidden');

}

function closeAccountDetailsModal() {
    const modal = document.getElementById('accountDetailsModal');
    if (modal) {
        modal.remove();
        document.body.classList.remove('overflow-hidden');
    }
}

function togglePasswordDisplay(accountId, password) {
    const passwordDisplay = document.getElementById(`password-display-${accountId}`);
    const eyeIcon = document.getElementById(`eye-icon-${accountId}`);

    if (!passwordDisplay || !eyeIcon) return;

    if (passwordDisplay.textContent === '*******' || passwordDisplay.textContent === '*******') {
        passwordDisplay.textContent = password || 'No password set';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        passwordDisplay.textContent = '*******';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

function editAccount(id) {
    const account = ecomAccounts.find(acc => String(acc.id) === String(id));
    if (!account) {
        console.error('Account not found:', id);
        return;
    }

    const existingModal = document.getElementById('editAccountModal');
    if (existingModal) {
        existingModal.remove();
    }

    let districtValue = '';
    if (account.district && account.district !== 'N/A') {
        districtValue = account.district.replace('District ', '');
    }

    const modalHtml = `
        <div id="editAccountModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeEditAccountModal()"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full overflow-hidden">
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-black">Edit Account</h3>
                            </div>
                            <button onclick="closeEditAccountModal()" 
                                class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-white/20 active:scale-95 transition-all duration-200">
                                <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form id="editAccountForm" class="px-6 py-6">
                        <input type="hidden" id="edit_account_id" value="${account.id}">
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="edit_username" value="${account.name || ''}" required
                                        class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                                        placeholder="Enter username">
                                    <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    New Password 
                                </label>
                                <div class="relative">
                                    <input type="password" id="edit_password" 
                                        class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                                        placeholder="Enter new password">
                                    <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <button type="button" onclick="toggleEditPassword()" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="edit-eye-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Confirm New Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="edit_confirm_password" 
                                        class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                                        placeholder="Confirm new password">
                                    <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters with uppercase, lowercase, and numbers</p>
                            </div>

                            <!-- District -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    District <span class="text-red-500">*</span>
                                </label>
                                <select id="edit_district" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200 bg-white">
                                    <option value="">Select District</option>
                                    <option value="1" ${districtValue === '1' ? 'selected' : ''}>District 1</option>
                                    <option value="2" ${districtValue === '2' ? 'selected' : ''}>District 2</option>
                                    <option value="3" ${districtValue === '3' ? 'selected' : ''}>District 3</option>
                                    <option value="4" ${districtValue === '4' ? 'selected' : ''}>District 4</option>
                                    <option value="5" ${districtValue === '5' ? 'selected' : ''}>District 5</option>
                                    <option value="6" ${districtValue === '6' ? 'selected' : ''}>District 6</option>
                                    <option value="7" ${districtValue === '7' ? 'selected' : ''}>District 7</option>
                                    <option value="8" ${districtValue === '8' ? 'selected' : ''}>District 8</option>
                                    <option value="9" ${districtValue === '9' ? 'selected' : ''}>District 9</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Account Status
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="edit_status" value="active" 
                                            ${account.status?.toLowerCase() === 'active' ? 'checked' : ''}
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
                                        <input type="radio" name="edit_status" value="inactive"
                                            ${account.status?.toLowerCase() === 'inactive' ? 'checked' : ''}
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
                            <button type="button" onclick="closeEditAccountModal()"
                                class="flex-1 px-4 py-3 text-gray-700 font-medium border border-gray-300 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all duration-200">
                                Update Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    document.body.classList.add('overflow-hidden');

    document.getElementById('editAccountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateEditAccount(id);
    });
}

function closeEditAccountModal() {
    const modal = document.getElementById('editAccountModal');
    if (modal) {
        modal.remove();
        document.body.classList.remove('overflow-hidden');
    }
}

function toggleEditPassword() {
    const passwordInput = document.getElementById('edit_password');
    const eyeIcon = document.getElementById('edit-eye-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

async function updateEditAccount(id) {
    const username = document.getElementById('edit_username').value.trim();
    const password = document.getElementById('edit_password').value;
    const confirmPassword = document.getElementById('edit_confirm_password').value;
    const district = document.getElementById('edit_district').value;
    const status = document.querySelector('input[name="edit_status"]:chceked')?.value || 'active';

    if (!username || !district) {
        alert ('Please fill in all required fields.');
        return;
    }

    if (password || confirmPassword) {
        if (!password || !confirmPassword) {
            alert('Please fill in both password fields fields to change password');
            return;
        }

        if (password !== confirmPassword) {
            alert('Password do not match');
            return;
        }

        if (passwod.length < 8) {
            alert('Password must be at least 8 characters long');
            return;
        }

        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);

        if (!hasUpperCase || !hasLowerCase || !hasLowerCase) {
            alert('Password must contain uppercase, lower, and numbers');
            return;
        }
    }

    try {
        const updateData = {
            name: username,
            district: district,
            status: status,
            email: `${username}@example.com`
        };

        if (password) {
            updateData.password = password;
            updateData.password_confirmation = confirmPassword;
        }

        const response = await fetch(`/api/ecom-profile/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
            },
            body: JSON.stringify(updateData)
        });

        const responseData = await response.json();

        if(!response.ok) {
            throw new Error(responseData.message || 'Failed to update account');
        }

        const accountIndex = ecomAccounts.findIndex(acc => String(acc.id) === String(id));
        if (accountIndex !== -1) {
            ecomAccount[accountIndex] = {
                ...ecomAccount[accountIndex],
                name: username,
                district: district ? `District ${district}` : 'N/A',
                status: status,
                email: `${username}@example.com`
            };
        }

        alert('Account updated successfully');
        closeEditAccountModal();
        renderAccountsTable();
    } catch (error) {
        console.error('Account updated successfully');
    }
}

let currentEditId = null;

async function updateAccount(id) {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const district = document.getElementById('district').value;
    const status = document.querySelector('input[name="status"]:checked')?.value || 'active';

    if (!username || !district) {
        alert('Please fill in all required fields.');
        return;
    }

    if (password || confirmPassword) {
        if (!password || !confirmPassword) {
            alert('Please fill in both password fields');
            return;
        }

        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        if (password.length < 8) {
            alert('Password must be atleast 8 characters long');
            return;
        }
    }

    try {
        const updateData = {
            name: username,
            district: district,
            status: status,
            email: `${username}@example.com`
        };

        if (password) {
            updateData.password = password;
            updateData.password_confirmation = confirmPassword;
        }

        const response = await fetch(`/api/ecom-profile/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token]')?.getAttribute('content')
            },
            body: JSON.stringify(updateData)
        });

        if (!response.ok) {
            const err = await response.json();
            throw new Error(err.message || 'Failed to update account');
        }

        const accountIndex = ecomAccounts.findIndex(acc => String(acc.id) === String());
        if (accountIndex !== -1) {
            ecomAccounts[accountIndex] = {
                ...ecomAccounts[accountIndex],
                name: username,
                district: district ? `District ${district}` : 'N/A',
                status: status,
                email: `${username}@example.com`
            }
        }

        alert('Account updated successfully');
        closeAddAccountModal();
        renderAccountsTable();
    } catch (error) {
        console.error('Updated error:', error);
        alert(error.message || 'Failed to update account. Please try again');
    }

}

async function deleteAccount(id) {
    if (!confirm('Are yous sure you want tp delete this account?')) {
        return;
    }

    try {
        const response = await fetch(`/api/ecom-profile/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ||
                    document.querySelector('meta[name="csrf-toke"]')?.getAttribute('content')
            }
        });

        const json = await response.json();

        if (!response.ok) {
            alert(json.message || 'Delete failed');
            return;
        }

        ecomAccounts = ecomAccounts.filter(acc => acc.id !== id);

        renderAccountsTable();

        alert('Account deleted successfully');

    } catch (error) {
        console.error('Delete error:', error);
        alert(error.message || 'Failed to update account. Please try again');
    }
}

async function addNewAccount() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const district = document.getElementById('district').value;
    const status = document.querySelector('input[name="status"]:checked')?.value || 'active';

    if (!username || !password || !confirmPassword || !district) {
        alert('Please fill in all required fields.');
        return;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return;
    }

    if (password.length < 8) {
        alert('Password must be at least 8 characters long');
        return;
    }

    try {
        const response = await fetch('/api/ecom-profile', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify({
                name: username,
                email: `${username}@example.com`,
                password: password,
                password_confirmation: confirmPassword,
                district: district,
                status: status
            })
        });

        const json = await response.json();

        if (!response.ok) {
            alert(json.message || json.errors ? Object.values(json.errors).flat().join('\n') : 'Failed to create account');
            return;
        }

        closeAddAccountModal();
        loadEcomAccounts();
        alert('Account created successfully!');

    } catch (error) {
        console.error('Add account error:', error);
        alert('Something went wrong.');
    }
}


function openAddAccountModal() {
    const modal = document.getElementById('addAccountModal');
    const modalTitle = document.querySelector('#addAccountModal h3');
    const submitButton = document.querySelector('#addAccountModal button[type="submit"]');

    if (!currentEditId) {
        document.getElementById('addAccountForm').reset();
        if (modalTitle) {
            modalTitle.textContent = 'Create New Account';
        }
        if (submitButton) {
            submitButton.textContent = 'Create Account';
        }

        document.getElementById('password').required = true;
        document.getElementById('confirm_password').required = true;
    }

    const form = document.getElementById('addAccountForm');
    form.onsubmit = function (e) {
        e.preventDefault();
        if (currentEditId) {
            updateAccount(currentEditId);
        } else {
            addNewAccount();
        }
    };

    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAddAccountModal() {
    document.getElementById('addAccountModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');

    document.getElementById('addAccountForm').reset();
    currentEditId = null;

    document.getElementById('password').required = true;
    document.getElementById('confirm_password').required = true;

    const form = document.getElementById('addAccountForm');
    form.onsubmit = function(e) {
        e.preventDefault();
        addNewAccount();
    }
}

function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    if (!passwordInput || !eyeIcon) return;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="join" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

function filterAccount() {
    renderAccountsTable();
}

document.addEventListener('DOMContentLoaded', function () {
    loadEcomAccounts();

    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const districtFilter = document.getElementById('district-filter');

    if (searchInput) {
        searchInput.addEventListener('input', filterAccounts);
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', filterAccounts);
    }

    if (districtFilter) {
        districtFilter.addEventListener('change', filterAccounts);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAddAccountModal();
        }
    });

    const modal = document.getElementById('addAccountModal');
    if (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === this) {
                closeAddAccountModal();
            }
        });
    }
});

function openEditModal(id, name, status) {
    const modalHtml = `
        <div id="editDistrictModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeEditModal()"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full overflow-hidden">
                    <div class="px-6 py-5 bg-blue-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-white">Edit District</h3>
                            </div>
                            <button onclick="closeEditModal()" class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-white/20 active:scale-95 transition-all duration-200">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form id="editDistrictForm" onsubmit="updateDistrict(event, ${id})" class="px-6 py-6">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    District Name
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="district_name" value="${name}" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Active Button -->
                                    <button type="button" 
                                        onclick="selectEditStatus('Active')"
                                        id="edit-status-active-btn"
                                        class="edit-status-btn flex items-center justify-center p-4 rounded-xl border-2 transition-all duration-200 w-full
                                            ${status === 'Active'
            ? 'border-blue-500 bg-blue-50 text-blue-700'
            : 'border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50'}">
                                        <div class="flex items-center">
                                            <div class="h-5 w-5 rounded-full border-2 mr-3 transition-colors
                                                ${status === 'Active'
            ? 'border-blue-500 bg-blue-500'
            : 'border-gray-300 bg-white'}">
                                            </div>
                                            <span class="font-medium">Active</span>
                                        </div>
                                    </button>

                                    <!-- Inactive Button -->
                                    <button type="button" 
                                        onclick="selectEditStatus('Inactive')"
                                        id="edit-status-inactive-btn"
                                        class="edit-status-btn flex items-center justify-center p-4 rounded-xl border-2 transition-all duration-200 w-full
                                            ${status === 'Inactive'
            ? 'border-gray-500 bg-gray-50 text-gray-700'
            : 'border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50'}">
                                        <div class="flex items-center">
                                            <div class="h-5 w-5 rounded-full border-2 mr-3 transition-colors
                                                ${status === 'Inactive'
            ? 'border-gray-500 bg-gray-500'
            : 'border-gray-300 bg-white'}">
                                            </div>
                                            <span class="font-medium">Inactive</span>
                                        </div>
                                    </button>
                                </div>
                                
                                <!-- Hidden input to store the selected value -->
                                <input type="hidden" name="status" id="edit-status-value" value="${status || 'Active'}" required>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-3 text-gray-700 font-medium border border-gray-300 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all">
                                Update District
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    const existingModal = document.getElementById('editDistrictModal');
    if (existingModal) {
        existingModal.remove();
    }

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    document.body.classList.add('overflow-hidden');
}

window.viewAccountDetails = viewAccountDetails;
window.closeAccountDetailsModal = closeAccountDetailsModal;
window.togglePasswordDisplay = togglePasswordDisplay;
window.openAddAccountModal = openAddAccountModal;
window.closeAddAccountModal = closeAddAccountModal;
window.editAccount = editAccount;
window.deleteAccount = deleteAccount;
window.togglePassword = togglePassword;
window.editAccount = editAccount;
window.closeEditAccountModal = closeEditAccountModal;
window.toggleEditPassword = toggleEditPassword;
