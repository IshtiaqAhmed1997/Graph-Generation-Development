<?php

namespace App\Services;

use App\Models\ChartRecord;
use App\Models\RawRecord;
use Illuminate\Support\Carbon;

class ChartService
{
    public function getRawRecordChart(?string $client = null): array
    {
        $goals = RawRecord::when($client, fn($q) => $q->where('client_name', $client))
            ->where('billable', true)
            ->whereIn('cpt_code', ['97153', '97154', '97155'])
            ->selectRaw('target_text, COUNT(*) as count')
            ->groupBy('target_text')
            ->pluck('target_text');

        $datasets = [];
        $colors = [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
        ];

        foreach ($goals as $goal) {
            $records = RawRecord::when($client, fn($q) => $q->where('client_name', $client))
                ->where('billable', true)
                ->whereIn('cpt_code', ['97153', '97154', '97155'])
                ->where('target_text', $goal)
                ->orderBy('date_of_service')
                ->get(['date_of_service', 'accuracy', 'frequency', 'cpt_code', 'phase_change']);

            if ($records->count() < 3) continue;

            $isSkill = $records->every(fn($r) => in_array($r->cpt_code, ['97153', '97154', '97155']) && $r->accuracy !== null && $r->accuracy > 0);
            $isBehavior = $records->every(fn($r) => in_array($r->cpt_code, ['97153', '97155']) && $r->frequency !== null && $r->frequency >= 0);

            if (!$isSkill && !$isBehavior) continue;

            $data = $records->map(fn($r) => [
                'x' => Carbon::parse($r->date_of_service)->format('Y-m-d'),
                'y' => $isSkill ? $r->accuracy : $r->frequency,
            ])->filter(fn($point) => $point['y'] !== null)->values();

            if ($data->count() < 3) continue;

            $color = $colors[count($datasets) % count($colors)];
            $label = $goal;

            $baseline = $isSkill
                ? $data->first()['y']
                : round($data->take(5)->avg('y'), 2);

            $trend = $data->map(function ($point, $i) use ($data) {
                $subset = $data->slice(max(0, $i - 2), 3);
                return ['x' => $point['x'], 'y' => round($subset->avg('y'), 2)];
            });

            $masteryPoints = [];

            if ($isSkill) {
                $mastery = $data->filter(fn($p) => $p['y'] >= 80)->unique('x');
                if ($mastery->count() >= 2) {
                    $masteryPoints[] = $mastery->last();
                    $label .= ' ✅ Mastered';
                }
            } else {
                $goalLine = 2;
                $mastery = $data->filter(fn($p) => $p['y'] <= $goalLine)->unique('x');
                if ($mastery->count() >= 3) {
                    $masteryPoints[] = $mastery->last();
                    $label .= ' ✅ Mastered';
                }
            }

            $datasets[] = [
                'label' => $label,
                'data' => $data,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'tension' => 0.3,
            ];

            $datasets[] = [
                'label' => $goal . ' Baseline',
                'data' => $data->map(fn($d) => ['x' => $d['x'], 'y' => $baseline]),
                'borderDash' => [2, 3],
                'borderColor' => 'gray',
                'backgroundColor' => 'transparent',
                'tension' => 0.3,
            ];

            $datasets[] = [
                'label' => $goal . ' Mastery Line',
                'data' => $data->map(fn($d) => [
                    'x' => $d['x'],
                    'y' => $isSkill ? 80 : 2
                ]),
                'borderDash' => [5, 5],
                'borderColor' => 'green',
                'backgroundColor' => 'transparent',
                'tension' => 0.3,
            ];

            $datasets[] = [
                'label' => $goal . ' Trend',
                'data' => $trend,
                'borderDash' => [5, 5],
                'borderColor' => 'orange',
                'backgroundColor' => 'transparent',
                'tension' => 0.3,
            ];

            if (!empty($masteryPoints)) {
                $datasets[] = [
                    'label' => $goal . ' Mastery',
                    'data' => $masteryPoints,
                    'type' => 'scatter',
                    'pointBackgroundColor' => 'green',
                    'pointRadius' => 6,
                    'showLine' => false,
                ];
            }

            $phaseDates = $records->filter(fn($r) => $r->phase_change)->pluck('date_of_service')->unique();

            foreach ($phaseDates as $phaseDate) {
                $datasets[] = [
                    'label' => 'Phase Line',
                    'data' => [
                        ['x' => Carbon::parse($phaseDate)->format('Y-m-d'), 'y' => 0],
                        ['x' => Carbon::parse($phaseDate)->format('Y-m-d'), 'y' => 100],
                    ],
                    'borderColor' => 'black',
                    'borderDash' => [1, 3],
                    'backgroundColor' => 'transparent',
                    'tension' => 0,
                    'pointRadius' => 0,
                ];
            }
        }

        return $datasets;
    }

    public function saveGeneratedChart(
    int $fileUploadId,
    string $clientName,
    string $goalName,
    string $chartType,
    array $chartConfig,
    ?string $chartImagePath = null
): void {
    // Check if chart already exists (for regeneration rule)
    $existingChart = ChartRecord::where('file_upload_id', $fileUploadId)
        ->where('goal_name', $goalName)
        ->where('user_id', auth()->id())
        ->latest('version_id')
        ->first();

    $version = 1;
    $regenerationAllowed = true;

    if ($existingChart) {
        $version = $existingChart->version_id ? ((int)$existingChart->version_id + 1) : 2;

        // ❌ Already regenerated once → Block regeneration
        $alreadyRegenerated = ChartRecord::where('file_upload_id', $fileUploadId)
            ->where('goal_name', $goalName)
            ->where('version_id', '>=', 2)
            ->exists();

        if ($alreadyRegenerated) {
            $regenerationAllowed = false;
        }
    }

    if (! $regenerationAllowed) {
        throw new \Exception("This chart has already been regenerated once and cannot be regenerated again.");
    }

    // ✅ Add version_id to chart config
    $chartConfig['version_id'] = $version;

    // Save chart
    $chart = ChartRecord::create([
        'user_id'          => auth()->id(),
        'file_upload_id'   => $fileUploadId,
        'client_name'      => $clientName,
        'goal_name'        => $goalName,
        'chart_type'       => $chartType,
        'chart_config'     => $chartConfig,
        'chart_image_path' => $chartImagePath,
        'version_id'       => $version,
    ]);

    // ✅ Log audit
    app(\App\Services\AuditLogService::class)->log(
        action: $version > 1 ? 'chart_regenerated' : 'chart_generated',
        goalResultId: null,
        details: "Chart {$goalName} (Version {$version}) " . ($version > 1 ? 'regenerated' : 'created'),
        versionId: (string) $version
    );
}

}
