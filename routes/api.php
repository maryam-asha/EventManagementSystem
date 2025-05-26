<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\EventTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




// Public routes
Route::get('events', [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Event routes
    Route::prefix('events')->group(function () {
        Route::post('/', [EventController::class, 'store']);
        Route::put('/{event}', [EventController::class, 'update']);
        Route::delete('/{event}', [EventController::class, 'destroy']);
        Route::post('/{event}/publish', [EventController::class, 'publish']);
        Route::post('/{event}/feature', [EventController::class, 'feature']);
    });
    // Reservation routes
    Route::prefix('reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index']);
        Route::post('/', [ReservationController::class, 'store']);
        Route::get('/{reservation}', [ReservationController::class, 'show']);
        Route::put('/{reservation}', [ReservationController::class, 'update']);
        Route::delete('/{reservation}', [ReservationController::class, 'destroy']);
        Route::post('/{reservation}/cancel', [ReservationController::class, 'cancel']);
        Route::post('/{reservation}/confirm', [ReservationController::class, 'confirm']);
    });

    // Location routes
    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationController::class, 'index']);
        Route::post('/', [LocationController::class, 'store']);
        Route::get('/{location}', [LocationController::class, 'show']);
        Route::put('/{location}', [LocationController::class, 'update']);
        Route::delete('/{location}', [LocationController::class, 'destroy']);
    });

    // Event Types
    Route::apiResource('event-types', EventTypeController::class);
});
