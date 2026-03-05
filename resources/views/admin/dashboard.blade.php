<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Welcome back, {{ Auth::user()->name }}
                </p>
            </div>
            <div class="text-sm text-gray-500">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div
                    class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8 relative">
                        <div
                            class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-green-500/20 to-transparent rounded-full -translate-y-32 translate-x-32">
                        </div>
                        <div class="relative">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h1 class="text-lg md:text-4xl font-bold text-white mb-2 drop-shadow">
                                        {{ __('Admin Dashboard') }}
                                    </h1>
                                    <p class="text-sm text-green-100/90 max-w-2xl">
                                        {{ __('Manage districts, nominees, and monitor election progress.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors duration-300">
                                <x-total-districts-logo class="h-8 w-8 text-blue-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">{{ __('Total Districts') }}</p>
                                <div class="flex items-end justify-between">
                                    <h3 class="text-3xl font-bold text-gray-900">9</h3>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <a href="{{ route('districts') }}"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center group">
                                View all districts
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl 
                    transition-all duration-300 overflow-hidden border 
                    border-gray-100 hover:border-purple-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-purple-50 rounded-xl 
                                group-hover:bg-purple-100 transition-colors 
                                duration-300">
                                <x-nominees-logo class="h-8 w-8 text-purple-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">{{ __('Total Nominees') }}</p>
                                <div class="flex items-end justify-between">
                                    <h3 id='total-nominees-count' class="text-3xl font-bold text-gray-900">0</h3>
                                </div>
                            </div>

                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <a href="{{ route('nominees') }}"
                                class="text-sm text-purple-600 hover:text-purple-800 font-medium flex items-center group">
                                Manage nominees
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-green-50 rounded-xl group-hover:bg-green-100 transition-colors duration-300">
                                <x-votes-cast-logo class="h-8 w-8 text-green-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">{{ __('Votes Cast') }}</p>
                                <div class="flex items-end justify-between">
                                    <h3 id="total-votes-count"
                                        class="total-votes-count text-3xl font-bold text-gray-900">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <a href="{{ route('tally-results') }}"
                                class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center group">
                                View live results
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <x-schedule-logo />
                                <div>
                                    <h3 class="font-semibold text-gray-900">Election Schedule</h3>
                                    <p class="text-xs text-gray-500">May 13 - 23, 2026</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full">9 Districts</span>
                        </div>
                    </div>

                    <div class="px-5 pb-5">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <span class="text-sm font-semibold text-gray-900">May 13</span>
                                    <p class="text-xs text-gray-500">2026</p>
                                </div>
                                <div class="h-8 w-px bg-gray-200"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">District 1</p>
                                    <p class="text-xs text-gray-500">Tuesday · 8:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-green-600 bg-green-50 px-3 py-1.5 rounded-full">Active</span>
                        </div>
                        
                        <div class="mt-3 text-right">
                            <a href="{{ route('schedule') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                View full schedule →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ __('Voting Progress by District') }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">Voting turnout across all districts</p>
                            </div>
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <x-votes-cast-logo class="h-6 w-6 text-blue-600" />
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-80">
                            <canvas id="votingChart"></canvas>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                    <span class="text-gray-600">Completed Votes</span>
                                </div>
                                <div class="text-gray-900 font-semibold">
                                    Total: <span id="total-votes-count" class="total-votes-count text-blue-600">0</span>
                                    votes
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ __('Recent Activity') }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">Latest actions in the system</p>
                                </div>
                                <div class="p-2 bg-purple-50 rounded-lg">
                                    <x-history-logo class="h-6 w-6 text-purple-600" />
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-semibold text-gray-900">New District Added</h4>
                                            <span class="text-xs text-gray-500">10 min ago</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">District 6 was added to the system</p>
                                        <div class="mt-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Create
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-semibold text-gray-900">Votes Updated</h4>
                                            <span class="text-xs text-gray-500">25 min ago</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">300 new votes recorded in District 3</p>
                                        <div class="mt-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Update
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-semibold text-gray-900">New Nominee Registered</h4>
                                            <span class="text-xs text-gray-500">1 hour ago</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">John Doe registered for District 2
                                            elections
                                        </p>
                                        <div class="mt-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                                </svg>
                                                Registration
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <a href="#"
                                    class="text-sm text-gray-600 hover:text-gray-900 font-medium flex items-center justify-center group">
                                    View all activities
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            @vite('resources/js/admin-dashboard.js')

</x-app-layout>
