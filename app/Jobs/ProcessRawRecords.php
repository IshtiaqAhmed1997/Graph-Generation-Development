<?php

namespace App\Jobs;

use App\Models\GoalResult;
use App\Models\ProcessLog;
use App\Models\RawRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRawRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $records = RawRecord::whereNull('processed_at')
            ->where('billable', true)
            ->whereNotNull('accuracy')
            ->get()
            ->groupBy(fn ($r) => $r->file_upload_id.'|'.$r->target_text);

        foreach ($records as $key => $group) {
            [$uploadId, $targetText] = explode('|', $key);

            DB::beginTransaction();

            try {
                $dataPoints = [];
                $totalAccuracy = 0;
                $recordCount = 0;
                $recordIds = [];

                foreach ($group as $record) {
                    $dataPoints[] = [
                        'x' => $record->date_of_service,
                        'y' => $record->accuracy,
                    ];

                    $totalAccuracy += $record->accuracy;
                    $recordCount++;
                    $recordIds[] = $record->id;
                }

                $finalAccuracy = $recordCount > 0
                    ? round($totalAccuracy / $recordCount, 2)
                    : 0;

                GoalResult::updateOrCreate(
                    ['file_upload_id' => $uploadId, 'target_text' => $targetText],
                    ['data_points' => $dataPoints, 'final_accuracy' => $finalAccuracy]
                );

                RawRecord::whereIn('id', $recordIds)->update(['processed_at' => now()]);

                ProcessLog::create([
                    'file_upload_id' => $uploadId,
                    'target_text' => $targetText,
                    'records_processed' => $recordCount,
                    'final_accuracy' => $finalAccuracy,
                ]);

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Failed processing: $uploadId - $targetText | ".$e->getMessage());
            }
        }
    }
}
