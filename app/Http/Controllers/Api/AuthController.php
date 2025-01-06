<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "mobile" => $request->mobile,
                "password" => $request->password,
            ]);
            if ($user) {
                return ResponseHelper::success(message: "User created successfully", data: new UserResource($user), statusCode: 201);
            } else {
                return ResponseHelper::error(message: "User created successfully",  statusCode: 400);
            }
        } catch (Exception $e) {

            return ResponseHelper::error(message: "User created successfully",  statusCode: 400);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            // return $request->remember_me;   
            if (Auth::attempt($credentials, $request->remember_me)) {
                $user = Auth::user(); // Retrieve authenticated user
                $token = $user->createToken($credentials['email'])->plainTextToken;

                return ResponseHelper::success(status: "success", message: "Login successful", data: ['user' => new UserResource($user), 'token' => $token], statusCode: 200);
            } else {
                // return response()->json(['error' => 'Invalid credentials'], 401);
                return ResponseHelper::error(status: "error", message: "Invalid login details", statusCode: 401);
            }
        } catch (Exception $e) {
            return ResponseHelper::error(status: "error", message: "Unable to login user", statusCode: 500);
        }
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }


    public function user(Request $request)
    {
        return ResponseHelper::success(status: "success", message: "User data retrieved successfully", data: ['user' => new UserResource($request->user())], statusCode: 200);
    }



    public function forgotPassword(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email|exists:users,email']);

        Password::sendResetLink($validated);

        return response()->json(['message' => 'Password reset link sent'], 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->forceFill(['password' => $password])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'], 200)
            : response()->json(['message' => 'Invalid token'], 400);
    }
}
