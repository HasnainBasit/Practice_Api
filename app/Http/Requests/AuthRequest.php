<?php

namespace App\Http\Requests;

use Tymon\JWTAuth\Validators\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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

    //Rules for registration
     public function rulesForRegistration(): array
    {
        return [
            'name'=>'required',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|min:6',
            'phone_number'=>'required',
            'status'=>'required|string'
        ];
    }

    //Rules for login
    public function rulesForLogin(): array
    {
        return [
            'email'=>'required|string|email',
            'password'=>'required|string|min:6'
        ];
    }

    //Rules for profile
    public function rulesForProfile(): array
    {
        return [
            response()->json(auth()->user()),
        ];
    }

    //Rules for logging out
    
    public function rulesForLogout(): array
    {
        return [
            response()->json(),
        ];
    }
}
