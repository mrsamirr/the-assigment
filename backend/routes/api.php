<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected routes
    Route::middleware('jwt.auth')->group(function () {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // User
        Route::get('/user/profile', [UserController::class, 'profile']);

        // Appointments
        Route::get('/appointments', [AppointmentController::class, 'index']);
        Route::post('/appointments', [AppointmentController::class, 'store']);
        Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
    });
});
