<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService {
    /**
     * Realiza login do usuário
     */
    public function login(array $data): array {
        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ])) {
            throw ValidationException::withMessages([
                'email' => ['Email ou senha inválidos.'],
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken($data['device_name'])->plainTextToken;
        return compact('user', 'token');
    }

    /**
     * Registra um novo usuário
     */
    public function register(array $data): array {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $token = $user->createToken($data['device_name'])->plainTextToken;
        return compact('user', 'token');
    }

    /**
     * Faz logout do usuário atual
     */
    public function logout($request): void {
        $request->user()->currentAccessToken()->delete();
    }
}
