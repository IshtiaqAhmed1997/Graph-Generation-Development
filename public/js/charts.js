document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('clientSelect').addEventListener('change', loadCharts);
    loadCharts(); // initial load
});

let charts = {};

function loadCharts() {
    const client = document.getElementById('clientSelect').value;

    Object.values(charts).forEach(chart => chart.destroy());

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

    fetch(`/charts/raw-records?client_name=${encodeURIComponent(client)}`)
        .then(res => res.json())
        .then(chartData => {
            const ctx = document.getElementById('progressChart').getContext('2d');
            charts.progress = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Accuracy Over Time by Goal' }
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
        });
}
