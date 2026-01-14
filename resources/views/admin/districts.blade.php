<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="p-3 h-10 font-black text-2xl text-gray-900">
                            {{ __('Districts') }}
                        </div>
                        <a class="p-3 text-gray-500">
                            Manage election districts in Bohol.
                        </a>
                    </div>
                    <button
                        class="mr-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-plus-logo class="h-6 w-6 pr-2" />
                        Add New District
                    </button>
                </div>


                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Total Districts -->
                        <div class="bg-white border border-gray-100 overflow-hidden shadow-md sm:rounded-lg p-6">
                            <div class="flex mb-4 items-center">
                                <x-total-districts-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-sm text-gray-500">
                                        {{ __('Total Districts') }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Voters -->
                        <div class="bg-white border border-gray-100 overflow-hidden shadow-md sm:rounded-lg p-6">
                            <div class="flex mb-4 items-center">
                                <x-voters-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-sm text-gray-500">
                                        {{ __('Total Voters') }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Election -->
                        <div class="bg-white border border-gray-100 pt-8 overflow-hidden shadow-md sm:rounded-lg p-6">
                            <div class="flex mb-4 items-center">
                                <x-active-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-sm text-gray-500">
                                        {{ __('Active Election') }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6">
                    <div class="grid grid-cols-2 gap-4 flex mb-4 items-center">
                        <div class="pl-2">
                            <div class="text-lg font-bold text-gray-900">
                                {{ __('All Districts') }}
                            </div>
                            <div class="text-sm text-gray-400">
                                List of all districts with the election status.
                            </div>
                        </div>

                        {{-- Search Input --}}
                        <input type="text" name="search" id="search-input" value="{{ request('search', '') }}"
                            placeholder="Search districts..."
                            class="w-full px-4 py-2 rounded-md border border-primary bg-gray-100 dark:text-black focus:ring-2 focus:ring-primary focus:border-transparent">

                    </div>
                </div>

            </div>
        </div>
</x-app-layout>
