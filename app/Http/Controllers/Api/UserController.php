<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseHelperTrait;

class UserController extends Controller
{
    use ResponseHelperTrait;

    public function index()
    {
        try {
            $users = User::all();
            return $this->successResponse(UserResource::collection($users), 'Users fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch users', $e->getMessage(), 500);
        }
    }

    public function show(User $user)
    {
        return $this->successResponse(new UserResource($user), 'User fetched successfully');
        // try {
        //     $user = User::find($id);
        //     if (!$user) {
        //         return $this->errorResponse('User not found', null, 404);
        //     }
        //     return $this->successResponse(new UserResource2($user), 'User fetched successfully');
        // } catch (\Exception $e) {
        //     return $this->errorResponse('Failed to fetch user', $e->getMessage(), 500);
        // }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            // Use UserResource for consistent data formatting
            return $this->successResponse(new UserResource($user), 'User created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create user',  $e->getMessage(), 500);
        }
    }
}
