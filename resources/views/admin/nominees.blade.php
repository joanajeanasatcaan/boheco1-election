<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center p-3">
                    <div>
                        <div class="h-10 font-black text-2xl text-gray-900">
                            {{ __('Nominees') }}
                        </div>
                        <a class="text-gray-500">
                            Manage candidates for each district.
                        </a>
                    </div>
                    <button
                        class="mr-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-plus-logo class="h-6 w-6 pr-2" />
                        Add Nominees
                    </button>
                </div>

                <div class="flex items-center space-x-4 p-4">
                    <input type="text" name="search" id="search-input" value="{{ request('search', '') }}"
                        placeholder="Search nominees..."
                        class="w-full px-4 py-2 rounded-md border border-primary bg-gray-100 dark:text-black focus:ring-2 focus:ring-primary focus:border-transparent">

                    <div class="relative inline-block text-left">
                        <button id="dropdown-button" type="button"
                            class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50">
                            All districts
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="dropdown-menu"
                            class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg hidden">
                            <div class="py-1">
                                <a href="District 1"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Account
                                    settings</a>
                                <a href="District 2"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Support</a>
                                <a href="District 3"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">License</a>
                            </div>
                        </div>
                    </div>

                    <script>
                        const dropdownButton = document.getElementById('dropdown-button');
                        const dropdownMenu = document.getElementById('dropdown-menu');

                        dropdownButton.addEventListener('click', function() {
                            dropdownMenu.classList.toggle('hidden');
                        });

                        document.addEventListener('click', function(event) {
                            if (!event.target.closest('[id="dropdown-button"]') && !event.target.closest('[id="dropdown-menu"]')) {
                                dropdownMenu.classList.add('hidden');
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
