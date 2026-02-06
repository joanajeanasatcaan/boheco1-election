<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <nav class="bg-white border-b border-gray-200 shadow-lg py-4">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ecom') }}" class="flex items-center space-x-3 group">
                        <div class="relative">
                            <x-application-logo class="relative h-12 w-12 " />
                        </div>
                        <div class="transform transition-all duration-500 group-hover:translate-x-2">
                            <div class="font-helvetica text-2xl font-black text-gray-900">
                                BOHECO I
                            </div>
                            <div class="font-helvetica text-sm font-semibold text-gray-600">
                                Election Committee Portal
                            </div>
                        </div>
                    </a>
                </div>

                <div class="flex relative">
                    {{-- <x-nav-link :href="route('online-voters-receipts')" :active="request()->routeIs('online-voters-receipts')"
                    class="group relative flex items-center px-5 py-3 rounded-xl font-semibold text-sm text-gray-700 tracking-wide bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md border border-gray-200">
                    <x-print-logo class="h-5 w-5 mr-2 text-gray-600 group-hover:text-emerald-600 transition-colors duration-300" />
                    <span class="group-hover:text-emerald-700 transition-colors duration-300">Print Receipts</span>
                </x-nav-link> --}}

                    <x-nav-link :href="route('history')" :active="request()->routeIs('history')"
                        class="group mr-2 relative flex items-center px-5 py-3 rounded-2xl tracking-wide bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500/30 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md border border-gray-200">
                        <x-history-no-color-logo
                            class="h-5 w-5 mr-2 group-hover:text-gray-600 transition-colors duration-300" />
                    </x-nav-link>
                    <button id="profileButton"
                        class="flex items-center gap-3 px-4 py-2.5 bg-white/10 backdrop-blur-sm rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray/20 transition-all duration-300 border border-gray/20 hover:border-gray/30 focus:outline-none focus:ring-2 focus:ring-gray/40 focus:ring-offset-2 focus:ring-offset-blue-900">
                        <div class="text-left">
                            <div class="font-medium">{{ Auth::user()->name }}</div>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor"
                            stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="profileDropdown"
                        class="hidden absolute right-0 mt-3 w-56 bg-gray-800 rounded-xl shadow-2xl border border-gray-200/80 backdrop-blur-sm overflow-hidden z-50 animate-fade-in">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'Administrator' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full bg-white text-left px-4 py-3.5 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200 flex items-center gap-2 group">
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-90"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div>
        @include('ecom.dashboard')
    </div>
</body>

</html>
