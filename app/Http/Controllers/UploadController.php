<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileUploadRequest;
use App\Imports\RawRecordImport;
use App\Models\FileUpload;
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

        $import = new RawRecordImport($upload->id);
        Excel::import($import, $file);

        $upload->is_processed = true;
        $upload->validated_by = Auth::id();
        $upload->save();

        if (! empty($import->errors)) {
            return redirect()->route('upload.index')
                ->withErrors($import->errors)
                ->with('success', 'File processed with some validation errors.');
        }

        return redirect()->route('upload.index')->with('success', 'Fi   le uploaded and processed successfully.');
    }
}
