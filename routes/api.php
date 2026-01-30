<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// ==================== AUTH ==================== //
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-email', [AuthController::class, 'verifyEmailOtp']);

// 🔹 LOGIN OTP TANPA PASSWORD
Route::post('/login-otp-request', [AuthController::class, 'loginOtpRequest']);
Route::post('/login-otp-verify', [AuthController::class, 'loginOtpVerify']);

// 🔹 LOGIN PAKAI PASSWORD
Route::post('/login-password', [AuthController::class, 'loginPassword']);

// 🔹 LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ==================== DASHBOARD ==================== //
Route::middleware('auth:sanctum')->get('/dashboard', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to dashboard',
        'user' => $request->user(),
    ]);
});

// ==================== UPDATE NAMA USER ==================== //
Route::middleware('auth:sanctum')->put('/update-name', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $user = $request->user();
    $user->update(['name' => $request->name]);

    return response()->json([
        'message' => 'Nama berhasil diperbarui',
        'user' => $user,
    ]);
});

// ==================== RESET PASSWORD ==================== //
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// ==================== ROLE & TASK SYSTEM ==================== //
Route::middleware('auth:sanctum')->group(function () {
    // 🔹 TASKS untuk USER biasa
    Route::get('/tasks', [AuthController::class, 'getTasks']);

    // 🔹 ADMIN: kelola user
    Route::get('/users', [AuthController::class, 'getAllUsers']);
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser']);
    Route::put('/users/{id}/role', [AuthController::class, 'updateUserRole']);
});


