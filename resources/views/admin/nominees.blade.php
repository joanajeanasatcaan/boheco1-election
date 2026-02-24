<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Nominees') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Manage candidates for each district.
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
                    class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8 relative">
                        <div
                            class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-green-500/20 to-transparent rounded-full -translate-y-32 translate-x-32">
                        </div>
                        <div class="relative">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-6 md:mb-0">
                                    <h1 class="md:text-4xl font-bold text-white mb-2 drop-shadow">
                                        {{ __('Nominees Management') }}
                                    </h1>
                                    <p class="text-sm text-green-100/90 max-w-2xl">
                                        {{ __('Manage candidates for each district. Add, edit, and monitor nominee information.') }}
                                    </p>
                                </div>
                                <button onclick="openAddNomineeModal()"
                                    class="group text-sm inline-flex items-center justify-center px-6 
                                    py-3 bg-white text-green-700 font-semibold rounded-xl hover:bg-green-50 
                                    active:scale-[0.98] transition-all duration-200 shadow-lg hover:shadow-xl 
                                    focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 
                                    focus:ring-offset-green-700">
                                    <x-plus-logo class="h-5 w-5 mr-2 group-hover:rotate-90 transition-transform" />
                                    Add Nominee
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="relative flex-1">
                        <div class="relative">
                            <input type="text" id="search-input" placeholder="Search nominees by name..."
                                class="w-full px-4 py-3 pl-12 pr-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 placeholder-gray-400 text-gray-700">
                            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative">
                        <select id="district-filter"
                            class="w-full md:w-48 px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none">
                            <option value="">All Districts</option>
                            <option value="1">District 1</option>
                            <option value="2">District 2</option>
                            <option value="3">District 3</option>
                            <option value="4">District 4</option>
                            <option value="5">District 5</option>
                            <option value="6">District 6</option>
                            <option value="7">District 7</option>
                            <option value="8">District 8</option>
                            <option value="9">District 9</option>
                        </select>
                    </div>
                </div>
            </div>



            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">All Nominees</h3>
                        <p class="text-sm text-gray-500">Candidates across all districts</p>
                    </div>
                    <div class="text-sm text-gray-500" id="nominee-count">
                        Showing 0 nominees
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 
                xl:grid-cols-4 gap-6"
                    id="nominees-grid">

                    <div class="text-center py-12 text-gray-500 col-span-full">
                        <svg class="h-12 w-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p>No nominees found. Add your first nominee to get started.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addNomineeModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeAddNomineeModal()"></div>
            </div>

            <div
                class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                                <x-plus-logo class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-white">Add New Nominee</h3>
                        </div>
                        <button onclick="closeAddNomineeModal()"
                            class="h-8 w-8 rounded-lg flex items-center justify-center hover:bg-white/20 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form id="addNomineeForm" class="px-6 py-6"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div class="text-center">
                            <div class="relative inline-block">
                                <div
                                    class="h-32 w-32 rounded-full border-4 border-white shadow-lg bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                    <img id="profile-preview" src="" alt=""
                                        class="h-full w-full object-cover hidden">
                                    <div id="profile-placeholder"
                                        class="h-full w-full flex items-center justify-center text-gray-400">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <label for="image"
                                    class="absolute bottom-0 right-0 h-10 w-10 bg-green-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-green-700 transition-colors">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" id="image" name="image" accept="image/*"
                                        class="hidden" onchange="previewProfileImage(event)">
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Upload nominee profile picture</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                    placeholder="Enter first name">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Middle Name
                                </label>
                                <input type="text" name="middle_name"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                    placeholder="Enter middle name">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                    placeholder="Enter last name">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nickname
                                </label>
                                <input type="text" name="nickname" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                                    placeholder="Enter nickname">
                            </div>
                        </div>


                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Suffix
                            </label>
                            <select name="suffix"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 bg-white">
                                <option value="">Select Suffix</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                District <span class="text-red-500">*</span>
                            </label>
                            <select name="district" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 bg-white">
                                <option value="">Select District</option>
                                <option value="1">District 1</option>
                                <option value="2">District 2</option>
                                <option value="3">District 3</option>
                                <option value="4">District 4</option>
                                <option value="5">District 5</option>
                                <option value="6">District 6</option>
                                <option value="7">District 7</option>
                                <option value="8">District 8</option>
                                <option value="9">District 9</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Town <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="town" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none transition-all duration-200 placeholder-gray-400"
                            placeholder="Enter town">
                    </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="button" onclick="closeAddNomineeModal()"
                    class="flex-1 px-4 py-3 text-gray-700 font-medium border border-gray-300 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all duration-200">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:shadow-lg active:scale-[0.98] transition-all duration-200">
                    Add Nominee
                </button>
            </div>
            </form>
        </div>
    </div>
    </div>

    @vite('resources/js/admin-nominees.js')
</x-app-layout>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }
</style>
