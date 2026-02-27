<?php

namespace Database\Seeders;

use App\Models\Client;
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
        \App\Models\Client::factory(10)->create();

        // Client::factory()->create([
        //     'nome' => 'Joel', 
        //     'cpf' => '111111111-11', 
        //     'telefone' => '(11) 11111-1111', 
        //     'reserva' => 1
        // ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Joel',
        //     'email' => 'joel@confeccao.com',
        // ]);
    }
}
