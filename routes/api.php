<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\TicketTypeApiController;
use App\Http\Controllers\Api\EventDiscoveryController;
use App\Http\Controllers\Api\ExternalApiController;
use App\Http\Resources\UserResource;

/**
 * Eventix API v1 Routes
 * Prefix: /api/v1 (configured in bootstrap/app.php)
 */

// Authentication
Route::middleware('throttle:login')->post('/login', [AuthController::class, 'login']);

// Public Discovery API
Route::prefix('discovery')->group(function () {
    Route::get('/', [EventDiscoveryController::class, 'index']);
    Route::get('/search', [EventDiscoveryController::class, 'search']);
});

// External Integrations
Route::get('/external/weather', [ExternalApiController::class, 'eventWeather']);

// Protected Routes
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    
    // Auth Management
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function () {
    return new UserResource(auth()->user());
});
    
    // Events API
    Route::prefix('events')->group(function () {
        Route::get('/', [EventApiController::class, 'index']);
        Route::get('/upcoming', [EventApiController::class, 'upcoming']);
        Route::get('/{event}', [EventApiController::class, 'show']);
        Route::get('/{event}/availability', [EventApiController::class, 'availability']);
        Route::post('/', [EventApiController::class, 'store']);
    });
    
    // Bookings API
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingApiController::class, 'index']);
        Route::post('/', [BookingApiController::class, 'store']);
        Route::delete('/{booking}', [BookingApiController::class, 'destroy']);
    });

    // Ticket Types API (The "All Methods" way)
    Route::apiResource('ticket-types', TicketTypeApiController::class);
});
