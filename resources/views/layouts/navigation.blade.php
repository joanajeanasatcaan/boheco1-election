<div x-data="{ open: true }" class="flex h-screen bg-gray-100">

    <aside class="bg-white border-r hidden md:flex flex-col transition-all duration-300"
        x-bind:class="open ? 'w-64' : 'w-20'">

        <!-- Header (Logo + Title) -->
        <div class="flex items-center h-20 border-b px-4">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <x-application-logo class="h-10 w-10 flex-shrink-0" />
            </a>

            <div x-show="open" x-transition class="ml-2">
                <div class="text-lg font-bold text-gray-900">
                    BOHECO I
                </div>
                <div class="text-sm text-gray-600">
                    Election Management System
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 flex flex-col p-2 overflow-y-auto space-y-2">

            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-green-600 transition-all"
                x-bind:class="open ? 'justify-start' : 'justify-center'">
                <x-dashboard-logo class="h-6 w-6" />
                <span x-show="open" x-transition class="ml-3 font-semibold">
                    Dashboard
                </span>
            </x-nav-link>

            <x-nav-link :href="route('districts')" :active="request()->routeIs('districts')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-green-600 transition-all"
                x-bind:class="open ? 'justify-start' : 'justify-center'">
                <x-districts-logo class="h-6 w-6" />
                <span x-show="open" x-transition class="ml-3 font-semibold">
                    Districts
                </span>
            </x-nav-link>

            <x-nav-link :href="route('nominees')" :active="request()->routeIs('nominees')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-green-600 transition-all"
                x-bind:class="open ? 'justify-start' : 'justify-center'">
                <x-nominees-logo class="h-6 w-6" />
                <span x-show="open" x-transition class="ml-3 font-semibold">
                    Nominees
                </span>
            </x-nav-link>

            <x-nav-link :href="route('tally-results')" :active="request()->routeIs('tally-results')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-green-600 transition-all"
                x-bind:class="open ? 'justify-start' : 'justify-center'">
                <x-tally-results-logo class="h-6 w-6" />
                <span x-show="open" x-transition class="ml-3 font-semibold">
                    Tally Results
                </span>
            </x-nav-link>

            <x-nav-link :href="route('schedule')" :active="request()->routeIs('schedule')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-green-600 transition-all"
                x-bind:class="open ? 'justify-start' : 'justify-center'">
                <x-schedule-logo class="h-6 w-6" />
                <span x-show="open" x-transition class="ml-3 font-semibold">
                    Schedule
                </span>
            </x-nav-link>

            <x-nav-link :href="route('ecom-accounts')" :active="request()->routeIs('ecom-accounts')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-green-600 transition-all"
                x-bind:class="open ? 'justify-start' : 'justify-center'">
                <x-ecom-accounts-logo class="h-6 w-6" />
                <span x-show="open" x-transition class="ml-3 font-semibold">
                    Ecom Accounts
                </span>
            </x-nav-link>

            <x-nav-link :href="route('masterlists')" :active="request()->routeIs('masterlists')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-green-600 transition-all"
                x-bind:class="open ? 'justify-start' : 'justify-center'">
                <x-masterlists-logo class="h-6 w-6" />
                <span x-show="open" x-transition class="ml-3 font-semibold">
                    Masterlists
                </span>
            </x-nav-link>

            <!-- Collapse/Expand Button -->
            <div class="flex justify-center mt-4 mb-4">
                <button @click="open = !open"
                    class="bg-white border shadow-md rounded-full w-8 h-8 flex items-center justify-center
                       hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 transition-transform duration-300 ease-in-out" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" x-bind:class="open ? 'rotate-180' : 'rotate-0'">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

        </div>

        <!-- User Section -->
        <div class="border-t p-2">
            <div x-show="open" x-transition>
                <div class="text-sm font-medium text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit"
                    class="flex items-center w-full p-2 rounded-lg text-red-600 hover:bg-red-50 transition-all"
                    x-bind:class="open ? 'justify-start' : 'justify-center'">
                    <x-logout-logo class="h-5 w-5" />
                    <span x-show="open" x-transition class="ml-2">
                        Log Out
                    </span>
                </button>
            </form>
        </div>

    </aside>

    <main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </main>

</div>
