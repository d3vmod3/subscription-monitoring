<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Api\SubscribersController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/profile', [ProfileController::class, 'save']);
    

    Route::post('/logout', [AuthController::class, 'logout']);

    // Subsribers
    Route::get('/subscribers', [SubscribersController::class, 'list']);
    Route::get('/mikrotik-names/{id}', [SubscribersController::class, 'mikrotikNames']);
    Route::get('/subscription-totals/{id}', [SubscribersController::class, 'subscriptionTotals']);
    Route::get('/billing-pdf/{subscriptionHash}/{from?}/{to?}',
            [SubscribersController::class, 'generatePdf']
        );
    // Payments
    Route::post('/payments', [PaymentController::class, 'store']);
});