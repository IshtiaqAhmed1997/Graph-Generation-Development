document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('clientSelect').addEventListener('change', loadCharts);
    loadCharts(); // initial load
});

let charts = {};

function loadCharts() {
    const client = document.getElementById('clientSelect').value;

    // Destroy old charts
    Object.values(charts).forEach(chart => chart.destroy());

    // Goals Chart
    fetch(`/chart/goals?client_name=${encodeURIComponent(client)}`)
        .then(res => res.json())
        .then(chartData => {
            const ctx = document.getElementById('goalsChart').getContext('2d');
            charts.goals = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Average Accuracy',
                        data: chartData.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: { display: true, text: 'Average Accuracy by Goal' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: { display: true, text: 'Accuracy (%)' }
                        }
                    }
                }
            });
        });

    // Behavior Chart
    fetch(`/chart/behavior?client_name=${encodeURIComponent(client)}`)
        .then(res => res.json())
        .then(chartData => {
            const ctx = document.getElementById('behaviorChart').getContext('2d');
            charts.behavior = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Total Accuracy',
                        data: chartData.values,
                        fill: false,
                        borderColor: 'rgba(255, 99, 132, 0.7)',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: { display: true, text: 'Total Accuracy by Date' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Total Accuracy' }
                        }
                    }
                }
            });
        });

    fetch(`/chart/programs?client_name=${encodeURIComponent(client)}`)
        .then(res => res.json())
        .then(chartData => {
            const ctx = document.getElementById('programsChart').getContext('2d');
            charts.programs = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Average Accuracy',
                        data: chartData.values,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: { display: true, text: 'Average Accuracy by Program' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: { display: true, text: 'Accuracy (%)' }
                        }
                    }
                }
            });
        });

    // Progress Chart
    fetch(`/charts/raw-records?client_name=${encodeURIComponent(client)}`)
        .then(res => res.json())
        .then(chartData => {
            const ctx = document.getElementById('progressChart').getContext('2d');
            const masteryInfoEl = document.getElementById('masteryInfo');
            masteryInfoEl.innerHTML = ''; // Clear previous info

            const masteryPoints = chartData.datasets
                .filter(ds => ds.label.endsWith(' Mastery'))
                .flatMap(ds => ds.data.map(pt => pt.x));

            const latestMastery = masteryPoints.sort().pop();
            if (latestMastery) {
                masteryInfoEl.innerHTML = `✅ Final mastery achieved on: <strong>${latestMastery}</strong>`;
            }

            charts.progress = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: Array.isArray(chartData.datasets) ? chartData.datasets : []
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    plugins: {
                        legend: { position: 'top' },
                        title: {
                            display: true,
                            text: 'Accuracy Over Time by Goal'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.dataset.label || '';
                                    const yVal = context.parsed.y;
                                    if (label.endsWith('Mastery')) {
                                        return `Mastery Point: ${yVal}%`;
                                    }
                                    if (label.endsWith('Trend')) {
                                        return `Trend Avg: ${yVal}%`;
                                    }
                                    return `Accuracy: ${yVal}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: { unit: 'day' },
                            title: { display: true, text: 'Date' }
                        },
                        y: {
                            min: 0,
                            max: 100,
                            title: { display: true, text: 'Accuracy (%)' }
                        }
                    }
                }
            });
        })
        .catch(err => {
            console.error("Failed to load progress chart:", err);
        });
}
