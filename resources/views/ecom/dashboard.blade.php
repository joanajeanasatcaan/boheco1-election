<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-8 p-6 bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="h-1.5 w-6 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full"></div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                        District # Election Voters
                    </h1>
                </div>
                <p class="text-base text-gray-600 mb-6 ml-9">
                    Manage and monitor all registered voters in the district
                </p>
                
                <div class="relative ml-9">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search-input" value="{{ request('search', '') }}"
                        placeholder="Search voter name, ID, or district..."
                        class="w-full md:w-80 pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-white focus:ring-3 focus:ring-blue-500/20 focus:border-blue-500 shadow-sm transition-all duration-300 hover:border-blue-400">
                </div>
            </div>

            <div class="flex items-center gap-4 ml-9 md:ml-0">
                <x-nav-link :href="route('online-voters-receipts')" :active="request()->routeIs('online-voters-receipts')"
                    class="flex items-center px-5 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-emerald-700 rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:from-green-700 hover:to-emerald-700 focus:from-green-700 focus:to-emerald-700 active:from-green-800 active:to-emerald-800 focus:outline-none focus:ring-3 focus:ring-emerald-500/40 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                    <x-print-logo class="h-5 w-5 mr-2" />
                    Print Reports
                </x-nav-link>
                <x-nav-link :href="route('history')" :active="request()->routeIs('history')"
                    class="flex items-center px-5 py-3 bg-gradient-to-r from-gray-700 to-gray-800 border border-gray-800 rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:from-gray-800 hover:to-gray-900 focus:from-gray-800 focus:to-gray-900 active:from-gray-900 active:to-gray-950 focus:outline-none focus:ring-3 focus:ring-gray-500/40 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                    <x-history-no-color-logo class="h-5 w-5 mr-2" />
                    View History
                </x-nav-link>
            </div>
        </div>
    </div>
    
    <main class="flex-1 overflow-y-auto py-6">
        {{ $slot }}
    </main>
    
    <script>
        const btn = document.getElementById('profileButton');
        const dropdown = document.getElementById('profileDropdown');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            dropdown.classList.toggle('animate-fade-in');
        });

        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</div>