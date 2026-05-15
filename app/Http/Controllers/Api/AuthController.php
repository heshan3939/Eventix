<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Invalid credentials.', 401);
        }

        $abilities = match($user->role) {
            'admin' => ['*'],
            'organiser' => ['event:read', 'event:write', 'booking:read'],
            'customer' => ['event:read', 'booking:read', 'booking:write'],
            default => []
        };

        $token = $user->createToken($request->device, $abilities, now()->addDays(30));

        return $this->successResponse([
            'token' => $token->plainTextToken,
            'expires_at' => now()->addDays(30),
            'user' => new UserResource($user)
        ], 'Login successful.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully.', 200);
    }
}
