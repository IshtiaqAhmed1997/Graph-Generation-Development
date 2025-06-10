<?php

namespace App\Http\Controllers;

use App\Models\RawRecord;

class RawRecordController extends Controller
{
    public function index()
    {
        $records = RawRecord::latest()->paginate(10);

        return view('raw-records.index', compact('records'));
    }
}
