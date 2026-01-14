<div>
    <nav class="bg-white border-b border-gray-300 shadow-xl py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ecom') }}" class="flex items-center space-x-2">
                        <x-application-logo class="h-10 w-10" />
                    </a>
                    <div>
                        <div class="text-lg font-bold text-gray-900">
                            BOHECO 1
                        </div>
                        <div class="text-sm text-gray-600">
                            Election System
                        </div>
                    </div>
                </div>

                <!-- Profile -->
                <div class="relative">
                    <button id="profileButton"
                        class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                        {{ Auth::user()->name }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div id="profileDropdown"
                        class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    District # Election Voters
                </h1>
                <p class="text-sm text-gray-600">
                    List of registered voters
                </p>

                <input type="text" name="search" id="search-input" value="{{ request('search', '') }}"
                    placeholder="Search voter name..."
                    class="mt-3 w-64 px-4 py-2 rounded-md border border-gray-300 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="flex items-center">
                <x-nav-link :href="route('online-voters-receipts')" :active="request()->routeIs('online-voters-receipts')"
                    class="flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-print-logo class="h-6 w-6 pr-2 ml-4 cursor-pointer hover:bg-gray-200 rounded-full" />
                </x-nav-link>
                <x-nav-link :href="route('history')" :active="request()->routeIs('history')"
                    class="flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-history-no-color-logo class="h-6 w-6 p-2 ml-4 cursor-pointer hover:bg-gray-200 rounded-full" />
                </x-nav-link>
            </div>
        </div>
    </div>
    <main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </main>
    <script>
        const btn = document.getElementById('profileButton');
        const dropdown = document.getElementById('profileDropdown');

        btn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
