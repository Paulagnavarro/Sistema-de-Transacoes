<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransacaoResource extends JsonResource {
    /**
     * Converte em um array para JSON
     */
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo ?? 'desconhecido',
            'valor' => $this->valor ?? 0,
            'descricao' => $this->descricao ?? '',
            'data' => $this->created_at ? $this->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i'),
            'cliente' => [
                'id' => $this->cliente->id ?? null,
                'nome' => $this->cliente->nome ?? '',
                'email' => $this->cliente->email ?? '',
            ],
        ];
    }
}
