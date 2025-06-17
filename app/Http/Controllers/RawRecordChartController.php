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
        $datasets = $this->chartService->getRawRecordChart(auth()->id(), $client);

        $upload = FileUpload::where('user_id', auth()->id())
            ->where('client_name', $client)
            ->latest()
            ->first();

        if (! $upload) {
            return response()->json([
                'error' => 'No file upload record found for this client.',
            ], 404);
        }

        return response()->json([
            'file_upload_id' => $upload?->id,
            'datasets' => $datasets,
        ]);
    }

    public function storeChart(Request $request): JsonResponse
    {

        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if (! $request->expectsJson()) {
            return response()->json(['error' => 'Invalid JSON request'], 400);
        }

        $request->validate([
            'goal' => 'nullable|string',
            'chart_type' => 'required|string',
            'file_upload_id' => 'required|exists:file_uploads,id',
            'chart_data' => 'required|array',
        ]);

        $imagePath = null;

        $existing = \App\Models\ChartRecord::where([
            'user_id' => auth()->id(),
            'file_upload_id' => $request->file_upload_id,
            'goal_name' => $request->goal,
            'chart_type' => $request->chart_type,
        ])->first();

        if ($existing && $existing->chart_image_path) {
            $oldPath = str_replace('storage/', '', $existing->chart_image_path);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        if ($request->has('chart_image')) {
            $base64 = $request->input('chart_image');
            $image = str_replace('data:image/png;base64,', '', $base64);
            $image = str_replace(' ', '+', $image);
            $filename = 'chart_'.uniqid().'.png';

            try {
                $decoded = base64_decode($image, true);
                if ($decoded === false) {
                    return response()->json(['error' => 'Invalid chart image provided.'], 422);
                }

                Storage::disk('public')->put("chart_images/{$filename}", $decoded);
                $imagePath = "storage/chart_images/{$filename}";

            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to save chart image.'], 500);
            }
        }

        $this->chartService->saveGeneratedChart(
            auth()->id(),
            $request->file_upload_id,
            $request->goal,
            $request->chart_type,
            $request->chart_data,
            $imagePath
        );

        return response()->json([
            'message' => 'Chart saved with image.',
            'image_path' => $imagePath,
        ]);
    }
}
