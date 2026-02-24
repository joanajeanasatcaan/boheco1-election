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
                    class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8 relative">
                        <div
                            class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-green-500/20 to-transparent rounded-full -translate-y-32 translate-x-32">
                        </div>
                        <div class="relative">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-6 md:mb-0">
                                    <h1 class="text-lg md:text-4xl font-bold text-white mb-2 drop-shadow">
                                        {{ __('Tally Results') }}
                                    </h1>
                                    <p class="text-sm text-green-100/90 max-w-2xl">
                                        {{ __('Monitor election results across all districts.') }}
                                    </p>
                                </div>
                                <button onclick="exportToCSV()" class="group text-sm 
                                inline-flex items-center justify-center px-4 py-2 bg-white 
                                text-green-700 font-semibold rounded-xl hover:bg-green-50 
                                active:scale-[0.98] transition-all duration-200 shadow-lg 
                                hover:shadow-xl focus:outline-none focus:ring-2 
                                focus:ring-white focus:ring-offset-2 focus:ring-offset-green-700">
                                    <x-export-logo class="h-5 w-5 mr-2" />
                                    Export Results
                                </button>
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
                                    <h3 id="total-votes" class="text-3xl font-bold text-gray-900">0</h3>
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
                                    <h3 id="registered-voters" class="text-3xl font-bold text-gray-900">0</h3>
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
                                <x-tally-results-logo class="h-8 w-8 text-purple-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">{{ __('Overall Turnout') }}</p>
                                <div class="flex items-end justify-between">
                                    <h3 id="turnout-percentage" class="text-3xl font-bold text-gray-900">0%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex rounded-lg border-transparent bg-gray-200 p-2 space-x-2" id="district-buttons">
                        <button onclick="showDistrict('all')" 
                                class="district-bt  n active px-4 py-1 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                            <p class="text-sm font-bold text-gray-900">All Districts</p>
                        </button>
                        <button onclick="showDistrict(1)" 
                                class="district-btn px-4 py-1 rounded-lg border border-gray-200 hover:bg-white transition-colors">
                            <p class="text-sm font-bold text-gray-800">District 1</p>
                        </button>
                        <button onclick="showDistrict(2)" 
                                class="district-btn px-4 py-1 rounded-lg border border-gray-200 hover:bg-white transition-colors">
                            <p class="text-sm font-bold text-gray-800">District 2</p>
                        </button>
                        <button onclick="showDistrict(3)" 
                                class="district-btn px-4 py-1 rounded-lg border border-gray-200 hover:bg-white transition-colors">
                            <p class="text-sm font-bold text-gray-800">District 3</p>
                        </button>
                    </div>
                    <div class="text-sm text-gray-500" id="tally-results">
                        Showing 0 tallies
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="tally-grid">
                </div>
            </div>
        </div>
    </div>
<script>
    let currentDistrict = 'all';

    function showDistrict(district, btn = null) {
        currentDistrict = district;

        if (btn) {
            document.querySelectorAll('.district-btn').forEach(b => {
                b.classList.remove('active', 'bg-white', 'border-gray-300');
                b.classList.add('border-gray-200');
                b.querySelector('p')?.classList.remove('text-gray-900');
                b.querySelector('p')?.classList.add('text-gray-800');
            });

            btn.classList.add('active', 'bg-white', 'border-gray-300');
            btn.classList.remove('border-gray-200');
            btn.querySelector('p')?.classList.add('text-gray-900');
            btn.querySelector('p')?.classList.remove('text-gray-800');
        }

        loadDistrictData(district);
    }

    async function loadDistrictData(district = 'all') {
        const tallyGrid = document.getElementById('tally-grid');
        tallyGrid.innerHTML = '<p class="text-gray-500">Loading results...</p>';

        const response = await fetch(`/api/tally-results?district=${district}`);
        const data = await response.json();

        console.log('Loaded tally data:', data);

        let totalVotes = 0;
        let totalVoters = 0;

        data.forEach(d => {
            totalVotes += d.votesCast;
            totalVoters += d.totalVoters;
        });

        const turnout = totalVoters > 0
            ? Math.round((totalVotes / totalVoters) * 100)
            : 0;

        document.getElementById('total-votes').textContent = totalVotes.toLocaleString();
        document.getElementById('registered-voters').textContent = totalVoters.toLocaleString();
        document.getElementById('turnout-percentage').textContent = `${turnout}%`;
        document.getElementById('tally-results').textContent =
            `Showing ${data.length} ${data.length === 1 ? 'tally' : 'tallies'}`;

        tallyGrid.innerHTML = '';

        data.forEach(districtData => {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200';

            card.innerHTML = `
                <div class="p-6">
                    <h3 class="text-lg font-bold">${districtData.district}</h3>
                    <p class="text-sm text-gray-600 mb-3">
                        ${districtData.votesCast} / ${districtData.totalVoters}
                        (${districtData.turnout}% turnout)
                    </p>

                    ${districtData.candidates.map(c => `
                        <div class="flex justify-between bg-gray-50 p-3 rounded mb-2">
                            <span>${c.name}</span>
                            <span class="font-bold">${c.votes} (${c.percentage}%)</span>
                        </div>
                    `).join('')}
                </div>
            `;

            tallyGrid.appendChild(card);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadDistrictData('all');
    });
</script>


</x-app-layout>