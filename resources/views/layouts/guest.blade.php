<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BOHECO 1 Election System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div
        class="min-h-screen bg-gradient-to-br from-green-50 via-white to-gray-50 flex items-center justify-center px-4 py-8">
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute -top-40 -right-40 w-80 h-80 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-80 h-80 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20">
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-green-50 rounded-full mix-blend-multiply filter blur-3xl opacity-10">
            </div>
        </div>

        <div
            class="relative w-full max-w-6xl flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-16 py-12">

            <div class="lg:w-1/2 w-full flex flex-col items-start space-y-8">
                <div class="flex items-center space-x-4 group cursor-pointer">
                    <div class="relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-green-600 to-green-800 rounded-2xl blur-md opacity-60 group-hover:opacity-80 transition-opacity duration-300">
                        </div>
                        <x-application-logo
                            class="relative w-24 h-24 text-white drop-shadow-lg group-hover:scale-105 transition-transform duration-300 floating" />
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-green-800 to-green-600 bg-clip-text text-transparent leading-tight"
                            style="font-family: 'Helvetica', 'Arial', 'sans-serif';">
                            BOHECO I
                        </h1>
                        <div class="space-y-1">
                            <p class="text-lg font-semibold text-gray-800" style="font-family: 'Helvetica', 'Arial', 'sans-serif';">
                                Election Management System
                            </p>
                            <p class="text-gray-600 max-w-md">
                                Bohol 1 Electric Cooperative, Inc.
                                <br>Secure digital voting platform
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50">
                    <p class="text-sm text-blue-800/80 font-medium">
                        <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Authorized personnel only. All activities are logged for security.
                    </p>
                </div>
            </div>

            <div class="lg:w-1/2 w-full flex justify-center">
                <div class="relative w-full max-w-lg">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-green-600 to-green-400 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-1000 group-hover:duration-200">
                    </div>

                    <div
                        class="relative w-full bg-white/95 backdrop-blur-sm border border-gray-200/50 px-8 py-10 rounded-2xl shadow-2xl overflow-hidden">


                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-100 rounded-full opacity-20"></div>
                        <div class="absolute -left-6 -bottom-6 w-20 h-20 bg-green-200 rounded-full opacity-10"></div>

                        <div class="relative">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
