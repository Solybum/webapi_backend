<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('csrf', function (Request $request) {
    return response()->noContent();
});

Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('/sign-in', [AuthController::class, 'signIn'])
            ->name('signIn');

        Route::post('/sign-out', [AuthController::class, 'signOut'])
            ->name('signOut');
    });
