<?php

namespace App\Jobs;

use App\Models\RawRecord;
use App\Models\GoalResult;
use App\Models\ProcessLog;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
class ProcessRawRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $records = RawRecord::whereNull('processed_at')
            ->where('is_billable', true)
            ->get()
            ->groupBy(fn($r) => $r->upload_id . '|' . $r->target_text);

        foreach ($records as $key => $group) {
            [$uploadId, $targetText] = explode('|', $key);

            DB::beginTransaction();

            try {
                $dataPoints = [];
                $totalCorrect = 0;
                $totalTrials = 0;
                $recordIds = [];

                foreach ($group as $record) {
                    if ($record->total_trials > 10) {
                        continue;
                    }

                    $accuracy = $record->total_trials > 0
                        ? round(($record->correct_trials / $record->total_trials) * 100, 2)
                        : 0;

                    $dataPoints[] = [
                        'x' => $record->session_date,
                        'y' => $accuracy,
                    ];

                    $totalCorrect += $record->correct_trials;
                    $totalTrials += $record->total_trials;
                    $recordIds[] = $record->id;
                }

                $finalAccuracy = $totalTrials > 0
                    ? round(($totalCorrect / $totalTrials) * 100, 2)
                    : 0;

                GoalResult::updateOrCreate(
                    ['upload_id' => $uploadId, 'target_text' => $targetText],
                    ['data_points' => $dataPoints, 'final_accuracy' => $finalAccuracy]
                );

                RawRecord::whereIn('id', $recordIds)->update(['processed_at' => now()]);

                ProcessLog::create([
                    'upload_id' => $uploadId,
                    'target_text' => $targetText,
                    'records_processed' => count($recordIds),
                    'final_accuracy' => $finalAccuracy,
                ]);

                DB::commit();

            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Failed processing: $uploadId - $targetText | " . $e->getMessage());
            }
        }
    }
}
