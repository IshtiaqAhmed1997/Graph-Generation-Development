<?php

namespace App\Services;

use App\Models\ChartRecord;
use App\Models\FileUpload;
use Barryvdh\DomPDF\Facade\Pdf;

class ChartExportService
{
    public function generateClientPdf(int $userId, string $clientName)
    {
        $fileUploadIds = FileUpload::where('user_id', $userId)
            ->where('client_name', $clientName)
            ->pluck('id');

        $charts = ChartRecord::where('user_id', $userId)
            ->whereIn('file_upload_id', $fileUploadIds)
            ->whereNotNull('chart_image_path')
            ->orderBy('goal_name')
            ->get();

        return Pdf::loadView('charts.export', [
            'clientName' => $clientName,
            'charts' => $charts,
        ]);
    }
}
