let nominees = [];
let filteredNominees = [];

async function fetchNominees() {
    try {
        const response = await fetch('/api/nominees', {
            credentials: 'include'
        });

        if (!response.ok) {
            const text = await response.text();
            console.error('Unexpected response:', text);
            return;
        }

        const result = await response.json();

        nominees = result.data;
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
        const response = await fetch('/api/nominees', {
            method: 'POST',
            body: formData,
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
    } catch (error) {
        console.error('Error saving nominee:', error);
    }
}


function renderNomineesGrid() {
    const grid = document.getElementById('nominees-grid');
    grid.innerHTML = '';

    if (filteredNominees.length === 0) {
        grid.innerHTML = `
            <div class="text-center py-12 text-gray-500 col-span-full">
                <p>No nominees found.</p>
            </div>
        `;
        document.getElementById('nominee-count').innerText = 'Showing 0 nominees';
        return;
    }

    filteredNominees.forEach(nominee => {
        grid.innerHTML += `
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-100 p-5 transition-all duration-300">
        <!-- Header with image and menu -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4">
                <img
                    src="${nominee.image_url ?? '/images/default-avatar.png'}"
                    class="h-16 w-16 rounded-full object-cover border-2 border-gray-200"
                    alt="${nominee.first_name} ${nominee.last_name}"
                >
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">
                        ${nominee.first_name} ${nominee.last_name}
                    </h4>
                    <p class="text-sm text-gray-500 flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        ${nominee.nickname ?? 'Director'}
                    </p>
                </div>
            </div>
            
            <!-- 3-dots dropdown menu -->
            <div class="relative dropdown-container-${nominee.id}">
                <button onclick="toggleDropdown(${nominee.id})" 
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="6" r="2" fill="currentColor"/>
                        <circle cx="12" cy="12" r="2" fill="currentColor"/>
                        <circle cx="12" cy="18" r="2" fill="currentColor"/>
                    </svg>
                </button>
                
                <!-- Dropdown menu -->
                <div id="dropdown-${nominee.id}" 
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-1 hidden z-50">
                    <button onclick="editNominee(${nominee.id})" 
                        class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center gap-2 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Nominee
                    </button>
                    <button onclick="deleteNominee(${nominee.id})" 
                        class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 flex items-center gap-2 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Nominee
                    </button>
                </div>
            </div>
        </div>

        <!-- Location -->
        <div class="flex items-center gap-2 text-gray-600 mb-3">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-sm">${nominee.town}, ${nominee.district}</span>
        </div>

        <!-- Votes section -->
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <span class="text-sm text-gray-500">Current Votes</span>
            <span class="text-2xl font-bold text-blue-600">${nominee.votes_count}</span>
        </div>
    </div>
`;
    });

    document.getElementById('nominee-count').innerText =
        `Showing ${filteredNominees.length} nominees`;
}

function toggleDropdown(id) {
    const dropdown = document.getElementById(`dropdown-${id}`);
    if (!dropdown) return;

    document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
        if (d.id !== `dropdown-${id}`) {
            d.classList.add('hidden');
        }
    });

    dropdown.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('[class^="dropdown-container"]')) {
        document.querySelector.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});

function filterNominees() {
    const search = document.getElementById('search-input').value.toLowerCase();
    const district = document.getElementById('district-filter').value;

    filteredNominees = nominees.filter(n => {
        const fullName = `${n.first_name} ${n.last_name}`.toLowerCase();
        const matchesSearch = fullName.includes(search);

        const matchesDistrict = district
            ? n.district == district
            : true;

        return matchesSearch && matchesDistrict;
    });

    renderNomineesGrid();
}



function editNominee(id) {
    alert(`Edit functionality for nominee ${id} would open here`);
}

function deleteNominee(id) {
    if (confirm('Are you sure you want to delete this nominee?')) {
        console.log('Delete nominee:', id);

        const index = nominees.findIndex(n => n.id === id);
        if (index !== -1) {
            nominees.splice(index, 1);
            filterNominees();
        }
    }
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

function openAddNomineeModal() {
    document.getElementById('addNomineeModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAddNomineeModal() {   
    document.getElementById('addNomineeModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');

    document.getElementById('addNomineeForm').reset();
    document.getElementById('profile-preview').src = '';
    document.getElementById('profile-preview').classList.add('hidden');
    document.getElementById('profile-placeholder').classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', async () => {
    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });

    fetchNominees();

    document.getElementById('search-input')
        .addEventListener('input', filterNominees);

    document.getElementById('district-filter')
        .addEventListener('change', filterNominees);

    document.getElementById('addNomineeForm')
        .addEventListener('submit', storeNominee);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeAddNomineeModal();
    });
});

window.openAddNomineeModal = openAddNomineeModal;
window.closeAddNomineeModal = closeAddNomineeModal;
window.editNominee = editNominee;
window.deleteNominee = deleteNominee;
window.toggleDropdown = toggleDropdown;