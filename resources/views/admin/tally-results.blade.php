<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 h-10 font-black text-2xl text-gray-900">
                    {{ __('Tally Results') }}
                </div>
                <a class="p-3 text-gray-500">
                    Monitor election results accross all districts.
                </a>

                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Total Votes Cast -->
                        <div class="bg-white border border-gray-100 overflow-hidden shadow-md sm:rounded-lg p-6">
                            <div class="flex mb-4 items-center">
                                <x-total-votes-cast-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-sm text-gray-500">
                                        {{ __('Total Votes Cast') }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Registered Voters -->
                        <div class="bg-white border border-gray-100 overflow-hidden shadow-md sm:rounded-lg p-6">
                            <div class="flex mb-4 items-center">
                                <x-voters-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-sm text-gray-500">
                                        {{ __('Registered Voters') }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Overall Turnout -->
                        <div class="bg-white border border-gray-100 overflow-hidden shadow-md sm:rounded-lg p-6">
                            <div class="flex mb-4 items-center">
                                <x-votes-cast-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-sm text-gray-500">
                                        {{ __('Overall Turnout') }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
