<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteStoreRequest;
use App\Http\Requests\ClienteUpdateRequest;
use App\Http\Resources\ClienteResource;
use App\Services\ClienteService;
use Illuminate\Http\JsonResponse;
use App\Models\Cliente;

class ClienteController extends Controller {
    private ClienteService $service;

    public function __construct(ClienteService $service) {
        $this->service = $service;
    }

    /**
     * Lista todos os clientes
     */
    public function index(): JsonResponse {
        $clientes = $this->service->list();
        return response()->json(ClienteResource::collection($clientes));
    }

    /**
     * Cria novo cliente
     */
    public function store(ClienteStoreRequest $request): JsonResponse {
        $cliente = $this->service->create($request->validated());
        return response()->json([
            'message' => 'Cliente cadastrado com sucesso!',
            'cliente' => new ClienteResource($cliente)
        ], 201);
    }

    /**
     * Mostra um cliente específico
     */
    public function show(Cliente $cliente): JsonResponse {
        return response()->json(new ClienteResource($cliente));
    }

    /**
     * Atualiza cliente
     */
    public function update(ClienteUpdateRequest $request, Cliente $cliente): JsonResponse {
        $clienteAtualizado = $this->service->update($cliente, $request->validated());
        return response()->json([
            'message' => 'Cliente atualizado com sucesso!',
            'cliente' => new ClienteResource($clienteAtualizado)
        ]);
    }
}
