<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->company(), 
            'cnpj' => fake()->numerify('##.###.###/0001-##'), 
            'categoria' => fake()->randomElement(['Tecidos', 'Linhas e Fios', 'Estamparia', 'Tingimento', 'Produtos Químicos']), 
            'telefone' => fake()->phoneNumber(),
        ];
    }
}
