<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Chart Report - {{ $clientName }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            color: #111;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .chart-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .chart-image {
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
        }

        .chart-section {
            margin-bottom: 40px;
        }
    </style>
</head>

<body>
    <h2>Client Chart Report<br><small>{{ $clientName }}</small></h2>
    <p style="text-align: right; font-size: 12px;">
        Report Generated on: {{ now()->format('F d, Y') }}
    </p>

    @if($charts->isNotEmpty())
        <h3>📋 Summary Table</h3>
        <table width="100%" border="1" cellpadding="6" cellspacing="0" style="font-size: 12px; border-collapse: collapse;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th>Goal</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Final Mastery Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($charts as $chart)
                    @php
                        $start = $end = $mastery = null;

                        if (isset($chart->chart_config['labels']) && is_array($chart->chart_config['labels'])) {
                            $labels = $chart->chart_config['labels'];
                            sort($labels);
                            $start = reset($labels);
                            $end = end($labels);
                        } elseif (isset($chart->chart_config['data']) && is_array($chart->chart_config['data'])) {
                            $dates = array_column($chart->chart_config['data'], 'x');
                            sort($dates);
                            $start = reset($dates);
                            $end = end($dates);
                        }

                        if (isset($chart->chart_config['mastery_point'])) {
                            $mastery = $chart->chart_config['mastery_point'];
                        }
                    @endphp
                    <tr>
                        <td>{{ $chart->goal_name }}</td>
                        <td>{{ $start }}</td>
                        <td>{{ $end }}</td>
                        <td>{{ $mastery ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>
    @endif

    @if($charts->isNotEmpty())
        @foreach($charts as $chart)
            <div class="chart-section" style="{{ !$loop->last ? 'page-break-after: always;' : '' }}">
                <div class="chart-title">Goal: {{ $chart->goal_name }}</div>
                @if(file_exists(public_path($chart->chart_image_path)))
                    <img src="{{ public_path($chart->chart_image_path) }}" class="chart-image"
                        alt="Chart for {{ $chart->goal_name }}">
                @else
                    <p style="color:red;">[Image missing for {{ $chart->goal_name }}]</p>
                @endif

            </div>
        @endforeach
    @else
        <p>No charts found for this client.</p>
    @endif
</body>

</html>