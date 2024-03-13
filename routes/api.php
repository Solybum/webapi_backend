<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('csrf', function (Request $request) {
    return response()->noContent();
})->middleware('web');

Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('/sign-in', [AuthController::class, 'signIn'])
            ->name('signIn')
            ->middleware('web');

        Route::post('/sign-out', [AuthController::class, 'signOut'])
            ->name('signOut')
            ->middleware('web');

        Route::post('/token', [AuthController::class, 'getToken'])
            ->name('getToken');

        Route::get('/user', [AuthController::class, 'getUser'])
            ->name('getUser')
            ->middleware('auth:sanctum');

        Route::post('/user', [AuthController::class, 'updateUser'])
            ->name('updateUser')
            ->middleware('auth:sanctum');

        Route::post('/sign-up', [AuthController::class, 'signUp'])
            ->name('signUp');

        Route::post('/request-validation', [AuthController::class, 'requestValidation'])
            ->name('requestValidation')
            ->middleware('auth:sanctum');

        Route::post('/validate', [AuthController::class, 'validate'])
            ->name('validate')
            ->middleware('signed');

        Route::post('/request-password-reset', [AuthController::class, 'requestPasswordReset'])
            ->name('requestPasswordReset');

        Route::post('/reset-password', [AuthController::class, 'resetPassword'])
            ->name('resetPassword')
            ->middleware('signed');
    });
