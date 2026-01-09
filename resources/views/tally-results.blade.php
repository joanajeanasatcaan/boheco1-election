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
                    {{ __("Tally Results") }}
                </div>
                <a class="p-3 text-gray-500">
                    Monitor election results accross all districts.
                </a>

                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Total Districts") }}
                </div>
            </div>

            </div>
        </div>
    </div>
</x-app-layout>
