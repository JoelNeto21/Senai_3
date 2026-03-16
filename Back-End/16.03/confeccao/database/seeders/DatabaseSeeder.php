<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Product::factory(3)->create();
        \App\Models\Client::factory(3)->create();
        \App\Models\Order::factory(3)->create();
        \App\Models\Supplier::factory(3)->create();
        \App\Models\Stock::factory(3)->create();

        // Client::factory()->create([
        //     'nome' => 'Joel', 
        //     'cpf' => '111111111-11', 
        //     'telefone' => '(11) 11111-1111', 
        //     'reserva' => 1
        // ]);
    }
}
