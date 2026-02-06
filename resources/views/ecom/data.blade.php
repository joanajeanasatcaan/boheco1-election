<x-ecom-layout>
    <div class="p-6">
        <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-2xl shadow-xl overflow-hidden">
            <div class="md:p-8 relative">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-green-500/20 to-transparent rounded-full -translate-y-32 translate-x-32">
                </div>
                <div class="relative">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-6 md:mb-0">
                            <h1 class="text-lg md:text-4xl font-bold text-white mb-2 drop-shadow">
                                {{ __('District Election Voters') }}
                            </h1>
                            <p class="text-sm text-green-100/90 max-w-2xl">
                                {{ __('Manage and monitor all registered voters in the district.') }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-3">
                            <button onclick="exportToCSV()"
                                class="group inline-flex items-center justify-center px-4 py-3 bg-white text-green-700 font-semibold rounded-xl hover:bg-green-50 active:scale-[0.98] transition-all duration-200 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-700">
                                <x-export-logo class="h-5 w-5 mr-2" />
                                Export List
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="md:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search-input"
                                    value="{{ request('search', '') }}"
                                    placeholder="Search voter name, ID, or district..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-white focus:ring-3 focus:ring-blue-500/20 focus:border-blue-500 shadow-sm transition-all duration-300 hover:border-blue-400">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <select id="voted-filter"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none">
                                    <option value="">All Voters</option>
                                    <option value="Voted Online">Voted Online</option>
                                    <option value="Voted Physically">Voted Physically</option>
                                </select>
                            </div>

                            <div class="relative">
                                <select id="status-filter"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none">
                                    <option value="">All Status</option>
                                    <option value="Verified">Verified</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="voterModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-2xl bg-white">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-2xl font-bold text-gray-900">Voter Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="modalContent" class="space-y-4">

                </div>

                <div class="flex justify-end pt-6">
                    <button onclick="closeModal()"
                        class="px-6 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 mr-3">
                        Close
                    </button>
                    <button onclick="verifyVoter()" id="verifyButton"
                        class="px-6 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600">
                        Verify
                    </button>
                </div>
            </div>
        </div>

        <div id="editIDModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-2xl bg-white">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-xl font-bold text-gray-900">
                        Add Valid ID
                    </h3>
                    <button onclick="closeIDModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="editIDModalContent" class="space-y-4">
                </div>
                <div class="flex justify-end pt-6">
                    <button onclick="closeIDModal()"
                        class="px-6 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 mr-3">
                        Cancel
                    </button>
                    <button onclick="saveID()" id="saveIDButton"
                        class="px-6 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600">
                        Add ID
                    </button>
                </div>
            </div>
        </div>

        <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-2xl bg-white">
                <div class="flex justify-between items-center pb-3">
                    <h3 id="editModalTitle" class="text-xl font-bold text-gray-900">Edit Information</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="editModalContent" class="space-y-4">

                </div>

                <div class="flex justify-end pt-6">
                    <button onclick="closeEditModal()"
                        class="px-6 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 mr-3">
                        Cancel
                    </button>
                    <button onclick="saveEdit()" id="saveEditButton"
                        class="px-6 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-600 to-emerald-600">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="select-all"
                                        class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    Profile
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Name
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    ID Number
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Date/Time Voted
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Remarks
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Status
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" id="votersTable">
                        <tr onclick="viewVoterDetails(1)"
                            class="hover:bg-gray-50 transition-colors duration-150 cursor-pointer" data-voter-id="1">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                        class="voter-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                        value="1">
                                    <div class="h-10 w-10 flex-shrink-0 ml-3">

                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-left">
                                    <div class="text-sm font-medium text-gray-900">John Kenneth Lebita</div>
                                    <div class="text-sm text-gray-500">District 1-Tubigon</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-mono">000987654321</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">2024-03-15</div>
                                <div class="text-sm text-gray-500">14:30 PM</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        Voted Online
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Verified
                                </span>
                            </td>
                        </tr>

                        <tr onclick="viewVoterDetails(2)"
                            class="hover:bg-gray-50 transition-colors duration-150 cursor-pointer" data-voter-id="2">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                        class="voter-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                        value="2">
                                    <div class="h-10 w-10 flex-shrink-0 ml-3">

                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-left">
                                    <div class="text-sm font-medium text-gray-900">Chris Marie Calesa</div>
                                    <div class="text-sm text-gray-500">District 1-Clarin</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-mono">009897656543</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                            </td>
                            <td class="px-6 py-4">

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </td>
                        </tr>

                        <tr onclick="viewVoterDetails(3)"
                            class="hover:bg-gray-50 transition-colors duration-150 cursor-pointer" data-voter-id="3">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                        class="voter-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                        value="3">
                                    <div class="h-10 w-10 flex-shrink-0 ml-3">

                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-left">
                                    <div class="text-sm font-medium text-gray-900">Aira Lebita</div>
                                    <div class="text-sm text-gray-500">District 1-Loon</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-mono">009098767389</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">2025-09-23</div>
                                <div class="text-sm text-gray-500">08:09 AM</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <span
                                        class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Voted
                                        Physically</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Verified
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span
                            class="font-medium">3</span> voters
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Previous
                        </button>
                        <button
                            class="px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            1
                        </button>
                        <button
                            class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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

            const votersData = [
        {
            id: 1,
            name: "John Kenneth Lebita",
            district: "District 1-Tubigon",
            idNumber: "000987654321",
            email: "john.lebita@example.com",
            phone: "+63 912 345 6789",
            birthdate: "1990-05-15",
            address: "123 Purok 1, Tubigon, Bohol",
            votedDate: "2024-03-15",
            votedTime: "14:30 PM",
            votingMethod: "Voted Online",
            status: "Verified",
            verificationDate: "2024-01-10"
        },
        {
            id: 2,
            name: "Chris Marie Calesa",
            district: "District 1-Clarin",
            idNumber: "009897656543",
            email: "chris.calesa@example.com",
            phone: "+63 923 456 7890",
            birthdate: "1988-11-22",
            address: "456 Purok 2, Clarin, Bohol",
            votedDate: "",
            votedTime: "",
            votingMethod: "",
            status: "Pending",
            verificationDate: ""
        },
        {
            id: 3,
            name: "Aira Lebita",
            district: "District 1-Loon",
            idNumber: "009098767389",
            email: "aira.lebita@example.com",
            phone: "+63 934 567 8901",
            birthdate: "1995-03-08",
            address: "789 Purok 3, Loon, Bohol",
            votedDate: "2025-09-23",
            votedTime: "08:09 AM",
            votingMethod: "Voted Physically",
            status: "Verified",
            verificationDate: "2024-03-05"
        }
    ];


        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const voterCheckboxes = document.querySelectorAll('.voter-checkbox');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
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
                checkbox.addEventListener('change', function(e) {
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
                checkbox.addEventListener('click', function(e) {
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

                    csvContent += `"${name}", "${district}", "${idNumber}", "${dateVoted}", "${timeVoted}", "${remarks}", "${status}"\n`;
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

            const voter = votersData.find(v => v.id === voterId);
            if (!voter) return;

            const voterIDList = voterIDs[voterId] || [];

            modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-20 w-20 rounded-full border-4 border-green-100 bg-gray-200 flex items-center justify-center overflow-hidden">
                                </div>
                                <button onclick="uploadProfilePicture(${voterId})" class="absolute bottom-0 right-0 bg-gray-300 text-gray-400 p-1 rounded-full hover:bg-green-600 transition-colors duration-200" title="Upload profile picture">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">${voter.name}</h4>
                                <p class="text-gray-600">${voter.district}</p>
                                <span class="mt-1 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${voter.status === 'Verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}"> ${voter.status} </span>
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
                                                <p class="text-sm font-medium">${voter.idNumber}</p>
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
                                                <p class="text-sm font-medium">${voter.birthdate}</p>
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
                                            <span class="text-sm">${voter.phone}</span>
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
                printQr.innerHTML =`
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
            alert ('Upload profile picture code here')
        }

        function printQrCode() {
            alert ('Printing QR Code again');
        }

        function closeModal() {
            const modal = document.getElementById('voterModal');
            modal.classList.remove('block');
            modal.classList.add('hidden');
            currentVoterId = null;
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
                alert ('Please enter an ID number');
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
            const voter = votersData.find(v => v.id === voterId);
            openEditModal('Edit Birthdate', 'Enter new birthdate (YYYY-MM-DD):', voter.birthdate);
        }

        function editEmail(voterId) {
            currentEditField = 'email';
            const voter = votersData.find(v => v.id === voterId);
            openEditModal('Edit Email', 'Enter new email:', voter.email);
        }

        function editPhone(voterId) {
            currentEditField = 'phone';
            const voter = votersData.find(v => v.id === voterId);
            openEditModal('Edit Phone Number', 'Enter new phone number:', voter.phone);
        }

        function editAddress(voterId) {
            currentEditField = 'address';
            const voter = votersData.find(v => v.id === voterId);
            openEditModal('Edit Address', 'Enter new address:', voter.address );
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

        function closeEditModal() {
            const editModal = document.getElementById('editModal');
            editModal.classList.remove('block');
            editModal.classList.add('hidden');
            currentEditField = null;
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
                const voterIndex = votersData.findIndex(v => v.id === currentVoterId);
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
            const searchInput = document.getElementById('search-input');
            const votedFilter = document.getElementById('voted-filter');
            const statusFilter = document.getElementById('status-filter');

            if(!searchInput || !votedFilter || !statusFilter) return;

            const searchTerm = searchInput.value.toLowerCase();
            const votedValue = votedFilter.value;
            const statusValue = statusFilter.value;

            const filteredVoters = votersData.filter(voter => {
                const matchesSearch = !searchTerm ||
                voter.name.toLowerCase().includes(searchTerm) ||
                voter.idNumber.toLowerCase().includes(searchTerm) ||
                voter.district.toLowerCase().includes(searchTerm);

                let matchesVoted = true;
                if (votedValue === 'Voted Online') {
                    matchesVoted = voter.votingMethod === 'Voted Online'
                } else if (votedValue === 'Voted Physically') {
                    matchesVoted = voter.votingMethod === 'Voted Physically';
                }

                const matchesStatus = !statusValue || voter.status === statusValue;

                return matchesSearch && matchesVoted && matchesStatus;
            });

            updateTable(filteredVoters);
        }

        function updateTable(filteredVoters) {
            const tableBody = document.getElementById('votersTable');
            const showingText = document.querySelector('.text-sm.text-gray-700');

            if (!tableBody) return;

            tableBody.innerHTML = '';

            filteredVoters.forEach(voter => {
                const row = document.createElement('tr');
                row.setAttribute('data-voter-id', voter.id);
                row.setAttribute('onclick',`viewVoterDetails(${voter.id})`);
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
                checkbox.addEventListener('change', function(e) {
                    e.stopPropagation();
                    if (this.checked) {
                        selectedVoters.add(this.value);
                    } else {
                        selectedVoters.delete(this.value);
                    }
                    updateSelectAllState();
                });

                checkbox.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });

            if (showingText) {
                showingText.innerHTML =`
                Showing <span class="font-medium">${Math.min(1, filteredVoters.length)}</span>
                to <span class="font-medium">${filteredVoters.length}</span> of
                <span class="font-medium">${filteredVoters.length}</span> voters
                `;
            }
        }
    </script>

    <style>
        #voterModal,
        #editModal {
            transition: opacity 0.3s ease;
        }

        input[type="checkbox"]:indeterminate {
            background-color: #10b981;
            border-color: #10b981;
        }

        tr[data-voter-id] {
            cursor: pointer;
        }
    </style>
</x-ecom-layout>
