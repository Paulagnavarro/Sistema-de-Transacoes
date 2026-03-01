<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cliente;
use App\Models\Transacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransacaoTest extends TestCase {
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void {
        parent::setUp();
        // Cria um usuário admin para todos os testes dessa classe
        $this->user = User::factory()->create([
            'email' => 'admin@exemplo.com',
            'password' => bcrypt('12345678'),
        ]);
    }

    /**
     * Testa que não é permitido fazer débito maior que o saldo do cliente
     */
    public function test_nao_permite_debito_maior_que_saldo() {
        // Simula login com o usuário admin (agora todas as requisições são autenticadas)
        $this->actingAs($this->user, 'sanctum');

        $cliente = Cliente::factory()->create();

        // Adiciona crédito de R$ 100
        Transacao::factory()->create([
            'cliente_id' => $cliente->id,
            'tipo' => 'credito',
            'valor' => 100.00,
        ]);

        // Tenta débito inválido (deve dar 422)
        $response = $this->postJson('/api/transacoes', [
            'cliente_id' => $cliente->id,
            'tipo' => 'debito',
            'valor' => 150.00,
            'descricao' => 'Teste débito inválido',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Débito não permitido: saldo insuficiente.'
        ]);
    }

    /**
     * Testa que é permitido fazer débito quando o saldo é suficiente
     */
    public function test_permite_debito_quando_saldo_suficiente() {
        $this->actingAs($this->user, 'sanctum');
        $cliente = Cliente::factory()->create();
        Transacao::factory()->create([
            'cliente_id' => $cliente->id,
            'tipo' => 'credito',
            'valor' => 200.00,
        ]);
        $response = $this->postJson('/api/transacoes', [
            'cliente_id' => $cliente->id,
            'tipo' => 'debito',
            'valor' => 150.00,
        ]);
        $response->assertStatus(201);
        $response->assertJsonPath('message', 'Transação registrada com sucesso!');
    }

    /**
     * Testa se é possível obter o histórico de transações e o saldo atual do cliente
     */
    public function test_retorna_historico_e_saldo_atual() {
        $this->actingAs($this->user, 'sanctum');

        $cliente = Cliente::factory()->create();
        Transacao::factory()->count(3)->create(['cliente_id' => $cliente->id]);

        $response = $this->getJson("/api/transacoes?cliente_id={$cliente->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'transacoes' => [
                '*' => ['id', 'tipo', 'valor', 'descricao', 'created_at']
            ],
            'saldo_atual',
            'cliente'
        ]);
    }

    /**
     * Testa cadastro de crédito para o cliente
     */
    public function test_permite_cadastro_de_credito() {
        $this->actingAs($this->user, 'sanctum');

        $cliente = Cliente::factory()->create();

        $response = $this->postJson('/api/transacoes', [
            'cliente_id' => $cliente->id,
            'tipo' => 'credito',
            'valor' => 300.00,
            'descricao' => 'Depósito teste',
        ]);

        $response->assertStatus(201);

        // Verifica se saldo aumentou
        $this->assertEquals(300.00, $cliente->transacoes()->sum('valor'));
    }

    /**
     * Testa que usuário não logado não pode criar transações
     */
    public function test_nao_logado_nao_pode_criar_transacao() {
        $cliente = Cliente::factory()->create();
        $response = $this->postJson('/api/transacoes', [
            'cliente_id' => $cliente->id,
            'tipo' => 'credito',
            'valor' => 100,
        ]);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
