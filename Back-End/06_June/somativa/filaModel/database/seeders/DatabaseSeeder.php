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
        // Call seeders in order, ensuring coherent data
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            ClientesSeeder::class,
            FornecedoresSeeder::class,
            ProdutosSeeder::class,
            InsumosSeeder::class,
            PedidosSeeder::class,
            MovimentacoesSeeder::class,
        ]);
    }
}