<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileUploadRequest;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Auth;

class FileUploadController extends Controller
{
    public function store(StoreFileUploadRequest $request)
    {
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('uploads');

        $upload = FileUpload::create([
            'user_id' => Auth::id(),
            'filename' => $originalName,
            'filepath' => $path,
            'file_type' => $file->getClientMimeType(),
            'is_processed' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'data' => $upload,
        ]);
    }
}
