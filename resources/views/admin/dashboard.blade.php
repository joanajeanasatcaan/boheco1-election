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
                    {{ __('Admin Dashboard') }}
                </div>
                <a class="p-3 text-gray-500">
                    Manage districts, nominees, and monitor election progress.
                </a>

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

                        <!-- Total Nominees -->
                        <div class="bg-white border border-gray-100 overflow-hidden shadow-md sm:rounded-lg p-6">
                            <div class="flex mb-4 items-center">
                                <x-nominees-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-sm text-gray-500">
                                        {{ __('Total Nominees') }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Votes Cast -->
                        <div class="bg-white border border-gray-100 pt-8 overflow-hidden shadow-md sm:rounded-lg p-6">
                            <div class="flex mb-4 items-center">
                                <x-votes-cast-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-sm text-gray-500">
                                        {{ __('Votes Cast') }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                    <div class="grid grid-cols-2 gap-5">

                        <!-- Voting Progress by District -->
                        <div class="bg-white p-6">
                            <div class="flex mb-4 items-center">
                                <x-votes-cast-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ __('Voting Progress by District') }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        Voting turnout across all districts.
                                    </div>
                                </div>
                            </div>

                            <!-- Add to resources/views/admin/dashboard.blade.php -->
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                            <div class="bg-white p-6 rounded-lg shadow">
                                <canvas id="votingChart"></canvas>
                            </div>

                            <script>
                                const ctx = document.getElementById('votingChart').getContext('2d');
                                const votingChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['District 1', 'District 2', 'District 3', 'District 4', 'District 5'],
                                        datasets: [{
                                            data: [150, 230, 180, 210, 195],
                                            backgroundColor: 'rgba(34, 197, 94, 0.6)',
                                            borderColor: 'rgba(34, 197, 94, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        indexAxis: 'y',
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                display: true,
                                                position: 'top'
                                            }
                                        },
                                        scales: {
                                            x: { 
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 50
                                                }
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>

                        <!-- Recent History -->
                        <div class="bg-white p-6">
                            <div class="flex mb-4 items-center">
                                <x-history-logo class="h-12 w-12 mr-4" />
                                <div class="pl-2">
                                    <div class="text-xl font-bold text-gray-900">
                                        {{ __('Recent Activity') }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        Latest actions in the system.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
</x-app-layout>
