
let masterlists = [];
// let currentEditId = null;
// let currentPage = 1;
// let itemsPerPage = 100;
// let filteredVoters = [];

// function getPaginatedVoters() {
//     const startIndex = (currentPage - 1) * itemsPerPage;
//     const endIndex = startIndex + itemsPerPage;
//     return filteredVoters.slice(startIndex, endIndex);
// }

// function updatePaginationControls() {
//     const totalPages = Math.ceil(filteredVoters.length / itemsPerPage);
//     const showingCount = document.getElementById('showing-count');
//     const pageInfo = document.getElementById('page-info');
//     const prevButton = document.getElementById('prev-page');
//     const nextButton = document.getElementById('next-page');

//     if (showingCount) {
//         showingCount.textContent = filteredVoters.length;
//     }

//     if (pageInfo) {
//         pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
//     }

//     if(prevButton) {
//         if (currentPage === 1) {
//             prevButton.disabled = true;
//             prevButton.classList.add('opacity-50', 'cursor-not-allowed');
//         } else {
//             prevButton.disabled = false;
//             prevButton.classList.remove('opacity-50', 'cursor-not-allowed');
//         }
//     }

//     if (nextButton) {
//         if (currentPage === totalPages || totalPages === 0) {
//             nextButton.disabled = true;
//             nextButton.classList.add('opacity-50', 'cursor-not-allowed');
//         } else {
//             nextButton.disabled = false;
//             nextButton.classList.remove('opacity-50', 'cursor-not-allowed');
//         }
//     }
// }

// function prevPage() {
//     if (currentPage > 1) {
//         currentPage--;
//         renderMasterlistsTable();
//     }
// }

// function nextPage() {
//     const totalPages = Math.ceil(filteredVoters.length / itemsPerPage);
//     if (currentPage < totalPages) {
//         currentPage++;
//         renderMasterlistsTable();
//     }
// }

// function goToPage(page) {
//     const totalPages = Math.ceil(filteredVoters.length / itemsPerPage);
//     if (page >= 1 && page <= totalPages) {
//             currentPage = page;
//             renderMasterlistsTable();
//     }
// }


async function loadMasterlists() {
    try {
        const response = await fetch('/api/admin/members', {
                credentials: 'include'
            });
        const json = await response.json();

        masterlists = (json.data ?? []).map(member => {
            const id = member.id ?? member.member_id ?? '';
            
            let firstName = member.first_name ?? '';
            let middleName = member.middle_name ?? '';
            let lastName = member.last_name ?? '';

            if (member.is_verified !== undefined) {
                member.status = member.is_verified ? 'Verified' : 'Pending';
            }

            let completeDistrict = member.district != null ? "District " + member.district : 'N/A';

            return {
                id: id,
                firstName: member.first_name ?? firstName,
                middleName: member.middle_name ?? middleName,
                lastName: member.last_name ?? lastName,
                suffix: member.suffix ?? '',
                district: completeDistrict,
                status: member.status ?? 'Pending',
                voterId: id,
                email: member.email ?? '',
                phone: member.contact_number ?? member.phone ?? '',
                address: member.address ?? member.full_address ?? '',
                registrationDate: member.created_at || new Date().toISOString().split('T')[0],
                lastUpdated: member.updated_at || new Date().toISOString().split('T')[0]
            };
        });

        filteredVoters = [...masterlists];

        updateStats();

        renderMasterlistsTable();
    } catch (error) {
        console.error('Failed to load masterlists:', error);
    }
}


document.addEventListener('DOMContentLoaded', loadMasterlists);

    function getFullName(voter) {
        let name = `${voter.firstName} `;
        if (voter.middleName) name += `${voter.middleName} `;
        name += voter.lastName;
        if (voter.suffix) name += ` ${voter.suffix}`;
        return name;
    }

    function createVoterRow(voter) {
        const fullName = getFullName(voter);
        
        let statusClass, statusIcon;
        switch(voter.status) {
            case 'Verified':
                statusClass = 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-700';
                statusIcon = '<svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
                break;
            case 'Pending':
                statusClass = 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-700';
                statusIcon = '<svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>';
                break;
        }

        return `
            <tr class="group hover:bg-gradient-to-r hover:from-green-50/50 hover:to-emerald-50/50 transition-all duration-200 even:bg-gray-50/50">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <span class="text-sm font-bold text-blue-700">
                                ${voter.firstName.charAt(0)}${voter.lastName.charAt(0)}
                            </span>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 group-hover:text-green-700 transition-colors">
                                ${fullName}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                ID: ${voter.voterId}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="inline-flex flex-col items-start">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 mb-1">
                            <svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            ${voter.district}
                        </span>
                        <div class="text-xs text-gray-500">
                            ${voter.email || voter.phone || 'No contact info'}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold ${statusClass}">
                        ${statusIcon}
                        ${voter.status}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="editVoter(${voter.id})"
                            class="group/edit inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 hover:shadow-lg active:scale-95 transition-all duration-200">
                            <svg class="h-4 w-4 mr-1.5 group-hover/edit:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                        <button onclick="deleteVoter(${voter.id})"
                            class="group/delete inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-semibold rounded-lg hover:from-red-600 hover:to-red-700 hover:shadow-lg active:scale-95 transition-all duration-200">
                            <svg class="h-4 w-4 mr-1.5 group-hover/delete:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function renderMasterlistsTable(filteredVoters = masterlists) {
        const tableBody = document.getElementById('masterlists-table-body');
        const totalVoters = document.getElementById('total-voters');
        const verifiedVoters = document.getElementById('verified-voters');
        const districtsCovered = document.getElementById('districts-covered');
        const voterCount = document.getElementById('voter-count');
        const showingCount = document.getElementById('showing-count');
        
        if (filteredVoters.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="h-20 w-20 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-2">No voters found</h4>
                            <p class="text-gray-500 mb-4">Add your first voter to get started</p>
                            <button onclick="openAddVoterModal()"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                                <x-plus-logo class="h-5 w-5 mr-2" />
                                Add First Voter
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            totalVoters.textContent = '0';
            verifiedVoters.textContent = '0';
            districtsCovered.textContent = '0';
            voterCount.textContent = 'Showing 0 voters';
            showingCount.textContent = '0';
            return;
        }
        
        const verifiedCount = filteredVoters.filter(voter => voter.status === 'Verified').length;
        const uniqueDistricts = new Set(filteredVoters.map(voter => voter.district)).size;
        
        totalVoters.textContent = filteredVoters.length;
        verifiedVoters.textContent = verifiedCount;
        districtsCovered.textContent = uniqueDistricts;
        voterCount.textContent = `Showing ${filteredVoters.length} voters`;
        showingCount.textContent = filteredVoters.length;
        
        tableBody.innerHTML = filteredVoters.map(createVoterRow).join('');
    }

    function filterVoters() {
        const searchInput = document.getElementById('search-input');
        const districtFilter = document.getElementById('district-filter');
        const statusFilter = document.getElementById('status-filter');
        
        const searchTerm = searchInput.value.toLowerCase();
        const selectedDistrict = districtFilter.value;
        const selectedStatus = statusFilter.value;
        
        let filtered = masterlists;
        
        if (searchTerm) {
            filtered = filtered.filter(voter => 
                getFullName(voter).toLowerCase().includes(searchTerm) ||
                voter.voterId.toLowerCase().includes(searchTerm) ||
                voter.district.toLowerCase().includes(searchTerm)
            );
        }
        
        if (selectedDistrict) {
            filtered = filtered.filter(voter => voter.district === selectedDistrict);
        }
        
        if (selectedStatus) {
            filtered = filtered.filter(voter => voter.status === selectedStatus);
        }
        
        renderMasterlistsTable(filtered);
    }

    function openAddVoterModal() {
        currentEditId = null;
        document.getElementById('modal-title').textContent = 'Add New Voter';
        const modalIcon = document.getElementById('modal-icon');
        if (modalIcon) {
            modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
        }
        document.getElementById('submit-button').textContent = 'Add Voter';
        
        document.getElementById('voterForm').reset();
        
        document.getElementById('voterModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function editVoter(id) {
        const voter = masterlists.find(v => v.id === id);
        if (!voter) return;
        
        currentEditId = id;
        
        document.getElementById('modal-title').textContent = 'Edit Voter';
        const modalIcon = document.getElementById('modal-icon');
        if (modalIcon) {
            modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />';
        }
        document.getElementById('submit-button').textContent = 'Update Voter';
        
        document.getElementById('first_name').value = voter.firstName;
        document.getElementById('middle_name').value = voter.middleName || '';
        document.getElementById('last_name').value = voter.lastName;
        document.getElementById('suffix').value = voter.suffix || '';
        document.getElementById('email').value = voter.email || '';
        document.getElementById('phone').value = voter.phone || '';
        document.getElementById('district').value = voter.district;
        document.getElementById('status').value = voter.status;
        document.getElementById('voter_id').value = voter.voterId;
        document.getElementById('address').value = voter.address || '';
        
        document.getElementById('voterModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function saveVoter(e) {
        if (e) e.preventDefault();
        
        const firstName = document.getElementById('first_name').value.trim();
        const middleName = document.getElementById('middle_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const suffix = document.getElementById('suffix').value;
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const district = document.getElementById('district').value;
        const status = document.getElementById('status').value;
        const voterId = document.getElementById('voter_id').value.trim();
        const address = document.getElementById('address').value.trim();
        
        if (!firstName || !lastName || !district || !status || !voterId) {
            alert('Please fill in all required fields.');
            return false;
        }
        
        if (currentEditId) {
            const index = masterlists.findIndex(v => v.id === currentEditId);
            if (index !== -1) {
                masterlists[index] = {
                    ...masterlists[index],
                    firstName,
                    middleName,
                    lastName,
                    suffix,
                    fullName: getFullName({firstName, middleName, lastName, suffix}),
                    district,
                    status,
                    voterId,
                    email,
                    phone,
                    address
                };
                
                alert('Voter updated successfully!');
            }
        } else {
            const newVoter = {
                id: masterlists.length + 1,
                firstName,
                middleName,
                lastName,
                suffix,
                fullName: getFullName({firstName, middleName, lastName, suffix}),
                district,
                status,
                voterId,
                email,
                phone,
                address
            };
            
            masterlists.push(newVoter);
            alert('Voter added successfully!');
        }
        
        closeVoterModal();
        filterVoters();
        return false;
    }

    function deleteVoter(id) {
        if (confirm('Are you sure you want to delete this voter?')) {
            const index = masterlists.findIndex(v => v.id === id);
            if (index !== -1) {
                masterlists.splice(index, 1);
                filterVoters();
                alert('Voter deleted successfully!');
            }
        }
    }

    function closeVoterModal() {
        document.getElementById('voterModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function exportToCSV() {
        const filteredVoters = getFilteredVoters();
        if (filteredVoters.length === 0) {
            alert('No data to export.');
            return;
        }
        
        const headers = ['ID', 'Full Name', 'Voter ID', 'District', 'Status', 'Email', 'Phone', 'Address'];
        
        const csvData = [
            headers.join(','),
            ...filteredVoters.map(voter => [
                voter.id,
                `"${getFullName(voter)}"`,
                voter.voterId,
                voter.district,
                voter.status,
                voter.email || '',
                voter.phone || '',
                `"${voter.address || ''}"`
            ].join(','))
        ].join('\n');
        
        const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', `voter_masterlist_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function exportToPDF() {
        const filteredVoters = getFilteredVoters();
        if (filteredVoters.length === 0) {
            alert('No data to export.');
            return;
        }
        
        alert('PDF export functionality would be implemented here with a PDF library.\n' + 
              `Exporting ${filteredVoters.length} voters to PDF.`);
    }

    function printMasterlist() {
        const filteredVoters = getFilteredVoters();
        if (filteredVoters.length === 0) {
            alert('No data to print.');
            return;
        }
        
        alert('Print functionality would be implemented here.\n' + 
              `Printing ${filteredVoters.length} voters.`);
    }

    function getFilteredVoters() {
        const searchInput = document.getElementById('search-input');
        const districtFilter = document.getElementById('district-filter');
        const statusFilter = document.getElementById('status-filter');
        
        const searchTerm = searchInput.value.toLowerCase();
        const selectedDistrict = districtFilter.value;
        const selectedStatus = statusFilter.value;
        
        let filtered = masterlists;
        
        if (searchTerm) {
            filtered = filtered.filter(voter => 
                getFullName(voter).toLowerCase().includes(searchTerm) ||
                voter.voterId.toLowerCase().includes(searchTerm) ||
                voter.district.toLowerCase().includes(searchTerm)
            );
        }
        
        if (selectedDistrict) {
            filtered = filtered.filter(voter => voter.district === selectedDistrict);
        }
        
        if (selectedStatus) {
            filtered = filtered.filter(voter => voter.status === selectedStatus);
        }
        
        return filtered;
    }


    document.addEventListener('DOMContentLoaded', function() {
        renderMasterlistsTable();
        
        const searchInput = document.getElementById('search-input');
        const districtFilter = document.getElementById('district-filter');
        const statusFilter = document.getElementById('status-filter');
        
        if (searchInput) searchInput.addEventListener('input', filterVoters);
        if (districtFilter) districtFilter.addEventListener('change', filterVoters);
        if (statusFilter) statusFilter.addEventListener('change', filterVoters);
        
        const voterForm = document.getElementById('voterForm');
        if (voterForm) {
            voterForm.addEventListener('submit', saveVoter);
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeVoterModal();
            }
        });
        
        const voterModal = document.getElementById('voterModal');
        if (voterModal) {
            voterModal.addEventListener('click', function(event) {
                if (event.target === this) {
                    closeVoterModal();
                }
            });
        }
    });