let selectedVoters = new Set();
let currentVoterId = null;
let currentEditField = null;
let voterIDs = {
    1: [{
        type: "Consumer ID",
        number: "00989876866"
    }],
    2: [{
        type: "Consumer ID",
        number: "09877996588"
    }],
    3: [{
        type: "Consumer ID",
        number: "00976556788"
    }]
};

const validIDOptions = [{
    id: "philsys",
    name: "Philsys ID"
},
{
    id: "driver",
    name: "Driver License"
},
{
    id: "passport",
    name: "Passport"
},
{
    id: "umid",
    name: "UMID"
},
{
    id: "sss",
    name: "SSS"
},
{
    id: "gsis",
    name: "GSIS"
},
{
    id: "prc",
    name: "PRC ID"
},
{
    id: "voter",
    name: "Voter's ID"
},
{
    id: "nbi",
    name: "NBI Clearance"
}
]

let votersData = [];
let nextCursor = null;

function renderVotersTable(voters) {
    const table = document.getElementById('votersTable');
    table.innerHTML = '';

    voters.forEach(voter => {

        const fullName = `${voter.first_name} ${voter.middle_name ?? ''} ${voter.last_name}`;

        const statusBadge = voter.is_verified
                    ? `<span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Verified</span>`
                    : `<span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>`;


        const district = voter.district ? `District ${voter.district}` : 'No district';

        const row = `
            <tr onclick="viewVoterDetails('${voter.member_id}')"
                class="hover:bg-gray-50 transition-colors duration-150 cursor-pointer">

                <td class="px-6 py-4">
                    <input type="checkbox"
                        class="voter-checkbox h-4 w-4 rounded border-gray-300 text-green-600"
                        value="${voter.member_id}">
                </td>

                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${fullName}</div>
                    <div class="text-sm text-gray-500">${district}</div>
                </td>

                <td class="px-6 py-4 font-mono">
                    ${voter.member_id}
                </td>

                <td class="px-6 py-4">
                    -
                </td>

                <td class="px-6 py-4">
                   ${statusBadge}
                </td>

                <td class="px-6 py-4">
                    -
                </td>
            </tr>
        `;

        table.insertAdjacentHTML('beforeend', row);
    });
}

async function loadVoters(params = {}) {
    try {
        const query = new URLSearchParams(params).toString();

        const response = await fetch(`/api/members?${query}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'include' 
        });

        if (!response.ok) {
            throw new Error('Failed to fetch voters');
        }

        const data = await response.json();

        votersData = data.data;
        console.log('Fetched voters:', votersData);
        nextCursor = data.meta?.next_cursor ?? null;

        renderVotersTable(votersData);

    } catch (error) {
        console.error(error);
        alert('Error loading voters');
    }
}


document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    const voterCheckboxes = document.querySelectorAll('.voter-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            voterCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
                if (selectAll.checked) {
                    selectedVoters.add(checkbox.value);
                } else {
                    selectedVoters.delete(checkbox.value);
                }
            });
        });
    }

    voterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function (e) {
            e.stopPropagation();
            if (this.checked) {
                selectedVoters.add(this.value);
            } else {
                selectedVoters.delete(this.value);
            }
            updateSelectAllState();
        });
    });

    voterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    const searchInput = document.getElementById('search-input');
    const votedFilter = document.getElementById('voted-filter');
    const statusFilter = document.getElementById('status-filter');

    if (searchInput) {
        searchInput.addEventListener('input', filterVoters);
    }

    if (votedFilter) {
        votedFilter.addEventListener('change', filterVoters);
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', filterVoters);
    }

    loadVoters({
        per_page: 20
    });
});

function updateSelectAllState() {
    const selectAll = document.getElementById('select-all');
    const voterCheckboxes = document.querySelectorAll('.voter-checkbox');
    const checkedCount = Array.from(voterCheckboxes).filter(cb => cb.checked).length;

    if (selectAll) {
        selectAll.checked = checkedCount === voterCheckboxes.length;
        selectAll.indeterminate = checkedCount > 0 && checkedCount < voterCheckboxes.length;
    }
}

function exportToCSV() {
    const rows = document.querySelectorAll('#votersTable tr');
    let csvContent = "data:text/csv;charset=utf-8";
    csvContent += "Name,District,ID Number,Date Voted,Time Voted,Remarks,Status\n";

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const name = cells[1]?.querySelector('.text-sm.font-medium')?.textContent || '';
            const district = cells[1]?.querySelector('.text-sm.text-gray-500')?.textContent || '';
            const idNumber = cells[2]?.textContent.trim() || '';
            const dateVoted = cells[3]?.querySelector('div:first-child')?.textContent || '';
            const timeVoted = cells[3]?.querySelector('div:last-child')?.textContent || '';
            const remarks = cells[4]?.querySelector('span')?.textContent || '';
            const status = cells[5]?.querySelector('span')?.textContent || '';

            csvContent +=
                `"${name}", "${district}", "${idNumber}", "${dateVoted}", "${timeVoted}", "${remarks}", "${status}"\n`;
        }
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "voters_list.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function viewVoterDetails(voterId) {
    currentVoterId = voterId;
    const modal = document.getElementById('voterModal');
    const modalContent = document.getElementById('modalContent');

    const voter = votersData.find(v => v.member_id === voterId);
    if (!voter) return;

    const voterIDList = voterIDs[voterId] || [];

    modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-20 w-20 rounded-full border-4 border-green-100 bg-gray-200 
                                flex items-center justify-center overflow-hidden">
                                </div>
                                <button onclick="uploadProfilePicture(${voterId})" class="absolute bottom-0 right-0 bg-gray-300 text-gray-400 p-1 rounded-full hover:bg-green-600 transition-colors duration-200" title="Upload profile picture">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">${voter.first_name}</h4>
                                <p class="text-gray-600">District ${voter.district}</p>
                                <span class="mt-1 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${voter.is_verified === true ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}"> ${voter.is_verified ? 'Verified' : 'Not Verified'} </span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-1">Personal Information</h5>
                                <div class="space-y-3">

                                    <div class="group">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs text-gray-500">ID Number</p>
                                                <p class="text-sm font-medium">${voter.member_id}</p>
                                            </div>
                                            <button onclick="editIDNumber(${voterId})" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        ${voterIDList.map((id, index) => `
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <div class="flex items-center space-x-2 mb-1">
                                                                
                                                                <span class="text-sm font-medium text-gray-700">
                                                                    ${id.type}
                                                                </span>
                                                            </div>
                                                            <p class="text-xs text-gray-500"> ${id.number}</p>
                                                            ${id.dateAdded ? `<p class="text-xs text-gray-400 mt-1">Added: ${id.Added}</p>` : ''}
                                                        </div>
                                                            <button onclick="removeID(${voterId}, ${index})" class="text-gray-400 hover:text-red-600 p-1">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                    </div>
                                                </div>
                                            `).join('')}
                                    </div>

                                    <div class="group">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs text-gray-500">Birthdate</p>
                                                <p class="text-sm font-medium">${voter.birth_date}</p>
                                            </div>
                                            <button onclick="editBirthdate(${voterId})" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h5 class="text-sm font-medium text-gray-500 mb-2">Contact Information</h5>
                            <div class="space-y-2">
                                <div class="group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-sm">${voter.email}</span>
                                        </div>
                                        <button onclick="editEmail(${voterId})" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span class="text-sm">${voter.contact_number}</span>
                                        </div>
                                        <button onclick="editPhone(${voterId})" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>      
                                </div>
                            </div>
                        </div>
                        
                        <div class="group">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="text-sm font-medium text-gray-500">Address</h5>
                                <button onclick="editAddress(${voterId})" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-sm">${voter.address}</p>
                        </div>
                        
                        <div>
                            <h5 class="text-sm font-medium text-gray-500 mb-2">Voting Information</h5>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-500">Voting Method</p>
                                    <p class="text-sm font-medium">
                                        ${voter.votingMethod ? `<span class="px-2 py-1 text-xs font-medium ${voter.votingMethod === 'Voted Online' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'} rounded-full">${voter.votingMethod}</span>` : 'Not yet voted'}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Verification Date</p>
                                    <p class="text-sm font-medium">${voter.verificationDate || 'Not verified'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                ${voter.votedDate !== 'Not yet voted' ? `
                        <div class="mt-6 p-4 bg-green-50 rounded-xl">
                            <h5 class="text-sm font-medium text-green-800 mb-2">Voting Record</h5>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <p class="text-xs text-green-600">Date Voted</p>
                                    <p class="text-sm font-medium text-green-900">${voter.votedDate}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-green-600">Time Voted</p>
                                    <p class="text-sm font-medium text-green-900">${voter.votedTime}</p>
                                </div>
                            
                                <button onclick="printQrCode(${voterId})" id="printQr" class="items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    <span>Print Qr Code</span>
                                </button>
                            </div>
                        </div>
                        ` : ''}
            `;

    const printQr = document.getElementById('printQr');
    if (voter.status === 'Verified') {
        printQr.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Print Qr Code</span>
                `;
        printQr.textContent = 'Print Qr Code';
        printQr.disabled = false;
        printQr.classList.remove('bg-gray-300', 'cursor-not-allowed');
        printQr.classList.add('bg-green-500', 'hover:bg-green-600');
    } else {
        printQr.textContent = 'Print Qr Code';
        printQr.disabled = true;
        printQr.classList.remove('bg-green-500', 'hover:bg-green-600');
        printQr.classList.add('bg-gray-300', 'cursor-not-allowed');
    }

    const verifyButton = document.getElementById('verifyButton');
    if (voter.status === 'Verified') {
        verifyButton.textContent = 'Already Verified';
        verifyButton.disabled = true;
        verifyButton.classList.remove('bg-green-500', 'hover:bg-green-600');
        verifyButton.classList.add('bg-gray-300', 'cursor-not-allowed');
    } else {
        verifyButton.textContent = 'Verify';
        verifyButton.disabled = false;
        verifyButton.classList.remove('bg-gray-300', 'cursor-not-allowed');
        verifyButton.classList.add('bg-green-500', 'hover:bg-green-600');
    }

    modal.classList.remove('hidden');
    modal.classList.add('block');
}

function uploadProfilePicture() {
    alert('Upload profile picture code here')
}

function printQrCode() {
    alert('Printing QR Code again');
}

function closeModal() {
    const modal = document.getElementById('voterModal');
    modal.classList.remove('block');
    modal.classList.add('hidden');
    currentVoterId = null;
}

function closeIDModal() {
    const editIDModal = document.getElementById('editIDModal');
    if (editIDModal) {
        editIDModal.classList.remove('block');
        editIDModal.classList.add('hidden');
        currentEditField = null;
    }
}

function closeEditModal() {
    const editModal = document.getElementById('editModal');
    editModal.classList.remove('block');
    editModal.classList.add('hidden');
    currentEditField = null;
}

function editIDNumber(voterId) {
    currentEditField = voterId;
    const editIDModal = document.getElementById('editIDModal');
    const editIDModalContent = document.getElementById('editIDModalContent');

    editIDModalContent.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-3">Select a valid ID type to add:</p>
                        <div class="grid grid-cols-2 gap-3">
                            ${validIDOptions.map(id => `
                                        <button onclick="selectIDType('${id.id}', '${id.name}')" class="flex items-center space-x-3 p-3 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-left">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${id.name}</p>
                                        </div>
                                        </button>
                                    `).join('')}
                        </div>
                    </div>

                    <div id="idInputSection" class="hidden">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" id="idTypeLabel"></label>
                                <input type="text" id="idNumberInput" placeholder="Enter ID Number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                    </div>
                </div>
            `;

    editIDModal.classList.remove('hidden');
    editIDModal.classList.remove('block');
}

function selectIDType(id, name) {
    document.getElementById('idTypeLabel').textContent = `${name} Number`;
    document.getElementById('idInputSection').classList.remove('hidden');

    setTimeout(() => {
        document.getElementById('idNumberInput').focus();
    }, 100);
}

function saveID() {
    const idNumberInput = document.getElementById('idNumberInput');
    const idTypeLabel = document.getElementById('idTypeLabel');

    if (!idNumberInput || !idTypeLabel) {
        alert('Error: Could not find input elements');
        return;
    }

    const idNumber = idNumberInput.value;
    const idType = idTypeLabel.textContent.replace(' Number', '').trim();

    if (!idNumber.trim()) {
        alert('Please enter an ID number');
        return;
    }

    if (!currentEditField) {
        alert('Error: No voter selected');
        return;
    }

    if (!voterIDs[currentEditField]) {
        voterIDs[currentEditField] = [];
    }

    voterIDs[currentEditField].push({
        type: idType,
        number: idNumber,
        dateAdded: new Date().toISOString().split('T')[0]
    });

    alert(`${idType} added successfully`);
    closeIDModal();

    if (currentEditField) {
        viewVoterDetails(currentEditField);
    }
}


function removeID(voterId, index) {
    if (confirm('Are you sure you want to remove this ID?')) {
        if (voterIDs[voterId] && voterIDs[voterId][index]) {
            const removedID = voterIDs[voterId][index];
            voterIDs[voterId].splice(index, 1);
            alert(`Removed ${removedID.type}`);

            viewVoterDetails(voterId);
        }
    }
}

function editBirthdate(voterId) {
    currentEditField = 'birthdate';
    const voter = votersData.find(v => v.member_id === voterId);
    openEditModal('Edit Birthdate', 'Enter new birthdate (YYYY-MM-DD):', voter.birthdate);
}

function editEmail(voterId) {
    currentEditField = 'email';
    const voter = votersData.find(v => v.member_id === voterId);
    openEditModal('Edit Email', 'Enter new email:', voter.email);
}

function editPhone(voterId) {
    currentEditField = 'phone';
    const voter = votersData.find(v => v.member_id === voterId);
    openEditModal('Edit Phone Number', 'Enter new phone number:', voter.phone);
}

function editAddress(voterId) {
    currentEditField = 'address';
    const voter = votersData.find(v => v.member_id === voterId);
    openEditModal('Edit Address', 'Enter new address:', voter.address);
}

function openEditModal(title, label, currentValue) {
    const editModal = document.getElementById('editModal');
    const editModalTitle = document.getElementById('editModalTitle');
    const editModalContent = document.getElementById('editModalContent');

    editModalTitle.textContent = title;
    editModalContent.innerHTML = `
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">${label}</label>
                        <input type="text" id="editInput" value="${currentValue}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            `;

    editModal.classList.remove('hidden');
    editModal.classList.add('block');

    setTimeout(() => {
        document.getElementById('editInput').focus();
    }, 100);
}

function saveEdit() {
    const newValue = document.getElementById('editInput').value;

    if (!newValue.trim()) {
        alert('Please enter a value');
        return;
    }

    alert(`Updated ${currentEditField} to: ${newValue}`);

    if (currentVoterId) {
        viewVoterDetails(currentVoterId);
    }

    closeEditModal();
}

function verifyVoter() {
    if (!currentVoterId) return;

    if (confirm(`Are you sure you want to verify this voter?`)) {
        const voterIndex = votersData.findIndex(v => v.member_id === currentVoterId);
        if (voterIndex !== -1) {
            votersData[voterIndex].status = 'Verified';
            votersData[voterIndex].verificationDate = new Date().toISOString().split('T')[0];
        }

        alert(`Voter has been verified!`);

        const row = document.querySelector(`[data-voter-id="${currentVoterId}"]`);
        if (row) {
            const statusCell = row.querySelector('.inline-flex');
            if (statusCell) {
                statusCell.innerHTML =
                    '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>';
            }
        }

        viewVoterDetails(currentVoterId);
    }
}

function filterVoters() {
    const search = document.getElementById('search-input').value;

    loadVoters({
        search: search,
        per_page: 20
    });
}

function updateTable(filteredVoters) {
    const tableBody = document.getElementById('votersTable');
    const showingText = document.querySelector('.text-sm.text-gray-700');

    if (!tableBody) return;

    tableBody.innerHTML = '';

    filteredVoters.forEach(voter => {
        const row = document.createElement('tr');
        row.setAttribute('data-voter-id', voter.id);
        row.setAttribute('onclick', `viewVoterDetails(${voter.id})`);
        row.className = 'hover:bg-gray-50 transition-colors duration-150 cursor-pointer';

        row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="checkbox" class="voter-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500" value="${voter.id}">
                        <div class="h-10 w-10 flex-shrink-0 ml-3">
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-left">
                        <div class="text-sm font-medium text-gray-900">
                            ${voter.name}
                        </div>
                        <div class="text-sm text-gray-500">
                            ${voter.district}
                        </div>
                    </div>
                </td>
                <td class="px-6 p-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 font-mono">
                        ${voter.idNumber}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${voter.votedDate ? `
                        <div class="text-sm text-gray-900">${voter.votedDate}</div>
                        <div class="text-sm text-gray-500">${voter.votedTime}</div>
                        ` : ''}
                </td>
                <td class="px-6 py-4">
                    ${voter.votingMethod ? `
                        <div class="text-sm text-gray-900">
                            <span class="px-2 py-1 text-xs font-medium ${voter.votingMethod === 'Voted Online' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'} rounded-full">
                                ${voter.votingMethod}
                            </span>
                        </div>
                        ` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${voter.status === 'Verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                        ${voter.status}
                    </span>
                </td>
                `;

        tableBody.appendChild(row);
    });

    const voterCheckboxes = tableBody.querySelectorAll('.voter-checkbox');
    voterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function (e) {
            e.stopPropagation();
            if (this.checked) {
                selectedVoters.add(this.value);
            } else {
                selectedVoters.delete(this.value);
            }
            updateSelectAllState();
        });

        checkbox.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    if (showingText) {
        showingText.innerHTML = `
                Showing <span class="font-medium">${Math.min(1, filteredVoters.length)}</span>
                to <span class="font-medium">${filteredVoters.length}</span> of
                <span class="font-medium">${filteredVoters.length}</span> voters
                `;
    }
}

window.viewVoterDetails = viewVoterDetails;
window.closeModal = closeModal;
window.closeIDModal = closeIDModal;
window.closeEditModal = closeEditModal;
window.exportToCSV = exportToCSV;
window.verifyVoter = verifyVoter;
window.saveID = saveID;
window.saveEdit = saveEdit;
window.editIDNumber = editIDNumber;
window.editBirthdate = editBirthdate;
window.editEmail = editEmail;
window.editPhone = editPhone;
window.editAddress = editAddress;
window.uploadProfilePicture = uploadProfilePicture;
window.printQrCode = printQrCode;
window.removeID = removeID;
window.selectIDType = selectIDType;