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
                <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                    No ECOM accounts found
                </td>
            </tr>
        `;
        counter.textContent = 'Showing 0 accounts';
        return;
    }

    counter.textContent = `Showing ${ecomAccounts.length} accounts`;

    ecomAccounts.forEach(acc => {
        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="font-semibold text-gray-900">${acc.name}</div>
                    <div class="text-sm text-gray-500">${acc.email}</div>
                </td>

                <td class="px-6 py-4">
                    <div class="text-gray-900">${acc.district}</div>
                </td>

                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        ${acc.status}
                    </span>
                </td>

                <td class="px-6 py-4">
                    <button onclick="editAccount(${acc.id})"
                        class="text-blue-600 hover:underline mr-3">
                        Edit
                    </button>
                    <button onclick="deleteAccount(${acc.id})"
                        class="text-red-600 hover:underline">
                        Delete
                    </button>
                </td>
            </tr>
        `;
    });
}
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

    async function deleteAccount(id) {
        if (!confirm('Delete this profile?')) return;

        try {
            const response = await fetch(`/api/ecom-profile/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                }
            });

            const json = await response.json();

            if (!response.ok) {
                alert(json.message || 'Delete failed');
                return;
            }

            ecomAccounts = ecomAccounts.filter(acc => acc.id !== id);
            renderAccountsTable();
        } catch (error) {
            console.error(error);
            alert('Error deleting profile');
        }
    }



    async function addNewAccount() {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const district = document.getElementById('district').value;

        if (!username || !password || !confirmPassword || !district) {
            alert('Please fill in all required fields.');
            return;
        }

        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }

        try {
            const response = await fetch('/api/ecom-profile', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    name: username,
                    email: `${username}@example.com`, 
                    password: password,
                    password_confirmation: confirmPassword,
                    district: district
                })
            });

            if (!response.ok) {
                const err = await response.json();
                alert(err.message ?? 'Failed to create account');
                return;
            }

            closeAddAccountModal();
            loadEcomAccounts();
            alert('Account created successfully!');

        } catch (error) {
            console.error(error);
            alert('Something went wrong.');
        }
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

        document.getElementById('addAccountModal')?.addEventListener('click', function (event) {
            if (event.target === this) {
                closeAddAccountModal();
            }
        });
    });

window.openAddAccountModal = openAddAccountModal;
window.closeAddAccountModal = closeAddAccountModal;
window.editAccount = editAccount;
window.deleteAccount = deleteAccount;
window.togglePassword = togglePassword;