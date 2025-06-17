let charts = {};

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('clientSelect').addEventListener('change', loadCharts);
    updateDownloadButton();
    loadCharts();
});

function updateDownloadButton() {
    const selectedClient = document.getElementById('clientSelect').value;

    const downloadBtn = document.getElementById('downloadPdfBtn');
    downloadBtn.href = `/charts/export?client_name=${encodeURIComponent(selectedClient)}`;

    const zipBtn = document.getElementById('downloadZipBtn');
    zipBtn.href = `/charts/download-zip?client_name=${encodeURIComponent(selectedClient)}`;
}


document.getElementById('clientSelect').addEventListener('change', updateDownloadButton);

function loadCharts() {
    const client = document.getElementById('clientSelect').value;
    console.log('🚀 loadCharts() for client:', client);

    Object.values(charts).forEach(chart => chart?.destroy?.());

    fetch(`/charts/raw-records?client_name=${encodeURIComponent(client)}`)
        .then(res => {
            if (!res.ok) throw new Error('404 or Server Error');
            return res.json();
        })
        .then(chartData => {
            const clientUploadId = chartData.file_upload_id;

            if (!clientUploadId) {
                console.warn("⚠️ No file_upload_id found. Charts will render, but saving will be skipped.");
            }

            renderProgressChart(chartData, clientUploadId);
            renderGoalsChart(client, clientUploadId);
            renderBehaviorChart(client, clientUploadId);
            renderProgramChart(client, clientUploadId);
        })
        .catch(err => {
            console.error('❌ Failed to load chart data:', err);
            renderProgressChart({}, null);
            renderGoalsChart(client, null);
            renderBehaviorChart(client, null);
            renderProgramChart(client, null);
        });
}

function delayedSaveChartImage(canvasId, goalName, dataset, fileUploadId) {
    if (!fileUploadId) {
        console.warn(`⏩ Skipping save for "${goalName}" (no file_upload_id)`);
        return;
    }

    setTimeout(() => {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const imageData = canvas.toDataURL('image/png');

        fetch('/charts/store', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                goal: goalName,
                chart_type: goalName,
                file_upload_id: fileUploadId,
                chart_data: dataset,
                chart_image: imageData
            })
        })
            .then(res => res.json())
            .then(data => console.log(`✅ Chart saved for ${goalName}`, data))
            .catch(err => console.error(`❌ Failed to save ${goalName}`, err));
    }, 500);
}

function renderGoalsChart(client, fileUploadId) {
    fetch(`/chart/goals?client_name=${encodeURIComponent(client)}`)
        .then(res => res.json())
        .then(chartData => {
            const labels = chartData.labels?.length ? chartData.labels : ['No Data'];
            const values = chartData.values?.length ? chartData.values : [0];

            const ctx = document.getElementById('goalsChart').getContext('2d');
            charts.goals = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Average Accuracy',
                        data: values,
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

            delayedSaveChartImage('goalsChart', 'Goals Summary', { labels, values }, fileUploadId);
        });
}

function renderBehaviorChart(client, fileUploadId) {
    fetch(`/chart/behavior?client_name=${encodeURIComponent(client)}`)
        .then(res => res.json())
        .then(chartData => {
            const labels = chartData.labels?.length ? chartData.labels : ['No Data'];
            const values = chartData.values?.length ? chartData.values : [0];

            const ctx = document.getElementById('behaviorChart').getContext('2d');
            charts.behavior = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total Accuracy',
                        data: values,
                        borderColor: 'rgba(255, 99, 132, 0.7)',
                        tension: 0.3,
                        fill: false
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

            delayedSaveChartImage('behaviorChart', 'Behavior Accuracy', { labels, values }, fileUploadId);
        });
}

function renderProgramChart(client, fileUploadId) {
    fetch(`/chart/programs?client_name=${encodeURIComponent(client)}`)
        .then(res => res.json())
        .then(chartData => {
            const labels = chartData.labels?.length ? chartData.labels : ['No Data'];
            const values = chartData.values?.length ? chartData.values : [0];

            const ctx = document.getElementById('programsChart').getContext('2d');
            charts.programs = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Average Accuracy',
                        data: values,
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

            delayedSaveChartImage('programsChart', 'Program Performance', { labels, values }, fileUploadId);
        });
}

function renderProgressChart(chartData, fileUploadId) {
    const ctx = document.getElementById('progressChart').getContext('2d');
    const masteryInfoEl = document.getElementById('masteryInfo');
    masteryInfoEl.innerHTML = '';

    const datasets = Array.isArray(chartData.datasets) && chartData.datasets.length > 0
        ? chartData.datasets
        : [{
            label: 'No Data',
            data: [{ x: new Date().toISOString().split('T')[0], y: 0 }],
            borderColor: 'gray',
            backgroundColor: 'lightgray',
            tension: 0.3,
            fill: false
        }];

    const masteryPoints = datasets
        .filter(ds => ds.label.endsWith(' Mastery'))
        .flatMap(ds => ds.data.map(pt => pt.x));

    const latestMastery = masteryPoints.sort().pop();
    if (latestMastery) {
        masteryInfoEl.innerHTML = `✅ Final mastery achieved on: <strong>${latestMastery}</strong>`;
    }

    charts.progress = new Chart(ctx, {
        type: 'line',
        data: { datasets },
        options: {
            responsive: true,
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Accuracy Over Time by Goal' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.dataset.label || '';
                            const yVal = context.parsed.y;
                            if (label.endsWith('Mastery')) return `Mastery Point: ${yVal}%`;
                            if (label.endsWith('Trend')) return `Trend Avg: ${yVal}%`;
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

    datasets.forEach(ds => {
        const label = ds.label;

        if (
            label &&
            !label.includes('Trend') &&
            !label.includes('Mastery') &&
            label !== 'No Data'
        ) {
            let mastery = null;
            const points = ds.data || [];

            for (let i = 2; i < points.length; i++) {
                if (
                    points[i - 2].y >= 80 &&
                    points[i - 1].y >= 80 &&
                    points[i].y >= 80
                ) {
                    mastery = points[i].x;
                    break;
                }
            }

            const enhancedDataset = {
                ...ds,
                mastery_point: mastery
            };

            delayedSaveChartImage('progressChart', label, enhancedDataset, fileUploadId);
        }
    });

}
