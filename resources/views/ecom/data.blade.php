<x-ecom-layout>
    <div class="p-6 h-screen flex flex-col overflow-hidden">
        <!-- Header Section -->
        <div class="flex-shrink-0 mb-6">
            <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8 relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-green-500/20 to-transparent rounded-full -translate-y-32 translate-x-32">
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
                                        placeholder="Search..."
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-white focus:ring-3 focus:ring-blue-500/20 focus:border-blue-500 shadow-sm transition-all duration-300 hover:border-blue-400">
                                </div>
                            </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <select id="voted-filter"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none">
                                    <option value="">All Voters</option>
                                    <option value="Online">Voted Online</option>
                                    <option value="Onsite">Voted Onsite</option>
                                </select>
                            </div>

                                <div class="relative">
                                    <select id="status-filter"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none">
                                        <option value="">All Status</option>
                                        <option value="verified">Verified</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="flex-1 min-h-0 bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden flex flex-col">
            <div class="flex-shrink-0">
                <table class="min-w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-600 to-emerald-600">
                        <tr>
                            <th scope="col" class="w-[8%] px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Profile</th>
                            <th scope="col" class="w-[25%] px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Name</th>
                            <th scope="col" class="w-[15%] px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">ID Number</th>
                            <th scope="col" class="w-[15%] px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Date/Time Voted</th>
                            <th scope="col" class="w-[22%] px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Voted by</th>
                            <th scope="col" class="w-[15%] px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="flex-1 overflow-y-auto" id="tableScrollContainer">
                <table class="min-w-full table-fixed divide-y divide-gray-200">
                    <colgroup>
                        <col class="w-[8%]">  <col class="w-[25%]"> <col class="w-[15%]"> <col class="w-[15%]"> <col class="w-[22%]"> <col class="w-[15%]"> </colgroup>
                    <tbody class="bg-white divide-y divide-gray-100" id="votersTable">
                        </tbody>
                </table>
                
                <div id="noDataTemplate" class="hidden">
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="h-20 w-20 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">No voters found</h4>
                        <p class="text-gray-500 text-center px-4">Try adjusting your search or filter to find what you're looking for.</p>
                    </div>
                </div>
            </div>

            <div class="flex-shrink-0 bg-white px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">20</span> of <span class="font-medium">20</span> voters
                    </div>
                    <div class="flex space-x-2">
                        <button id="prevPageButton" class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>
                        <button id="pageNumberButton" class="px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            1
                        </button>
                        <button id="nextPageButton" class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="voterModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center pb-3 border-b border-gray-150 mb-4">
                <h3 class="text-2xl font-bold text-gray-900">Voter Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="space-y-4"></div>
            <div class="flex justify-end pt-6">
                <button onclick="closeModal()" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 mr-3">Close</button>
                <button onclick="verifyVoter()" id="verifyButton" class="px-6 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600">Verify</button>
            </div>
        </div>
    </div>

    <div id="editIDModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-xl font-bold text-gray-900">Add Valid ID</h3>
                <button onclick="closeIDModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="editIDModalContent" class="space-y-4"></div>
            <div class="flex justify-end pt-6">
                <button onclick="closeIDModal()" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 mr-3">Cancel</button>
                <button onclick="saveID()" id="saveIDButton" class="px-6 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600">Add ID</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 id="editModalTitle" class="text-xl font-bold text-gray-900">Edit Information</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="editModalContent" class="space-y-4"></div>
            <div class="flex justify-end pt-6">
                <button onclick="closeEditModal()" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 mr-3">Cancel</button>
                <button onclick="saveEdit()" id="saveEditButton" class="px-6 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600">Save Changes</button>
            </div>
        </div>
    </div>

    <style>
        #voterModal, #editModal, #editIDModal {
            transition: opacity 0.3s ease;
        }

        tr[data-voter-id] {
            cursor: pointer;
        }

        #tableScrollContainer::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        #tableScrollContainer::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        #tableScrollContainer::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        #tableScrollContainer::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>

    @vite('resources/js/ecom-data.js')
</x-ecom-layout>