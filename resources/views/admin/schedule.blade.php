<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 
                leading-tight tracking-tight">
                    {{ __('Schedule') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Manage the election schedule every district.
                </p>
            </div>
            <div class="text-sm text-gray-500 mt-1">
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
                            class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br 
                            from-green-500/20 to-transparent rounded-full 
                            -translate-y-32 
                            translate-x-32">
                        </div>
                        <div class="relative">
                            <div class="flex flex-col md:flex-row md:items-center 
                            md:justify-between">
                                <div class="mb-6 md:mb-0">
                                    <h1 class="md:text-4xl font-bold text-white mb-2 drop-shadow">
                                        {{ __('Schedule') }}
                                    </h1>
                                    <p class="text-sm text-green-100/90 max-w-2xl">
                                        {{ __('Manage the election schedule every district.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-lg font-bold text-gray-900 mb-4">
                            {{ __('Election Schedule Calendar') }}
                        </div>

                        <!-- Calendar -->
                        <div class="bg-white rounded-lg shadow">
                            <!-- Calendar Header -->
                            <div class="bg-green-600 text-white p-4 rounded-t-lg">
                                <div class="flex justify-between items-center">
                                    <button class="text-xl">&lt;</button>
                                    <h2 class="text-xl font-bold" id="month-year">
                                        January 2026
                                    </h2>
                                    <button class="text-xl">&gt;</button>
                                </div>
                            </div>

                            <!-- Days of Week -->
                            <div class="grid grid-cols-7 gap-0 border-b">
                                <div class="p-3 text-center font-bold text-gray-600 bg-gray-50">Sun</div>
                                <div class="p-3 text-center font-bold text-gray-600 bg-gray-50">Mon</div>
                                <div class="p-3 text-center font-bold text-gray-600 bg-gray-50">Tue</div>
                                <div class="p-3 text-center font-bold text-gray-600 bg-gray-50">Wed</div>
                                <div class="p-3 text-center font-bold text-gray-600 bg-gray-50">Thu</div>
                                <div class="p-3 text-center font-bold text-gray-600 bg-gray-50">Fri</div>
                                <div class="p-3 text-center font-bold text-gray-600 bg-gray-50">Sat</div>
                            </div>

                            <!-- Calendar Days -->
                            <div class="grid grid-cols-7 gap-0" id="calendar-days">
                                <!-- Days will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const currentDate = new Date(); 

                function renderCalendar() {
                    const year = currentDate.getFullYear();
                    const month = currentDate.getMonth();

                    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];
                    document.getElementById('month-year').textContent = monthNames[month] + ' ' + year;

                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    const calendarDays = document.getElementById('calendar-days');
                    calendarDays.innerHTML = '';

                    for (let i = 0; i < firstDay; i++) {
                        const emptyCell = document.createElement('div');
                        emptyCell.className = 'p-3 border border-gray-200 bg-gray-50 h-20';
                        calendarDays.appendChild(emptyCell);
                    }

                    for (let day = 1; day <= daysInMonth; day++) {
                        const dayCell = document.createElement('div');
                        dayCell.className = 'p-3 border border-gray-200 h-20 hover:bg-green-50 cursor-pointer';
                        dayCell.innerHTML = `<div class="font-bold text-gray-900">${day}</div>`;

                        const today = new Date();
                        if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                            dayCell.className = 'p-3 border border-gray-200 h-20 bg-green-100 hover:bg-green-200 cursor-pointer';
                        }

                        calendarDays.appendChild(dayCell);
                    }
                }

                renderCalendar();
            </script>
</x-app-layout>