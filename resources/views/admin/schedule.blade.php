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
                const assignments = {}; 
                const districtOptions = ['District 1', 'District 2', 'District 3', 'District 4', 'District 5', 'District 6', 'District 7', 'District 8', 'District 9'];
                let activeEditingDate = null; 

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
                        emptyCell.className = 'p-3 border border-gray-200 bg-gray-50 h-24';
                        calendarDays.appendChild(emptyCell);
                    }

                    for (let day = 1; day <= daysInMonth; day++) {
                        const dayCell = document.createElement('div');
                        const dateKey = `${year}-${month}-${day}`;
                        const currentSelection = assignments[dateKey];

                        dayCell.className = 'p-3 border border-gray-200 h-24 hover:bg-gray-50 cursor-pointer relative flex flex-col transition-all';
                        dayCell.innerHTML = `<div class="font-bold text-gray-900">${day}</div>`;

                        if (activeEditingDate === dateKey) {
                            const select = document.createElement('select');
                            select.className = 'mt-2 text-xs border border-blue-500 rounded p-1 w-full bg-white outline-none';
                            
                            select.onclick = (e) => e.stopPropagation();

                            const defaultOpt = document.createElement('option');
                            defaultOpt.text = '-- Select District --';
                            defaultOpt.value = "";
                            select.appendChild(defaultOpt);

                            const usedDistricts = Object.entries(assignments)
                                .filter(([key, val]) => key !== dateKey)
                                .map(([key, val]) => val);

                            districtOptions.forEach(opt => {
                                if (!usedDistricts.includes(opt)) {
                                    const el = document.createElement('option');
                                    el.textContent = opt;
                                    el.value = opt;
                                    if (opt === currentSelection) el.selected = true;
                                    select.appendChild(el);
                                }
                            });

                            select.onchange = (e) => {
                                e.stopPropagation(); 
                                if (e.target.value) assignments[dateKey] = e.target.value;
                                else delete assignments[dateKey];
                                
                                activeEditingDate = null; 
                                renderCalendar();
                            };

                            dayCell.appendChild(select);
                            setTimeout(() => select.focus(), 0);

                        } else if (currentSelection) {
                            const label = document.createElement('div');
                            label.className = 'mt-auto text-xs bg-green-600 text-white px-2 py-1 rounded truncate text-center';
                            label.textContent = currentSelection;
                            dayCell.appendChild(label);
                        }

                        dayCell.onclick = (e) => {
                            e.stopPropagation(); 
                            activeEditingDate = dateKey;
                            renderCalendar();
                        };

                        calendarDays.appendChild(dayCell);
                    }
                }

                window.onclick = () => {
                    if (activeEditingDate !== null) {
                        activeEditingDate = null;
                        renderCalendar();
                    }
                };

                renderCalendar();
            </script>
</x-app-layout>