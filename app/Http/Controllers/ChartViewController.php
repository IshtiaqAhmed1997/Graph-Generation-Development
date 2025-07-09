<?php

namespace App\Http\Controllers;

use App\Models\RawRecord;
use Carbon\Carbon;

class ChartViewController extends Controller
{
   public function index()
{
    $userId = auth()->id();

    $clients = RawRecord::
    // where('user_id', $userId)
        distinct()
        ->pluck('client_name');

    $latestUploadId = RawRecord::
    // where('user_id', $userId)
        latest('id')
        ->value('file_upload_id');

    $records = RawRecord::
    
    // where('user_id', $userId)
        where('file_upload_id', $latestUploadId)
        ->orderBy('date_of_service')
        ->get();

    $labels = [];
    $skillAccuracy = [];

    foreach ($records as $record) {
        $labels[] = Carbon::parse($record->date_of_service)->format('Y-m-d');
        $skillAccuracy[] = (float) $record->accuracy;
    }

    $trend = collect($skillAccuracy)->map(function ($val, $i) {
        return $val + ($i * 1.5);
    });
    
    $chartData = [
        'labels' => $labels,
        'skillAccuracy' => $skillAccuracy,
        'trend' => $trend,
        'title' => 'Skill Accuracy Over Time',
        'baseline' => 30, 
        'phaseDate' => $labels[5] ?? null,
    ];  
   

    return view('charts.index', compact('clients', 'latestUploadId', 'chartData'));
}
}
