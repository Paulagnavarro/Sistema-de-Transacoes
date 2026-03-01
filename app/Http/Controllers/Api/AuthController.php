<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;

class AuthController extends Controller {
    /**
     * Injeta o serviço responsável pelas regras de autenticação.
     */
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Realiza login do usuário e retorna token de acesso.
     */
    public function login(LoginRequest $request) {
        $data = $this->authService->login($request->validated());
        return response()->json([
            'user' => new UserResource($data['user']),
            'token' => $data['token'],
        ]);
    }

    /**
     * Registra novo usuário e retorna token automaticamente.
     */
    public function register(RegisterRequest $request) {
        return response()->json(
            $this->authService->register($request->validated()),
            201
        );
    }

    /**
     * Realiza o logout do usuário autenticado.
     */
    public function logout(Request $request) {
        $this->authService->logout($request);
        return response()->json([
            'message' => 'Logout realizado com sucesso!',
        ]);
    }
}
