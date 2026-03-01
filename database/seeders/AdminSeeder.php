<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder {
    /**
     * Executa o seeder para criar um usuário administrador inicial
     */
    public function run(): void {
        User::create([
            'name' => 'Admin Inicial',
            'email' => 'admin@exemplo.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
