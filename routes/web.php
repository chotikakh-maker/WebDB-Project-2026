<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MovieManagerController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Api\DataFetchController;
use App\Http\Middleware\RoleCheckMiddleware;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MovieController; 

Route::get('/', function () { 
    return view('home'); 
})->name('home');


Route::redirect('/dashboard', '/');

// กดส่งรีวิว (ต้องล็อกอิน)
Route::middleware(['auth'])->group(function () {
    Route::post('/movies/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    // ... (เส้นทาง movies.create เดิมที่มีอยู่แล้ว)
});

Route::middleware(['auth'])->group(function () {
    Route::get('/movies/create', function () {
        return view('movies.create');
    })->name('movies.create');
});

Route::get('/api/v1/search', [DataFetchController::class, 'search']);


// โซนสำหรับผู้ใช้ที่ล็อกอินแล้ว
Route::middleware(['auth'])->group(function () {
    Route::get('/movies/create', [MovieController::class, 'create'])->name('movies.create');
    Route::post('/movies', [MovieController::class, 'store'])->name('movies.store.user'); // รับข้อมูล POST
});

Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');