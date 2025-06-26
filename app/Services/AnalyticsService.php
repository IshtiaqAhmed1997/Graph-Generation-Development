<?php

namespace App\Services;

use App\Models\GoalResult;
use App\Models\RawRecord;

class AnalyticsService
{
    public function processFileUpload(int $fileUploadId): void
    {
        $records = RawRecord::where('file_upload_id', $fileUploadId)
            ->where('billable', true)
            ->whereNotNull('accuracy')
            ->orderBy('date_of_service')
            ->get();

        $grouped = $records->groupBy(function ($record) {
            return $record->client_name.'|'.$record->target_text;
        });

        foreach ($grouped as $key => $group) {
            [$client, $target] = explode('|', $key);
            $firstDate = $group->first()->date_of_service;
            $lastDate = $group->last()->date_of_service;
            $totalTrials = $group->count();
            $totalCorrect = $group->sum(fn ($r) => round($r->accuracy / 100, 2)); // Each accuracy as ratio
            $averageAccuracy = round($group->avg('accuracy'));

            $mastered = false;
            $masteredOn = null;
            $streak = 0;

            foreach ($group as $record) {
                if ($record->accuracy >= 80) {
                    $streak++;
                    if ($streak === 3) {
                        $mastered = true;
                        $masteredOn = $record->date_of_service;
                        break;
                    }
                } else {
                    $streak = 0;
                }
            }

            GoalResult::updateOrCreate(
                [
                    'file_upload_id' => $fileUploadId,
                    'client_name' => $client,
                    'target_text' => $target,
                ],
                [
                    'first_date' => $firstDate,
                    'last_date' => $lastDate,
                    'total_trials' => $totalTrials,
                    'total_correct' => round($totalCorrect),
                    'average_accuracy' => $averageAccuracy,
                    'mastered' => $mastered,
                    'mastered_on' => $masteredOn,
                ]
            );
        }
    }
}
