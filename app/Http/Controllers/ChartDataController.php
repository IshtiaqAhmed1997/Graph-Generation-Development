<?php

namespace App\Http\Controllers;

use App\Services\ChartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChartDataController extends Controller
{
    protected ChartService $chartService;

    public function __construct(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function goalsByAccuracy(Request $request): JsonResponse
    {
        $client = $request->get('client_name');
        $data = $this->chartService->getGoalsByAccuracy($client);

        return response()->json([
            'labels' => $data->pluck('target_text'),
            'values' => $data->pluck('avg_accuracy'),
        ]);
    }

    public function getRawRecordChart(Request $request): JsonResponse
    {
        $client = $request->get('client_name');
        $datasets = $this->chartService->getRawRecordChart($client);

        return response()->json([
            'success' => true,
            'datasets' => $datasets,
        ]);
    }

    public function saveChart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file_upload_id' => 'required|integer',
            'client_name'    => 'required|string',
            'target_text'    => 'required|string',
            'chart_type'     => 'required|string',
            'chart_config'   => 'required|array',
            'chart_image'    => 'nullable|string',
        ]);

        $path = null;

        if (!empty($validated['chart_image'])) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $validated['chart_image']));
            $fileName = 'charts/' . uniqid() . '.png';
            Storage::put("public/{$fileName}", $imageData);
            $path = "storage/{$fileName}";
        }

        $this->chartService->saveGeneratedChart(
            $validated['file_upload_id'],
            $validated['client_name'],
            $validated['target_text'],
            $validated['chart_type'],
            $validated['chart_config'],
            $path
        );

        return response()->json(['success' => true, 'message' => 'Chart saved']);
    }
}
