<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SignInRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function signIn(SignInRequest $request)
    {
        $validated = $request->validated();
        if (Auth::attempt($validated)) {
            return response()->noContent();
        }

        return response()->isSuccessful();
    }

    public function signOut()
    {
        Auth::logout();
        return response()->noContent();
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}
