<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransacaoRequest;
use App\Models\Cliente;
use App\Services\TransacaoService;
use Illuminate\Http\Request;

class TransacaoController extends Controller {
    protected TransacaoService $service;

    public function __construct(TransacaoService $service) {
        $this->service = $service;
    }

    /**
     * Lista transações de um cliente
     */
    public function index(Request $request) {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
        ]);

        $cliente = Cliente::findOrFail($request->cliente_id);

        $transacoes = $cliente->transacoes()
            ->orderBy('created_at', 'desc')
            ->get();

        $saldoAtual = $cliente->saldoAtual();

        return response()->json([
            'transacoes' => $transacoes,
            'saldo_atual' => $saldoAtual,
            'cliente' => $cliente->only(['id', 'nome', 'email', 'ativo']),
        ]);
    }

    /**
     * Armazena uma nova transação
     */
    public function store(TransacaoRequest $request) {
        $cliente = Cliente::findOrFail($request->cliente_id);
        try {
            $transacao = $this->service->registrar($cliente, $request->validated());
            $novoSaldo = $cliente->saldoAtual();
            return response()->json([
                'message' => 'Transação registrada com sucesso!',
                'transacao' => $transacao,
                'saldo_atual' => $novoSaldo,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Mostra uma transação específica
     */
    public function show($id) {
        $transacao = $this->service->buscarPorId($id);
        if (!$transacao) {
            return response()->json(['message' => 'Transação não encontrada.'], 404);
        }
        return response()->json($transacao);
    }
}
