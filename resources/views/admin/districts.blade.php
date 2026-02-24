<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Districts') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Manage election districts and monitor voting progress
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
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-6 md:mb-0">
                                    <h1 class="md:text-4xl font-bold text-white mb-2 drop-shadow">
                                        {{ __('Districts Management') }}
                                    </h1>
                                    <p class="text-sm text-green-100/90 max-w-2xl">
                                        {{ __('Overview and management of election districts.') }}
                                    </p>
                                </div>
                                <button onclick="openAddDistrictModal()"
                                    class="group text-sm inline-flex items-center justify-center 
                                    px-6 py-3 bg-white text-green-700 font-semibold rounded-xl 
                                    hover:bg-green-50 active:scale-[0.98] transition-all 
                                    duration-200 shadow-lg hover:shadow-xl focus:outline-none 
                                    focus:ring-2 focus:ring-white focus:ring-offset-2 
                                    focus:ring-offset-green-700">
                                    <x-plus-logo class="h-5 w-5 mr-2 group-hover:rotate-90 transition-transform" />
                                    Add New District
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Districts Card -->
                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                <x-total-districts-logo class="h-8 w-8 text-green-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">{{ __('Total Districts') }}</p>
                                <div class="flex items-end justify-between">
                                    <h3 id="total-districts" class="text-3xl font-bold text-gray-900">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Voters Card -->
                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                <x-voters-logo class="h-8 w-8 text-blue-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">{{ __('Total Voters') }}</p>
                                <div class="flex items-end justify-between">
                                    <h3 id="total-voters" class="text-3xl font-bold text-gray-900">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Election Card -->
                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-purple-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                <x-active-logo class="h-8 w-8 text-purple-600" />
                            </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('Active Election') }}</p>
                            <div class="flex items-end justify-between">
                                <h3 id="total-votes" class="text-3xl font-bold text-gray-900">0</h3>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Districts Table Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 mb-8">
                <!-- Table Header -->
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ __('All Districts') }}</h3>
                            <p class="text-sm text-gray-500 mt-1">List of all districts with election status and
                                statistics</p>
                        </div>
                        <div class="relative">
                            <div class="relative">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request('search', '') }}" placeholder="Search districts by name..."
                                    class="w-full md:w-64 px-4 py-3 pl-10 pr-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 placeholder-gray-400 text-gray-700">
                                <svg class="absolute left-3 top-3.5 h-4 w-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-600 to-emerald-600">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        District Name
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Nominees</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Registered Voters</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Votes Cast</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="district-table-body" class="divide-y divide-gray-100 bg-white">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="addDistrictModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeAddDistrictModal()">
                </div>
            </div>

            <div
                class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                                <x-plus-logo class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-white">Add New District</h3>
                        </div>
                        <button onclick="closeAddDistrictModal()"
                            class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-white/20 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form id="addDistrictForm" method="POST" action="{{ route('districts.store') }}"
                    class="px-6 py-6">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                District Name
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="district_name" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                placeholder="Enter district name">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="Active" required
                                        class="peer sr-only">
                                    <div
                                        class="flex items-center justify-center p-4 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 transition-colors">
                                            </div>
                                            <span
                                                class="font-medium text-gray-700 peer-checked:text-green-700">Active</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="Inactive" required
                                        class="peer sr-only">
                                    <div
                                        class="flex items-center justify-center p-4 rounded-xl border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:border-red-500 peer-checked:bg-red-500 mr-3 transition-colors">
                                            </div>
                                            <span
                                                class="font-medium text-gray-700 peer-checked:text-red-700">Inactive</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" onclick="closeAddDistrictModal()"
                            class="flex-1 px-4 py-3 text-gray-700 font-medium border border-gray-300 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all duration-200">
                            Add District
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @vite('resources/js/admin-districts.js')
</x-app-layout>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slideInUp {
        animation: slideInUp 0.3s ease-out forwards;
    }

    .overflow-x-auto {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db transparent;
    }

    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
        border-radius: 3px;
    }

</style>
