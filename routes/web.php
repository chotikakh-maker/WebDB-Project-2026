<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MovieManagerController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Api\DataFetchController;
use App\Http\Middleware\RoleCheckMiddleware; // <-- 1. นำเข้าไฟล์ Middleware เข้ามา

Route::get('/', function () { 
    return view('home'); 
})->name('home');

Route::get('/api/v1/search', [DataFetchController::class, 'search']);

// 2. เปลี่ยนคำว่า 'role' เป็น RoleCheckMiddleware::class
Route::middleware(['auth', RoleCheckMiddleware::class . ':Super Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'report'])->name('analytics');
    Route::post('/movies/store', [MovieManagerController::class, 'store'])->name('movies.store');
});