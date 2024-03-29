<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:users,name,NULL,id,deleted_at,NULL',
            'password' => [
                'required',
                'string',
                'min:6',                // must be at least 6 characters in length
                'regex:/[a-z]/',        // must contain at least one lowercase letter
                'regex:/[A-Z]/',        // must contain at least one uppercase letter
                'regex:/[0-9]/',        // must contain at least one digit
                'regex:/[@$!%*#?&]/',   // must contain a special character
            ],
            'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL|email',
            'img' => 'nullable|string',
            'isAdmin' => 'nullable|boolean'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status' => 422,
            'message' => 'User registration error',
            'errorList' => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'name.required' => 'Name needed',
            'name.unique' => 'Name already used',
            'password.required' => 'Password needed',
            'password.min' => 'Password has to be at least 6 characters long',
            'password.regex' => 'Password needs at least: an uppercase letter, a lowercase letter, one digit, a special character',
            'email.required' => 'Email needed',
            'email.email' => 'Email format incorrect',
            'email.unique' => 'Email already used',
            'img.string' => 'Image path has to be a string'
        ];
    }
}
