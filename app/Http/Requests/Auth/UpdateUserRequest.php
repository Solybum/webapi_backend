<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'             => 'nullable|string|max:32',
            'email'            => [
                "required",
                "email",
                "max:255",
                Rule::unique('users')->ignore(Auth::user()->id),
            ],
            'new_password'     => 'nullable|sometimes|string|confirmed|min:8|max:32',
            'current_password' => [
                'nullable',
                'required_with:new_password',
                'string',
                'current_password',
            ],
        ];
    }
}
