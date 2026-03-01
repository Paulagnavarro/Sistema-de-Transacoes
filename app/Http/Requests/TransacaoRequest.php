<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransacaoRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    /**
     * Regras de validação para criar ou atualizar uma transação
     */
    public function rules(): array {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|in:credito,debito',
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string',
        ];
    }

    /**
     * Mensagens personalizadas de erro para validação
     */
    public function messages(): array {
        return [
            'cliente_id.required' => 'O cliente é obrigatório.',
            'tipo.in' => 'Tipo de transação inválido.',
            'valor.min' => 'O valor deve ser maior que zero.',
        ];
    }
}
