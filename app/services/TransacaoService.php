<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Transacao;
use Illuminate\Support\Facades\DB;

class TransacaoService {
    /**
     * Registra a transação garantindo regras de negócio
     */
    public function registrar(Cliente $cliente, array $dados): Transacao {
        if (!$cliente->ativo) {
            throw new \Exception('Cliente inativo não pode realizar transações.');
        }

        $saldoAtual = $cliente->saldoAtual();

        if ($dados['tipo'] === 'debito' && ($saldoAtual - $dados['valor']) < 0) {
            throw new \Exception('Débito não permitido: saldo insuficiente.');
        }

        $transacao = DB::transaction(function () use ($cliente, $dados) {
            return $cliente->transacoes()->create([
                'tipo' => $dados['tipo'],
                'valor' => $dados['valor'],
                'descricao' => $dados['descricao'] ?? null,
            ]);
        });
        return $transacao;
    }

    /**
     * Busca uma transação por ID
     */
    public function buscarPorId(int $id): ?Transacao {
        return Transacao::with('cliente')->find($id);
    }
}
