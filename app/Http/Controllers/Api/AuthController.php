<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * Summary of __construct
     * @param \App\Services\AuthService $service
     */
    public function __construct(protected AuthService $service) {}

    /**
     * Summary of register
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(
            [
                'message' => 'User created successfully',
                'success' => true,
                'user' => UserResource::make($user),
                'token' => $token
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Summary of login
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = $this->service->login($request->email, $request->password);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(
            [
                'success' => true,
                'user' =>UserResource::make($user),
                'token' => $token
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Summary of logout
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'User is logged out successfully'
        ], Response::HTTP_OK);
    }
}
