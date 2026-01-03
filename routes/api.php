<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login-with-code', [AuthController::class, 'loginWithEmployeeCode']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
});

Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return response()->json([
            'message' => 'Welcome to the admin dashboard',
        ]);
    });

    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('positions', PositionController::class);
    Route::apiResource('employees', EmployeeController::class);
});
