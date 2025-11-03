<?php

use App\Http\Controllers\ChartDataController;
use App\Http\Controllers\ChartExportController;
use App\Http\Controllers\ChartViewController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RawRecordChartController;
use App\Http\Controllers\RawRecordController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
    Route::get('/raw-records', [RawRecordController::class, 'index'])->name('raw-records.index');

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/charts', [ChartViewController::class, 'index'])->name('charts.index');

    Route::get('/chart/goals', [ChartDataController::class, 'goalsByAccuracy'])->name('chart.goals');
    Route::get('/chart/raw', [ChartDataController::class, 'getRawRecordChart'])->name('chart.raw');
    Route::post('/chart/save', [ChartDataController::class, 'saveChart'])->name('chart.save');

    Route::get('/charts/raw-records', [RawRecordChartController::class, 'index'])->name('charts.raw-records');
    Route::post('/charts/store', [RawRecordChartController::class, 'storeChart'])->name('charts.store');

    Route::get('/charts/pdf', [ChartExportController::class, 'download'])->name('charts.pdf');
    Route::get('/charts/download-zip', [ChartExportController::class, 'downloadZip'])->name('charts.download.zip');
    Route::get('/charts/export', [ChartExportController::class, 'export'])->name('charts.export');
});

require __DIR__.'/auth.php';
