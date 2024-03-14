<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Mail\Auth\EmailVerificationMail;
use App\Mail\Auth\ForgotPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function signIn(SignInRequest $request)
    {
        $validated = $request->validated();
        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return response(null, Response::HTTP_NO_CONTENT);
        }
        return response(null, Response::HTTP_BAD_REQUEST);
    }

    public function signOut()
    {
        Auth::logout();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getToken(SignInRequest $request)
    {
        $validated = $request->validated();
        if (Auth::attempt($validated)) {
            // TODO return an API token
        }
        return response(null, Response::HTTP_BAD_REQUEST);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response($user);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $requiresEmailValidation = false;
        $validated = $request->validated();
        $user = $request->user();

        if ($user->email != $validated['email']) {
            $requiresEmailValidation = true;
        }

        $user->update($validated);

        $new_password = $request->input('new_password');
        if ($new_password) {
            $user->password = Hash::make($new_password);
            $user->save();
        }

        if ($requiresEmailValidation) {
            $user->email_verified_at = null;
            $user->save();

            Mail::to($user)->send(new EmailVerificationMail($user));
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function signUp(SignUpRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $validated = $request->validated();
                $user = User::create($validated);
                $user->password = Hash::make($validated['password']);
                // $user->permissions = [Permissions::RecordSubmit];
                $user->save();

                Mail::to($user)->send(new EmailVerificationMail($user));
            });
            return response(null, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            // TODO log
        }
        return response(null, Response::HTTP_BAD_REQUEST);
    }

    public function emailVerification(Request $request)
    {
        $user = $request->user();

        if ($user->email_verified_at) {
            return response(null, Response::HTTP_NO_CONTENT);
        } else {
            try {
                Mail::to($user)->send(new EmailVerificationMail($user));
            } catch (\Throwable $th) {
                return response(null, Response::HTTP_FAILED_DEPENDENCY);
            }
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $validated = $request->validated();
        $user = User::find($validated['id']);

        if (!$user->email_verified_at) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();

        if ($user) {
            try {
                Mail::to($user)->send(new ForgotPasswordMail($user));
            } catch (\Throwable $th) {
                return response(null, Response::HTTP_FAILED_DEPENDENCY);
            }
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $user = User::find($validated['id']);

        if ($validated['hash'] != hash('sha256', $user->password)) {
            return response(null, Response::HTTP_BAD_REQUEST);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
