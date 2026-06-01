<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Mix de Eletrônicos, Vestuário e Alimentos
            'nome' => fake()->randomElement([
                'Monitor Gamer 24"', 'Fone de Ouvido Bluetooth', // Eletrônicos
                'Tênis Esportivo Casual', 'Jaqueta Corta-Vento',   // Vestuário
                'Café em Grãos 1kg', 'Kit de Temperos Gourmet',   // Alimentos
                'Teclado Mecânico RGB', 'Camiseta Algodão Egípcio' // Mix
            ]),
            'categoria' => fake()->randomElement(['Tecnologia', 'Moda Masculina', 'Empório', 'Acessórios', 'Fitness']),
            'descricao' => fake()->randomElement([
                'Produto de alta qualidade com acabamento premium.',
                'Design moderno e resistente, ideal para o dia a dia.',
                'Fabricado com materiais excelentes e de longa durabilidade.',
                'Item indispensável com tecnologia de ponta e praticidade.',
                'Excelente custo-benefício e entrega máxima performance.'
            ]),
            'valor_unitario' => fake()->randomFloat(2, 25, 2500),
            'quantidade' => fake()->numberBetween(0, 100),
        ];
    }
}
