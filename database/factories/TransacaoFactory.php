<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransacaoFactory extends Factory {
    /**
     * Define o modelo padrão para criar transações falsas nos testes
     */
    public function definition(): array {
        return [
            'cliente_id' => null,
            'tipo' => $this->faker->randomElement(['credito', 'debito']),
            'valor' => $this->faker->randomFloat(2, 10, 1000),
            'descricao' => $this->faker->sentence(),
        ];
    }
}
