<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FinancialRecordController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:analyst,admin'])->group(function () {
    Route::get('financial-records', [FinancialRecordController::class, 'index']);
    Route::get('financial-records/{id}', [FinancialRecordController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('financial-records', [FinancialRecordController::class, 'store']);
    Route::put('financial-records/{id}', [FinancialRecordController::class, 'update']);
    Route::delete('financial-records/{id}', [FinancialRecordController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('user-management', [UserController::class, 'index']);
    Route::get('user-management/{id}', [UserController::class, 'show']);
    Route::put('user-management/{id}', [UserController::class, 'update']);
    Route::delete('user-management/{id}', [UserController::class, 'destroy']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
