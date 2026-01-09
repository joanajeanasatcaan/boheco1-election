<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-white min-h-screen">
    <div class="flex items-center">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="BOHECO 1 Logo"
                    class="w-16 h-16"
                >
                <div class="leading-tight">
                    <div class="text-lg font-bold text-gray-800">
                        BOHECO 1
                    </div>
                    <div class="text-sm text-gray-600">
                        Bohol 1 Electric Cooperative, Inc.
                    </div>
                </div>
            </div>  

    <!-- Modal Backdrop -->
    <div
        id="welcomeModal"
        class="fixed inset-0 flex items-center justify-center bg-black/5"
    >
        <!-- Modal Box -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 text-center space-y-6">
            
            <h1 class="text-2xl font-semibold text-[#1b1b18]">
                Welcome
            </h1>

            <p class="text-sm text-gray-500">
                Please log in to continue
            </p>

            @if (Route::has('login'))
                <a
                    href="{{ route('login') }}"
                    class="inline-block w-full px-6 py-2 border border-[#19140035] hover:bg-[#06923E] rounded-sm text-sm font-medium transition"
                >
                    Log in
                </a>
            @endif
        </div>
    </div>
</body>
</html>
