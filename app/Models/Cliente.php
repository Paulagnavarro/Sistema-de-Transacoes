<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'data_nascimento',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento: um cliente possui várias transações
     */
    public function transacoes() {
        return $this->hasMany(Transacao::class, 'cliente_id');
    }

    /**
     * Calcula o saldo atual do cliente
     * Somatório de créditos menos débitos
     */
    public function saldoAtual(): float {
        return $this->transacoes()
            ->selectRaw("COALESCE(SUM(CASE WHEN tipo = 'credito' THEN valor ELSE -valor END), 0) as saldo")
            ->value('saldo');
    }
}
