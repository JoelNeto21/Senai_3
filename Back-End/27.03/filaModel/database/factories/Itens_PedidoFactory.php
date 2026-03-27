<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class Itens_PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pedido_id' => Pedido::factory(),
            'produto_id' => Produto::factory(),
            'quantidade' => fake()->numberBetween(1, 5),
            'preco_un' => fake()->randomFloat(2, 50, 1000),
        ];
    }
}
