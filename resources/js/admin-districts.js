
async function loadDistrictCounts() {
    try {
        const response = await fetch('/api/districts', {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        document.getElementById('total-districts').innerText = 
            (data.total_districts ?? 0).toLocaleString();
        document.getElementById('total-voters').innerText = 
            (data.total_voters ?? 0).toLocaleString();
        document.getElementById('total-votes').innerText = 
            (data.total_votes ?? 0).toLocaleString();

        renderDistrictTable(data.by_district);

    } catch (error) {
        console.error("Error loading districts:", error);
        showNotification('Failed to load districts. Please refresh the page.', 'error');
    }
}

function renderDistrictTable(districts) {
    const tbody = document.getElementById('district-table-body');
    tbody.innerHTML = '';

    if (!districts || districts.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-600">No districts found</p>
                        <p class="text-sm text-gray-500 mt-1">Click the "Add New District" button to create one.</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    districts.forEach((district, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition animate-slideInUp';
        row.style.animationDelay = `${index * 0.05}s`;
        
        const registeredVoters = district.registered_voters ?? 0;
        const votesCast = district.votes_cast ?? 0;
        const turnoutPercentage = registeredVoters > 0 
            ? ((votesCast / registeredVoters) * 100).toFixed(1) 
            : 0;

        row.innerHTML = `
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center mr-3">
                        <span class="text-green-700 font-bold text-sm">${district.district?.charAt(0) || 'D'}</span>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">${district.district || 'Unnamed District'}</div>
                        <div class="text-xs text-gray-500">ID: ${district.id || 'N/A'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="font-medium text-gray-700">${district.nominees_count ?? 0}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="font-medium text-gray-700">${registeredVoters.toLocaleString()}</span>
            </td>
            <td class="px-6 py-4">
                <div class="flex flex-col items-center">
                    <span class="font-medium text-gray-700">${votesCast.toLocaleString()}</span>
                    <div class="w-24 h-1.5 bg-gray-200 rounded-full mt-1">
                        <div class="h-1.5 bg-green-500 rounded-full" style="width: ${turnoutPercentage}%"></div>
                    </div>
                    <span class="text-xs text-gray-500 mt-1">${turnoutPercentage}% turnout</span>
                </div>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                    ${district.status === 'Active' 
                        ? 'bg-green-100 text-green-800 border border-green-200' 
                        : 'bg-gray-100 text-gray-800 border border-gray-200'}">
                    ${district.status || 'Inactive'}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-center space-x-2">
                    <button onclick="openEditModal(${district.id}, '${district.district?.replace(/'/g, "\\'")}', '${district.status}')" 
                        class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all duration-200 group"
                        title="Edit district">
                        <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="openDeleteModal(${district.id}, '${district.district?.replace(/'/g, "\\'")}')" 
                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all duration-200 group"
                        title="Delete district">
                        <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function openAddDistrictModal() {
    const modal = document.getElementById('addDistrictModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    const form = document.getElementById('addDistrictForm');
    form.reset();
    
    setTimeout(() => {
        form.querySelector('input[name="district_name"]').focus();
    }, 100);
}

function closeAddDistrictModal() {
    const modal = document.getElementById('addDistrictModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openEditModal(id, name, status) {
    const modalHtml = `
        <div id="editDistrictModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 
            text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"onclick="closeEditModal()"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-black">Edit District</h3>
                            </div>
                            <button onclick="closeEditModal()" class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-black/20 active:scale-95 transition-all duration-200">
                                <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

function closeEditModal() {
    const modal = document.getElementById('editDistrictModal');
    if (modal) {
        modal.remove();
    }
    document.body.classList.remove('overflow-hidden');
}

async function updateDistrict(event, id) {
    event.preventDefault();

    try {
        const form = event.target;
        const formData = new FormData(form);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch(`/api/districts/${id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                district_name: formData.get('district_name'),
                status: formData.get('status')
            })
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'Failed to update distict');
        }

        const result = await response.json();

        closeEditModal();
        await loadDistrictCounts();

        showNotification('District updated successfully', 'success');
    } catch (error) {
        console.error('Error updating district:', error);
        showNotification(error.message || 'Failed to update district. Please try again', 'error');
    }
}

function selectEditStatus(status) {
    document.getElementById('edit-status-value').value = status;
    
    const activeBtn = document.getElementById('edit-status-active-btn');
    const inactiveBtn = document.getElementById('edit-status-inactive-btn');
    
    if (status === 'Active') {
        activeBtn.className = 'edit-status-btn flex items-center justify-center p-4 rounded-xl border-2 transition-all duration-200 w-full border-blue-500 bg-blue-50 text-blue-700';
        const activeCircle = activeBtn.querySelector('div:first-child div:first-child');
        if (activeCircle) {
            activeCircle.className = 'h-5 w-5 rounded-full border-2 mr-3 transition-colors border-blue-500 bg-blue-500';
        }
        
        inactiveBtn.className = 'edit-status-btn flex items-center justify-center p-4 rounded-xl border-2 transition-all duration-200 w-full border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50';
        const inactiveCircle = inactiveBtn.querySelector('div:first-child div:first-child');
        if (inactiveCircle) {
            inactiveCircle.className = 'h-5 w-5 rounded-full border-2 mr-3 transition-colors border-gray-300 bg-white';
        }
    } else {
        inactiveBtn.className = 'edit-status-btn flex items-center justify-center p-4 rounded-xl border-2 transition-all duration-200 w-full border-gray-500 bg-gray-50 text-gray-700';
        const inactiveCircle = inactiveBtn.querySelector('div:first-child div:first-child');
        if (inactiveCircle) {
            inactiveCircle.className = 'h-5 w-5 rounded-full border-2 mr-3 transition-colors border-gray-500 bg-gray-500';
        }
        
        activeBtn.className = 'edit-status-btn flex items-center justify-center p-4 rounded-xl border-2 transition-all duration-200 w-full border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50';
        const activeCircle = activeBtn.querySelector('div:first-child div:first-child');
        if (activeCircle) {
            activeCircle.className = 'h-5 w-5 rounded-full border-2 mr-3 transition-colors border-gray-300 bg-white';
        }
    }
}

function openDeleteModal(id, name) {
    const modalHtml = `
        <div id="deleteDistrictModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-lg bg-black/20 flex items-center justify-center mr-4">
                                        <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </div>
                                        <h3 class="text-lg font-bold text-black">Delete District</h3>
                            </div>
                            <button onclick="closeDeleteModal()" class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-black/20 active:scale-95 transition-all duration-200">
                                <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <p class="text-gray-700 mb-4">
                            Are you sure you want to delete district <span class="font-bold">"${name}"</span>?
                        </p>
                        <p class="text-sm text-gray-500 mb-6">
                            This will permanently delete the district and all associated data including nominees and votes.
                        </p>

                        <div class="flex gap-3">
                            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 text-gray-700 font-medium border border-gray-300 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all">
                                Cancel
                            </button>
                            <button onclick="deleteDistrict(${id})" class="flex-1 px-4 py-3 bg-red-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all">
                                Delete District
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    const existingModal = document.getElementById('deleteDistrictModal');
    if (existingModal) {
        existingModal.remove();
    }

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    document.body.classList.add('overflow-hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteDistrictModal');
    if (modal) {
        modal.remove();
    }
    document.body.classList.remove('overflow-hidden');
}

async function deleteDistrict(id) {
    try {
        const response = await fetch(`/api/districts/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete district');
        }

        closeDeleteModal();
        await loadDistrictCounts();
        
        showNotification('District deleted successfully!', 'success');
        
    } catch (error) {
        console.error('Error deleting district:', error);
        showNotification('Failed to delete district. Please try again.', 'error');
        closeDeleteModal();
    }
}

function showNotification(message, type = 'success') {
    const existingNotification = document.querySelector('.notification-toast');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 animate-slideInUp`;
    
    const bgColor = type === 'success' ? 'from-green-500 to-emerald-600' : 'from-red-500 to-pink-600';
    const icon = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';

    notification.innerHTML = `
        <div class="bg-gradient-to-r ${bgColor} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center max-w-md">
            <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${icon}
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:bg-white/20 rounded-lg p-1">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

function filterDistrictTable() {
    const searchInput = document.getElementById('search-input');
    const filter = searchInput.value.toLowerCase();
    const table = document.querySelector('tbody');
    const rows = table.querySelectorAll('tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const districtCell = row.querySelector('td:first-child .font-semibold');
        if (districtCell) {
            const text = districtCell.textContent.toLowerCase();
            if (text.includes(filter) || filter === '') {
                row.style.display = '';
                row.classList.add('animate-fadeIn');
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
    });

    const resultsInfo = document.querySelector('.search-results-info');
    if (resultsInfo) {
        resultsInfo.textContent = `Showing ${visibleCount} of ${rows.length} districts`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = document.querySelector('input[name="_token"]')?.value || '';
        document.head.appendChild(meta);
    }

    loadDistrictCounts();
    
    setInterval(loadDistrictCounts, 30000);

    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', filterDistrictTable);
    }

    const addForm = document.getElementById('addDistrictForm');
    if (addForm) {
        addForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Failed to add district');
                }

                const result = await response.json();
                
                closeAddDistrictModal();
                await loadDistrictCounts();
                
                showNotification('District added successfully!', 'success');
                
            } catch (error) {
                console.error('Error adding district:', error);
                showNotification('Failed to add district. Please try again.', 'error');
            }
        });
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddDistrictModal();
        closeEditModal();
        closeDeleteModal();
    }
    
    if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
        event.preventDefault();
        document.getElementById('search-input')?.focus();
    }
    
    if ((event.ctrlKey || event.metaKey) && event.key === 'n') {
        event.preventDefault();
        openAddDistrictModal();
    }
});

window.openAddDistrictModal = openAddDistrictModal;
window.closeAddDistrictModal = closeAddDistrictModal;
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
window.openDeleteModal = openDeleteModal;
window.closeDeleteModal = closeDeleteModal;
window.deleteDistrict = deleteDistrict;
window.updateDistrict = updateDistrict;
window.filterDistrictTable = filterDistrictTable;
window.showNotification = showNotification;
window.selectEditStatus = selectEditStatus;