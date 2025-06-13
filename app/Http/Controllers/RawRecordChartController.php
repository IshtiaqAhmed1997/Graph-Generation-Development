<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Services\ChartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $datasets = $this->chartService->getRawRecordChart(auth()->id(), $client);

        $upload = FileUpload::where('user_id', auth()->id())
            ->where('client_name', $client)
            ->latest()
            ->first();

        return response()->json([
            'file_upload_id' => $upload?->id,
            'datasets' => $datasets,
        ]);
    }

    public function storeChart(Request $request): JsonResponse
    {

        $request->validate([
            'goal' => 'required|string',
            'file_upload_id' => 'required|exists:file_uploads,id',
            'chart_data' => 'required|array',
        ]);

        $imagePath = null;

        $existing = \App\Models\ChartRecord::where([
            'user_id' => auth()->id(),
            'file_upload_id' => $request->file_upload_id,
            'goal_name' => $request->goal,
        ])->first();

        if ($existing && $existing->chart_image_path) {
            $oldPath = str_replace('storage/', 'public/', $existing->chart_image_path);
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }

        if ($request->has('chart_image')) {
            $base64 = $request->input('chart_image');
            $image = str_replace('data:image/png;base64,', '', $base64);
            $image = str_replace(' ', '+', $image);
            $filename = 'chart_'.uniqid().'.png';
            Storage::put("public/chart_images/{$filename}", base64_decode($image));
            $imagePath = "storage/chart_images/{$filename}";
        }

        $this->chartService->saveGeneratedChart(
            auth()->id(),
            $request->file_upload_id,
            $request->goal,
            $request->chart_data,
            $imagePath
        );

        return response()->json(['message' => 'Chart saved with image.']);
    }
}
