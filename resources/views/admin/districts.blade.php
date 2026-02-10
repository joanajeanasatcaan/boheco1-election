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
                                    <h3 class="text-3xl font-bold text-gray-900">0</h3>
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
                                    <h3 class="text-3xl font-bold text-gray-900">0</h3>
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
                                <h3 class="text-3xl font-bold text-gray-900">0</h3>
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
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($districts as $item)
                                <tr
                                    class="group hover:bg-gradient-to-r hover:from-green-50/50 hover:to-emerald-50/50 transition-all duration-200 even:bg-gray-50/50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 flex-shrink-0 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="h-5 w-5 text-green-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM4 10a6 6 0 1112 0 6 6 0 01-12 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div
                                                    class="font-semibold text-gray-900 group-hover:text-green-700 transition-colors">
                                                    {{ $item->district_name }}</div>
                                                <div class="text-xs text-gray-500">ID: {{ $item->id ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700">
                                                {{ $item->nominees ?? 0 }}
                                            </span>
                                            <span class="text-xs text-gray-500 mt-1">nominees</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700">
                                                {{ $item->registered_voters ?? 0 }}
                                            </span>
                                            <span class="text-xs text-gray-500 mt-1">voters</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700">
                                                {{ $item->votes_cast ?? 0 }}
                                            </span>
                                            <span class="text-xs text-gray-500 mt-1">votes</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                                            @if ($item->status === 'Active') bg-gradient-to-r from-green-100 to-emerald-100 text-green-700
                                            @elseif($item->status === 'Inactive')
                                                bg-gradient-to-r from-red-100 to-pink-100 text-red-700
                                            @else
                                                bg-gradient-to-r from-gray-100 to-slate-100 text-gray-700 @endif
                                        ">
                                            @if ($item->status === 'Active')
                                                <span
                                                    class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                            @elseif($item->status === 'Inactive')
                                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                            @endif
                                            {{ $item->status ?? 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button onclick="openEditModal({{ $item->id }})"
                                                class="group/edit inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 hover:shadow-lg active:scale-95 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-1.5 group-hover/edit:rotate-12 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button onclick="openDeleteModal({{ $item->id }})"
                                                class="group/delete inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-semibold rounded-lg hover:from-red-600 hover:to-red-700 hover:shadow-lg active:scale-95 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-1.5 group-hover/delete:rotate-12 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="h-20 w-20 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                <svg class="h-10 w-10 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-700 mb-2">No districts found
                                            </h4>
                                            <p class="text-gray-500 mb-4">Get started by adding your first district
                                            </p>
                                            <button onclick="openAddDistrictModal()"
                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                                                <x-plus-logo class="h-5 w-5 mr-2" />
                                                Add First District
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add District Modal -->
    <div id="addDistrictModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="closeAddDistrictModal()">
                </div>
            </div>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full overflow-hidden">
                <!-- Modal header -->
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

                <!-- Modal form -->
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
</x-app-layout>

<script>
    function openAddDistrictModal() {
        document.getElementById('addDistrictModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeAddDistrictModal() {
        document.getElementById('addDistrictModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openEditModal(id) {
        console.log('Edit district:', id);
        alert('Edit functionality for district ' + id + ' would open here');
    }

    function openDeleteModal(id) {
        if (confirm('Are you sure you want to delete this district?')) {
            // Delete functionality
            console.log('Delete district:', id);
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddDistrictModal();
        }
    });

    // Enhanced search functionality
    function filterDistrictTable() {
        const searchInput = document.getElementById('search-input');
        const filter = searchInput.value.toLowerCase();
        const table = document.querySelector('tbody');
        const rows = table.querySelectorAll('tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const districtCell = row.querySelector('td:first-child');
            if (districtCell) {
                const text = districtCell.textContent.toLowerCase();
                if (text.includes(filter) || filter === '') {
                    row.style.display = '';
                    row.classList.add('animate-fadeIn');
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Update count display
        const countDisplay = document.querySelector('.showing-count');
        if (countDisplay) {
            countDisplay.textContent = visibleCount;
        }
    }

    // Add animation on page load
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            row.classList.add('animate-slideInUp');
        });
    });
</script>

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

    /* Hide scrollbar but keep functionality */
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
