<?php

namespace Database\Factories;

use App\Models\MovimentacaoEstoque;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MovimentacaoEstoque>
 */
class MovimentacaoEstoqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'produto_id' => Produto::factory(),
            'descricao' => fake()->randomElement([
                'Ajuste manual de estoque',
                'Entrada de mercadoria via nota fiscal',
                'Devolução de cliente',
                'Saída para entrega de pedido',
                'Perda por avaria no estoque'
            ]),
            'quantidade' => fake()->numberBetween(5, 50),
            'movimentacao' => fake()->randomElement(['Entrada', 'Saída']),
        ];
    }
}
