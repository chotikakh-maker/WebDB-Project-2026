<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MovieManagerController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Api\DataFetchController;

// แก้ไขบรรทัดนี้: เติม ->name('home') ต่อท้าย
Route::get('/', function () { 
    return view('home'); 
})->name('home');

// API Endpoint สำหรับ AJAX (ใช้ของเดิม)
Route::get('/api/v1/search', [DataFetchController::class, 'search']);

// Admin Route (ใช้ของเดิม)
Route::middleware(['auth', 'role:Super Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'report'])->name('analytics');
    Route::post('/movies/store', [MovieManagerController::class, 'store'])->name('movies.store');
});