<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileUploadRequest;
use App\Imports\RawRecordImport;
use App\Jobs\ProcessRawRecords;
use App\Models\FileUpload;
use App\Services\AnalyticsService;
use App\Services\ErrorLogService;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload.index');
    }

    public function store(StoreFileUploadRequest $request)
    {
        $file = $request->file('file');
        $path = $file->store('uploads');

        $upload = FileUpload::create([
            'user_id' => Auth::id(),
            'filename' => $file->getClientOriginalName(),
            'filepath' => $path,
            'file_type' => $file->getClientMimeType(),
            'is_processed' => false,
        ]);

        $import = new RawRecordImport($upload->id, 'treatment_plan', app(ErrorLogService::class));
        Excel::import($import, $file);

        app(AnalyticsService::class)->processFileUpload($upload->id);

        $upload->is_processed = true;
        $upload->validated_by = Auth::id();
        $upload->save();

        ProcessRawRecords::dispatch();

        return redirect()->route('upload.index')->with('success', 'File uploaded and processed successfully.');
    }
}
