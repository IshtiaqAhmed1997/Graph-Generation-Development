<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#1565c0] leading-tight">
            {{ __('All Charts Overview') }}
        </h2>
    </x-slot>
    <style>
        /* 🌿 Pharma Chart Dashboard Theme */
        .charts-section {
            background-color: #f5f9ff;
            min-height: calc(100vh - 120px);
            padding: 2rem 1rem;
        }

        .chart-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .form-select,
        .form-control {
            border-radius: 8px;
            border: 1px solid #cfd8dc;
            background-color: #fff;
        }

        .btn-primary {
            background-color: #1565c0;
            border: none;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #0d47a1;
        }

        .btn-outline-primary {
            border-color: #1565c0;
            color: #1565c0;
            border-radius: 8px;
        }

        .btn-outline-primary:hover {
            background-color: #1565c0;
            color: #fff;
        }

        .chart-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: #0d47a1;
        }

        #masteryInfo {
            font-weight: 500;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            #clientSelect {
                width: 100% !important;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.75rem;
                align-items: stretch;
            }
        }
    </style>

    <div class="charts-section">
        <div class="container-fluid px-0">

            <!-- Client Selector -->
            <div class="mb-4">
                <label for="clientSelect" class="form-label fw-medium">Select Client:</label>
                <select id="clientSelect" class="form-select w-auto d-inline-block">
                    @foreach($clients as $client)
                        <option value="{{ $client }}">{{ $client }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Download Buttons -->
            <div class="mb-4 d-flex justify-content-end align-items-center flex-wrap action-buttons gap-3">
                <a id="downloadPdfBtn" href="{{ route('charts.pdf', ['client_name' => $clients[0] ?? 'Default']) }}"
                    target="_blank" class="btn btn-danger d-flex align-items-center px-4">
                    <i class="bi bi-file-earmark-pdf me-2"></i> Download PDF Report
                </a>

                <a id="downloadZipBtn"
                    href="{{ route('charts.download.zip', ['client_name' => $clients[0] ?? 'Default']) }}"
                    target="_blank" class="btn btn-outline-primary d-flex align-items-center px-4">
                    <i class="bi bi-archive me-2"></i> Download All Charts (ZIP)
                </a>
            </div>

            <!-- Chart Card -->
            <div class="chart-card">
                <h3 class="chart-title mb-4">Progress Chart (Goal-wise Accuracy)</h3>
                <canvas id="rawRecordChart" height="400"></canvas>
                <p id="masteryInfo" class="mt-3 text-success"></p>
            </div>
        </div>
    </div>

    <!-- Chart.js & Icons -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('rawRecordChart').getContext('2d');
            const clientSelect = document.getElementById('clientSelect');

            const renderChart = (client) => {
                fetch(`/charts/raw-records?client_name=${client}`)
                    .then(res => res.json())
                    .then(res => {
                        const datasets = res.datasets;
                        if (!datasets || !datasets.length) return;

                        const baseline = {
                            label: 'Baseline (33%)',
                            data: datasets[0].data.map(p => ({ x: p.x, y: 33 })),
                            borderColor: 'orange',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            pointRadius: 0,
                            fill: false
                        };

                        const mastery = {
                            label: 'Mastery (80%)',
                            data: datasets[0].data.map(p => ({ x: p.x, y: 80 })),
                            borderColor: 'gold',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            pointRadius: 0,
                            fill: false
                        };

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                datasets: [baseline, mastery, ...datasets]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'bottom' },
                                    title: {
                                        display: true,
                                        text: `Frequency for ${datasets[0].label || 'Goal'}`,
                                        font: { size: 18 }
                                    }
                                },
                                scales: {
                                    x: {
                                        type: 'time',
                                        time: { unit: 'day' },
                                        title: { display: true, text: 'Date of Service' }
                                    },
                                    y: {
                                        min: 0,
                                        max: 100,
                                        title: { display: true, text: '% Accuracy' }
                                    }
                                }
                            }
                        });
                    });
            };

            // Initial Chart
            renderChart(clientSelect.value);

            // Update chart on client change
            clientSelect.addEventListener('change', (e) => renderChart(e.target.value));
        });
    </script>
</x-app-layout>