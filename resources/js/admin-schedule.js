let currentDate    = new Date();
let assignments    = {};
let activeEditingDate = null;

const districtOptions = [
    'District 1','District 2','District 3','District 4','District 5',
    'District 6','District 7','District 8','District 9'
];
const monthNames = [
    'January','February','March','April','May','June',
    'July','August','September','October','November','December'
];

const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function isoToDateKey(isoDate) {
    const [y, m, d] = isoDate.split('-');
    return `${parseInt(y)}-${parseInt(m) - 1}-${parseInt(d)}`;
}

async function loadSchedules(year, month) {
    try {
        const res = await fetch(`/api/admin/schedules?year=${year}&month=${month + 1}`, {
            headers: { 'Accept': 'application/json' },
            credentials: 'include',
        });
        if (!res.ok) throw new Error('Failed to load schedules');
        const data = await res.json();

        console.log('API returned:', data.data);
        console.log('Built assignments:', assignments);

        assignments = {};
        data.data.forEach(function(s) {
            const dateKey = isoToDateKey(s.scheduled_date); // ✅ "2026-03-05" → "2026-2-5"
            assignments[dateKey] = { id: s.id, district: s.district };
        });

        renderCalendar();
    } catch (err) {
        console.error('Load schedules error:', err);
    }
}

async function saveSchedule(dateKey, district) {
    try {
         const [y, m, d] = dateKey.split('-');
        const isoDate = `${y}-${String(parseInt(m) + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;

        const existing = assignments[dateKey];

        if (existing) {
            // Update
            const res = await fetch(`/api/admin/schedules/${existing.id}`, {
                method:  'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
                credentials: 'include',
                body: JSON.stringify({ district }),
            });
            if (!res.ok) throw new Error('Update failed');
            const data = await res.json();
            assignments[dateKey] = { id: data.data.id, district: data.data.district };

        } else {
            // Create
            const res = await fetch('/api/admin/schedules', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
                credentials: 'include',
                body: JSON.stringify({ scheduled_date: isoDate, district }),
            });
            if (!res.ok) throw new Error('Save failed');
            const data = await res.json();
            assignments[dateKey] = { id: data.data.id, district: data.data.district };
        }

        renderCalendar();

    } catch (err) {
        console.error('Schedule save error:', err);
        alert('Failed to save schedule. Please try again.');
    }
}

async function deleteSchedule(dateKey) {
    const existing = assignments[dateKey];
    if (!existing) return;

    if (!confirm(`Remove ${existing.district} from this date?`)) return;

    try {
        const res = await fetch(`/api/admin/schedules/${existing.id}`, {
            method:  'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            credentials: 'include',
        });
        if (!res.ok) throw new Error('Delete failed');

        // ✅ Only remove from assignments AFTER API confirms success
        delete assignments[dateKey];
        renderCalendar();

    } catch (err) {
        console.error('Schedule delete error:', err);
        alert('Failed to remove schedule. Please try again.');
        renderCalendar(); // re-render to restore the label
    }
}

// ─── Calendar render ──────────────────────────────────────────────────────────
function renderCalendar() {
    const year  = currentDate.getFullYear();
    const month = currentDate.getMonth();

    document.getElementById('month-year').textContent = monthNames[month] + ' ' + year;

    const firstDay     = new Date(year, month, 1).getDay();
    const daysInMonth  = new Date(year, month + 1, 0).getDate();
    const calendarDays = document.getElementById('calendar-days');
    calendarDays.innerHTML = '';

    for (let i = 0; i < firstDay; i++) {
        const empty = document.createElement('div');
        empty.className = 'p-3 border border-gray-200 bg-gray-50 h-24';
        calendarDays.appendChild(empty);
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey  = `${year}-${month}-${day}`;
        const current  = assignments[dateKey];

        const cellDate = new Date(year, month, day);
        cellDate.setHours(0, 0, 0, 0);
        const isToday  = cellDate.getTime() === today.getTime();
        const isPast   = cellDate < today && !isToday;

        const dayCell  = document.createElement('div');
        dayCell.className = 'p-3 border border-gray-200 h-24 relative flex flex-col transition-all'
            + (isToday ? ' bg-green-50 ring-2 ring-inset ring-green-400' : '')
            + (isPast  ? ' bg-gray-50 cursor-not-allowed' : ' hover:bg-gray-50 cursor-pointer');

        dayCell.innerHTML = `<div class="font-bold ${isPast ? 'text-gray-400' : (isToday ? 'text-green-700' : 'text-gray-900')}">${day}</div>`;

        if (activeEditingDate === dateKey && !isPast) {
            const select = document.createElement('select');
            select.className = 'mt-2 text-xs border border-blue-500 rounded p-1 w-full bg-white outline-none';
            select.onclick = (e) => e.stopPropagation();

            const noneOpt  = document.createElement('option');
            noneOpt.text   = '-- None --';
            noneOpt.value  = '';
            select.appendChild(noneOpt);

            const usedDistricts = Object.entries(assignments)
                .filter(([key]) => key !== dateKey)
                .map(([, val]) => val.district);

            districtOptions.forEach(opt => {
                if (!usedDistricts.includes(opt)) {
                    const el       = document.createElement('option');
                    el.textContent = opt;
                    el.value       = opt;
                    if (current && opt === current.district) el.selected = true;
                    select.appendChild(el);
                }
            });

            select.onchange = async (e) => {
                e.stopPropagation();
                const val = e.target.value;
                activeEditingDate = null;

                if (val) {
                    await saveSchedule(dateKey, val);
                } else if (current) {
                    await deleteSchedule(dateKey);
                } else {
                    renderCalendar();
                }
            };

            dayCell.appendChild(select);
            setTimeout(() => select.focus(), 0);

        } else if (current) {
            const label     = document.createElement('div');
            label.className = 'mt-auto text-xs px-2 py-1 rounded truncate text-center '
                + (isPast ? 'bg-gray-300 text-gray-500' : 'bg-green-600 text-white');
            label.textContent = current.district;
            dayCell.appendChild(label);

            if (isPast) {
                const fin       = document.createElement('div');
                fin.className   = 'text-xs text-gray-400 text-center mt-0.5';
                fin.textContent = 'Finished';
                dayCell.appendChild(fin);
            }
        }

        if (!isPast) {
            dayCell.onclick = (e) => {
                e.stopPropagation();
                activeEditingDate = dateKey;
                renderCalendar();
            };
        }

        calendarDays.appendChild(dayCell);
    }
}
// ─── Navigation ───────────────────────────────────────────────────────────────
function changeMonth(direction) {
    currentDate.setMonth(currentDate.getMonth() + direction);
    activeEditingDate = null;
    loadSchedules(currentDate.getFullYear(), currentDate.getMonth());
}

window.onclick = () => {
    if (activeEditingDate !== null) {
        activeEditingDate = null;
        renderCalendar();
    }
};

// ─── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    var prevBtn = document.querySelector('[data-nav="prev"]');
    var nextBtn = document.querySelector('[data-nav="next"]');

    if (prevBtn) prevBtn.addEventListener('click', function(e) { e.stopPropagation(); changeMonth(-1); });
    if (nextBtn) nextBtn.addEventListener('click', function(e) { e.stopPropagation(); changeMonth(+1); });

    loadSchedules(currentDate.getFullYear(), currentDate.getMonth());
});