<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Services\ChartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RawRecordChartController extends Controller
{
    protected ChartService $chartService;

    public function __construct(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function index(Request $request): JsonResponse
    {
        $client = $request->get('client_name');

        $upload = FileUpload::where('user_id', auth()->id())
            ->where('client_name', $client)
            ->latest()
            ->first();

        if (! $upload) {
            return response()->json([
                'error' => 'No file upload record found for this client.',
            ], 404);
        }

        $datasets = $this->chartService->getRawRecordChart($client);

        return response()->json([
            'file_upload_id' => $upload->id,
            'datasets' => $datasets,
        ]);
    }

    public function storeChart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file_upload_id' => 'required|exists:file_uploads,id',
            'client_name'    => 'required|string',
            'target_text'    => 'required|string',
            'chart_type'     => 'required|string',
            'chart_config'   => 'required|array',
            'chart_image'    => 'nullable|string',
        ]);

        $imagePath = null;

        if (!empty($validated['chart_image'])) {
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $validated['chart_image']);
            $image = base64_decode($base64);
            $filename = 'charts/' . uniqid() . '.png';
            Storage::put("public/{$filename}", $image);
            $imagePath = "storage/{$filename}";
        }

        $this->chartService->saveGeneratedChart(
            $validated['file_upload_id'],
            $validated['client_name'],
            $validated['target_text'],
            $validated['chart_type'],
            $validated['chart_config'],
            $imagePath
        );

        return response()->json([
            'success' => true,
            'message' => 'Chart saved with image.',
            'image_path' => $imagePath,
        ]);
    }
}
