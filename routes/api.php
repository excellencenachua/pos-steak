<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController; // <--- Tambahan
use App\Http\Controllers\BranchController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\InventoryController;

// Rute Public
Route::post('/auth/login', [AuthController::class, 'login']);

// Rute Khusus Admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Rute Dummy (bisa dihapus nanti)
    Route::get('/admin-only', function () {
        return response()->json(['message' => 'Selamat datang, Admin!']);
    });

    // Rute Master User (CRUD)
    Route::apiResource('/users', UserController::class);

    Route::apiResource('/branches', BranchController::class); // master cabang
});

// Rute Khusus Cashier
Route::middleware(['auth:sanctum', 'role:cashier'])->group(function () {
    Route::get('/cashier-only', function () {
        return response()->json(['message' => 'Selamat datang, Cashier!']);
    });

    Route::post('/orders', [OrderController::class, 'store']); 

});



// Rute Khusus Purchasing
Route::middleware(['auth:sanctum', 'role:purchasing'])->group(function () {
    Route::get('/purchasing-only', function () {
        return response()->json(['message' => 'Selamat datang, Purchasing!']);
    });

    Route::get('/inventory/stock', [InventoryController::class, 'index']);
});