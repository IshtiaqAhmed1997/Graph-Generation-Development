<?php

namespace App\Services;

use App\Models\ChartRecord;
use App\Models\RawRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ChartService
{
    public function getGoalsByAccuracy(?string $client = null): Collection
    {
        return RawRecord::selectRaw('target_text, AVG(accuracy) as avg_accuracy, COUNT(*) as total')
            ->when($client, fn ($q) => $q->where('client_name', $client))
            ->where('billable', true)
            ->whereIn('cpt_code', ['97153', '97154'])
            ->groupBy('target_text')
            ->orderBy('target_text')
            ->get();
    }

    public function getRawRecordChart(?string $client = null): array
    {
        $goals = RawRecord::when($client, fn ($q) => $q->where('client_name', $client))
            ->where('billable', true)
            ->whereIn('cpt_code', ['97153', '97154'])
            ->selectRaw('target_text, COUNT(*) as count')
            ->groupBy('target_text')
            ->pluck('target_text');

        $datasets = [];

        foreach ($goals as $goal) {
            $records = RawRecord::when($client, fn ($q) => $q->where('client_name', $client))
                ->where('billable', true)
                ->whereIn('cpt_code', ['97153', '97154'])
                ->where('target_text', $goal)
                ->orderBy('date_of_service')
                ->get(['date_of_service', 'accuracy']);

            $data = $records->map(fn ($r) => [
                'x' => $r->date_of_service ? Carbon::parse($r->date_of_service)->format('Y-m-d') : null,
                'y' => $r->accuracy,
            ])->filter()->values();

            $trend = $data->map(function ($point, $i) use ($data) {
                $subset = $data->slice(max(0, $i - 2), 3);
                $avg = $subset->avg('y');

                return ['x' => $point['x'], 'y' => round($avg, 2)];
            });

            $masteryPoints = [];
            for ($i = 2; $i < count($data); $i++) {
                if ($data[$i - 2]['y'] >= 80 && $data[$i - 1]['y'] >= 80 && $data[$i]['y'] >= 80) {
                    $masteryPoints[] = $data[$i];
                }
            }

            $datasets[] = [
                'label' => $goal,
                'data' => $data,
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'tension' => 0.3,
            ];

            $datasets[] = [
                'label' => $goal.' Trend',
                'data' => $trend,
                'borderDash' => [5, 5],
                'borderColor' => 'rgba(255, 159, 64, 1)',
                'backgroundColor' => 'transparent',
                'tension' => 0.3,
            ];

            if (count($masteryPoints)) {
                $datasets[] = [
                    'label' => $goal.' Mastery',
                    'data' => $masteryPoints,
                    'type' => 'scatter',
                    'pointBackgroundColor' => 'green',
                    'pointRadius' => 6,
                    'showLine' => false,
                ];
            }
        }

        return $datasets;
    }

    public function saveGeneratedChart(int $uploadId, string $client, string $target, string $chartType, array $chartData, ?string $chartImagePath = null): void
    {
        if (! isset($chartData['mastery_point']) && isset($chartData['data'])) {
            $consecutive = 0;
            foreach ($chartData['data'] as $point) {
                if (isset($point['y']) && $point['y'] >= 80) {
                    $consecutive++;
                    if ($consecutive >= 3) {
                        $chartData['mastery_point'] = $point['x'];
                        break;
                    }
                } else {
                    $consecutive = 0;
                }
            }
        }

        $existing = ChartRecord::where([
            'file_upload_id' => $uploadId,
            'client_name' => $client,
            'target_text' => $target,
            'chart_type' => $chartType,
        ])->first();

        if ($existing && $existing->chart_image_path) {
            $oldPath = str_replace('storage/', 'public/', $existing->chart_image_path);
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }

        ChartRecord::updateOrCreate(
            [
                'file_upload_id' => $uploadId,
                'client_name' => $client,
                'target_text' => $target,
                'chart_type' => $chartType,
            ],
            [
                'goal_name' => $target,
                'chart_config' => $chartData,
                'chart_image_path' => $chartImagePath,
            ]
        );
    }
}
