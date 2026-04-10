<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->randomElement(['Tecido Algodão', 'Zíper 20cm', 'Linha Drima', 'Botão Camisa', 'Rolo de Malha']),
            'quantidade' => fake()->numberBetween(1, 1000),
            'descricao' => fake()->text(200),
            'valor' => fake()->randomFloat(2, 20, 700),
        ];
    }
}
