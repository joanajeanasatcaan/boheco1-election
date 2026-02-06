<x-ecom-layout>
    <x-slot>
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
                            <select id="history-filter">
                                <option class="all" select>All Activities</option>
                                <option class="voted">Vote Cast</option>
                                <option class="verification">Verification</option>
                                <option class="updated">Consumer Profile Updates</option>
                            </select>
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-500 transition-colors"
                                    fill="none" stroke="currenColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </div>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 91-7-7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <button id="refresh-btn"
                            class="p-3.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 hover:shadow-sm active:scale-95 bg-white">
                            <svg class="w-5 h-5 text-gray-600 hover:text-blue-600 transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                    <input type="checkbox" id="select-all" class="roounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </x-slot>
</x-ecom-layout>
