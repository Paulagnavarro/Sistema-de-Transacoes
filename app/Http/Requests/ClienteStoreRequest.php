<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteStoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    /**
     * Regras de validação dos campos do cliente
     */
    public function rules(): array {
        return [
            'nome'            => 'required|string|min:3|max:150',
            'email'           => 'required|email:rfc,dns|unique:clientes,email|max:150',
            'telefone'        => 'nullable|string|regex:/^[0-9\s\-\+\(\)]{8,20}$/|max:20',
            'data_nascimento' => 'nullable|date|before_or_equal:today',
        ];
    }

    /**
     * Mensagens personalizadas de erro
     */
    public function messages(): array {
        return [
            'nome.required' => 'O nome completo é obrigatório.',
            'nome.min'      => 'O nome deve ter pelo menos 3 caracteres.',
            'nome.max'      => 'O nome não pode ter mais de 150 caracteres.',
            'email.required'=> 'O e-mail é obrigatório.',
            'email.email'   => 'Digite um endereço de e-mail válido.',
            'email.unique'  => 'Este e-mail já está cadastrado no sistema.',
            'telefone.regex'=> 'O telefone contém caracteres inválidos.',
            'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser futura.',
        ];
    }

    /**
     * Prepara os dados antes da validação
     */
    protected function prepareForValidation() {
        $this->merge([
            'nome'  => ucwords(strtolower(trim($this->nome ?? ''))),
            'email' => strtolower(trim($this->email ?? '')),
            'telefone' => !empty($this->telefone) ? preg_replace('/[^0-9]/', '', $this->telefone) : null,
            'ativo' => true,
        ]);
    }
}
