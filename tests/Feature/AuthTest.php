<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase {
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void {
        parent::setUp();
        // Cria um admin fixo para autenticar nos testes
        $this->admin = User::factory()->create([
            'email' => 'admin@exemplo.com',
            'password' => bcrypt('12345678'),
        ]);
    }

    /**
     * Testa se um admin logado consegue criar um novo usuário
     */
    public function test_admin_logado_pode_criar_novo_usuario() {
        // Simula login do admin
        $this->actingAs($this->admin, 'sanctum');
        $response = $this->postJson('/api/register', [
            'name' => 'Novo Admin',
            'email' => 'novo@admin.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'token'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'novo@admin.com',
        ]);
    }

    /**
     * Testa que não é possível registrar um usuário com email duplicado
     */
    public function test_registro_falha_com_email_duplicado_quando_logado() {
        $this->actingAs($this->admin, 'sanctum');

        // Cria um usuário duplicado antes
        User::factory()->create(['email' => 'duplicado@email.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Teste',
            'email' => 'duplicado@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'device_name' => 'test',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * Testa que o registro falha se não houver confirmação de senha
     */
    public function test_registro_falha_sem_confirmacao_de_senha_quando_logado() {
        $this->actingAs($this->admin, 'sanctum');
        $response = $this->postJson('/api/register', [
            'name' => 'Teste',
            'email' => 'teste@email.com',
            'password' => '12345678',
            'device_name' => 'test',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    /**
     * Testa que um usuário não logado não pode cadastrar usuário
     */
    public function test_nao_logado_nao_pode_cadastrar_usuario() {
        $response = $this->postJson('/api/register', [
            'name' => 'Tentativa',
            'email' => 'tentativa@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'device_name' => 'test',
        ]);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * Testa login com sucesso
     */
    public function test_admin_pode_fazer_login_com_sucesso() {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@exemplo.com',
            'password' => '12345678',
            'device_name' => 'test-device',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'token']);
    }

    /**
     * Testa login falha com senha incorreta
     */
    public function test_login_falha_com_senha_incorreta() {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@exemplo.com',
            'password' => 'senha-errada',
            'device_name' => 'test',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * Testa login falha com email inexistente
     */
    public function test_login_falha_com_email_inexistente() {
        $response = $this->postJson('/api/login', [
            'email' => 'naoexiste@email.com',
            'password' => '12345678',
            'device_name' => 'test',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /**
     *Testa logout do usuário logado
     */
    public function test_usuario_logado_pode_fazer_logout() {
        $user = User::factory()->create();
        $token = $user->createToken('test-device')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logout realizado com sucesso!']);

        // Verifica se token foi deletado
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-device',
        ]);
    }
}
