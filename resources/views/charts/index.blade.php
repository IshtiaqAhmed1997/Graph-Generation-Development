<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Charts Overview</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-6">
            <label for="clientSelect" class="block font-medium mb-1">Select Client:</label>
            <select id="clientSelect" class="form-select px-4 py-2 border rounded w-1/3">
                @foreach($clients as $client)
                    <option selected disabled>Select Client</option>
                    <option value="{{ $client }}">{{ $client }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6 flex justify-end space-x-3">
            <a id="downloadPdfBtn" href="{{ route('charts.pdf', ['client_name' => $clients[0] ?? 'Default']) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 transition">
                <i class="fas fa-file-pdf mr-2"></i> Download PDF Report
            </a>

            <a id="downloadZipBtn"
               href="{{ route('charts.download.zip', ['client_name' => $clients[0] ?? 'Default']) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 text-sm font-medium rounded hover:bg-blue-600 hover:text-white transition">
                <i class="fas fa-file-archive mr-2"></i> Download All Charts as ZIP
            </a>
        </div>

        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-lg font-semibold mb-4">Progress Chart (Goal-wise Accuracy)</h3>
            <canvas id="skillChart" height="170"></canvas>
            <p id="masteryInfo" class="mt-2 text-green-700 font-medium"></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>
    <script>
        const chartData = @json($chartData);

        const ctx = document.getElementById("skillChart").getContext("2d");

        const chart = new Chart(ctx, {
            type: "line",
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: "Skill Accuracy",
                        data: chartData.skillAccuracy,
                        borderColor: "orange",
                        backgroundColor: "orange",
                        tension: 0.3,
                        pointRadius: 4,
                    },
                    {
                        label: "Trend",
                        data: chartData.trend,
                        borderColor: "#f4a300",
                        borderDash: [4, 2],
                        fill: false,
                        tension: 0.1,
                    },
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: chartData.title,
                    },
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: true,
                        },
                    },
                    animation: {
                        onComplete: function (chart) {
                            const ctx = chart.ctx;
                            ctx.save();
                            ctx.font = "bold 14px Arial";
                            ctx.fillStyle = "#28a745";
                            const text = "✅ Mastered";
                            const x = chart.chartArea.right - ctx.measureText(text).width - 10;
                            const y = chart.chartArea.top + 20;
                            ctx.fillText(text, x, y);
                            ctx.restore();
                        },
                    },
                    annotation: {
                        annotations: {
                            baseline: {
                                type: "line",
                                yMin: chartData.baseline,
                                yMax: chartData.baseline,
                                borderColor: "orange",
                                borderWidth: 2,
                                borderDash: [5, 5],
                                label: {
                                    content: "Baseline",
                                    enabled: true,
                                    position: "end",
                                },
                            },
                            mastery: {
                                type: "line",
                                yMin: 80,
                                yMax: 80,
                                borderColor: "green",
                                borderWidth: 2,
                                borderDash: [2, 2],
                                label: {
                                    content: "Mastery (80%)",
                                    enabled: true,
                                    position: "start",
                                },
                            },
                            // phase: {
                            //     type: "line",
                            //     xMin: chartData.phaseDate,
                            //     xMax: chartData.phaseDate,
                            //     borderColor: "gray",
                            //     borderWidth: 2,
                            //     label: {
                            //         content: "Phase Shift",
                            //         enabled: true,
                            //         position: "top",
                            //     },
                            // },
                        },
                        
                    },
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        title: {
                            display: true,
                            text: "% Accuracy",
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: "Date of Service",
                        },
                    },
                },
            },
            
        });
        
    </script>
</x-app-layout>
