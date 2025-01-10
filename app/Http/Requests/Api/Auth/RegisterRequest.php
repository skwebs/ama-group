<?php

namespace App\Http\Requests\Api\Auth;

use App\Traits\ResponseHelperTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;


class RegisterRequest extends FormRequest
{
    use ResponseHelperTrait;
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
            'name'      => 'required|string|min:3|max:150',
            'email'     => 'required|email|unique:users',
            'mobile'    => 'nullable|numeric|digits:10|regex:/^[6-9]/',
            'password'  => ['required', 'max:20', Password::min(8)->max(20)->letters()->mixedCase()->numbers()->symbols()],
            'password_confirmation' => ['required', 'same:password'],

        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            "name.required"     => "Please enter your name",
            "name.min"          => "Name must be at least 3 characters",
            "name.max"          => "Name should not exceed 150 characters",
            "email.required"    => "Please enter your email address",
            "email.email"       => "Please enter a valid email address",
            "email.unique"      => "This email address is already registered",
            "mobile.numeric"    => "Mobile number must be numbers",
            "mobile.digits"     => "Mobile number must be 10 digits",
            "mobile.regex"      => "Mobile numbers must start with 6,7,8 or 9",
            "password.required" => "Please enter a password",
            "password.min"      => "Password must be at least 8 characters",
            "password.max"      => "Password should not exceed 20 characters",
            "password_confirmation.required" => " Please enter a confirm password",
            "password_confirmation.same" => "Password and Confirm Password should be same",
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        // Throw an HTTP exception with a JSON response
        throw new HttpResponseException(
            // response()->json([
            //     'success' => false,
            //     'message' => 'Validation errors occurred.',
            //     'data' => null,
            //     'errors' => $validator->errors()
            // ], 422)
            $this->errorResponse('Validation errors occurred.', $validator->errors(), 422)
        );
    }
}
