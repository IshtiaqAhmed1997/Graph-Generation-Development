<?php

namespace App\Http\Controllers;

use App\Services\ChartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        return response()->json(['datasets' => $datasets]);
    }
}
