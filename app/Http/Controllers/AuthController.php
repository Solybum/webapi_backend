<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SignInRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        return response()->noContent(Response::HTTP_BAD_REQUEST);
    }

    public function signOut()
    {
        Auth::logout();
        return response()->noContent();
    }

    public function getToken(SignInRequest $request)
    {
        $validated = $request->validated();
        if (Auth::attempt($validated)) {
            // TODO return an API token
        }
        return response()->noContent(Response::HTTP_BAD_REQUEST);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}
