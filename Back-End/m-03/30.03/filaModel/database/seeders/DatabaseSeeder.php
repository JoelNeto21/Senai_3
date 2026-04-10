<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\Itens_Pedido;
use App\Models\MovimentacaoEstoque;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criamos os cadastros que não dependem de ninguém
        $clientes = Cliente::factory(2)->create();
        Fornecedor::factory(2)->create();
        Insumo::factory(2)->create();
        $produtos = Produto::factory(2)->create();

        // 2. Criamos 2 Pedidos. 
        // O método "recycle" faz com que a factory use os 2 clientes que já criamos acima,
        // em vez de criar clientes novos.
        $pedidos = Pedido::factory(2)->recycle($clientes)->create();

        // 3. Para cada pedido gerado, vamos adicionar 2 itens usando os produtos existentes
        foreach ($pedidos as $pedido) {
            Itens_Pedido::factory(2)->create([
                'pedido_id' => $pedido->id,
                'produto_id' => $produtos->random()->id, // Pega um produto aleatório dos 2 criados
            ]);
        }

        // 4. Criamos 2 movimentações de estoque, também usando os produtos existentes
        MovimentacaoEstoque::factory(2)->recycle($produtos)->create();
    }
}