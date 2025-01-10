<?php

namespace App\Http\Controllers\Api;

// use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseHelperTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;



class AuthController extends Controller
{
    use ResponseHelperTrait;

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

            $data = [
                'user' => new UserResource($user),
            ];

            // return $this->successResponse(new UserResource($user), "User created successfully");
            if ($user) {
                return $this->successResponse($data, "User created successfully");
            } else {
                return $this->errorResponse("Something went wroing.", [], 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create user',  $e->getMessage(), 500);
        }
    }





    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            // Find user by email
            $user = User::where('email', $request->email)->first();

            // Validate user and credentials
            if (!$user || !Auth::attempt($credentials, $request->remember_me)) {
                // return $this->successResponse(new UserResource($user), "User created successfully");
                return $this->errorResponse("Invalid login details", [], 401);
            }

            // Generate token and attach it to the user object
            $token = $user->createToken($request->device_name)->plainTextToken;

            $data = [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ];

            return $this->successResponse($data, "Logged in successfully!");
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }



    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return $this->successResponse(null,  "Logged out successfully!");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }


    public function user(Request $request)
    {
        $data = [
            'user' => new UserResource($request->user()),
        ];
        return $this->successResponse($data, "User data retrieved successfully");
    }



    public function forgotPassword(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email|exists:users,email']);

        Password::sendResetLink($validated);
        return $this->successResponse(null, 'Password reset link sent successfully');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            function ($user, $password) {
                $user->forceFill(['password' => $password])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ?  $this->successResponse(null, 'Password reset link sent successfully')
            : $this->errorResponse('Invalid token', [], 400);
        // ? response()->json(['message' => 'Password reset successfully'], 200)
        // : response()->json(['message' => 'Invalid token'], 400);
    }
}
