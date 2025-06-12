<?php

namespace App\Http\Controllers;

use App\Services\ChartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $data = $this->chartService->getGoalsByAccuracy(auth()->id(), $client);

        return response()->json([
            'labels' => $data->pluck('target_text'),
            'values' => $data->pluck('avg_accuracy'),
        ]);
    }

    public function behaviorByDate(Request $request): JsonResponse
    {
        $client = $request->get('client_name');
        $data = $this->chartService->getBehaviorByDate(auth()->id(), $client);

        return response()->json([
            'labels' => $data->pluck('date'),
            'values' => $data->pluck('total_accuracy'),
        ]);
    }

    public function programPerformance(Request $request): JsonResponse
    {
        $client = $request->get('client_name');
        $data = $this->chartService->getProgramPerformance(auth()->id(), $client);

        return response()->json([
            'labels' => $data->pluck('program_name'),
            'values' => $data->pluck('avg_accuracy'),
        ]);
    }
}
