let ecomAccounts = [];

/* LOAD DATA FROM API */
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
            district: user.district != null ? "District " + user.district : 'N/A',
            status: user.status ?? 'Active',
            createdAt: user.created_at
        }));

        renderAccountsTable();
    } catch (error) {
        console.error('Failed to load ECOM accounts:', error);
    }
}

/* RENDER TABLE */
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
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div>
                            <div class="font-semibold text-gray-900">${acc.name || 'N/A'}</div>
                            <div class="text-sm text-gray-500">${acc.email || 'No email'}</div>
                        </div>
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
                            class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all duration-200 group">
                            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
        
                        <button onclick="deleteAccount('${acc.id}')" 
                            class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all duration-200 group">
                            <svg class="h-5 w-5 group-hover:scale-110 trnasition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}
function editAccount(id) {
    const account = ecomAccounts.find(acc => acc.id === id);
    if (!account) return;

    document.getElementById('username').value = account.name || '';
    document.getElementById('district').value = account.district ? account.district.replace('District ', '') : '';

    const statusRadios = document.querySelectorAll('input[name="status"]');
    statusRadios.forEach(radio => {
        if (radio.value === account.status?.toLowerCase()) {
            radio.checked = true;
        }
    });

    document.querySelector('#addAccountModal h3').textContent = 'Edit Account';
    document.querySelector('#addAccountModal button[type="submit"]').textContent = 'Update Account';

    const form = document.getElementById('addAccountForm');
    form.onsubmit = function(e) {
        e.preventDefault();
        updateAccount(id);
    };

    currentEditId = id;

    openAddAccountModal();
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

        if (password.length < 8 ) {
            alert('Password must be atleast 8 characters long');
            return;
        }
    }

    try {
        const updateData = {
            name:username,
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

        const accountIndex = ecomAccounts.findIndex(acc => acc.id === id);
        if (acountIndex !== -1) {
            ecomAccounts[accountIndex] = {
                ...ecomAccounts[accountIndex],
                name: username,
                district: district ? `District ${district}` : 'N/A',
                status: status
            };
        }

        alert('Account updated successfully');
        closeAddAccountModal();
        renderAccountsTable();
    } catch (error) {
        console.error('Update error', error);
        alert(error.message || 'Failed to update account. Please try again');
    }
}

async function deleteAccount(id) {
    if (!confirm('Are yous sure you want tp delete this account?')){
        return;
    }

    try {
        const response = await fetch(`/api/ecom-profile/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept' : 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ||
                                document.querySelector('meta[name="csrf-toke"]')?.getAttribute('content')
            }
        });
        
        const json = await response.json();

        if(!response.ok) {
            alert(json.message || 'Delete failed');
            return;
        }

        ecomAccounts = ecomAccounts.filter(acc => acc.id !== id);

        renderAccountsTable();

        alert('Account deleted successfully');

    } catch (error) {
        console.error('Delete error:', error);
        alert('Error deleting account. Please try again');
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

    if (!currentEditId) {
    document.getElementById('addAccountForm').reset();
    document.querySelector('#addAccountModal h3').textContent = 'Create New Account';
    document.querySelector('#addAccountModal button[type="submit"]').textContent = 'Create Account';
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

    document.getElementById('addAccountModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAddAccountModal() {
    document.getElementById('addAccountModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');

    document.getElementById('addAccountForm').reset();
    currentEditId = null;

    const form = document.getElementById('addAccountForm');
    form.onsubmit = function(e) {
        e.preventDefault();
        addNewAccount();
    };
}

function togglePassword() {
    const passwordInput = document.getAnimations('password');
    const eyeIcon = document.getElementById('eye-icon');

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
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeAddAccountModal();
            }
        });
    }
});

window.openAddAccountModal = openAddAccountModal;
window.closeAddAccountModal = closeAddAccountModal;
window.editAccount = editAccount;
window.deleteAccount = deleteAccount;
window.togglePassword = togglePassword;

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
