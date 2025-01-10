<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            "email" => "required|email",
            "password" => "required",
            "device_name" => "required",
            "remember_me" => "boolean"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw an HTTP exception with a JSON response
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(), // Validation errors
            ], 422)
        );
    }
}
