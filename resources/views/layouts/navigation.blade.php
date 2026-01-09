<div x-data="{ open: false }" class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r hidden md:flex flex-col">
        <!-- Logo -->
        <div class="h-20 flex items-center border-b">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <x-application-logo class="h-10 w-auto" />
            </a>
            <div class="leading-tight">
                    <div class="text-lg font-bold text-gray-800">
                        BOHECO 1
                    </div>
                    <div class="text-sm text-gray-600">
                        Election Management System
                    </div>
                </div>
        </div>

        <!-- Links -->
        <x-nav-link class="flex px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <x-dashboard-logo class="h-6 w-auto mx-auto" />
                Dashboard
        </x-nav-link>

        <x-nav-link class="flex px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm" :href="route('districts')" :active="request()->routeIs('districts')">
            <x-districts-logo class="h-6 w-auto mx-auto" />
                Districts
        </x-nav-link>

        <x-nav-link class="flex px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm" :href="route('nominees')" :active="request()->routeIs('nominees')">
            <x-nominees-logo class="h-6 w-auto mx-auto" />
                Nominees
        </x-nav-link>

        <x-nav-link class="flex px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm" :href="route('tally-results')" :active="request()->routeIs('tally-results')">
            <x-tally-results-logo class="h-6 w-auto mx-auto" />
                Tally Results
        </x-nav-link>

        <x-nav-link class="flex px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm" :href="route('schedule')" :active="request()->routeIs('schedule')">
            <x-schedule-logo class="h-6 w-auto mx-auto" />
                Schedule
        </x-nav-link>

        <x-nav-link class="flex px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm" :href="route('ecom-accounts')" :active="request()->routeIs('ecom-accounts')">
            <x-ecom-accounts-logo class="h-6 w-auto mx-auto" />
                Ecom Accounts
        </x-nav-link>

        
        <x-nav-link class="flex px-4 py-6 text-center hover:bg-green-600 rounded-xl" :href="route('masterlists')" :active="request()->routeIs('masterlists')">
            <x-masterlists-logo class="h-6 w-auto mx-auto" />
                Masterlists
        </x-nav-link>

        <!-- User -->
        <div class="border-t px-4 py-4">
            <div class="text-sm font-medium text-gray-800">
                {{ Auth::user()->name }}
            </div>
            <div class="text-xs text-gray-500">
                {{ Auth::user()->email }}
            </div>

            <div class="flex text-center">
            <form method="POST" action="{{ route('logout') }}" class="flex mt-3 w-full">
                @csrf
                <x-logout-logo class="h-6 w-auto mx-auto"/>
                <button class="text-sm text-red-600 hover:underline">
                    Log Out
                </button>
            </div>

                {{-- <x-logout-logo class="h-6 w-auto mx-auto" /> --}}

            </form>
        </div>
    </aside>

    <!-- Mobile Sidebar -->
    <div class="md:hidden flex">
        <button @click="open = !open" class="p-4">
            ☰
        </button>

        <div
            x-show="open"
            @click.away="open = false"
            class="fixed inset-0 bg-black bg-opacity-40 z-40"
        ></div>

        <aside
            x-show="open"
            class="fixed z-50 w-64 h-full bg-white border-r"
        >
            <div class="h-16 flex items-center px-6 border-b">
                <x-application-logo class="h-10" />
            </div>

            <nav class="px-4 py-6 space-y-2">
                <x-responsive-nav-link :href="route('dashboard')">
                    Dashboard
                </x-responsive-nav-link>
            </nav>
        </aside>
    </div>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </main>

</div>