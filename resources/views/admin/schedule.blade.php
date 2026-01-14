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
                    {{ __("Schedule") }}
                </div>
                <a class="p-3 text-gray-500">
                    Manage the election schedule every district.
                </a>

                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-lg font-bold text-gray-900 mb-4">
                            {{ __("Election Schedule Calendar") }}
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
    </div>

    <script>
        let currentDate = new Date(2026, 0, 13); 

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            // Update month/year display
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'];
            document.getElementById('month-year').textContent = monthNames[month] + ' ' + year;
            
            // Get first day and number of days in month
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            // Clear calendar
            const calendarDays = document.getElementById('calendar-days');
            calendarDays.innerHTML = '';
            
            // Add empty cells for days before month starts
            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'p-3 border border-gray-200 bg-gray-50 h-20';
                calendarDays.appendChild(emptyCell);
            }
            
            // Add days of month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = document.createElement('div');
                dayCell.className = 'p-3 border border-gray-200 h-20 hover:bg-green-50 cursor-pointer';
                dayCell.innerHTML = `<div class="font-bold text-gray-900">${day}</div>`;
                
                // Highlight today
                if (day === 13 && month === 0 && year === 2026) {
                    dayCell.className = 'p-3 border border-gray-200 h-20 bg-green-100 hover:bg-green-200 cursor-pointer';
                }
                
                calendarDays.appendChild(dayCell);
            }
        }

        // Initialize calendar
        renderCalendar();
    </script>
</x-app-layout>
