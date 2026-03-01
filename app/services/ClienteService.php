<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ClienteService {
    /**
     * Retorna todos os clientes.
     */
    public function list(int $perPage = 0): Collection|LengthAwarePaginator {
        $query = Cliente::query()->orderBy('nome');
        if ($perPage > 0) {
            return $query->paginate($perPage);
        }
        return $query->get();
    }

    /**
     * Busca um cliente pelo ID.
     */
    public function find(int $id): ?Cliente {
        return Cliente::find($id);
    }

    /**
     * Cria um novo cliente.
     */
    public function create(array $data): Cliente {
        $data = $this->normalize($data);
        $data['ativo'] = true;
        return Cliente::create($data);
    }

    /**
     * Atualiza um cliente existente.
     */
    public function update(Cliente $cliente, array $data): Cliente {
        $data = $this->normalize($data);
        $cliente->update($data);
        return $cliente->fresh();
    }

    /**
     * Remove um cliente.
     */
    public function delete(Cliente $cliente): bool {
        return $cliente->delete();
    }

    /**
     * Normaliza os dados antes de persistir.
     */
    private function normalize(array $data): array {
        if (isset($data['nome'])) {
            $data['nome'] = ucwords(strtolower(trim($data['nome'])));
        }

        if (isset($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        if (!empty($data['telefone'])) {
            $data['telefone'] = preg_replace('/[^0-9]/', '', $data['telefone']);
        }

        return $data;
    }
}
