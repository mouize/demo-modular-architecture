<?php

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Events\UserRegistered;
use Modules\Authentication\Events\UserVerified;
use Modules\Authentication\Http\Requests\LoginRequest;
use Modules\Authentication\Http\Requests\RegisterRequest;
use Modules\Authentication\Interfaces\UserRepositoryInterface;

class AuthenticationController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->register([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        Event::dispatch(new UserRegistered($user));

        return response()->json([
            'message' => 'Account created successfully. Please check your email to verify your account.',
        ], 201);
    }

    public function verify(Request $request): JsonResponse
    {
        $user = $this->userRepository->findOrFail((int) $request->route('id'));

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmail()))) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        Event::dispatch(new UserVerified($user));

        return response()->json(['message' => 'Email verified successfully']);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userRepository->getUserByEmail($request->validated('email'));

        if (! $user || ! Hash::check($request->validated('password'), $user->getPassword())) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token');

        return response()->json([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
