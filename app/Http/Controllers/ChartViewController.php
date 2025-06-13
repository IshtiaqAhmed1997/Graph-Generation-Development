<?php

namespace App\Http\Controllers;

use App\Models\RawRecord;

class ChartViewController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $clients = RawRecord::where('user_id', $userId)
            ->distinct()->pluck('client_name');

        $latestUploadId = RawRecord::where('user_id', $userId)
            ->latest('id')
            ->value('file_upload_id');

        return view('charts.index', compact('clients', 'latestUploadId'));
    }
}
