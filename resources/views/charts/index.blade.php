<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Charts Overview</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-6">
            <label for="clientSelect" class="block font-medium mb-1">Select Client:</label>
            <select id="clientSelect" class="form-select px-4 py-2 border rounded w-1/3">
                @foreach($clients as $client)
                    <option value="{{ $client }}">{{ $client }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6 flex justify-end space-x-3">
            <a id="downloadPdfBtn"
               href="{{ route('charts.pdf', ['client_name' => $clients[0] ?? 'Default']) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 transition">
                <i class="fas fa-file-pdf mr-2"></i> Download PDF Report
            </a>

            <a id="downloadZipBtn"
               href="{{ route('charts.download.zip', ['client_name' => $clients[0] ?? 'Default']) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 text-sm font-medium rounded hover:bg-blue-600 hover:text-white transition">
                <i class="fas fa-file-archive mr-2"></i> Download All Charts as ZIP
            </a>
        </div>

        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-lg font-semibold mb-4">Progress Chart (Goal-wise Accuracy)</h3>
            <canvas id="rawRecordChart" height="400"></canvas>
            <p id="masteryInfo" class="mt-2 text-green-700 font-medium"></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('rawRecordChart').getContext('2d');
            const client = document.getElementById('clientSelect').value;

            fetch(`/charts/raw-records?client_name=${client}`)
                .then(res => res.json())
                .then(res => {
                    const datasets = res.datasets;

                    const baseline = {
                        label: 'Baseline',
                        data: datasets[0]?.data?.map(p => ({ x: p.x, y: 33 })),
                        borderColor: 'orange',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    };

                    const mastery = {
                        label: 'Mastery (80%)',
                        data: datasets[0]?.data?.map(p => ({ x: p.x, y: 80 })),
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
                                legend: { position: 'left' },
                                title: {
                                    display: true,
                                    text: `Frequency for ${datasets[0]?.label || 'Goal'}`,
                                    font: { size: 18 }
                                }
                            },
                            scales: {
                                x: {
                                    type: 'time',
                                    time: { unit: 'day' },
                                    title: { display: true, text: 'Date of Service' },
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
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
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</x-app-layout>
