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
                            {{ __('Ecom Accounts') }}
                        </div>
                        <a class="p-3 text-gray-500">
                            Manage account in every district.
                        </a>
                    </div>
                    <button
                        class="mr-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-plus-logo class="h-6 w-6 pr-2" />
                        Add Account
                    </button>
                </div>
            </div>
</x-app-layout>
