window.toggleScheduleDetails = function() {
    const details = document.getElementById('schedule-details');
    const icon = document.getElementById('schedule-toggle-icon');
    details.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
};
// ─── Schedule summary ─────────────────────────────────────────────────────────
async function loadScheduleSummary() {
    try {
        const response = await fetch('http://192.168.1.5:8000/api/admin/schedules', {
            headers: { 'Accept': 'application/json' },
            credentials: 'include',
            
        });
        if (!response.ok) throw new Error('Failed to load schedules');
        const result    = await response.json();
        const schedules = result.data ?? [];

        const summaryEl = document.getElementById('schedule-summary');
        const countEl   = document.getElementById('schedule-district-count');
        const nextEl    = document.getElementById('schedule-next');
        const allEl     = document.getElementById('schedule-all');
        const toggleBtn = document.getElementById('schedule-toggle-btn');

        if (!schedules.length) {
            if (summaryEl) summaryEl.textContent = 'No schedules set';
            if (countEl)   countEl.textContent   = '0 Districts';
            if (nextEl)    nextEl.innerHTML = `<p class="text-sm text-gray-400 italic">No upcoming schedules.</p>`;
            return;
        }

        // ── Helpers ───────────────────────────────────────────────
        const today    = new Date();
        today.setHours(0, 0, 0, 0);

        const sorted   = [...schedules].sort((a, b) => new Date(a.scheduled_date) - new Date(b.scheduled_date));

        function getStatus(dateStr) {
            const d = new Date(dateStr);
            d.setHours(0, 0, 0, 0);
            if (d.toDateString() === today.toDateString()) return 'today';
            if (d < today) return 'finished';
            return 'upcoming';
        }

        function badgeHtml(status) {
            if (status === 'today')    return `<span class="text-xs font-medium text-green-600 bg-green-50 px-3 py-1.5 rounded-full whitespace-nowrap">Active Today</span>`;
            if (status === 'finished') return `<span class="text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1.5 rounded-full whitespace-nowrap">Finished</span>`;
            return `<span class="text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full whitespace-nowrap">Upcoming</span>`;
        }

        function rowHtml(s, highlight = false) {
            const d        = new Date(s.scheduled_date);
            const status   = getStatus(s.scheduled_date);
            const monthDay = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const year     = d.getFullYear();
            const dayName  = d.toLocaleDateString('en-US', { weekday: 'long' });
            const bg       = highlight ? 'bg-green-50 border-green-100' : 'bg-gray-50 border-gray-100';
            const opacity  = status === 'finished' ? 'opacity-60' : '';

            return `
                <div class="flex items-center justify-between p-4 ${bg} ${opacity} rounded-lg border">
                    <div class="flex items-center gap-4">
                        <div class="text-center min-w-[3rem]">
                            <span class="text-sm font-semibold text-gray-900">${monthDay}</span>
                            <p class="text-xs text-gray-500">${year}</p>
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${s.district}</p>
                            <p class="text-xs text-gray-500">${dayName} · 8:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                    ${badgeHtml(status)}
                </div>
            `;
        }

        // ── Summary header ────────────────────────────────────────
        const formatter = new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric' });
        const firstDate = new Date(sorted[0].scheduled_date);
        const lastDate  = new Date(sorted[sorted.length - 1].scheduled_date);
        if (summaryEl) summaryEl.textContent =
            `${formatter.format(firstDate)} – ${formatter.format(lastDate)}, ${lastDate.getFullYear()}`;
        if (countEl) countEl.textContent = `${schedules.length} District${schedules.length !== 1 ? 's' : ''}`;

        // ── Next upcoming row ─────────────────────────────────────
        const upcoming = sorted.filter(s => getStatus(s.scheduled_date) !== 'finished');
        const next     = upcoming.length ? upcoming[0] : sorted[sorted.length - 1];
        if (nextEl) nextEl.innerHTML = rowHtml(next, true);

        // ── Full list ─────────────────────────────────────────────
        if (allEl) allEl.innerHTML = sorted.map(s => rowHtml(s, getStatus(s.scheduled_date) === 'today')).join('');

        // ── Burger toggle ─────────────────────────────────────────
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const isExpanded = !allEl.classList.contains('hidden');

                if (isExpanded) {
                    // Collapse — show next only
                    allEl.classList.add('hidden');
                    nextEl.classList.remove('hidden');
                    toggleBtn.title = 'View all schedules';
                    toggleBtn.innerHTML = `
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>`;
                } else {
                    // Expand — show full list
                    allEl.classList.remove('hidden');
                    nextEl.classList.add('hidden');
                    toggleBtn.title = 'Show next only';
                    toggleBtn.innerHTML = `
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>`;
                }
            });
        }

    } catch (err) {
        console.error('Schedule summary error:', err);
        const summaryEl = document.getElementById('schedule-summary');
        if (summaryEl) summaryEl.textContent = 'Unable to load schedule';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('votingChart').getContext('2d');

    const labels = ['District 1', 'District 2', 'District 3', 'District 4', 'District 5', 'District 6', 'District 7', 'District 8', 'District 9'];

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(34, 197, 94, 0.8)');
    gradient.addColorStop(1, 'rgba(34, 197, 94, 0.2)');

    const votingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Votes Cast',
                data: new Array(labels.length).fill(0),
                backgroundColor: gradient,
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#1f2937',
                    bodyColor: '#4b5563',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return `Votes: ${context.parsed.x}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { drawBorder: false, color: 'rgba(229, 231, 235, 0.5)' },
                    ticks: { padding: 10, font: { size: 11 }, color: '#6b7280' }
                },
                y: {
                    grid: { display: false, drawBorder: false },
                    ticks: { padding: 10, font: { size: 12, weight: '500' }, color: '#374151' }
                }
            },
            interaction: { intersect: false, mode: 'index' },
            animation: { duration: 1000, easing: 'easeOutQuart' }
        }
    });

    async function loadChart() {
        const response = await fetch('/api/admin/dashboard-district-counts', {
            credentials: 'include'
        });
        const result = await response.json();
        const data = result.by_district;

        const votesArray = new Array(labels.length).fill(0);
        data.forEach(item => {
            const index = labels.indexOf(item.district);
            if (index !== -1) votesArray[index] = item.votes_count;
        });

        const grandTotal = votesArray.reduce((acc, val) => Number(acc) + Number(val), 0);

        votingChart.data.datasets[0].data = votesArray;
        votingChart.update();

        const totalNomineesEl = document.getElementById('total-nominees-count');
        if (totalNomineesEl) totalNomineesEl.innerText = Number(result.total_votes).toLocaleString();

        document.querySelectorAll('.total-votes-count').forEach(el => {
            el.innerText = grandTotal.toLocaleString();
        });
    }

    // ✅ Load both on init
    loadChart();
    loadScheduleSummary();
});