let nominees = [];
let filteredNominees = [];

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
        const response = await fetch('/api/admin/nominees', {
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
            <div class="bg-white rounded-xl shadow-md border p-5 hover:shadow-lg transition">
                <div class="flex items-center gap-4 mb-4">
                    <img
                        src="${nominee.image_url ?? '/images/default-avatar.png'}"
                        class="h-16 w-16 rounded-full object-cover border"
                    >
                    <div>
                        <h4 class="font-bold text-gray-800">
                            ${nominee.first_name} ${nominee.last_name}
                        </h4>
                        <p class="text-sm text-gray-500">
                            ${nominee.nickname ?? ''}
                        </p>
                    </div>
                </div>

                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>District:</strong> ${nominee.district}</p>
                    <p><strong>Town:</strong> ${nominee.town}</p>
                    <p><strong>Votes:</strong> ${nominee.votes_count}</p>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button onclick="editNominee(${nominee.id})"
                        class="px-3 py-1 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Edit
                    </button>
                    <button onclick="deleteNominee(${nominee.id})"
                        class="px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
        `;
    });

    document.getElementById('nominee-count').innerText =
        `Showing ${filteredNominees.length} nominees`;
}

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