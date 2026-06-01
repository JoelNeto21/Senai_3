<?php

namespace Database\Factories;

use App\Models\Fornecedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fornecedor>
 */
class FornecedorFactory extends Factory
{
    /**
     * Generate a valid CNPJ (with mask)
     */
    private function validCnpj(): string
    {
        $n1 = random_int(0, 9);
        $n2 = random_int(0, 9);
        $n3 = random_int(0, 9);
        $n4 = random_int(0, 9);
        $n5 = random_int(0, 9);
        $n6 = random_int(0, 9);
        $n7 = random_int(0, 9);
        $n8 = random_int(0, 9);
        $n9 = 0;
        $n10 = 0;
        $n11 = 0;
        $n12 = 1;
        
        // First check digit
        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        $digits = [$n1, $n2, $n3, $n4, $n5, $n6, $n7, $n8, $n9, $n10, $n11, $n12];
        for ($i = 0; $i < 12; $i++) {
            $sum += $digits[$i] * $weights1[$i];
        }
        $d1 = $sum % 11;
        $d1 = $d1 < 2 ? 0 : 11 - $d1;
        
        // Second check digit
        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        $digits[] = $d1;
        for ($i = 0; $i < 13; $i++) {
            $sum += $digits[$i] * $weights2[$i];
        }
        $d2 = $sum % 11;
        $d2 = $d2 < 2 ? 0 : 11 - $d2;
        
        return sprintf(
            '%d%d.%d%d%d.%d%d%d/%d%d%d%d-%d%d',
            $n1, $n2, $n3, $n4, $n5, $n6, $n7, $n8, $n9, $n10, $n11, $n12, $d1, $d2
        );
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->company(),
            'cnpj' => $this->validCnpj(),
            'email' => fake()->unique()->companyEmail(),
            'telefone' => fake()->numerify('(11) 9####-####'),
        ];
    }
}
