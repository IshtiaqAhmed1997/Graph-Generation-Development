<?php

namespace App\Exports;

use App\Models\ChartRecord;
use App\Models\FileUpload;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChartXlsxExport implements FromCollection, WithHeadings
{
    protected int $userId;
    protected string $clientName;

    public function __construct(int $userId, string $clientName)
    {
        $this->userId = $userId;
        $this->clientName = $clientName;
    }

    public function collection(): Collection
    {
        $fileUploadIds = FileUpload::where('user_id', $this->userId)
            ->where('client_name', $this->clientName)
            ->pluck('id');

        $charts = ChartRecord::where('user_id', $this->userId)
            ->whereIn('file_upload_id', $fileUploadIds)
            ->whereNotNull('chart_image_path')
            ->get();

        $rows = collect();

        foreach ($charts as $chart) {
            $config = $chart->chart_config;

            $labels = $config['labels'] ?? [];
            $data = $config['data'] ?? [];

            foreach ($data as $index => $point) {
                $rows->push([
                    'Client Name'     => $this->clientName,
                    'Goal Name'       => $chart->goal_name,
                    'Date'            => $point['x'] ?? null,
                    'Value'           => $point['y'] ?? null,
                    'Baseline'        => $config['baseline'] ?? null,
                    'Mastery Point'   => $config['mastery_point'] ?? null,
                    'Phase Change?'   => in_array($point['x'] ?? '', $config['phase_lines'] ?? []) ? 'Yes' : 'No',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Client Name',
            'Goal Name',
            'Date',
            'Value',
            'Baseline',
            'Mastery Point',
            'Phase Change?',
        ];
    }
}
