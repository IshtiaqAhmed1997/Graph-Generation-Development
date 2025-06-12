<?php

namespace App\Services;

use App\Models\RawRecord;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ChartService
{
    public function getGoalsByAccuracy(int $userId, ?string $client = null): Collection
    {
        return RawRecord::selectRaw('target_text, AVG(accuracy) as avg_accuracy, COUNT(*) as total')
            ->where('user_id', $userId)
            ->when($client, fn ($q) => $q->where('client_name', $client))
            ->where('billable', true)
            ->whereIn('cpt_code', ['97153', '97154'])
            ->groupBy('target_text')
            ->having('total', '>=', 10)
            ->orderBy('target_text')
            ->get();
    }

    public function getBehaviorByDate(int $userId, ?string $client = null): Collection
    {
        return RawRecord::selectRaw('DATE(date_of_service) as date, SUM(accuracy) as total_accuracy')
            ->where('user_id', $userId)
            ->when($client, fn ($q) => $q->where('client_name', $client))
            ->where('billable', true)
            ->whereIn('cpt_code', ['97153', '97154'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getProgramPerformance(int $userId, ?string $client = null): Collection
    {
        return RawRecord::selectRaw('program_name, AVG(accuracy) as avg_accuracy')
            ->where('user_id', $userId)
            ->when($client, fn ($q) => $q->where('client_name', $client))
            ->whereNotNull('program_name')
            ->where('billable', true)
            ->whereIn('cpt_code', ['97153', '97154'])
            ->groupBy('program_name')
            ->orderBy('program_name')
            ->get();
    }

    public function getRawRecordChart(int $userId, ?string $client = null): array
    {
        $goals = RawRecord::where('user_id', $userId)
            ->when($client, fn ($q) => $q->where('client_name', $client))
            ->where('billable', true)
            ->whereIn('cpt_code', ['97153', '97154'])
            ->selectRaw('target_text, COUNT(*) as count')
            ->groupBy('target_text')
            ->having('count', '>=', 10)
            ->pluck('target_text');

        $datasets = [];

        foreach ($goals as $goal) {
            $records = RawRecord::where('user_id', $userId)
                ->when($client, fn ($q) => $q->where('client_name', $client))
                ->where('billable', true)
                ->whereIn('cpt_code', ['97153', '97154'])
                ->where('target_text', $goal)
                ->orderBy('date_of_service')
                ->get(['date_of_service', 'accuracy']);

            $data = $records->map(fn ($r) => [
                'x' => $r->date_of_service ? Carbon::parse($r->date_of_service)->format('Y-m-d') : null,
                'y' => $r->accuracy,
            ])->filter();

            $datasets[] = [
                'label' => $goal,
                'data' => $data,
                'tension' => 0.3,
            ];
        }

        return $datasets;
    }
}
