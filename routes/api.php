<?php
use App\Http\Controllers\Api\DataFetchController;
Route::get('/v1/search', [DataFetchController::class, 'search']);