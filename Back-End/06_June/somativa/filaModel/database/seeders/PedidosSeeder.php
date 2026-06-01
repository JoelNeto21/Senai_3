<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\Itens_Pedido;
use App\Models\Produto;
use App\Models\Cliente;

class PedidosSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();

        if ($clientes->count() === 0 || $produtos->count() === 0) {
            return; // prerequisites
        }

        for ($i = 0; $i < 10; $i++) {
            $cliente = $clientes->random();
            $pedido = Pedido::create(['cliente_id' => $cliente->id, 'status' => 'Pendente', 'valor_total' => 0]);

            // add 1-4 items
            $numItems = rand(1,4);
            for ($j = 0; $j < $numItems; $j++) {
                $produto = $produtos->random();
                if ($produto->quantidade <= 0) continue;

                $qty = min(rand(1,5), $produto->quantidade);

                Itens_Pedido::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $qty,
                    'preco_un' => $produto->valor_unitario,
                ]);

                // reduce product stock
                $produto->quantidade = $produto->quantidade - $qty;
                $produto->save();
            }

            // recalc total (Itens_Pedido hook already updates, but ensure)
            $pedido->recalculateTotal();
        }
    }
}
