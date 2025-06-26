<?php

namespace App\Exports;

use App\Models\FileUpload;
use App\Models\GoalResult;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProcessedChartExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        $results = GoalResult::with('upload.user')
            ->get();

        $exportData = [];

        foreach ($results as $result) {
            $goalName = $result->target_text;
            $uploadId = $result->upload_id;
            $finalAccuracy = $result->final_accuracy;
            $dataPoints = $result->data_points ?? [];
            $masteryDate = $this->calculateMasteryDate($dataPoints);

            $upload = FileUpload::find($uploadId);
            $clientName = $upload->user->name ?? 'Unknown';
            $authNumber = $upload->authorization_number ?? '—';
            $authPeriod = $upload->authorization_period ?? '—';

            foreach ($dataPoints as $point) {
                $exportData[] = [
                    'Client Name' => $clientName,
                    'Upload ID' => $uploadId,
                    'Goal Name' => $goalName,
                    'Session Date' => $point['x'],
                    'Accuracy %' => $point['y'],
                    'Final Accuracy %' => $finalAccuracy,
                    'Mastery Date' => $masteryDate ?? '—',
                    'Authorization #' => $authNumber,
                    'Authorization Period' => $authPeriod,
                ];
            }
        }

        return collect(value: $exportData);
    }

    public function headings(): array
    {
        return [
            'Client Name',
            'Upload ID',
            'Goal Name',
            'Session Date',
            'Accuracy %',
            'Final Accuracy %',
            'Mastery Date',
            'Authorization #',
            'Authorization Period',
        ];
    }

    private function calculateMasteryDate(array $dataPoints): ?string
    {
        $consecutive = 0;
        foreach ($dataPoints as $point) {
            if ($point['y'] >= 80) {
                $consecutive++;
                if ($consecutive === 3) {
                    return $point['x']; // return the 3rd date
                }
            } else {
                $consecutive = 0;
            }
        }

        return null;
    }
}
