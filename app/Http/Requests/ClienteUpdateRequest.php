<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteUpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    /**
     * Regras de validação para atualização de cliente
     */
    public function rules(): array {
        $clienteId = $this->route('cliente')?->id;
        return [
            'nome'            => 'required|string|min:3|max:150',
            'email'           => [
                'required',
                'email:rfc,dns',
                'max:150',
                Rule::unique('clientes')->ignore($clienteId),
            ],
            'telefone'        => 'nullable|string|regex:/^[0-9\s\-\+\(\)]{8,20}$/|max:20',
            'data_nascimento' => 'nullable|date|before_or_equal:today',
            'ativo'           => 'sometimes|boolean',
        ];
    }

    /**
     * Mensagens de erro reutilizam as do ClienteStoreRequest
     */
    public function messages(): array {
        return (new ClienteStoreRequest())->messages();
    }

    /**
     * Prepara os dados antes da validação
     */
    protected function prepareForValidation() {
        $this->merge([
            'nome'  => ucwords(strtolower(trim($this->nome ?? ''))),
            'email' => strtolower(trim($this->email ?? '')),
            'telefone' => !empty($this->telefone) ? preg_replace('/[^0-9]/', '', $this->telefone) : null,
        ]);
    }
}
