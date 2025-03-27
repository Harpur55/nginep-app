<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;

// Group untuk Auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'login']);
});

// Group untuk Customer (Public & Authenticated)
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']); // Menampilkan semua customer
    Route::get('/users', [UserController::class, 'getAllUsers']); // Menampilkan semua user

    // Group khusus yang membutuhkan autentikasi
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/update', [CustomerController::class, 'updateProfile']);
    });
});

Route::prefix('hotels')->group(function () {
    Route::post('/create', [HotelController::class, 'createHotel']);
    Route::get('/', [HotelController::class, 'index']);
    Route::get('/{id}', [HotelController::class, 'show']);
    Route::put('/update/{id}', [HotelController::class, 'update']);
    Route::delete('/delete/{id}', [HotelController::class, 'destroy']);
});

Route::prefix('rooms')->group(function () {
    Route::post('/create', [RoomController::class, 'create']);
    Route::get('/', [RoomController::class, 'index']);
    Route::get('/{id}', [RoomController::class, 'show']);
    Route::put('/update/{id}', [RoomController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
});

