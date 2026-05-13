<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceApiController;

// Public API endpoints (no authentication required)
Route::get('/services', [ServiceApiController::class, 'getServices']);
Route::get('/wheeler-categories', [ServiceApiController::class, 'getWheelerCategories']);
Route::get('/services/by-category/{categoryId}', [ServiceApiController::class, 'getServicesByCategory']);
