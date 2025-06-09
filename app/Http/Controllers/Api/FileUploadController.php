<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileUploadRequest;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function store(StoreFileUploadRequest $request)
    {
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('uploads');

        $upload = FileUpload::create([
            'filename' => $originalName,
            'path' => $path,
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'data' => $upload,
        ]);
    }
}