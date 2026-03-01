<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTest extends TestCase {
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void {
        parent::setUp();
        // Cria um admin para usar nos testes
        $this->admin = User::factory()->create([
            'email' => 'admin@exemplo.com',
            'password' => bcrypt('12345678'),
        ]);
    }

    /**
     * Testa se o admin logado consegue cadastrar um novo cliente com dados válidos
     */
    public function test_admin_pode_cadastrar_novo_cliente() {
        $this->actingAs($this->admin, 'sanctum');
        $response = $this->postJson('/api/clientes', [
            'nome'            => 'João Silva',
            'email'           => 'joao@email.com',
            'telefone'        => '41999999999',
            'data_nascimento' => '1990-05-15',
            'ativo'           => true,
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('clientes', [
            'email' => 'joao@email.com',
        ]);
    }

    /**
     * Testa que não é possível cadastrar cliente sem informar o nome
     */
    public function test_nao_pode_cadastrar_cliente_sem_nome() {
        $this->actingAs($this->admin, 'sanctum');
        $response = $this->postJson('/api/clientes', [
            'email' => 'sem-nome@email.com',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nome');
    }
}
