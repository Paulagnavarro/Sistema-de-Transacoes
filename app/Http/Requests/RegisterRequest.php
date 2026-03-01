<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {
    /**
     * Autoriza apenas usuários autenticados a realizar o registro.
     */
    public function authorize(): bool {
        return $this->user() !== null;
    }

    /**
     * Regras de validação para criação de usuário.
     */
    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'device_name' => 'required|string',
        ];
    }

    /**
     * Mensagens personalizadas para erros de validação.
     */
    public function messages(): array {
        return [
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'device_name.required' => 'Erro interno de autenticação (device).',
            'device_name.string' => 'O identificador do dispositivo deve ser um texto válido.',
        ];
    }
}
