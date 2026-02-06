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
                                class="district-btn active px-4 py-1 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
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
        const tallyData = {
            'all': [
                {
                    district: "District 1",
                    votesCast: 1234,
                    totalVoters: 2000,
                    turnout: 62,
                    status: "Live",
                    candidates: [
                        { name: "Joana Valdez", votes: 456, percentage: 37 },
                        { name: "Hilario Rosco", votes: 423, percentage: 34 },
                        { name: "hahhahahaha", votes: 198, percentage: 16 },
                        { name: "blablabla", votes: 157, percentage: 13 }
                    ]
                },
            ]
        };

        let currentDistrict = 'all';

        function showDistrict(district) {
            currentDistrict = district;
            
            document.querySelectorAll('.district-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'border-gray-300');
                btn.classList.add('border-gray-200');
                btn.querySelector('p').classList.remove('text-gray-900');
                btn.querySelector('p').classList.add('text-gray-800');
            });
            
            const activeBtn = event.currentTarget;
            activeBtn.classList.add('active', 'bg-white', 'border-gray-300');
            activeBtn.classList.remove('border-gray-200');
            activeBtn.querySelector('p').classList.add('text-gray-900');
            activeBtn.querySelector('p').classList.remove('text-gray-800');
            
            loadDistrictData(district);
        }

        function loadDistrictData(district) {
            const data = tallyData[district];
            const tallyGrid = document.getElementById('tally-grid');
            
            let totalVotes = 0;
            let totalVoters = 0;
            
            data.forEach(districtData => {
                totalVotes += districtData.votesCast;
                totalVoters += districtData.totalVoters;
            });
            
            const turnoutPercentage = totalVoters > 0 ? Math.round((totalVotes / totalVoters) * 100) : 0;
            
            document.getElementById('total-votes').textContent = totalVotes.toLocaleString();
            document.getElementById('registered-voters').textContent = totalVoters.toLocaleString();
            document.getElementById('turnout-percentage').textContent = `${turnoutPercentage}%`;
            
            document.getElementById('tally-results').textContent = `Showing ${data.length} ${data.length === 1 ? 'tally' : 'tallies'}`;
            
            tallyGrid.innerHTML = '';
            
            data.forEach(districtData => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300';
                card.innerHTML = `
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">${districtData.district}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    ${districtData.votesCast.toLocaleString()} of ${districtData.totalVoters.toLocaleString()} votes
                                    <span class="font-semibold">(${districtData.turnout}% turnout)</span>
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                ${districtData.status}
                            </span>
                        </div>
                        
                        <div class="space-y-4">
                            ${districtData.candidates.map(candidate => `
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">${candidate.name}</h4>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">${candidate.votes.toLocaleString()} votes</p>
                                        <p class="text-sm text-gray-600">${candidate.percentage}%</p>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                tallyGrid.appendChild(card);
            });
        }

        function showAllDistricts() {
            showDistrict('all');
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadDistrictData('all');
        });

        function exportToCSV() {
            alert('Export functionality would be implemented here');
        }
    </script>

</x-app-layout>