
function openAddDistrictModal() {
    document.getElementById('addDistrictModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAddDistrictModal() {
    document.getElementById('addDistrictModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openEditModal(id) {
    console.log('Edit district:', id);
    alert('Edit functionality for district ' + id + ' would open here');
}

function openDeleteModal(id) {
    if (confirm('Are you sure you want to delete this district?')) {
        console.log('Delete district:', id);
    }
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeAddDistrictModal();
    }
});

function filterDistrictTable() {
    const searchInput = document.getElementById('search-input');
    const filter = searchInput.value.toLowerCase();
    const table = document.querySelector('tbody');
    const rows = table.querySelectorAll('tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const districtCell = row.querySelector('td:first-child');
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

    const countDisplay = document.querySelector('.showing-count');
    if (countDisplay) {
        countDisplay.textContent = visibleCount;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.05}s`;
        row.classList.add('animate-slideInUp');
    });
});
