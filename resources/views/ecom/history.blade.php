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
        disabled:cursor-not-allowed transition-all duration-200 
        hover:shadow-md">
                Delete Selected
            </button>
            <button id="cancelDeleteBtn" class="text-sm text-gray-600 
    hover:text-gray-900 hover:underline">
                Cancel
            </button>
        </div>
        <div
            class="absolute right-0 top-full mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden transition-all duration-200 origin-top-right">
            <a href="#"
                class="delete-option flex items-center gap-3 px-4 py-3 hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span class="text-sm">Delete</span>
            </a>
        </div>
        <div class="p-6">
            <div class="p-6 bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8">
                    <div class="mb-6 sm:mb-0">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-2xl font-bold text-gray-900 tracking-tight">
                                Voting History
                            </h3>
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
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-500 transition-colors duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </div>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <button id="refresh-btn"
                            class="p-3.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 hover:shadow-sm active:scale-95 bg-white">
                            <svg id="refresh-icon"
                                class="w-5 h-5 text-gray-600 hover:text-blue-600 transition-colors duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody id="historyTableBody" class="bg-white divide-y divide-gray-200">
                            <tr class="history-row hover:bg-gray-50 transition-colors duration-150" data-type="voted">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox"
                                        class="history-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Erlinda Lebita </div>
                                            <div class="text-sm text-gray-500">#0987654321</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-50 
                                    text-blue-700 border border-blue-200">
                                        Voted
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-900">
                                        Feb 9, 2026 14:30
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative">
                                        <button
                                            class="options-btn px-3 py-1 rounded-lg hover:bg-gray-200 transition-colors duration-150">
                                            ...
                                        </button>
                                        <div
                                            class="absolute right-0 top-full mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden transition-all duration-200 origin-top-right">
                                            <a href="#"
                                                class="delete-option flex items-center gap-3 px-4 py-3 hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors duration-150">
                                                <span class="text-sm">Delete</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="history-row hover:bg-gray-50 transition-colors duration-150"
                                data-type="verified">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox"
                                        class="history-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Jessie Lubiano</div>
                                            <div class="text-sm text-gray-600">#4566779978</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold 
                                    rounded-full bg-green-50 text-green-700 border 
                                    border-green-200">
                                        Verified
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-900">
                                        Feb 9, 2026 15:30
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative">
                                        <button
                                            class="options-btn px-3 py-1 rounded-lg hover:bg-gray-200 transition-colors duration-150">
                                            ...
                                        </button>
                                        <div
                                            class="absolute right-0 top-full mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden transition-all duration-200 origin-top-right">
                                            <a href="#"
                                                class="delete-option flex items-center gap-3 px-4 py-3 hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors duration-150">
                                                <span class="text-sm">Delete</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="history-row hover:bg-gray-50 transition-colors duration-150"
                                data-type="updated">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox"
                                        class="history-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Sedric Gabas</div>
                                            <div class="text-sm text-gray-600">#67589903048</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-50 text-orange-700 border border-orange-200">
                                        Profile Updates
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-900">
                                        Feb 9, 2026 16:30
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative">
                                        <button
                                            class="options-btn px-3 py-1 rounded-lg hover:bg-gray-200 transition-colors duration-150">
                                            ...
                                        </button>
                                        <div
                                            class="absolute right-0 top-full mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden transition-all duration-200 origin-top-right">
                                            <a href="#"
                                                class="delete-option flex items-center gap-3 px-4 py-3 hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors duration-150">
                                                <span class="text-sm">Delete</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @vite('resources/js/ecom-history.js')
        
    </x-slot>
</x-ecom-layout>
