let masterlists = [];
let currentEditId = null;
let currentPage = 1;
let itemsPerPage = 100;
let filteredVoters = [];

function getPaginatedVoters() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return filteredVoters.slice(startIndex, endIndex);
}

function updatePaginationControls() {
    const totalPages = Math.ceil(filteredVoters.length / itemsPerPage);
    const showingCount = document.getElementById('showing-count');
    const pageInfo = document.getElementById('page-info');
    const prevButton = document.getElementById('prev-page');
    const nextButton = document.getElementById('next-page');

    if (showingCount) {
        showingCount.textContent = filteredVoters.length;
    }

    if (pageInfo) {
        pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
    }

    if(prevButton) {
        if (currentPage === 1) {
            prevButton.disabled = true;
            prevButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevButton.disabled = false;
            prevButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    if (nextButton) {
        if (currentPage === totalPages || totalPages === 0) {
            nextButton.disabled = true;
            nextButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            nextButton.disabled = false;
            nextButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderMasterlistsTable();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredVoters.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderMasterlistsTable();
    }
}

function goToPage(page) {
    const totalPages = Math.ceil(filteredVoters.length / itemsPerPage);
    if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderMasterlistsTable();
    }
}

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

function updateStats() {
    const totalVoters = document.getElementById('total-voters');
    const verifiedVoters = document.getElementById('verified-voters');
    const districtsCovered = document.getElementById('districts-covered');
    const voterCountElement = document.getElementById('voter-count');

    const verifiedCount = masterlists.filter(voter => voter.status === 'Verified').length;
    const uniqueDistricts = new Set(masterlists.map(voter => voter.district)).size;

    if (totalVoters) totalVoters.textContent = masterlists.length;
    if (verifiedVoters) verifiedVoters.textContent = verifiedCount;
    if (districtsCovered) districtsCovered.textContent = uniqueDistricts;
    if (voterCountElement) voterCountElement.textContent = `Showing ${filteredVoters.length} voters`;
}

function getFullName(voter) {
    let name = `${voter.firstName} `;
    if (voter.middleName) name += `${voter.middleName} `;
    name += voter.lastName;
    if (voter.suffix) name += ` ${voter.suffix}`;
    return name.trim();
}

function createVoterRow(voter) {
    const fullName = getFullName(voter);

    let statusClass, statusIcon;
    switch (voter.status) {
        case 'Verified':
            statusClass = 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-700';
            statusIcon = '<svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
            break;
        case 'Pending':
            statusClass = 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-700';
            statusIcon = '<svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>';
            break;
        default:
            statusClass = 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700';
            statusIcon = '<svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>';
    }

    const voterJson = JSON.stringify(voter).replace(/"/g, '&quot;');

    return `
        <tr class="group hover:bg-gradient-to-r hover:from-green-50/50 hover:to-emerald-50/50 transition-all duration-200 even:bg-gray-50/50 cursor-pointer" 
            onclick='openVoterDetailsModal(${voterJson})'>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <span class="text-sm font-bold text-blue-700">
                            ${voter.firstName ? voter.firstName.charAt(0) : ''}${voter.lastName ? voter.lastName.charAt(0) : ''}
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
            <td class="px-6 py-4" onclick="event.stopPropagation()">
                <div class="flex items-center gap-2">
                    <button onclick="editVoter('${voter.id}')"
                        class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all duration-200 group">
                        <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

function renderMasterlistsTable() {
    const tableBody = document.getElementById('masterlists-table-body');
    if (!tableBody) return;

    const paginatedVoters = getPaginatedVoters();

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
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                            Add First Voter
                    </button>
                </div>
            </td>
        </tr>
        `;
    } else {
        tableBody.innerHTML = paginatedVoters.map(voter => createVoterRow(voter)).join('');
    }
    updatePaginationControls();
    updateStats();
}

function filterVoters() {
    const searchInput = document.getElementById('search-input');
    const districtFilter = document.getElementById('district-filter');
    const statusFilter = document.getElementById('status-filter');

    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const selectedDistrict = districtFilter ? districtFilter.value : '';
    const selectedStatus = statusFilter ? statusFilter.value : '';

    filteredVoters = [...masterlists];

    if (searchTerm) {
        filteredVoters = filteredVoters.filter(voter =>
            getFullName(voter).toLowerCase().includes(searchTerm) ||
            voter.voterId.toLowerCase().includes(searchTerm) ||
            voter.district.toLowerCase().includes(searchTerm)
        );
    }

    if (selectedDistrict) {
        filteredVoters = filteredVoters.filter(voter => voter.district === selectedDistrict);
    }

    if (selectedStatus) {
        filteredVoters = filteredVoters.filter(voter => voter.status === selectedStatus);
    }

    currentPage = 1;

    renderMasterlistsTable();
}

function openAddVoterModal() {
    currentEditId = null;
    document.getElementById('modal-title').textContent = 'Add New Voter';
    const modalIcon = document.getElementById('modal-icon');
    if (modalIcon) {
        modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
    }
    document.getElementById('submit-button').textContent = 'Add Voter';

    const form = document.getElementById('voterForm');
    if (form) form.reset();

    document.getElementById('voterModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function createEditVoterModal() {
    if (document.getElementById('editVoterModal')) return;

    const modalHTML = `
    <div id="editVoterModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 
        pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" 
                onclick="closeEditVoterModal()"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl 
            transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full 
            overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Voter Details</h3>
                        <button onclick="closeEditVoterModal()" 
                            class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-gray-100 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form id="editVoterForm" onsubmit="saveEditVoter(event)" class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                <span class="h-1 w-5 bg-blue-600 rounded-full"></span>
                                Personal Information
                            </h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required-field">First Name</label>
                                    <input type="text" id="edit_first_name" name="first_name" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter first name">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                    <input type="text" id="edit_middle_name" name="middle_name"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter middle name (optional)">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required-field">Last Name</label>
                                    <input type="text" id="edit_last_name" name="last_name" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter last name">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                                    <input type="text" id="edit_suffix" name="suffix"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="e.g., Jr., Sr., III">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                <span class="h-1 w-5 bg-blue-600 rounded-full"></span>
                                Contact & Details
                            </h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" id="edit_email" name="email"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="consumer@boheco1.com">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" id="edit_phone" name="phone"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="+1 (555) 123-4567">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required-field">District</label>
                                    <select id="edit_district" name="district" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select District</option>
                                        <option value="District 1">District 1</option>
                                        <option value="District 2">District 2</option>
                                        <option value="District 3">District 3</option>
                                        <option value="District 4">District 4</option>
                                        <option value="District 5">District 5</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required-field">Status</label>
                                    <select id="edit_status" name="status" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="Verified">Verified</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required-field">Voter ID</label>
                                    <input type="text" id="edit_voter_id" name="voter_id" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter voter ID">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2 mb-3">
                            <span class="h-1 w-5 bg-blue-600 rounded-full"></span>
                            Address Information
                        </h4>
                        <textarea id="edit_address" name="address" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Enter full address"></textarea>
                    </div>

                    <input type="hidden" id="edit_voter_hidden_id">

                    <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEditVoterModal()"
                            class="flex-1 px-6 py-3 text-gray-700 font-medium border-2 border-gray-300 rounded-xl hover:bg-gray-50 active:scale-95 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-semibold rounded-xl hover:from-green-700 hover:to-green-800 hover:shadow-lg active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Voter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function openEditVoterModal(voter) {
    let editModal = document.getElementById('editVoterModal');
    if (!editModal) {
        createEditVoterModal();
        editModal = document.getElementById('editVoterModal');
    }

    document.getElementById('edit_first_name').value = voter.firstName || '';
    document.getElementById('edit_middle_name').value = voter.middleName || '';
    document.getElementById('edit_last_name').value = voter.lastName || '';
    document.getElementById('edit_suffix').value = voter.suffix || '';
    document.getElementById('edit_email').value = voter.email || '';
    document.getElementById('edit_phone').value = voter.phone || '';
    document.getElementById('edit_district').value = voter.district || '';
    document.getElementById('edit_status').value = voter.status || 'Pending';
    document.getElementById('edit_voter_id').value = voter.voterId || '';
    document.getElementById('edit_address').value = voter.address || '';
    document.getElementById('edit_voter_hidden_id').value = voter.id;

    editModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeEditVoterModal() {
    const modal = document.getElementById('editVoterModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function saveEditVoter(e) {
    e.preventDefault();

    const firstName = document.getElementById('edit_first_name').value.trim();
    const middleName = document.getElementById('edit_middle_name').value.trim();
    const lastName = document.getElementById('edit_last_name').value.trim();
    const suffix = document.getElementById('edit_suffix').value;
    const email = document.getElementById('edit_email').value.trim();
    const phone = document.getElementById('edit_phone').value.trim();
    const district = document.getElementById('edit_district').value;
    const statusField = document.getElementById('edit_status');
    const status = statusField ? statusField.value : 'Pending';
    const voterId = document.getElementById('edit_voter_id').value.trim();
    const address = document.getElementById('edit_address').value.trim();

    if (!firstName || !lastName || !district || !voterId) {
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
                district,
                status,
                voterId,
                email,
                phone,
                address,
                lastUpdated: new Date().toISOString().split('T')[0]
            };

            alert('Voter updated successfully!');
        }
    } else {
        const newVoter = {
            id: Date.now().toString(),
            firstName,
            middleName,
            lastName,
            suffix,
            district,
            status,
            voterId,
            email,
            phone,
            address,
            registrationDate: new Date().toISOString().split('T')[0],
            lastUpdated: new Date().toISOString().split('T')[0]
        };

        masterlists.push(newVoter);
        alert('Voter added successfully!');
    }

    closeEditVoterModal();
    filterVoters();
    return false;
}

function editVoter(id) {
    const voter = masterlists.find(v => v.id === id);
    if (!voter) return;

    currentEditId = id;
    openEditVoterModal(voter);
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

function saveVoter(event) {
    event.preventDefault();

    const  firstName = document.getElementById('first_name').value.trim();
    const middleName = document.getElementById('middle_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const suffix = document.getElementById('suffix').value;
    const district = document.getElementById('district').value;
    const email =document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const voterId = document.getElementById('voter_id').value.trim();
    const address = document.getElementById('address').value.trim();

    if (!firstName || !lastName || !district || !voterId) {
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
                district,
                status: masterlists[index].status || 'Pending',
                voterId,
                email,
                phone,
                address,
                lastUpdated: new Date().toISOString().split('T')[0]
            };
            alert('Voter updated successfully!');
        }
    } else {
        const newVoter = {
            id: Date.now().toString(),
            firstName,
            middleName,
            lastName,
            suffix,
            district,
            status: 'Pending',
            voterId,
            email,
            phone,
            address,
            registrationDate: new Date().toISOString().split('T')[0],
            lastUpdated: new Date().toISOString().split('T')[0]
        };
        masterlists.push(newVoter);
        alert('Voter added successfully!');
    }

    closeVoterModal();
    filterVoters();
    return false;
}

function openVoterDetailsModal(voter) {
    let detailsModal = document.getElementById('voterDetailsModal');
    if (!detailsModal) {
        createVoterDetailsModal();
        detailsModal = document.getElementById('voterDetailsModal');
    }

    document.getElementById('details-full-name').textContent = getFullName(voter);
    document.getElementById('details-voter-id').textContent = voter.voterId;
    document.getElementById('details-district').textContent = voter.district;

    updateDetailsStatus(voter.status);

    document.getElementById('details-email').textContent = voter.email || 'Not provided';
    document.getElementById('details-phone').textContent = voter.phone || 'Not provided';
    
    if (typeof voter.address === 'string') {
        document.getElementById('details-address-sitio').textContent = voter.address || '-';
        document.getElementById('details-address-barangay').textContent = '-';
        document.getElementById('details-address-town').textContent = '-';
    } else if (voter.address && typeof voter.address === 'object') {
        document.getElementById('details-address-sitio').textContent = voter.address.street || '-';
        document.getElementById('details-address-barangay').textContent = voter.address.barangay || '-';
        document.getElementById('details-address-town').textContent = voter.address.town || '-';
    }

    document.getElementById('details-reg-date').textContent = voter.registrationDate || '-';
    document.getElementById('details-last-updated').textContent = voter.lastUpdated || '-';

    document.getElementById('details-edit-btn').onclick = () => {
        closeVoterDetailsModal();
        editVoter(voter.id);
    };

    detailsModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function updateDetailsStatus(status) {
    const statusContainer = document.getElementById('details-status');
    if (!statusContainer) return;

    let statusClass = '';
    let statusText = status || 'Unknown';

    switch(status?.toLowerCase()) {
        case 'verified':
            statusClass = 'bg-green-100 text-green-800';
            break;
        case 'pending':
            statusClass = 'bg-yellow-100 text-yellow-800';
            break;
        case 'inactive':
            statusClass = 'bg-gray-100 text-gray-800';
            break;
        default:
            statusClass = 'bg-gray-100 text-gray-800';
            statusText = status || '-';
    }

    statusContainer.innerHTML = `<span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">${statusText}</span>`;
}

function closeVoterDetailsModal() {
    const modal = document.getElementById('voterDetailsModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function createVoterDetailsModal() {
    if (document.getElementById('voterDetailsModal')) return;

    const modalHTML = `
    <div id="voterDetailsModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeVoterDetailsModal()"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Voter Details</h3>
                        <button onclick="closeVoterDetailsModal()" 
                            class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-gray-100 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex space-x-4">
                                <div class="relative flex-shrink-0">
                                    <div class="h-20 w-20 rounded-full border-2 border-gray-100 bg-gray-200 overflow-hidden">
                                        <img id="details-profile-pic" class="h-full w-full object-cover" src="" alt="Profile" style="display: none;">
                                        <div id="details-profile-placeholder" class="h-full w-full flex items-center justify-center bg-gradient-to-br from-gray-500 to-gray-600">
                                            <svg class="h-10 w-10 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <button onclick="uploadProfilePicture()" class="absolute bottom-0 right-0 bg-white text-gray-600 p-1.5 rounded-full hover:bg-blue-600 hover:text-white transition-colors duration-200 shadow-lg border border-gray-200" title="Upload profile picture">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex-1">
                                    <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                        <div>
                                            <p class="text-xs text-gray-500">Full Name</p>
                                            <p class="text-sm font-semibold text-gray-900" id="details-full-name">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Voter ID</p>
                                            <p class="font-mono text-sm font-semibold text-gray-900" id="details-voter-id">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500">District</p>
                                    <p class="text-sm font-semibold text-gray-900" id="details-district">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Status</p>
                                    <div id="details-status" class="inline-flex"></div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Contact Information</h4>
                                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Email Address</p>
                                        <p class="text-sm text-gray-900 break-all" id="details-email">-</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Phone Number</p>
                                        <p class="text-sm text-gray-900" id="details-phone">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Address Information</h4>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-900" id="details-address-sitio">-</p>
                                        <p class="text-sm text-gray-900" id="details-address-barangay">-</p>
                                        <p class="text-sm text-gray-900" id="details-address-town">-</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Voting Information</h4>
                                <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                    <div>
                                        <p class="text-xs text-gray-500">Verification Date</p>
                                        <p class="text-sm text-gray-900" id="details-reg-date">-</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Voted Date/Time</p>
                                        <p class="text-sm text-gray-900" id="details-last-updated">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <button onclick="closeVoterDetailsModal()"
                        class="flex-1 px-4 py-2.5 text-gray-700 font-medium border border-gray-300 rounded-lg hover:bg-gray-100 active:scale-95 transition-all duration-200">
                        Close
                    </button>
                    <button id="details-edit-btn"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 hover:shadow-lg active:scale-95 transition-all duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Voter
                    </button>
                </div>
            </div>
        </div>
    </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function uploadProfilePicture() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    
    input.onchange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
            const profilePic = document.getElementById('details-profile-pic');
            const placeholder = document.getElementById('details-profile-placeholder');
            if (profilePic && placeholder) {
                profilePic.src = e.target.result;
                profilePic.style.display = 'block';
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    };
    
    input.click();
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

    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const selectedDistrict = districtFilter ? districtFilter.value : '';
    const selectedStatus = statusFilter ? statusFilter.value : '';

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

document.addEventListener('DOMContentLoaded', function () {
    loadMasterlists();
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

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeVoterModal();
            closeVoterDetailsModal();
        }
    });

    const voterModal = document.getElementById('voterModal');
    if (voterModal) {
        voterModal.addEventListener('click', function (event) {
            if (event.target === this) {
                closeVoterModal();
            }
        });
    }
});

window.openAddVoterModal = openAddVoterModal;
window.closeVoterModal = closeVoterModal;
window.editVoter = editVoter;
window.deleteVoter = deleteVoter;
window.exportToCSV = exportToCSV;
window.exportToPDF = exportToPDF;
window.printMasterlist = printMasterlist;
window.openVoterDetailsModal = openVoterDetailsModal;
window.closeVoterDetailsModal = closeVoterDetailsModal;
window.uploadProfilePicture = uploadProfilePicture;
window.filterVoters = filterVoters;
window.closeEditVoterModal = closeEditVoterModal;