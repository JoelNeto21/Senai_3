<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insumo;

class InsumosSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Linha Branca','Linha Preta','Linha Azul','Tecido Algodão','Tecido Jeans','Lã','Botão','Zíper','Etiqueta','Elástico',
            'Linha Verde','Linha Vermelha','Tecido Malha','Forro','Tag Impressa'
        ];

        foreach ($items as $name) {
            Insumo::updateOrCreate(['nome' => $name], [
                'nome' => $name,
                'unidade_medida' => in_array($name, ['Tecido Algodão','Tecido Jeans','Tecido Malha','Forro']) ? 'metro' : 'unidade',
                'preco_custo' => rand(100, 2000) / 100.0,
                'estoque' => rand(10, 500) / 1,
            ]);
        }

        // ensure at least 15
        $count = Insumo::count();
        if ($count < 15) {
            Insumo::factory(15 - $count)->create();
        }
    }
}
