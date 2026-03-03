 let currentDistrict = 'all';

    function showDistrict(district, btn = null) {
        currentDistrict = district;

        if (btn) {
            document.querySelectorAll('.district-btn').forEach(b => {
                b.classList.remove('active', 'bg-white', 'border-gray-300');
                b.classList.add('border-gray-200');
                b.querySelector('p')?.classList.remove('text-gray-900');
                b.querySelector('p')?.classList.add('text-gray-800');
            });

            btn.classList.add('active', 'bg-white', 'border-gray-300');
            btn.classList.remove('border-gray-200');
            btn.querySelector('p')?.classList.add('text-gray-900');
            btn.querySelector('p')?.classList.remove('text-gray-800');
        }

        loadDistrictData(district);
    }

    async function loadDistrictData(district = 'all') {
        const tallyGrid = document.getElementById('tally-grid');
        tallyGrid.innerHTML = '<p class="text-gray-500">Loading results...</p>';

        const response = await fetch(`/api/admin/tally-results?district=${district}`, {
                credentials: 'include'
            });
        const data = await response.json();

        console.log('Loaded tally data:', data);

        let totalVotes = 0;
        let totalVoters = 0;

        data.forEach(d => {
            totalVotes += d.votesCast;
            totalVoters += d.totalVoters;
        });

        const turnout = totalVoters > 0
            ? Math.round((totalVotes / totalVoters) * 100)
            : 0;

        document.getElementById('total-votes').textContent = totalVotes.toLocaleString();
        document.getElementById('registered-voters').textContent = totalVoters.toLocaleString();
        document.getElementById('turnout-percentage').textContent = `${turnout}%`;
        document.getElementById('tally-results').textContent =
            `Showing ${data.length} ${data.length === 1 ? 'tally' : 'tallies'}`;

        tallyGrid.innerHTML = '';

        data.forEach(districtData => {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200';

            card.innerHTML = `
                <div class="p-6">
                    <h3 class="text-lg font-bold">${districtData.district}</h3>
                    <p class="text-sm text-gray-600 mb-3">
                        ${districtData.votesCast} / ${districtData.totalVoters}
                        (${districtData.turnout}% turnout)
                    </p>

                    ${districtData.candidates.map(c => `
                        <div class="flex justify-between bg-gray-50 p-3 rounded mb-2">
                            <span>${c.name}</span>
                            <span class="font-bold">${c.votes} (${c.percentage}%)</span>
                        </div>
                    `).join('')}
                </div>
            `;

            tallyGrid.appendChild(card);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadDistrictData('all');
    });