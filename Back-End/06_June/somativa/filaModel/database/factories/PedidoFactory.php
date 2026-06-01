<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(), 
            'status' => fake()->randomElement(['Pendente', 'Em Produção', 'Entregue']),
            'valor_total' => 0,
        ];
    }
}
