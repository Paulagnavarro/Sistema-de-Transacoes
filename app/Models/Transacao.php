<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model {
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo',
        'valor',
        'descricao',
    ];

    /**
     * Relacionamento: cada transação pertence a um cliente
     */
    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
}
