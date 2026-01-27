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
    <nav class="bg-gradient-to-r from-green-900 to-green-700 border-b border-green-800 shadow-2xl py-4">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ecom') }}" class="flex items-center space-x-3 group">
                        <x-application-logo class="h-12 w-12 text-white drop-shadow-lg transition-transform duration-300 group-hover:scale-105" />
                        <div class="transform transition-all duration-300 group-hover:translate-x-1">
                            <div class="text-xl font-extrabold text-white tracking-tight drop-shadow-lg">
                                BOHECO 1
                            </div>
                            <div class="text-sm font-medium text-green-100 opacity-90">
                                Election Management System
                            </div>
                        </div>
                    </a>
                </div>

                <div class="relative">
                    <button id="profileButton"
                        class="flex items-center gap-3 px-4 py-2.5 bg-white/10 backdrop-blur-sm rounded-xl text-sm font-semibold text-white hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/30 focus:outline-none focus:ring-2 focus:ring-white/40 focus:ring-offset-2 focus:ring-offset-blue-900">
                        <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <div class="font-medium">{{ Auth::user()->name }}</div>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="profileDropdown"
                        class="hidden absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-2xl border border-gray-200/80 backdrop-blur-sm overflow-hidden z-50 animate-fade-in">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'Administrator' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-3.5 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200 flex items-center gap-2 group">
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @include('ecom.dashboard')
</body>

</html>
