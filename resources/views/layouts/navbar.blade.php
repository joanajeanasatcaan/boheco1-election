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

<body class="bg-gray-100">

    {{-- ✅ Instant loader — blocks flash before gate runs --}}
    <script>
        (function() {
            var loader = document.createElement('div');
            loader.id  = 'schedule-loader';
            loader.style.cssText = 'position:fixed;inset:0;z-index:9999;background:#f9fafb;display:flex;align-items:center;justify-content:center;font-family:Segoe UI,sans-serif;';
            loader.innerHTML = `
                <div style="text-align:center;padding:24px;">
                    <div style="position:relative;width:80px;height:80px;margin:0 auto 24px;">
                        <svg style="animation:spin 1.2s linear infinite;width:80px;height:80px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" stroke-width="2" stroke="#dcfce7"/>
                            <path d="M12 2a10 10 0 0 1 10 10" stroke-width="2.5" stroke="#16a34a" stroke-linecap="round"/>
                        </svg>
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:28px;height:28px;" fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <h2 style="font-size:1.1rem;font-weight:700;color:#111827;margin-bottom:6px;">Verifying Access</h2>
                    <p style="font-size:0.85rem;color:#6b7280;">Checking election schedule...</p>
                    <div style="margin-top:20px;display:flex;justify-content:center;gap:6px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;animation:bounce 1s infinite 0s;display:inline-block;"></span>
                        <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;animation:bounce 1s infinite 0.2s;display:inline-block;"></span>
                        <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;animation:bounce 1s infinite 0.4s;display:inline-block;"></span>
                    </div>
                    <style>
                        @keyframes spin   { to { transform:rotate(360deg); } }
                        @keyframes bounce { 0%,100%{transform:translateY(0);opacity:.5;}50%{transform:translateY(-8px);opacity:1;} }
                    </style>
                </div>
            `;
            document.documentElement.appendChild(loader);
        })();
    </script>

    <nav class="bg-white border-b border-gray-200 shadow-lg py-4">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ecom') }}" class="flex items-center space-x-3 group">
                        <div class="relative">
                            <x-application-logo class="relative h-12 w-12" />
                        </div>
                        <div class="transform transition-all duration-500 group-hover:translate-x-2">
                            <div class="font-helvetica text-2xl font-black text-gray-900">BOHECO I</div>
                            <div class="font-helvetica text-sm font-semibold text-gray-600">Election Committee Portal</div>
                        </div>
                    </a>
                </div>

                <div class="flex relative">
                    <x-nav-link :href="route('history')" :active="request()->routeIs('history')"
                        class="group mr-2 relative flex items-center px-5 py-3 rounded-2xl tracking-wide bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500/30 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md border border-gray-200">
                        <x-history-no-color-logo class="h-5 w-5 mr-2 group-hover:text-gray-600 transition-colors duration-300" />
                    </x-nav-link>

                    <button id="profileButton"
                        class="flex items-center gap-3 px-4 py-2.5 bg-white/10 backdrop-blur-sm rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray/20 transition-all duration-300 border border-gray/20 hover:border-gray/30 focus:outline-none focus:ring-2 focus:ring-gray/40 focus:ring-offset-2 focus:ring-offset-blue-900">
                        <div class="text-left">
                            <div class="font-medium">{{ Auth::user()->name }}</div>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
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

    {{-- ✅ Auth user data for JS schedule gate --}}
    <span id="auth-user" class="hidden"
        data-district="{{ auth()->user()->ecomProfile?->district ?? '' }}"
        data-name="{{ auth()->user()->name ?? '' }}">
    </span>

    <div>
        @include('ecom.dashboard')
    </div>

</body>
</html>