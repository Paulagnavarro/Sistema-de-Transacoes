<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {
    /**
     * Permite acesso público ao login.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Regras de validação para autenticação.
     */
    public function rules(): array {
        return [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required|string',
        ];
    }

    /**
     * Mensagens personalizadas de erro.
     */
    public function messages(): array {
        return [
            'email.required' => 'O e-mail é obrigatório.',
            'password.required' => 'A senha é obrigatória.',
            'device_name.required' => 'Erro interno de autenticação.',
        ];
    }
}
