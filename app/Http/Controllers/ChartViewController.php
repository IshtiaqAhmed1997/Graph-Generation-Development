<?php

namespace App\Http\Controllers;

use App\Models\RawRecord;

class ChartViewController extends Controller
{
    public function index()
    {
        $clients = RawRecord::whereNotNull('client_name')
            ->where('user_id', auth()->id())
            ->distinct()
            ->orderBy('client_name')
            ->pluck('client_name');

        return view('charts.index', compact('clients'));
    }
}
