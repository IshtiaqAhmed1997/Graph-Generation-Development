<?php

use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\RawRecordController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/uploads', [FileUploadController::class, 'store']);
    Route::post('/raw-records/import', [RawRecordController::class, 'import']);

});
