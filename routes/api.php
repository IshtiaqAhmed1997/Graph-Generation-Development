<?php

use App\Http\Controllers\Api\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/uploads', [FileUploadController::class, 'store']);
});
