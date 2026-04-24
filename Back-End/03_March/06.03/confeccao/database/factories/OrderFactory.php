<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'data_pedido' => fake()->date(),
            'data_entrega' => fake()->dateTimeBetween('now', '+1 month'),
            'quantidade' => fake()->numberBetween(1, 50),
            'descricao' => fake()->text(200),
            'valor' => fake()->randomFloat(2, 20, 700),
        ];
    }
}
