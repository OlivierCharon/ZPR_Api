<?php

namespace App\Http\Requests;

use App\Rules\UserOrEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginUserRequest extends FormRequest
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
            // 'name'=>'required|string|unique:users,name',
            'login'=>[
                'required',
                'string',
                new UserOrEmail
            ],
            'password'=>[
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
                'min:6',                // must be at least 6 characters in length
                'regex:/[a-z]/',        // must contain at least one lowercase letter
                'regex:/[A-Z]/',        // must contain at least one uppercase letter
                'regex:/[0-9]/',        // must contain at least one digit
                'regex:/[@$!%*#?&]/',   // must contain a special character
            ],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'status'=>422,
            'message'=>'Login failed',
            'errorList'=>$validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'login.required'=>'Login needed',
            'password.required'=>'Password needed',
        ];
    }
}
