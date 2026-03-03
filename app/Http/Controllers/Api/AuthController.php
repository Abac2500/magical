<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $payload = $request->validated();

        $user = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
        ]);

        $token = $this->resolveUserApiToken($user);

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], Response::HTTP_CREATED);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $payload = $request->validated();

        $user = User::where('email', $payload['email'])
            ->first();

        if (! $user || ! Hash::check($payload['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверный email или пароль.'],
            ]);
        }

        $token = $this->resolveUserApiToken($user);

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('animal.species');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'animal' => $user->animal ? [
                'id' => $user->animal->id,
                'name' => $user->animal->name,
                'species_name' => $user->animal->species?->name,
            ] : null,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();
        $user->forceFill(['api_token' => null])->save();

        return response()->json([
            'message' => 'Вы успешно вышли из системы.',
        ]);
    }

    /**
     * @param User $user
     * @return string
     */
    private function resolveUserApiToken(User $user): string
    {
        $tokenName = 'api-token';
        $storedToken = $user->api_token;
        $tokenExists = $user->tokens()
            ->where('name', $tokenName)
            ->exists();

        if (filled($storedToken) && $tokenExists) {
            return $storedToken;
        }

        $user->tokens()->delete();

        $newToken = $user->createToken($tokenName)->plainTextToken;
        $user->forceFill(['api_token' => $newToken])->save();

        return $newToken;
    }
}
