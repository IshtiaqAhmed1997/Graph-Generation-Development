<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\RawRecordImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RawRecordController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv|max:10240',
        ]);

        $import = new RawRecordImport;
        Excel::import($import, $request->file('file'));

        return response()->json([
            'success' => true,
            'message' => 'Import completed.',
            'errors' => $import->errors,
        ]);
    }
}
