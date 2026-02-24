<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Masterlists') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Voter lists management across all election districts.
                </p>
            </div>
            <div class="text-sm text-gray-500">
                Last updated: {{ now()->format('M j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8 relative">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-green-500/20 to-transparent rounded-full -translate-y-32 translate-x-32"></div>
                        <div class="relative">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-6 md:mb-0">
                                    <h1 class="md:text-4xl font-bold text-white mb-2 drop-shadow">
                                        {{ __('Voter Masterlists') }}
                                    </h1>
                                    <p class="text-sm text-green-100/90 max-w-2xl">
                                        {{ __('Voter lists management across all election districts.') }}
                                    </p>
                                </div>
                                <button onclick="openAddVoterModal()"
                                    class="group text-sm inline-flex items-center 
                                    justify-center px-6 py-3 bg-white text-green-700 
                                    font-semibold rounded-xl hover:bg-green-50 
                                    active:scale-[0.98] transition-all duration-200 
                                    shadow-lg hover:shadow-xl focus:outline-none 
                                    focus:ring-2 focus:ring-white focus:ring-offset-2 
                                    focus:ring-offset-green-700">
                                    <x-plus-logo class="h-5 w-5 mr-2 group-hover:rotate-90 transition-transform" />
                                    Add Voter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 font-medium mb-1">Total Voters</p>
                            <h3 class="text-3xl font-bold text-gray-900" id="total-voters">0</h3>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center shadow-sm">
                            <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 font-medium mb-1">Verified Voters</p>
                            <h3 class="text-3xl font-bold text-gray-900" id="verified-voters">0</h3>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center shadow-sm">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 font-medium mb-1">Districts Covered</p>
                            <h3 class="text-3xl font-bold text-gray-900" id="districts-covered">0</h3>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center shadow-sm">
                            <svg class="h-6 w-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM4 10a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

       
            <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input type="text" id="search-input" 
                                placeholder="Search by name, voter ID, or district..."
                                class="w-full px-4 py-3 pl-12 pr-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 placeholder-gray-400 text-gray-700">
                            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative">
                        <select id="district-filter" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none">
                            <option value="">All Districts</option>
                            <option value="District 1">District 1</option>
                            <option value="District 2">District 2</option>
                            <option value="District 3">District 3</option>
                            <option value="District 4">District 4</option>
                        </select>
                    </div>

                    <div class="relative">
                        <select id="status-filter" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 
                            bg-white text-gray-700 font-medium focus:outline-none 
                            focus:ring-2 focus:ring-green-500 focus:border-transparent 
                            appearance-none">
                            <option value="">All Status</option>
                            <option value="Verified">Verified</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3 mt-4">
                    <button onclick="exportToCSV()"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg active:scale-[0.98] transition-all duration-200">
                        <x-export-logo class="h-5 w-5 mr-2" />
                        Export to CSV
                    </button>
                    <button onclick="exportToPDF()"
                        class="inline-flex items-center justify-center px-4 py-2 
                        bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold 
                        rounded-lg hover:shadow-lg active:scale-[0.98] transition-all 
                        duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export to PDF
                    </button>
                    <button onclick="printMasterlist()"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:shadow-lg active:scale-[0.98] transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print List
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Voter Masterlist</h3>
                            <p class="text-sm text-gray-500">Complete list of registered voters across districts</p>
                        </div>
                        <div class="text-sm text-gray-500" id="voter-count">
                            Showing 0 voters
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-green-600 to-emerald-600">
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        Voter Details
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        
                                        District
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white" id="masterlists-table-body">
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
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between text-sm text-gray-500">
                        <div class="mb-2 md:mb-0">
                            Showing <span class="font-semibold text-gray-700" id="showing-count">0</span> voters
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="prev-page" onclick="prevPage()" class="px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                Previous
                            </button>
                            <span id="page-info" class="px-3 py-1.5 text-gray-600">Page 1 of 1</span>
                            <button id="next-page" onclick="nextPage()" class="px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="voterModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeVoterModal()"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                                <svg id="modal-icon" class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white" id="modal-title">Add New Voter</h3>
                        </div>
                        <button onclick="closeVoterModal()" 
                            class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-white/20 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form id="voterForm" class="px-6 py-6">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="first_name" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                        placeholder="Enter first name">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Middle Name
                                    </label>
                                    <input type="text" id="middle_name"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                        placeholder="Enter middle name">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="last_name" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                        placeholder="Enter last name">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Suffix
                                    </label>
                                    <select id="suffix"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 bg-white">
                                        <option value="">None</option>
                                        <option value="Jr.">Jr.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email Address
                                    </label>
                                    <input type="email" id="email"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                        placeholder="Enter email address">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" id="phone"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                        placeholder="Enter phone number">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Registration Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        District <span class="text-red-500">*</span>
                                    </label>
                                    <select id="district" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 bg-white">
                                        <option value="">Select District</option>
                                        <option value="District 1">District 1</option>
                                        <option value="District 2">District 2</option>
                                        <option value="District 3">District 3</option>
                                        <option value="District 4">District 4</option>
                                    </select>
                                </div>
                                 <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Voter ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="voter_id" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                placeholder="Enter voter ID">
                        </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Address
                            </label>
                            <textarea id="address" rows="2"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                placeholder="Enter complete address"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" onclick="closeVoterModal()"
                            class="flex-1 px-4 py-3 text-gray-700 font-medium border border-gray-300 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all duration-200"
                            id="submit-button">
                            Add Voter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @vite('resources/js/admin-masterlists.js')
    
</x-app-layout>