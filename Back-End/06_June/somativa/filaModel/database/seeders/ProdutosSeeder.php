<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutosSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nome' => 'Camiseta Básica', 'valor_unitario' => 30.00, 'quantidade' => 100, 'categoria' => 'vestuario', 'descricao' => 'Camiseta básica de algodão'],
            ['nome' => 'Camiseta Premium', 'valor_unitario' => 80.00, 'quantidade' => 50, 'categoria' => 'vestuario', 'descricao' => 'Camiseta premium com acabamento'],
            ['nome' => 'Calça Jeans', 'valor_unitario' => 150.00, 'quantidade' => 40, 'categoria' => 'vestuario', 'descricao' => 'Calça jeans tradicional'],
            ['nome' => 'Jaqueta Jeans', 'valor_unitario' => 200.00, 'quantidade' => 20, 'categoria' => 'vestuario', 'descricao' => 'Jaqueta jeans'],
            ['nome' => 'Moletom', 'valor_unitario' => 120.00, 'quantidade' => 30, 'categoria' => 'vestuario', 'descricao' => 'Moletom confortável'],
            ['nome' => 'Vestido', 'valor_unitario' => 90.00, 'quantidade' => 25, 'categoria' => 'vestuario', 'descricao' => 'Vestido feminino'],
            ['nome' => 'Bermuda', 'valor_unitario' => 60.00, 'quantidade' => 50, 'categoria' => 'vestuario', 'descricao' => 'Bermuda casual'],
            ['nome' => 'Polo', 'valor_unitario' => 70.00, 'quantidade' => 40, 'categoria' => 'vestuario', 'descricao' => 'Camisa polo'],
            ['nome' => 'Regata', 'valor_unitario' => 25.00, 'quantidade' => 80, 'categoria' => 'vestuario', 'descricao' => 'Regata básica'],
            ['nome' => 'Blusa Feminina', 'valor_unitario' => 85.00, 'quantidade' => 35, 'categoria' => 'vestuario', 'descricao' => 'Blusa feminina elegante'],
        ];

        foreach ($items as $item) {
            Produto::updateOrCreate(['nome' => $item['nome']], $item);
        }
    }
}
