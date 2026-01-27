<div x-data="{ open: false }" class="flex h-screen bg-gray-100">

    <aside class="w-64 bg-white border-r hidden md:flex flex-col" x-data="{ open: false }">
        <div class="h-20 flex items-center border-b border-gray-300 shadow px-1 space-x-3">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <x-application-logo class="h-10 w-10" />
            </a>
            <div class="leading-tight">
                <div class="text-lg font-bold text-gray-900 text-shadow-lg/30">
                    BOHECO 1
                </div>
                <div class="text-sm text-gray-600">
                    Election Management System
                </div>
            </div>

            <button @click="open = !open" class="p-4">
                ☰
            </button>

        </div>


        <div class="flex-1 flex pl-4 pr-4 flex-col justify-between overflow-y-auto">
            <x-nav-link class="flex pt-4 px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm"
                :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-dashboard-logo class="h-6 w-auto mx-auto" />
                <div class="pl-2 text-base font-bold">
                    <ion-icon name="grid-outline"> Dashboard</ion-icon>
                </div>
            </x-nav-link>

            <x-nav-link class="flex pt-4 px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm"
                :href="route('districts')" :active="request()->routeIs('districts')">
                <x-districts-logo class="h-6 w-auto mx-auto" />
                <div class="pl-2 text-base font-bold">
                    Districts
                </div>
            </x-nav-link>

            <x-nav-link class="flex pt-4 px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm"
                :href="route('nominees')" :active="request()->routeIs('nominees')">
                <x-nominees-logo class="h-6 w-auto mx-auto" />
                <div class="pl-2 text-base font-bold">
                    Nominees
                </div>
            </x-nav-link>

            <x-nav-link class="flex pt-4 px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm"
                :href="route('tally-results')" :active="request()->routeIs('tally-results')">
                <x-tally-results-logo class="h-6 w-auto mx-auto" />
                <div class="pl-2 text-base font-bold">
                    Tally Results
                </div>
            </x-nav-link>

            <x-nav-link class="flex pt-4 px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm"
                :href="route('schedule')" :active="request()->routeIs('schedule')">
                <x-schedule-logo class="h-6 w-auto mx-auto" />
                <div class="pl-2 text-base font-bold">
                    Schedule
                </div>
            </x-nav-link>

            <x-nav-link class="flex pt-4 px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm"
                :href="route('ecom-accounts')" :active="request()->routeIs('ecom-accounts')">
                <x-ecom-accounts-logo class="h-6 w-auto mx-auto" />
                <div class="pl-2 text-base font-bold">
                    Ecom Accounts
                </div>
            </x-nav-link>


            <x-nav-link class="flex pt-4 px-4 py-6 text-center hover:bg-green-600 rounded-xl block text-sm"
                :href="route('masterlists')" :active="request()->routeIs('masterlists')">
                <x-masterlists-logo class="h-6 w-auto mx-auto" />
                <div class="pl-2 text-base font-bold">
                    Masterlists
                </div>
            </x-nav-link>
        </div>

        <!-- User -->
        <div class="border-t mt-auto px-4 py-4">
            <div class="text-sm font-medium text-gray-800">
                {{ Auth::user()->name }}
            </div>
            <div class="text-xs text-gray-500">
                {{ Auth::user()->email }}
            </div>

            <div class="flex text-center">
               <form method="POST" action="{{ route('logout') }}" class="mt-4 w-full">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-sm text-red-600 hover:text-800 transition-colors duration-200 group w-full p-2 rounded-lg hover:bg-red-50">
                    <x-logout-logo class="h-5 w-5"/>
                    <span>Log Out</span>
                </button>
            </div>

        </div>
    </aside>

    <!-- Mobile Sidebar -->
    <div class="md:hidden flex">
        <button @click="open = !open" class="absolute top-0 left-0 p-4">
            ☰
        </button>

        <div x-show="open" @click.away="open = false" class="fixed inset-0 bg-black bg-opacity-40 z-40"></div>

        <aside x-show="open" class="fixed z-50 w-64 h-full bg-white border-r">
            <div class="h-20 flex items-center border-b border-gray-300 shadow px-6 space-x-3">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <x-application-logo class="h-10 w-auto" />
                </a>
                <div class="leading-tight">
                    <div class="font-helvetica text-lg font-bold text-gray-900 text-shadow-lg/30">
                        BOHECO 1
                    </div>
                    <div class="text-sm text-gray-600">
                        Election Management System
                    </div>
                </div>
            </div>

            <div class="flex-1 flex pl-4 pr-4 flex-col justify-between overflow-y-auto">

                <x-nav-link class="flex items-center pt-4 px-4 py-6 text-center rounded-xl block text-sm"
                    :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <x-dashboard-logo class="h-6 w-auto mx-auto" />
                    <div class="pl-2 text-base font-bold">
                        <ion-icon name="grid-outline"> Dashboard</ion-icon>
                    </div>
                </x-nav-link>

                <x-nav-link class="flex items-center pt-4 px-4 py-6 text-center  rounded-xl block text-sm"
                    :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <x-districts-logo class="h-6 w-auto mx-auto" />
                    <div class="pl-2 text-base font-bold">
                        <ion-icon name="grid-outline"> Districts</ion-icon>
                    </div>
                </x-nav-link>

                <x-nav-link class="flex items-center pt-4 px-4 py-6 text-center rounded-xl block text-sm"
                    :href="route('masterlists')" :active="request()->routeIs('masterlists')">
                    <x-nominees-logo class="h-6 w-auto mx-auto" />
                    <div class="pl-2 text-base font-bold">
                        <ion-icon name="grid-outline"> Nominees</ion-icon>
                    </div>
                </x-nav-link>

                <x-nav-link class="flex items-center pt-4 px-4 py-6 text-center rounded-xl block text-sm"
                    :href="route('masterlists')" :active="request()->routeIs('masterlists')">
                    <x-tally-results-logo class="h-6 w-auto mx-auto" />
                    <div class="pl-2 text-base font-bold">
                        <ion-icon name="grid-outline"> Tally Results</ion-icon>
                    </div>
                </x-nav-link>

                <x-nav-link class="flex items-center pt-4 px-4 py-6 text-center rounded-xl block text-sm"
                    :href="route('masterlists')" :active="request()->routeIs('masterlists')">
                    <x-schedule-logo class="h-6 w-auto mx-auto" />
                    <div class="pl-2 text-base font-bold">
                        <ion-icon name="grid-outline"> Schedule</ion-icon>
                    </div>
                </x-nav-link>

                <x-nav-link class="flex items-center pt-4 px-4 py-6 text-center rounded-xl block text-sm"
                    :href="route('masterlists')" :active="request()->routeIs('masterlists')">
                    <x-ecom-accounts-logo class="h-6 w-auto mx-auto" />
                    <div class="pl-2 text-base font-bold">
                        <ion-icon name="grid-outline"> Ecom Accounts</ion-icon>
                    </div>
                </x-nav-link>

                <x-nav-link class="flex items-center pt-4 px-4 py-6 text-center rounded-xl block text-sm"
                    :href="route('masterlists')" :active="request()->routeIs('masterlists')">
                    <x-masterlists-logo class="h-6 w-auto mx-auto" />
                    <div class="pl-2 text-base font-bold">
                        <ion-icon name="grid-outline"> Masterlists</ion-icon>
                    </div>
                </x-nav-link>

            </div>

            <!-- User -->
            <div class="border-t absolute inset-x-0 bottom-0 mt-auto px-4 py-4">
                <div class="text-sm font-medium text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ Auth::user()->email }}
                </div>

                <div class="flex text-center">
                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-4">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-sm text-red-600 hover:text-red-800 transition-colors duration-200 group w-full p-2 rounded-lg hover:bg-red-50">
                        <x-logout-logo class="h-5 w-5" />
                        <span>Log out</span>
                    </button>
                    </form>

                </div>
            </div>
        </aside>
    </div>

    <main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </main>

</div>
