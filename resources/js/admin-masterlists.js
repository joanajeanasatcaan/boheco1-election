let masterlists = [];
let currentEditId = null;
let currentPage = 1;
let itemsPerPage = 100;
let filteredVoters = [];

let nextCursor     = null;
let cursorHistory  = [];
let activeFilters  = {};

// ─── Debounce ─────────────────────────────────────────────────────────────────
let searchDebounceTimer = null;

function debounce(fn, delay) {
    return function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => fn(), delay);
    };
}

const debouncedFilter = debounce(filterVoters, 600);

function updatePaginationControls() {
    const showingCount = document.getElementById('showing-count');
    const pageInfo     = document.getElementById('page-info');
    const prevButton   = document.getElementById('prev-page');
    const nextButton   = document.getElementById('next-page');

    if (showingCount) showingCount.textContent = filteredVoters.length;
    if (pageInfo) pageInfo.textContent = `Page ${currentPage}${nextCursor ? '+' : ''}`;

    if (prevButton) {
        prevButton.disabled = currentPage <= 1;
        prevButton.classList.toggle('opacity-50',         currentPage <= 1);
        prevButton.classList.toggle('cursor-not-allowed', currentPage <= 1);
    }
    if (nextButton) {
        nextButton.disabled = !nextCursor;
        nextButton.classList.toggle('opacity-50',         !nextCursor);
        nextButton.classList.toggle('cursor-not-allowed', !nextCursor);
    }
}

function prevPage() {
    if (currentPage <= 1) return;
    currentPage--;
    cursorHistory.pop();
    const cursor = cursorHistory.length > 0 ? cursorHistory[cursorHistory.length - 1] : null;
    loadMasterlists(activeFilters, cursor);
}

function nextPage() {
    if (!nextCursor) return;
    cursorHistory.push(nextCursor);
    currentPage++;
    loadMasterlists(activeFilters, nextCursor);
}

function goToPage(page) {
    if (page === 1) {
        currentPage   = 1;
        cursorHistory = [];
        loadMasterlists(activeFilters, null);
    }
}

function renderMasterlistsSkeleton() {
    const tableBody = document.getElementById('masterlists-table-body');
    if (!tableBody) return;
    tableBody.innerHTML = Array.from({ length: 8 }, () => `
        <tr class="animate-pulse">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gray-200 mr-3"></div>
                    <div class="space-y-2">
                        <div class="h-3.5 bg-gray-200 rounded w-36"></div>
                        <div class="h-3 bg-gray-200 rounded w-24"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="space-y-2">
                    <div class="h-6 bg-gray-200 rounded-full w-28"></div>
                    <div class="h-3 bg-gray-200 rounded w-32"></div>
                </div>
            </td>
            <td class="px-6 py-4"><div class="h-6 bg-gray-200 rounded-full w-20"></div></td>
            <td class="px-6 py-4"><div class="h-8 bg-gray-200 rounded-lg w-9"></div></td>
        </tr>
    `).join('');
}

async function loadMasterlists(params, cursor) {
    params = params || {};
    renderMasterlistsSkeleton();

    try {
        const clean = { per_page: itemsPerPage, ...params };

        if (cursor) {
            const isPageNumber = /^\d+$/.test(String(cursor));
            if (isPageNumber) { clean.page = cursor; } else { clean.cursor = cursor; }
        }

        const response = await fetch('/api/admin/members?' + new URLSearchParams(clean).toString(), {
            credentials: 'include'
        });
        const json = await response.json();

        nextCursor = json.meta?.next_cursor ?? null;

        masterlists = (json.data ?? []).map(member => {
            if (member.is_verified !== undefined) {
                member.status = member.is_verified ? 'Verified' : 'Pending';
            }
            const id = member.member_id ?? member.id ?? '';
            const rawPhone = member.contact_number ?? member.phone ?? '';
            return {
                id:               id,
                firstName:        member.first_name  ?? '',
                middleName:       member.middle_name ?? '',
                lastName:         member.last_name   ?? '',
                suffix:           member.suffix      ?? '',
                district:         member.district != null ? 'District ' + member.district : 'N/A',
                status:           member.status      ?? 'Pending',
                voterId:          id,
                email:            member.email ?? '',
                // Normalise phone: treat "0" or empty as blank
                phone:            (rawPhone && rawPhone !== '0') ? rawPhone : '',
                address:          member.address ?? member.full_address ?? '',
                registrationDate: member.created_at  || new Date().toISOString().split('T')[0],
                lastUpdated:      member.updated_at  || new Date().toISOString().split('T')[0],
                isSpouse:         !!member.member,
                spouseOf:         member.member?.full_name ?? null,
            };
        });

        filteredVoters = [...masterlists];
        updateStats();
        renderMasterlistsTable();

    } catch (error) {
        console.error('Failed to load masterlists:', error);
        const tableBody = document.getElementById('masterlists-table-body');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="h-16 w-16 mb-4 rounded-full bg-red-50 flex items-center justify-center">
                                <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-semibold text-gray-700 mb-1">Failed to load voters</h4>
                            <p class="text-sm text-gray-500 mb-4">Check your connection and try again</p>
                            <button onclick="loadMasterlists(activeFilters)"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                Retry
                            </button>
                        </div>
                    </td>
                </tr>`;
        }
    }
}

function updateStats() {
    const showingCount   = document.getElementById('showing-count');
    const voterCountEl   = document.getElementById('voter-count');

    if (showingCount) showingCount.textContent = filteredVoters.length;
    if (voterCountEl) voterCountEl.textContent = `Showing ${filteredVoters.length} voters`;

    const totalVoters      = document.getElementById('total-voters');
    const verifiedVoters   = document.getElementById('verified-voters');
    const districtsCovered = document.getElementById('districts-covered');

    const verifiedCount   = filteredVoters.filter(v => v.status === 'Verified').length;
    const uniqueDistricts = new Set(filteredVoters.map(v => v.district)).size;

    if (totalVoters)      totalVoters.textContent      = filteredVoters.length;
    if (verifiedVoters)   verifiedVoters.textContent   = verifiedCount;
    if (districtsCovered) districtsCovered.textContent = uniqueDistricts;
}

function getFullName(voter) {
    let name = `${voter.firstName} `;
    if (voter.middleName) name += `${voter.middleName} `;
    name += voter.lastName;
    if (voter.suffix) name += ` ${voter.suffix}`;
    return name.trim();
}

function formatPhone(phone) {
    return (phone && phone !== '0' && phone !== '-') ? phone : 'No phone number provided';
}

function createVoterRow(voter) {
    const fullName = getFullName(voter);
    let statusClass, statusIcon;
    switch (voter.status) {
        case 'Verified':
            statusClass = 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-700';
            statusIcon  = '<svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
            break;
        case 'Pending':
            statusClass = 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-700';
            statusIcon  = '<svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>';
            break;
        default:
            statusClass = 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700';
            statusIcon  = '<svg class="h-3 w-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>';
    }

    return `
        <tr class="group hover:bg-gradient-to-r hover:from-green-50/50 hover:to-emerald-50/50 transition-all duration-200 even:bg-gray-50/50 cursor-pointer"
            data-voter-id="${voter.id}"
            onclick="openVoterDetailsById(this.dataset.voterId)">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <span class="text-sm font-bold text-blue-700">
                            ${voter.firstName ? voter.firstName.charAt(0) : ''}${voter.lastName ? voter.lastName.charAt(0) : ''}
                        </span>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900 group-hover:text-green-700 transition-colors">${fullName}</div>
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
                    <div class="text-xs text-gray-500">${voter.email || formatPhone(voter.phone) || 'No contact info'}</div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold ${statusClass}">
                    ${statusIcon}${voter.status}
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

// ── Look up voter by id from masterlists array — avoids inline JSON entirely
function openVoterDetailsById(id) {
    const voter = masterlists.find(v => v.id === id);
    if (voter) openVoterDetailsModal(voter);
}

function renderMasterlistsTable() {
    const tableBody = document.getElementById('masterlists-table-body');
    if (!tableBody) return;

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
                </div>
            </td>
        </tr>`;
    } else {
        tableBody.innerHTML = filteredVoters.map(voter => createVoterRow(voter)).join('');
    }

    updatePaginationControls();
    updateStats();
}

function filterVoters() {
    const searchInput    = document.getElementById('search-input');
    const districtFilter = document.getElementById('district-filter');
    const statusFilter   = document.getElementById('status-filter');

    activeFilters = {};

    if (searchInput && searchInput.value.trim()) {
        const val = searchInput.value.trim();
        const isIdSearch = /^\d{2,}$/.test(val);
        if (isIdSearch) { activeFilters.id_search = val; } else { activeFilters.search = val; }
    }
    if (statusFilter   && statusFilter.value)   activeFilters.status   = statusFilter.value;
    if (districtFilter && districtFilter.value) activeFilters.district = districtFilter.value.replace('District ', '').trim();

    currentPage   = 1;
    cursorHistory = [];
    loadMasterlists(activeFilters, null);
}

// ─── Edit modal (created dynamically) ────────────────────────────────────────
function createEditVoterModal() {
    if (document.getElementById('editVoterModal')) return;
    const modalHTML = `
    <div id="editVoterModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeEditVoterModal()"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Voter Details</h3>
                        <button onclick="closeEditVoterModal()" class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-gray-100 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                <form id="editVoterForm" onsubmit="saveEditVoter(event)" class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2"><span class="h-1 w-5 bg-blue-600 rounded-full"></span>Personal Information</h4>
                            <div class="space-y-3">
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label><input type="text" id="edit_first_name" name="first_name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Enter first name"></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label><input type="text" id="edit_middle_name" name="middle_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Enter middle name (optional)"></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label><input type="text" id="edit_last_name" name="last_name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Enter last name"></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Suffix</label><input type="text" id="edit_suffix" name="suffix" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="e.g., Jr., Sr., III"></div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2"><span class="h-1 w-5 bg-blue-600 rounded-full"></span>Contact & Details</h4>
                            <div class="space-y-3">
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label><input type="email" id="edit_email" name="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="email@example.com"></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label><input type="tel" id="edit_phone" name="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="+63 9XX XXX XXXX"></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">District *</label><select id="edit_district" name="district" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"><option value="">Select District</option><option>District 1</option><option>District 2</option><option>District 3</option><option>District 4</option><option>District 5</option></select></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Status *</label><select id="edit_status" name="status" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"><option value="Verified">Verified</option><option value="Pending">Pending</option><option value="Inactive">Inactive</option></select></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Voter ID *</label><input type="text" id="edit_voter_id" name="voter_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Enter voter ID"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2 mb-3"><span class="h-1 w-5 bg-blue-600 rounded-full"></span>Address Information</h4>
                        <textarea id="edit_address" name="address" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Enter full address"></textarea>
                    </div>
                    <input type="hidden" id="edit_voter_hidden_id">
                    <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEditVoterModal()" class="flex-1 px-6 py-3 text-gray-700 font-medium border-2 border-gray-300 rounded-xl hover:bg-gray-50 active:scale-95 transition-all duration-200">Cancel</button>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-semibold rounded-xl hover:from-green-700 hover:to-green-800 hover:shadow-lg active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Update Voter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function openEditVoterModal(voter) {
    let editModal = document.getElementById('editVoterModal');
    if (!editModal) { createEditVoterModal(); editModal = document.getElementById('editVoterModal'); }

    document.getElementById('edit_first_name').value  = voter.firstName  || '';
    document.getElementById('edit_middle_name').value = voter.middleName || '';
    document.getElementById('edit_last_name').value   = voter.lastName   || '';
    document.getElementById('edit_suffix').value      = voter.suffix     || '';
    document.getElementById('edit_email').value       = voter.email      || '';
    document.getElementById('edit_phone').value       = voter.phone      || '';
    document.getElementById('edit_district').value    = voter.district   || '';
    document.getElementById('edit_status').value      = voter.status     || 'Pending';
    document.getElementById('edit_voter_id').value    = voter.voterId    || '';
    document.getElementById('edit_address').value     = voter.address    || '';
    document.getElementById('edit_voter_hidden_id').value = voter.id;

    editModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeEditVoterModal() {
    const modal = document.getElementById('editVoterModal');
    if (modal) { modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
}

function saveEditVoter(e) {
    e.preventDefault();
    const firstName  = document.getElementById('edit_first_name').value.trim();
    const middleName = document.getElementById('edit_middle_name').value.trim();
    const lastName   = document.getElementById('edit_last_name').value.trim();
    const suffix     = document.getElementById('edit_suffix').value;
    const email      = document.getElementById('edit_email').value.trim();
    const phone      = document.getElementById('edit_phone').value.trim();
    const district   = document.getElementById('edit_district').value;
    const status     = document.getElementById('edit_status')?.value || 'Pending';
    const voterId    = document.getElementById('edit_voter_id').value.trim();
    const address    = document.getElementById('edit_address').value.trim();

    if (!firstName || !lastName || !district || !voterId) { alert('Please fill in all required fields.'); return false; }

    if (currentEditId) {
        const index = masterlists.findIndex(v => v.id === currentEditId);
        if (index !== -1) {
            masterlists[index] = { ...masterlists[index], firstName, middleName, lastName, suffix, district, status, voterId, email, phone, address, lastUpdated: new Date().toISOString().split('T')[0] };
            alert('Voter updated successfully!');
        }
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
        if (index !== -1) { masterlists.splice(index, 1); filterVoters(); alert('Voter deleted successfully!'); }
    }
}

function closeVoterModal() {
    const m = document.getElementById('voterModal');
    if (m) { m.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
}

function saveVoter(event) {
    event.preventDefault();
    const firstName  = document.getElementById('first_name').value.trim();
    const middleName = document.getElementById('middle_name').value.trim();
    const lastName   = document.getElementById('last_name').value.trim();
    const suffix     = document.getElementById('suffix').value;
    const district   = document.getElementById('district').value;
    const email      = document.getElementById('email').value.trim();
    const phone      = document.getElementById('phone').value.trim();
    const voterId    = document.getElementById('voter_id').value.trim();
    const address    = document.getElementById('address').value.trim();

    if (!firstName || !lastName || !district || !voterId) { alert('Please fill in all required fields.'); return false; }

    const newVoter = {
        id: Date.now().toString(), firstName, middleName, lastName, suffix, district, status: 'Pending', voterId, email,
        phone: (phone && phone !== '0') ? phone : '',
        address, registrationDate: new Date().toISOString().split('T')[0], lastUpdated: new Date().toISOString().split('T')[0]
    };
    masterlists.push(newVoter);
    alert('Voter added successfully!');
    closeVoterModal();
    filterVoters();
    return false;
}

// ─── Voter details modal ──────────────────────────────────────────────────────
function openVoterDetailsModal(voter) {
    let detailsModal = document.getElementById('voterDetailsModal');
    if (!detailsModal) { createVoterDetailsModal(); detailsModal = document.getElementById('voterDetailsModal'); }

    document.getElementById('details-full-name').textContent  = getFullName(voter);
    document.getElementById('details-voter-id').textContent   = voter.voterId;
    document.getElementById('details-district').textContent   = voter.district;
    document.getElementById('details-email').textContent      = voter.email || 'No email provided';
    document.getElementById('details-phone').textContent      = formatPhone(voter.phone);
    document.getElementById('details-reg-date').textContent   = voter.registrationDate || '-';
    document.getElementById('details-last-updated').textContent = voter.lastUpdated || '-';

    updateDetailsStatus(voter.status);

    // if (typeof voter.address === 'string') {
    //     document.getElementById('details-address-sitio').textContent    = voter.address || '-';
    //     document.getElementById('details-address-barangay').textContent = '-';
    //     document.getElementById('details-address-town').textContent     = '-';
    // } else if (voter.address && typeof voter.address === 'object') {
    //     document.getElementById('details-address-sitio').textContent    = voter.address.street   || '-';
    //     document.getElementById('details-address-barangay').textContent = voter.address.barangay || '-';
    //     document.getElementById('details-address-town').textContent     = voter.address.town     || '-';
    // }

    document.getElementById('details-edit-btn').onclick = () => { closeVoterDetailsModal(); editVoter(voter.id); };

    detailsModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function updateDetailsStatus(status) {
    const statusContainer = document.getElementById('details-status');
    if (!statusContainer) return;
    let statusClass = 'bg-gray-100 text-gray-800';
    switch (status?.toLowerCase()) {
        case 'verified': statusClass = 'bg-green-100 text-green-800'; break;
        case 'pending':  statusClass = 'bg-yellow-100 text-yellow-800'; break;
        case 'inactive': statusClass = 'bg-gray-100 text-gray-800'; break;
    }
    statusContainer.innerHTML = `<span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">${status || '-'}</span>`;
}

function closeVoterDetailsModal() {
    const modal = document.getElementById('voterDetailsModal');
    if (modal) { modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
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
                        <button onclick="closeVoterDetailsModal()" class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-gray-100 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex space-x-4">
                                <div class="relative flex-shrink-0">
                                    <div class="h-20 w-20 rounded-full border-2 border-gray-100 bg-gray-200 overflow-hidden">
                                        <img id="details-profile-pic" class="h-full w-full object-cover" src="" alt="Profile" style="display:none;">
                                        <div id="details-profile-placeholder" class="h-full w-full flex items-center justify-center bg-gradient-to-br from-gray-500 to-gray-600">
                                            <svg class="h-10 w-10 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                        </div>
                                    </div>
                                    <button onclick="uploadProfilePicture()" class="absolute bottom-0 right-0 bg-white text-gray-600 p-1.5 rounded-full hover:bg-blue-600 hover:text-white transition-colors duration-200 shadow-lg border border-gray-200" title="Upload profile picture">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </button>
                                </div>
                                <div class="flex-1">
                                    <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                        <div><p class="text-xs text-gray-500">Full Name</p><p class="text-sm font-semibold text-gray-900" id="details-full-name">-</p></div>
                                        <div><p class="text-xs text-gray-500">Voter ID</p><p class="font-mono text-sm font-semibold text-gray-900" id="details-voter-id">-</p></div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                <div><p class="text-xs text-gray-500">District</p><p class="text-sm font-semibold text-gray-900" id="details-district">-</p></div>
                                <div><p class="text-xs text-gray-500">Status</p><div id="details-status" class="inline-flex"></div></div>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Contact Information</h4>
                                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                    <div><p class="text-xs text-gray-500">Email Address</p><p class="text-sm text-gray-900 break-all" id="details-email">-</p></div>
                                    <div><p class="text-xs text-gray-500">Phone Number</p><p class="text-sm text-gray-900" id="details-phone">-</p></div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Voting Information</h4>
                                <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                    <div><p class="text-xs text-gray-500">Verification Date</p><p class="text-sm text-gray-900" id="details-reg-date">-</p></div>
                                    <div><p class="text-xs text-gray-500">Voted Date/Time</p><p class="text-sm text-gray-900" id="details-last-updated">-</p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <button onclick="closeVoterDetailsModal()" class="flex-1 px-4 py-2.5 text-gray-700 font-medium border border-gray-300 rounded-lg hover:bg-gray-100 active:scale-95 transition-all duration-200">Close</button>
                    <button id="details-edit-btn" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 hover:shadow-lg active:scale-95 transition-all duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Edit Voter
                    </button>
                </div>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function uploadProfilePicture() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) { alert('Please select an image file'); return; }
        if (file.size > 5 * 1024 * 1024) { alert('File size must be less than 5MB'); return; }
        const reader = new FileReader();
        reader.onload = (e) => {
            const profilePic   = document.getElementById('details-profile-pic');
            const placeholder  = document.getElementById('details-profile-placeholder');
            if (profilePic && placeholder) { profilePic.src = e.target.result; profilePic.style.display = 'block'; placeholder.style.display = 'none'; }
        };
        reader.readAsDataURL(file);
    };
    input.click();
}

// ─── Export / Print ───────────────────────────────────────────────────────────
function exportToCSV() {
    if (filteredVoters.length === 0) { alert('No data to export.'); return; }
    const headers = ['ID', 'Full Name', 'Voter ID', 'District', 'Status', 'Email', 'Phone', 'Address'];
    const csvData = [
        headers.join(','),
        ...filteredVoters.map(voter => [
            voter.id, `"${getFullName(voter)}"`, voter.voterId, voter.district, voter.status,
            voter.email || '', formatPhone(voter.phone), `"${voter.address || ''}"`
        ].join(','))
    ].join('\n');
    const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.setAttribute('href', URL.createObjectURL(blob));
    link.setAttribute('download', `voter_masterlist_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportToPDF() {
    if (filteredVoters.length === 0) { alert('No data to export.'); return; }
    if (window.jspdf) { _generatePDF(); return; }
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
    script.onload = function() {
        const script2 = document.createElement('script');
        script2.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js';
        script2.onload = _generatePDF;
        script2.onerror = function() { alert('Failed to load PDF library. Check your connection.'); };
        document.head.appendChild(script2);
    };
    script.onerror = function() { alert('Failed to load PDF library. Check your connection.'); };
    document.head.appendChild(script);
}

function _generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    const pageWidth  = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    const today = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

    doc.setFillColor(22, 101, 52);
    doc.rect(0, 0, pageWidth, 28, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(16); doc.setFont('helvetica', 'bold');
    doc.text('BOHECO I ELECTION SYSTEM', pageWidth / 2, 11, { align: 'center' });
    doc.setFontSize(10); doc.setFont('helvetica', 'normal');
    doc.text('Voter Masterlist Report', pageWidth / 2, 18, { align: 'center' });
    doc.setFontSize(8);
    doc.text('Bohol I Electric Cooperative, Inc.', pageWidth / 2, 24, { align: 'center' });

    doc.setTextColor(55, 65, 81); doc.setFontSize(8); doc.setFont('helvetica', 'normal');
    const verifiedCount = filteredVoters.filter(v => v.status === 'Verified').length;
    const pendingCount  = filteredVoters.length - verifiedCount;
    doc.text(`Generated: ${today}`, 14, 35);
    doc.text(`Total: ${filteredVoters.length}  |  Verified: ${verifiedCount}  |  Pending: ${pendingCount}`, pageWidth - 14, 35, { align: 'right' });

    const filterParts = [];
    const si = document.getElementById('search-input');
    const df = document.getElementById('district-filter');
    const sf = document.getElementById('status-filter');
    if (si && si.value.trim()) filterParts.push(`Search: "${si.value.trim()}"`);
    if (df && df.value)        filterParts.push(`District: ${df.value}`);
    if (sf && sf.value)        filterParts.push(`Status: ${sf.value}`);
    if (filterParts.length) { doc.setFontSize(7); doc.setTextColor(107, 114, 128); doc.text('Filters: ' + filterParts.join(' · '), 14, 40); }

    const tableRows = filteredVoters.map((voter, i) => [i + 1, getFullName(voter), voter.voterId || '-', voter.district || '-', voter.email || '-', formatPhone(voter.phone), voter.status || '-']);
    doc.autoTable({
        startY: filterParts.length ? 44 : 40,
        head: [['#', 'Full Name', 'Voter ID', 'District', 'Email', 'Phone', 'Status']],
        body: tableRows,
        theme: 'grid',
        styles: { fontSize: 8, cellPadding: 3, textColor: [31, 41, 55], lineColor: [229, 231, 235], lineWidth: 0.3 },
        headStyles: { fillColor: [22, 101, 52], textColor: [255, 255, 255], fontStyle: 'bold', fontSize: 8 },
        alternateRowStyles: { fillColor: [249, 250, 251] },
        columnStyles: { 0: { halign: 'center', cellWidth: 10 }, 1: { cellWidth: 55 }, 2: { cellWidth: 28, font: 'courier' }, 3: { cellWidth: 28 }, 4: { cellWidth: 50 }, 5: { cellWidth: 32 }, 6: { cellWidth: 22, halign: 'center' } },
        didParseCell: function(data) {
            if (data.column.index === 6 && data.section === 'body') {
                if (data.cell.raw === 'Verified') { data.cell.styles.textColor = [22, 101, 52]; data.cell.styles.fontStyle = 'bold'; }
                else if (data.cell.raw === 'Pending') { data.cell.styles.textColor = [161, 98, 7]; data.cell.styles.fontStyle = 'bold'; }
            }
        },
        didDrawPage: function() {
            const pageCount = doc.internal.getNumberOfPages();
            const pageNum   = doc.internal.getCurrentPageInfo().pageNumber;
            doc.setFontSize(7); doc.setTextColor(156, 163, 175);
            doc.text(`Page ${pageNum} of ${pageCount}  ·  BOHECO I Election System  ·  Confidential`, pageWidth / 2, pageHeight - 6, { align: 'center' });
        },
    });
    doc.save(`voter_masterlist_${new Date().toISOString().split('T')[0]}.pdf`);
}

function printMasterlist() {
    if (filteredVoters.length === 0) { alert('No data to print.'); return; }
    const today         = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    const verifiedCount = filteredVoters.filter(v => v.status === 'Verified').length;
    const pendingCount  = filteredVoters.length - verifiedCount;
    const filterParts   = [];
    const si = document.getElementById('search-input');
    const df = document.getElementById('district-filter');
    const sf = document.getElementById('status-filter');
    if (si && si.value.trim()) filterParts.push(`Search: "${si.value.trim()}"`);
    if (df && df.value)        filterParts.push(`District: ${df.value}`);
    if (sf && sf.value)        filterParts.push(`Status: ${sf.value}`);
    const rows = filteredVoters.map((voter, i) => `<tr class="${i % 2 === 0 ? 'even' : 'odd'}"><td class="num">${i + 1}</td><td class="name">${getFullName(voter)}</td><td class="mono">${voter.voterId || '-'}</td><td>${voter.district || '-'}</td><td>${voter.email || '-'}</td><td>${formatPhone(voter.phone)}</td><td class="status ${voter.status === 'Verified' ? 'verified' : 'pending'}">${voter.status || '-'}</td></tr>`).join('');
    const win = window.open('', '_blank', 'width=1100,height=800');
    win.document.write(`<!DOCTYPE html><html><head><title>Voter Masterlist — BOHECO I</title><style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:"Segoe UI",Arial,sans-serif;font-size:11px;color:#1f2937;background:#fff}.header{background:linear-gradient(135deg,#166534,#059669);color:#fff;padding:18px 24px 14px;display:flex;justify-content:space-between;align-items:flex-end}.header-left h1{font-size:18px;font-weight:700;letter-spacing:.3px}.header-left p{font-size:10px;opacity:.85;margin-top:2px}.header-right{text-align:right;font-size:10px;opacity:.85;line-height:1.6}.meta{background:#f0fdf4;border-bottom:1px solid #bbf7d0;padding:8px 24px;display:flex;justify-content:space-between;align-items:center;font-size:10px;color:#374151}.meta .badges span{display:inline-block;margin-left:8px;padding:2px 8px;border-radius:999px;font-weight:600;font-size:9px}.badge-total{background:#dbeafe;color:#1d4ed8}.badge-verified{background:#dcfce7;color:#166534}.badge-pending{background:#fef9c3;color:#854d0e}.filters{padding:6px 24px;font-size:9px;color:#6b7280;border-bottom:1px solid #e5e7eb}.wrap{padding:16px 24px 24px}table{width:100%;border-collapse:collapse;font-size:10.5px}thead tr{background:#166534;color:#fff}thead th{padding:7px 10px;text-align:left;font-weight:600;font-size:9.5px;letter-spacing:.4px;text-transform:uppercase}tbody tr.even{background:#f9fafb}tbody tr.odd{background:#fff}tbody td{padding:6px 10px;border-bottom:1px solid #e5e7eb;vertical-align:middle}.num{text-align:center;width:32px;color:#9ca3af}.name{font-weight:600}.mono{font-family:"Courier New",monospace;font-size:10px}.status{font-weight:700;font-size:9.5px;text-align:center}.status.verified{color:#166534}.status.pending{color:#92400e}.footer{margin-top:20px;text-align:center;font-size:9px;color:#9ca3af;border-top:1px solid #e5e7eb;padding-top:10px}@media print{body{font-size:10px}.header{-webkit-print-color-adjust:exact;print-color-adjust:exact}thead tr{-webkit-print-color-adjust:exact;print-color-adjust:exact}tbody tr.even{-webkit-print-color-adjust:exact;print-color-adjust:exact}@page{margin:10mm;size:A4 landscape}}</style></head><body><div class="header"><div class="header-left"><h1>BOHECO I Election System</h1><p>Voter Masterlist Report &nbsp;·&nbsp; Bohol I Electric Cooperative, Inc.</p></div><div class="header-right"><div>Generated: ${today}</div><div>Confidential — For Internal Use Only</div></div></div><div class="meta"><div>Displaying <strong>${filteredVoters.length}</strong> voter${filteredVoters.length !== 1 ? 's' : ''}</div><div class="badges"><span class="badge-total">Total: ${filteredVoters.length}</span><span class="badge-verified">Verified: ${verifiedCount}</span><span class="badge-pending">Pending: ${pendingCount}</span></div></div>${filterParts.length ? `<div class="filters">Active filters: ${filterParts.join(' &nbsp;·&nbsp; ')}</div>` : ''}<div class="wrap"><table><thead><tr><th class="num">#</th><th>Full Name</th><th>Voter ID</th><th>District</th><th>Email</th><th>Phone</th><th style="text-align:center">Status</th></tr></thead><tbody>${rows}</tbody></table><div class="footer">BOHECO I Election System &nbsp;·&nbsp; Voter Masterlist &nbsp;·&nbsp; ${today} &nbsp;·&nbsp; Confidential</div></div><script>window.onload=function(){window.print()}<\/script></body></html>`);
    win.document.close();
}

function getFilteredVoters() {
    return filteredVoters;
}

// ─── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    loadMasterlists();

    const searchInput    = document.getElementById('search-input');
    const districtFilter = document.getElementById('district-filter');
    const statusFilter   = document.getElementById('status-filter');

    if (searchInput) {
        searchInput.addEventListener('input', debouncedFilter);
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { clearTimeout(searchDebounceTimer); filterVoters(); }
        });
    }
    if (districtFilter) districtFilter.addEventListener('change', filterVoters);
    if (statusFilter)   statusFilter.addEventListener('change',   filterVoters);

    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    if (prevBtn) prevBtn.addEventListener('click', prevPage);
    if (nextBtn) nextBtn.addEventListener('click', nextPage);

    const voterForm = document.getElementById('voterForm');
    if (voterForm) voterForm.addEventListener('submit', saveVoter);

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') { closeVoterModal(); closeVoterDetailsModal(); }
    });

    const voterModal = document.getElementById('voterModal');
    if (voterModal) voterModal.addEventListener('click', function(event) { if (event.target === this) closeVoterModal(); });
});

// ─── Global exports — must be before any inline onclick can fire ──────────────
window.openAddVoterModal      = function() {};   // stub — button is commented out
window.closeVoterModal        = closeVoterModal;
window.editVoter              = editVoter;
window.deleteVoter            = deleteVoter;
window.exportToCSV            = exportToCSV;
window.exportToPDF            = exportToPDF;
window.printMasterlist        = printMasterlist;
window.openVoterDetailsModal  = openVoterDetailsModal;
window.openVoterDetailsById   = openVoterDetailsById;
window.closeVoterDetailsModal = closeVoterDetailsModal;
window.uploadProfilePicture   = uploadProfilePicture;
window.filterVoters           = filterVoters;
window.closeEditVoterModal    = closeEditVoterModal;
window.prevPage               = prevPage;
window.nextPage               = nextPage;
window.loadMasterlists        = loadMasterlists;