<?php
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\TodoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('email/verify', [AuthController::class, 'verifyOtp']);
    Route::post('resend/otp', [AuthController::class, 'resendOtp']);
    Route::get('user/profile', [AuthController::class, 'userProfile']);

    Route::apiResource('todo', TodoController::class)->middleware('verified');
});