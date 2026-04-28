let currentDistrict = 'all';

function showDistrict(district, btn = null) {
    currentDistrict = district;

    document.querySelectorAll('.district-btn').forEach(b => {
        b.classList.remove('active', 'bg-white', 'border-gray-300');
        b.classList.add('border-gray-200');
        b.querySelector('p')?.classList.remove('text-gray-900');
        b.querySelector('p')?.classList.add('text-gray-800');
    });

    if (btn) {
        btn.classList.add('active', 'bg-white', 'border-gray-300');
        btn.classList.remove('border-gray-200');
        btn.querySelector('p')?.classList.add('text-gray-900');
        btn.querySelector('p')?.classList.remove('text-gray-800');
    }

    loadDistrictData(district);
}

async function loadDistrictData(district = 'all') {
    const tallyGrid = document.getElementById('tally-grid');
    tallyGrid.innerHTML = `
        <div class="col-span-full flex items-center justify-center py-12">
            <div class="flex flex-col items-center gap-3 text-gray-400">
                <svg class="animate-spin h-8 w-8" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="text-sm font-medium">Loading results…</p>
            </div>
        </div>`;

    try {
        const response = await fetch(`/api/admin/tally-results?district=${district}`, {
            credentials: 'include'
        });
        const data = await response.json();

        let totalVotes  = 0;
        let totalVoters = 0;
        let totalAbstain = 0;

        data.forEach(d => {
            totalVotes   += d.votesCast;
            totalVoters  += d.totalVoters;
            totalAbstain += d.abstainCount ?? 0;
        });

        const turnout = totalVoters > 0
            ? Math.round((totalVotes / totalVoters) * 100)
            : 0;

        document.getElementById('total-votes').textContent        = totalVotes.toLocaleString();
        document.getElementById('registered-voters').textContent  = totalVoters.toLocaleString();
        document.getElementById('turnout-percentage').textContent = `${turnout}%`;
        document.getElementById('tally-results').textContent      =
            `Showing ${data.length} ${data.length === 1 ? 'tally' : 'tallies'}`;

        tallyGrid.innerHTML = '';

        if (data.length === 0) {
            tallyGrid.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400">
                    <svg class="h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm font-medium">No tally data available</p>
                </div>`;
            return;
        }

        data.forEach(districtData => {
            const abstainCount    = districtData.abstainCount    ?? 0;
            const candidateVotes  = districtData.candidateVotes  ?? districtData.votesCast;
            const totalVotesCast  = districtData.votesCast;
            const abstainPct      = totalVotesCast > 0
                ? Math.round((abstainCount / totalVotesCast) * 100)
                : 0;

            const sortedCandidates = [...districtData.candidates].sort((a, b) => b.votes - a.votes);
            const leader = sortedCandidates[0];

            const candidateRows = sortedCandidates.map((c, i) => {
                const isLeader = i === 0 && c.votes > 0;
                return `
                <div class="mb-3">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2 min-w-0">
                            ${isLeader ? '<span class="flex-shrink-0 inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-700 text-xs font-bold">1</span>' : `<span class="flex-shrink-0 inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">${i + 1}</span>`}
                            <span class="text-sm font-medium text-gray-800 truncate">${c.name}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 ml-2 flex-shrink-0">${c.votes.toLocaleString()}</span>
                    </div>
                    <div class="relative h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-500 ${isLeader ? 'bg-green-500' : 'bg-blue-400'}"
                             style="width: ${c.percentage}%"></div>
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5 text-right">${c.percentage}% of candidate votes</div>
                </div>`;
            }).join('');

            const abstainRow = abstainCount > 0 ? `
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="flex-shrink-0 inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-amber-700">Abstain / No Vote</span>
                        </div>
                        <span class="text-sm font-bold text-amber-700">${abstainCount.toLocaleString()}</span>
                    </div>
                    <div class="relative h-2 bg-amber-50 rounded-full overflow-hidden">
                        <div class="absolute inset-y-0 left-0 bg-amber-400 rounded-full transition-all duration-500"
                             style="width: ${abstainPct}%"></div>
                    </div>
                    <div class="text-xs text-amber-500 mt-0.5 text-right">${abstainPct}% of total votes cast</div>
                </div>` : '';

            const card = document.createElement('div');
            card.className = 'bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 flex flex-col';
            card.innerHTML = `
                <div class="px-5 py-4 bg-gradient-to-r from-green-600 to-emerald-600 flex items-center justify-between">
                    <h3 class="text-base font-bold text-white">${districtData.district}</h3>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex flex-wrap items-center justify-between gap-x-6 gap-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-tight">Votes Cast:</span>
                        <span class="text-lg font-black text-gray-900">${totalVotesCast.toLocaleString()}</span>
                    </div>
                    <div class="hidden md:block h-4 w-px bg-gray-200"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-tight">Registered:</span>
                        <span class="text-lg font-black text-gray-900">${districtData.totalVoters.toLocaleString()}</span>
                    </div>
                    <div class="hidden md:block h-4 w-px bg-gray-200"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-tight">Turnout:</span>
                        <span class="text-lg font-black ${districtData.turnout >= 50 ? 'text-green-600' : 'text-orange-500'}">${districtData.turnout}%</span>
                    </div>
                </div>

                <div class="p-5 flex-1">
                    ${sortedCandidates.length > 0 ? candidateRows : '<p class="text-sm text-gray-400 text-center py-4">No candidate votes yet</p>'}
                    ${abstainRow}
                </div>`;

            tallyGrid.appendChild(card);
        });

    } catch (err) {
        console.error('Failed to load tally data:', err);
        tallyGrid.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-red-400">
                <svg class="h-10 w-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p class="text-sm font-medium">Failed to load results. Please try again.</p>
                <button onclick="loadDistrictData('${currentDistrict}')" class="mt-3 text-xs text-blue-500 hover:underline">Retry</button>
            </div>`;
    }
}

function exportToCSV() {
    fetch(`/api/admin/tally-results?district=${currentDistrict}`, { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            let csv = 'District,Candidate,Votes,% of Candidate Votes,Abstains,Total Votes Cast,Registered Voters,Turnout\n';
            data.forEach(d => {
                const abstain = d.abstainCount ?? 0;
                d.candidates.forEach(c => {
                    csv += `"${d.district}","${c.name}",${c.votes},${c.percentage}%,${abstain},${d.votesCast},${d.totalVoters},${d.turnout}%\n`;
                });
                if (d.candidates.length === 0 && abstain > 0) {
                    csv += `"${d.district}","Abstain / No Vote",${abstain},-,${abstain},${d.votesCast},${d.totalVoters},${d.turnout}%\n`;
                }
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = `tally_results_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
        })
        .catch(err => console.error('Export failed:', err));
}

document.addEventListener('DOMContentLoaded', () => {
    loadDistrictData('all');
});

window.showDistrict    = showDistrict;
window.loadDistrictData = loadDistrictData;
window.exportToCSV     = exportToCSV;