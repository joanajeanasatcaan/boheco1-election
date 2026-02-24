
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

        reader.onload = function (e) {
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

document.addEventListener('DOMContentLoaded', function () {
    renderNomineesGrid();

    document.getElementById('search-input').addEventListener('input', filterNominees);
    document.getElementById('district-filter').addEventListener('change', filterNominees);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAddNomineeModal();
        }
    });
});
