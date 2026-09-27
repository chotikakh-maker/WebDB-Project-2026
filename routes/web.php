<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{MovieManagerController, AnalyticsController};
use App\Http\Controllers\Api\DataFetchController;

Route::get('/', function () { return view('frontend.home'); });

// API Endpoint สำหรับ AJAX
Route::get('/api/v1/search', [DataFetchController::class, 'search']);

// Admin Route Group ปกป้องด้วย Middleware ตรวจสอบ Role
Route::middleware(['auth', 'role:Super Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'report'])->name('analytics');
    Route::post('/movies/store', [MovieManagerController::class, 'store'])->name('movies.store');
});