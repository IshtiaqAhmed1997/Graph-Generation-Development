<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileUploadController;

Route::middleware('api')->group(function () {
    Route::post('/uploads', [FileUploadController::class, 'store']);
});
