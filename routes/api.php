<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('csrf', function (Request $request) {
    return response()->noContent();
})->middleware('web');

Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('/sign-in', [AuthController::class, 'signIn'])
            ->name('signIn');

        Route::post('/sign-out', [AuthController::class, 'signOut'])
            ->name('signOut')
            ->middleware('web');

        Route::get('/user', [AuthController::class, 'getUser'])
            ->name('user')
            ->middleware('auth:sanctum');

        Route::post('/user', [AuthController::class, 'updateUser'])
            ->name('updateUser')
            ->middleware('auth:sanctum');

        Route::post('/sign-up', [AuthController::class, 'signUp'])
            ->name('signUp');

        Route::post('/request-validation', [AuthController::class, 'requestValidation'])
            ->name('request_validation')
            ->middleware('auth:sanctum');

        Route::post('/validate', [AuthController::class, 'validate'])
            ->name('validate')
            ->middleware('signed');

        Route::post('/request-password-reset', [AuthController::class, 'requestPasswordReset'])
            ->name('request_password_reset');

        Route::post('/reset-password', [AuthController::class, 'resetPassword'])
            ->name('reset_password')
            ->middleware('signed');
    });
