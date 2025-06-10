<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = FileUpload::query()->with('user');

        if ($request->filled('filename')) {
            $query->where('filename', 'like', '%'.$request->filename.'%');
        }

        if ($request->filled('status')) {
            $query->where('is_processed', $request->status === 'processed' ? true : false);
        }

        if ($request->filled('uploaded_by')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->uploaded_by.'%');
            });
        }

        $logs = $query->latest()->paginate(10)->withQueryString();

        return view('logs.index', compact('logs'));
    }
}
