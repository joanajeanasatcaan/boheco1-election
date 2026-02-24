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
