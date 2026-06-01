<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MovimentacaoEstoque;
use App\Models\Produto;

class MovimentacoesSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = Produto::all();
        if ($produtos->count() === 0) return;

        $count = 0;
        $tries = 0;
        while ($count < 20 && $tries < 200) {
            $tries++;
            $produto = $produtos->random();
            $tipo = rand(0,1) ? 'Entrada' : 'Saída';
            $quantidade = rand(1, 30);

            // ensure coherence: only create Saída if stock available
            if ($tipo === 'Saída' && $produto->quantidade < $quantidade) {
                // make it an entrada instead
                $tipo = 'Entrada';
            }

            MovimentacaoEstoque::create([
                'produto_id' => $produto->id,
                'descricao' => $tipo === 'Entrada' ? 'Ajuste/Reposição' : 'Saída por venda',
                'quantidade' => $quantidade,
                'movimentacao' => $tipo,
            ]);

            $produto->refresh();
            $count++;
        }
    }
}
