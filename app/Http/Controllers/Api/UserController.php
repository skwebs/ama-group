<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ResponseHelperTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseHelperTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            $users = $this->userService->getAllUsers($request->get('per_page', 15));
            return $this->successResponse(
                UserResource::collection($users),
                'Users fetched successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch users', $e->getMessage(), 500);
        }
    }

    public function show(User $user)
    {
        try {
            return $this->successResponse(
                new UserResource($user),
                'User fetched successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch user', $e->getMessage(), 500);
        }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());
            return $this->successResponse(
                new UserResource($user),
                'User created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create user', $e->getMessage(), 500);
        }
    }
}
