<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::factory(10)->create();
    }
}
