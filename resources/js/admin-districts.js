async function loadDistrictCounts() {
    try {
        const response = await fetch('/api/admin/districts', {
            credentials: 'include'
        });

        if (!response.ok) {
            const text = await response.text();
            console.error('API error:', text);
            return;
        }

        const data = await response.json();

        // Update stats cards
        document.getElementById('total-districts').innerText =
            (data.total_districts ?? 0).toLocaleString();

        document.getElementById('total-voters').innerText =
            (data.total_voters ?? 0).toLocaleString();

        document.getElementById('total-votes').innerText =
            (data.total_votes ?? 0).toLocaleString();

        const tbody = document.getElementById('district-table-body');
        tbody.innerHTML = '';

        if (!data.by_district || data.by_district.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No districts found.
                    </td>
                </tr>
            `;
            return;
        }

        data.by_district.forEach(district => {
            const row = `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        ${district.district}
                    </td>

                    <td class="px-6 py-4 text-center">
                        ${district.nominees_count ?? 0}
                    </td>

                    <td class="px-6 py-4 text-center">
                        ${district.votes_cast ?? 0}
                    </td>

                    <td class="px-6 py-4 text-center">
                        <button onclick="openEditModal(${district.id})"
                            class="px-3 py-1 bg-blue-500 text-white rounded">
                            Edit
                        </button>

                        <button onclick="openDeleteModal(${district.id})"
                            class="px-3 py-1 bg-red-500 text-white rounded ml-2">
                            Delete
                        </button>
                    </td>
                </tr>
            `;

            tbody.innerHTML += row;
        });

    } catch (error) {
        console.error("Error loading districts:", error);
    }
}


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
            // Delete functionality
            console.log('Delete district:', id);
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddDistrictModal();
        }
    });

    // Enhanced search functionality
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

        // Update count display
        const countDisplay = document.querySelector('.showing-count');
        if (countDisplay) {
            countDisplay.textContent = visibleCount;
        }
    }

    // Add animation on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDistrictCounts();

        setInterval(loadDistrictCounts, 5000);

        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            row.classList.add('animate-slideInUp');
        });
    });