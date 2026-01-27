<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Tally Results') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Monitor election results all districts.
                </p>
            </div>
            <div class="text-sm text-gray-500">
                Last updated: {{ now()->format('M j, Y') }}
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
                            <div class="flex flex-col md:flex-row md::items-center md:justify-between">
                                <div class="mb-6 md:mb-0">
                                    <h1 class="text-lg md:text-4xl font-bold text-white mb-2 drop-shadow">
                                        {{ __('Tally Results') }}
                                    </h1>
                                    <p class="text-sm text-green-100/90 max-w-2xl">
                                        {{ __('Monitor election results accross all districts.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-purple-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-50 rounded-xl group-hover:bg-purple-100 transition-colors">
                                <x-votes-cast-logo class="h-8 w-8 text-purple-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1"> {{ __('Total Votes Cast') }}</p>
                                <div class="flex items-end justify-between">
                                    <h3 class="text-3xl font-bold text-gray-900">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-purple-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-purple-50 rounded-xl group-hover:bg-purple-100 transition-colors duration-300">
                                <x-voters-logo class="h-8 w-8 text-purple-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">{{ __('Registered Voters') }} </p>
                                <div class="flex items-end justify-between">
                                    <h3 class="text-3xl font-bold text-gray-900">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-purple-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-purple-50 rounded-xl group-hover:bg-purple-10 transition-color duration-300">
                                <x-tally-results-logo class="h-8 w-8 text-purple-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">{{ __('Overall Turnout') }}</p>
                                <div class="felx items-end justify-between">
                                    <h3 class="text-3xl font-bold text-gray-900">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Vote Tally</h3>
                        <p class="text-sm text-gray-500">Tally across all districts</p>
                    </div>
                    <div class="text-sm text-gray-500" id="tally-results">
                        Showing 0 tallies
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="tally-grid">
                    <div class="text-center py-12 text-gray-500 col-span-full">
                        <svg class="h-12 w-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p>No tally available.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
