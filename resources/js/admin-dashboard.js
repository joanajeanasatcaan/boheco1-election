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
                    grid: {
                        drawBorder: false,
                        color: 'rgba(229, 231, 235, 0.5)'
                    },
                    ticks: {
                        padding: 10,
                        font: { size: 11 },
                        color: '#6b7280'
                    }
                },
                y: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        padding: 10,
                        font: { size: 12, weight: '500' },
                        color: '#374151'
                    }
                }
            },
            interaction: { intersect: false, mode: 'index' },
            animation: { duration: 1000, easing: 'easeOutQuart' }
        }
    });

    async function loadChart() {
        const response = await fetch('/api/dashboard-district-counts', {
                credentials: 'include'
            });
        const result = await response.json();
        const data = result.by_district;

        const labels = votingChart.data.labels;
        const votesArray = new Array(labels.length).fill(0);

        data.forEach(item => {
            const index = labels.indexOf(item.district);
            if (index !== -1) votesArray[index] = item.votes_count;
        });


        const grandTotal = votesArray.reduce((acc, val) => Number(acc) + Number(val), 0);

        console.log('Grand Total Votes:', grandTotal, votesArray);

        votingChart.data.datasets[0].data = votesArray;
        votingChart.update();

        const totalNomineesEl = document.getElementById('total-nominees-count');
        if (totalNomineesEl) {
            totalNomineesEl.innerText = Number(result.total_votes).toLocaleString();
        }

        const totalElements = document.querySelectorAll('.total-votes-count');
        totalElements.forEach(el => {
            el.innerText = grandTotal.toLocaleString(); 
        });
    }

    loadChart();

    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('✅ Connected to Reverb!');
    });
});