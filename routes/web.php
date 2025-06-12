<?php

use App\Http\Controllers\ChartDataController;
use App\Http\Controllers\ChartViewController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RawRecordChartController;
use App\Http\Controllers\RawRecordController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

    Route::get('/charts/raw-records', [RawRecordChartController::class, 'index'])->name('charts.raw-records');
    Route::get('/chart/goals', [ChartDataController::class, 'goalsByAccuracy'])->name('chart.goals');
    Route::get('/chart/behavior', [ChartDataController::class, 'behaviorByDate'])->name('chart.behavior');
    Route::get('/chart/programs', [ChartDataController::class, 'programPerformance'])->name('chart.programs');

});

require __DIR__.'/auth.php';
