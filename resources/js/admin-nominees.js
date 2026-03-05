let nominees = [];
let filteredNominees = [];

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

document.addEventListener('DOMContentLoaded', async () => {
    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
    fetchNominees();

    const searchInput = document.getElementById('search-input');
    const districtFilter = document.getElementById('district-filter');
    const addForm = document.getElementById('addNomineeForm');

    if (searchInput) searchInput.addEventListener('input', filterNominees);
    if (districtFilter) districtFilter.addEventListener('change', filterNominees);
    if (addForm) addForm.addEventListener('submit', storeNominee);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAddNomineeModal();
            closeEditNomineeModal();
        }
    });
});

async function fetchNominees() {
    try {
        const response = await fetch('/api/admin/nominees', {
            credentials: 'include'
        });

        if (!response.ok) {
            const text = await response.text();
            console.error('Unexpected response:', text);
            return;
        }

        const result = await response.json();

        nominees = result.data || [];
        filteredNominees = nominees;

        renderNomineesGrid();
    } catch (error) {
        console.error('Failed to load nominees:', error);
    }
}

async function storeNominee(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('/api/admin/nominees', {
            method: 'POST',
            body: formData,
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });

        if (!response.ok) {
            const text = await response.text();
            console.error('Unexpected response:', text);
            alert('Cannot save nominee. Are you logged in?');
            return;
        }

        const data = await response.json();
        console.log('Nominee saved:', data);

        closeAddNomineeModal();
        fetchNominees();
        showToast('Nominee created successfully!', 'success');
    } catch (error) {
        console.error('Error saving nominee:', error);
        showToast('Failed to create nominee', 'error');
    }
}

async function updateNominee(event) {
    event.preventDefault();

    const id = document.getElementById('edit_nominee_id').value;
    const form = event.target;
    const formData = new FormData(form);
    formData.append('_method', 'PUT');

    try {
        const response = await fetch(`/api/admin/nominees/${id}`, {
            method: 'POST',
            body: formData,
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });

        if (!response.ok) {
            const text = await response.text();
            console.error('Unexpected response:', text);
            alert('Failed to update nominee');
            return;
        }

        const data = await response.json();
        console.log('Nominee updated:', data);

        closeEditNomineeModal();
        fetchNominees();
        showToast('Nominee updated successfully!', 'success');
    } catch (error) {
        console.error('Error updating nominee:', error);
        showToast('Failed to update nominee', 'error');
    }
}

function renderNomineesGrid() {
    const grid = document.getElementById('nominees-grid');
    if (!grid) return;
    
    grid.innerHTML = '';

    if (filteredNominees.length === 0) {
        grid.innerHTML = `
            <div class="text-center py-12 text-gray-500 col-span-full">
                <div class="flex flex-col items-center">
                    <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="text-gray-500">No nominees found.</p>
                </div>
            </div>
        `;
        document.getElementById('nominee-count').innerText = 'Showing 0 nominees';
        return;
    }

    filteredNominees.forEach(nominee => {
        grid.innerHTML += `
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex flex-col h-full">
        <div class="p-4 pb-2">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex-shrink-0">
                        ${nominee.image_url ? `
                            <img
                                src="${nominee.image_url}"
                                class="h-12 w-12 rounded-full object-cover border border-gray-200"
                                alt="${nominee.first_name} ${nominee.last_name}"
                            >
                        ` : `
                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center border border-gray-300">
                                <svg class="h-6 w-6 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                        `}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-semibold text-gray-900 leading-tight">
                            ${nominee.first_name} ${nominee.last_name}
                        </h4>
                        <p class="text-xs text-gray-500 leading-tight">
                            ${nominee.nickname || 'Nominee'}
                        </p>
                    </div>
                </div>
                
                <div class="relative flex-shrink-0">
                    <button onclick="toggleDropdown(event, ${nominee.id})" 
                        class="p-1 hover:bg-gray-100 rounded-lg">
                        <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="6" r="2"/>
                            <circle cx="12" cy="12" r="2"/>
                            <circle cx="12" cy="18" r="2"/>
                        </svg>
                    </button>
                    
                    <div id="dropdown-${nominee.id}" 
                        class="absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border border-gray-100 py-1 hidden z-50">
                        <button onclick="editNominee(${nominee.id}); event.stopPropagation();" 
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-blue-50 flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                        <button onclick="deleteNominee(${nominee.id}); event.stopPropagation();" 
                            class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-red-50 flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 py-2">
            <div class="flex items-center gap-1 text-xs text-gray-500">
                <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>${nominee.town || 'N/A'}, District ${nominee.district || 'N/A'}</span>
            </div>
        </div>

        <div class="flex-grow"></div>
        <div class="px-4 py-3 border-t border-gray-100 mt-auto">
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">Current Votes</span>
                <span class="text-lg font-semibold text-blue-600">${nominee.votes_count || 0}</span>
            </div>
        </div>
    </div>
`;
    });

    document.getElementById('nominee-count').innerText =
        `Showing ${filteredNominees.length} nominees`;
}

function toggleDropdown(event, id) {
    event.stopPropagation();
    
    const dropdown = document.getElementById(`dropdown-${id}`);
    if (!dropdown) return;

     document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
        if (d.id !== `dropdown-${id}`) {
            d.classList.add('hidden');
        }
    });

     dropdown.classList.toggle('hidden');
}

 document.addEventListener('click', function() {
    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
        dropdown.classList.add('hidden');
    });
});

function filterNominees() {
    const search = document.getElementById('search-input')?.value.toLowerCase() || '';
    const district = document.getElementById('district-filter')?.value || '';

    filteredNominees = nominees.filter(n => {
        const fullName = `${n.first_name || ''} ${n.last_name || ''}`.toLowerCase();
        const matchesSearch = fullName.includes(search) || 
                            (n.nickname && n.nickname.toLowerCase().includes(search));
        const matchesDistrict = district ? String(n.district) === String(district) : true;
        return matchesSearch && matchesDistrict;
    });

    renderNomineesGrid();
}

function editNominee(id) {
    const nominee = nominees.find(n => n.id === id);
        if (!nominee) {
            console.error('Nominee not found:', id);
            return;
        }

        const existingModal = document.getElementById('editNomineeModal');
        if (existingModal) {
            existingModal.remove();
        }

        const modalHTML = `
            <div id="editNomineeModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeEditNomineeModal()"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full overflow-hidden">
                    <div class="px-6 py-5 bg-white border-b border-gray-150">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-black/20 flex items-center justify-center mr-3">
                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-black">Edit Nominee</h3>
                            </div>
                            <button onclick="closeEditNomineeModal()"
                                class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-black/20 active:scale-95 transition-all duration-200">
                                <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form id="editNomineeForm" onsubmit="updateNominee(event)" class="px-6 py-6" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="${getCsrfToken()}">
                        <input type="hidden" id="edit_nominee_id" name="id" value="${nominee.id}">
                        
                        <div class="space-y-6">
                            <!-- Image Upload Section -->
                            <div class="text-center">
                                <div class="relative inline-block">
                                    <div class="h-32 w-32 rounded-full border-4 border-white shadow-lg bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                        <img id="edit-profile-preview" src="${nominee.image_url || ''}" alt="" 
                                            class="h-full w-full object-cover ${nominee.image_url ? '' : 'hidden'}">
                                        <div id="edit-profile-placeholder" class="h-full w-full flex items-center justify-center text-gray-400 ${nominee.image_url ? 'hidden' : ''}">
                                            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <label for="edit_image" class="absolute bottom-0 right-0 h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-blue-700 transition-colors">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <input type="file" id="edit_image" name="image" accept="image/*" class="hidden" onchange="previewEditProfileImage(event)">
                                    </label>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Update nominee profile picture</p>
                            </div>

                            <!-- Name Fields Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="edit_first_name" name="first_name" value="${nominee.first_name || ''}" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                                        placeholder="Enter first name">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="edit_last_name" name="last_name" value="${nominee.last_name || ''}" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                                        placeholder="Enter last name">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nickname
                                    </label>
                                    <input type="text" id="edit_nickname" name="nickname" value="${nominee.nickname || ''}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                                        placeholder="Enter nickname">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Town <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="edit_town" name="town" value="${nominee.town || ''}" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                                        placeholder="Enter town">
                                </div>
                            </div>

                            <!-- District and Votes -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        District <span class="text-red-500">*</span>
                                    </label>
                                    <select id="edit_district" name="district" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200 bg-white">
                                        <option value="">Select District</option>
                                        <option value="1" ${nominee.district == '1' ? 'selected' : ''}>District 1</option>
                                        <option value="2" ${nominee.district == '2' ? 'selected' : ''}>District 2</option>
                                        <option value="3" ${nominee.district == '3' ? 'selected' : ''}>District 3</option>
                                        <option value="4" ${nominee.district == '4' ? 'selected' : ''}>District 4</option>
                                        <option value="5" ${nominee.district == '5' ? 'selected' : ''}>District 5</option>
                                        <option value="6" ${nominee.district == '6' ? 'selected' : ''}>District 6</option>
                                        <option value="7" ${nominee.district == '7' ? 'selected' : ''}>District 7</option>
                                        <option value="8" ${nominee.district == '8' ? 'selected' : ''}>District 8</option>
                                        <option value="9" ${nominee.district == '9' ? 'selected' : ''}>District 9</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Votes Count
                                    </label>
                                    <input type="number" id="edit_votes_count" name="votes_count" value="${nominee.votes_count || 0}" min="0"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                                        placeholder="Enter votes count">
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex gap-3 mt-8">
                                <button type="button" onclick="closeEditNomineeModal()"
                                    class="flex-1 px-4 py-3 text-gray-700 font-medium border border-gray-300 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all duration-200">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all duration-200">
                                    Update Nominee
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        document.body.classList.add('overflow-hidden');
}

function openEditNomineeModal() {
    const modal = document.getElementById('editNomineeModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

function closeEditNomineeModal() {
    const modal = document.getElementById('editNomineeModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    const form = document.getElementById('editNomineeForm');
    if (form) form.reset();

    const preview = document.getElementById('edit-profile-preview');
    const placeholder = document.getElementById('edit-profile-placeholder');
    if (preview) {
        preview.src = '';
        preview.classList.add('hidden');
    }
    if (placeholder) placeholder.classList.remove('hidden');
}

async function deleteNominee(id) {
    if (!confirm('Are you sure you want to delete this nominee?')) {
        return;
    }

    try {
        const response = await fetch(`/api/admin/nominees/${id}`, {
            method: 'DELETE',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            const result = await response.json();
            throw new Error(result.message || 'Failed to delete nominee');
        }

        nominees = nominees.filter(n => n.id !== id);
        filteredNominees = filteredNominees.filter(n => n.id !== id);

        const dropdown = document.getElementById(`dropdown-${id}`);
        if (dropdown) dropdown.classList.add('hidden');

        renderNomineesGrid();
        showToast('Nominee deleted successfully!', 'success');
    } catch (error) {
        console.error('Delete error:', error);
        showToast(error.message || 'Failed to delete nominee', 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white font-semibold shadow-lg z-50 transition-all duration-500 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.style.transform = 'translateY(100%)';
    toast.style.opacity = '0';
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
    }, 10);
    
    setTimeout(() => {
        toast.style.transform = 'translateY(100%)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

function previewProfileImage(event) {
    const input = event.target;
    const preview = document.getElementById('profile-preview');
    const placeholder = document.getElementById('profile-placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewEditProfileImage(event) {
    const input = event.target;
    const preview = document.getElementById('edit-profile-preview');
    const placeholder = document.getElementById('edit-profile-placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function openAddNomineeModal() {
    const modal = document.getElementById('addNomineeModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

function closeAddNomineeModal() {   
    const modal = document.getElementById('addNomineeModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    const form = document.getElementById('addNomineeForm');
    if (form) form.reset();

    const preview = document.getElementById('profile-preview');
    const placeholder = document.getElementById('profile-placeholder');
    if (preview) {
        preview.src = '';
        preview.classList.add('hidden');
    }
    if (placeholder) placeholder.classList.remove('hidden');
}

 window.fetchNominees = fetchNominees;
window.storeNominee = storeNominee;
window.updateNominee = updateNominee;
window.editNominee = editNominee;
window.deleteNominee = deleteNominee;
window.filterNominees = filterNominees;
window.toggleDropdown = toggleDropdown;
window.previewProfileImage = previewProfileImage;
window.previewEditProfileImage = previewEditProfileImage;
window.openAddNomineeModal = openAddNomineeModal;
window.closeAddNomineeModal = closeAddNomineeModal;
window.openEditNomineeModal = openEditNomineeModal;
window.closeEditNomineeModal = closeEditNomineeModal;