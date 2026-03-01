<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource {
    /**
     * Converte em um array para JSON
     */
    public function toArray($request): array {
        return [
            'id'              => $this->id,
            'nome'            => $this->nome,
            'email'           => $this->email,
            'telefone'        => $this->telefone,
            'data_nascimento' => $this->data_nascimento,
            'ativo'           => $this->ativo,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
