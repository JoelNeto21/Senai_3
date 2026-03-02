<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item' => fake()->randomElement(['Tecido Algodão', 'Zíper 20cm', 'Linha Drima', 'Botão Camisa', 'Rolo de Malha']),
            'categoria' => fake()->randomElement(['Tecidos', 'Aviamentos', 'Insumos']),
            'descricao' => fake()->sentence(), 
            'quantidade' => fake()->numberBetween(1, 100),
        ];
    }
}
