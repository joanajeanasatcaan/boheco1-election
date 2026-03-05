<x-ecom-layout>
    <x-slot>
        <div id="deleteOptionContainer"
            class="hidden items-center gap-4 p-3 mb-4 bg-gradient-to-r 
            from-red-50 to-orange-50 border border-red-200 rounded-lg">
            <div class="text-sm font-medium text-red-600">
                <span id="selectedCount">0</span> item(s) selected
            </div>
            <button id="deleteSelectedBtn" disabled
                class="px-4 py-2 bg-gradient-to-r from-red-600 
                to-red-700 text-white text-sm font-medium rounded-lg 
                hover:from-red-700 hover:to-red-800 disabled:opacity-50 
                disabled:cursor-not-allowed transition-all duration-200 hover:shadow-md">
                Delete Selected
            </button>
            <button id="cancelDeleteBtn" class="text-sm text-gray-600 hover:text-gray-900 hover:underline">
                Cancel
            </button>
        </div>

        <div class="p-6">
            <div class="p-6 bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8">
                    <div class="mb-6 sm:mb-0">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Voting History</h3>
                        </div>
                        <p class="text-sm text-gray-600">
                            Track actions and participation across all voting districts
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <div class="relative group">
                            <select id="history-filter"
                                class="pl-12 pr-10 py-3 border border-gray-300 rounded-xl bg-white text-gray-700 text-sm cursor-pointer hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition-all duration-200">
                                <option value="all" selected>All Activities</option>
                                <option value="voted">Voted</option>
                                <option value="verified">Verified</option>
                                <option value="updated">Profile Updates</option>
                            </select>
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </div>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <button id="refresh-btn"
                            class="p-3.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 hover:shadow-sm active:scale-95 bg-white">
                            <svg id="refresh-icon" class="w-5 h-5 text-gray-600 transition-colors duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 w-10"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performed By</th>
                                <th class="px-6 py-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody" class="bg-white divide-y divide-gray-200">
                            {{-- Rows are rendered by JS --}}
                        </tbody>
                    </table>

                    {{-- Empty state --}}
                    <div id="emptyState" class="hidden py-16 text-center">
                        <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-500 text-sm">No history records found</p>
                    </div>

                    {{-- Loading state --}}
                    <div id="loadingState" class="py-16 text-center">
                        <div class="inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-gray-500 text-sm mt-3">Loading history...</p>
                    </div>
                </div>
            </div>
        </div>

        @vite('resources/js/ecom-history.js')
    </x-slot>
</x-ecom-layout> 