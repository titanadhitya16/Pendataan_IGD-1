<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EscortApi;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Sanctum authentication routes
Route::get('/sanctum/csrf-cookie', [AuthApiController::class, 'csrfToken']);
Route::post('/auth/login', [AuthApiController::class, 'login']);
Route::post('/auth/logout', [AuthApiController::class, 'logout']);
Route::get('/auth/check', [AuthApiController::class, 'check']);
Route::get('/auth/user', [AuthApiController::class, 'user']);
Route::get('/auth/sanctum', [AuthApiController::class, 'sanctum']);

// Authentication required routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes - accessible for escort form submissions
Route::apiResource('escort', EscortApi::class)->only(['store']);

// Session stats endpoint (public for monitoring)
Route::get('/session-stats', [EscortApi::class, 'getSessionStats']);

// QR Code generation endpoint (public for easier access)
Route::get('/qr-code/form', [\App\Http\Controllers\EscortDataController::class, 'generateFormQrCode']);

// Protected API routes - require authentication (IGD Staff only)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('escort', EscortApi::class)->except(['store']);
    
    // Status management endpoint
    Route::patch('/escort/{escort}/status', [EscortApi::class, 'updateStatus']);
    
    // Base64 image endpoints
    Route::get('/escort/{escort}/image/base64', [EscortApi::class, 'getImageBase64']);
    Route::post('/escort/{escort}/image/base64', [EscortApi::class, 'uploadImageBase64']);
    
    // Additional protected endpoints
    Route::get('/dashboard/stats', [EscortApi::class, 'getDashboardStats']);
});
