<?php

namespace App\Http\Controllers;

use App\Models\ChartRecord;
use App\Services\ChartExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ChartXlsxExport;
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

        if (!$clientName) {
            abort(400, 'Client name is required.');
        }

        $userId = Auth::id();

        $pdf = $this->exportService->generateClientPdf($userId, $clientName);

        app(\App\Services\AuditLogService::class)->log(
            action: 'pdf_export',
            goalResultId: null,
            details: "PDF chart report exported for client: {$clientName}",
            versionId: null
        );
        return Response::streamDownload(
            fn() => print ($pdf->output()),
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

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'Chart image not found.');
        }

        app(\App\Services\AuditLogService::class)->log(
            action: 'chart_image_download',
            goalResultId: null,
            details: "Downloaded chart image (ID: {$chart->id}, Goal: {$chart->goal_name})",
            versionId: $chart->chart_config['version_id'] ?? null
        );


        return Storage::disk('public')->download($path, 'chart.png');
    }

    public function downloadZip(Request $request)
    {
        $clientName = $request->query('client_name');

        if (!$clientName) {
            abort(400, 'Client name is required.');
        }

        $userId = Auth::id();

        // $fileUploadIds = \App\Models\FileUpload::where('user_id', $userId)
        $fileUploadIds = \App\Models\FileUpload::where('client_name', $clientName)
            ->pluck('id');

        $charts = \App\Models\ChartRecord::where('file_upload_id', $fileUploadIds)
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

        $zip = new ZipArchive;
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

        app(\App\Services\AuditLogService::class)->log(
            action: 'zip_export',
            goalResultId: null,
            details: "Downloaded ZIP of charts for client: {$clientName}",
            versionId: null
        );

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function exportXlsx(Request $request)
    {
        $clientName = $request->query('client_name');
        if (!$clientName)
            abort(400, 'Client name is required.');

        app(\App\Services\AuditLogService::class)->log(
            action: 'xlsx_export',
            goalResultId: null,
            details: "Exported XLSX data for client: {$clientName}",
            versionId: null
        );

        return Excel::download(new ChartXlsxExport(Auth::id(), $clientName), 'chart_data_' . $clientName . '.xlsx');

    }


    public function exportExcel(Request $request)
    {
        $clientName = $request->query('client_name');

        if (!$clientName) {
            abort(400, 'Client name is required.');
        }

        $userId = Auth::id();
        $timestamp = now()->format('Ymd_His');
        $filename = "chart_export_{$clientName}_{$timestamp}.xlsx";

        app(\App\Services\AuditLogService::class)->log(
            action: 'xlsx_export',
            goalResultId: null,
            details: "Exported XLSX data for client: {$clientName}",
            versionId: null
        );

        return Excel::download(new ChartXlsxExport($userId, $clientName), $filename);
    }
}
