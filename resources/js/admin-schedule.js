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