<?php

namespace Database\Factories;

use App\Models\Insumo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Insumo>
 */
class InsumoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->randomElement([
                // Eletrônicos
                'Smartphone 128GB', 'Fone Bluetooth Noise Cancelling', 'Smartwatch Serie 9', 
                // Vestuário
                'Camiseta Oversized Preta', 'Tênis Esportivo Casual', 'Jaqueta Jeans Lavagem Clara', 
                // Alimentos
                'Café Arábica Gourmet', 'Chocolate Meio Amargo 70%', 'Azeite Extra Virgem 500ml'
            ]),
            'unidade_medida' => fake()->randomElement(['Unidade', 'Peça', 'Par', 'Garrafa', 'Barra', 'Pacote']),
            'preco_custo' => fake()->randomFloat(2, 5, 1200), // Range amplo para cobrir de chocolates a celulares
            'estoque' => fake()->randomFloat(0, 5, 1000),    // Inteiros costumam ser melhores para esses itens
        ];
    }
}
