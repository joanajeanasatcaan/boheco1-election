<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('ecom') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-8 w-8 text-blue-600" />
                    <div>
                        <span class="font-bold text-gray-900">BOHECO I</span>
                        <span class="text-xs text-gray-500 block -mt-1">Election Portal</span>
                    </div>
                </a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('history') }}" 
                       class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition {{ request()->routeIs('history') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <x-history-no-color-logo class="h-5 w-5" />
                    </a>

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

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>