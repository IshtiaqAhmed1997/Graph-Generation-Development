<?php

namespace App\Http\Controllers;

use App\Models\ChartRecord;
use App\Services\ChartExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;
use Illuminate\Support\Str;

class ChartExportController extends Controller
{
    protected ChartExportService $exportService;

    public function __construct(ChartExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function export(Request $request)
    {
        $clientName = $request->query('client_name');

        if (! $clientName) {
            abort(400, 'Client name is required.');
        }

        $userId = Auth::id();

        $pdf = $this->exportService->generateClientPdf($userId, $clientName);

        return Response::streamDownload(
            fn () => print ($pdf->output()),
            'client_chart_report.pdf'
        );
    }

    public function download(Request $request): StreamedResponse
    {
        $request->validate([
            'chart_id' => 'required|exists:chart_records,id',
        ]);

        $chart = ChartRecord::findOrFail($request->chart_id);

        $path = $chart->chart_image_path;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404, 'Chart image not found.');
        }

        return Storage::disk('public')->download($path, 'chart.png');
    }

    public function downloadZip(Request $request)
{
    $clientName = $request->query('client_name');

    if (! $clientName) {
        abort(400, 'Client name is required.');
    }

    $userId = Auth::id();

    $fileUploadIds = \App\Models\FileUpload::where('user_id', $userId)
        ->where('client_name', $clientName)
        ->pluck('id');

    $charts = \App\Models\ChartRecord::where('user_id', $userId)
        ->whereIn('file_upload_id', $fileUploadIds)
        ->whereNotNull('chart_image_path')
        ->get();

    if ($charts->isEmpty()) {
        abort(404, 'No charts found.');
    }

    $zipFileName = 'charts_' . Str::slug($clientName) . '.zip';
    $zipPath = storage_path("app/public/exports/{$zipFileName}");

    if (!file_exists(dirname($zipPath))) {
        mkdir(dirname($zipPath), 0777, true);
    }

    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        abort(500, 'Could not create ZIP file.');
    }

    foreach ($charts as $chart) {
        $fullPath = public_path($chart->chart_image_path);
        if (file_exists($fullPath)) {
            $filename = $chart->goal_name . '_' . basename($chart->chart_image_path);
            $zip->addFile($fullPath, $filename);
        }
    }

    $zip->close();

    return response()->download($zipPath)->deleteFileAfterSend(true);
}
}
