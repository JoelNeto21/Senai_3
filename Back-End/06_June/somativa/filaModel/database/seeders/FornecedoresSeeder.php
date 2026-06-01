<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fornecedor;

class FornecedoresSeeder extends Seeder
{
    public function run(): void
    {
        Fornecedor::factory(10)->create();
    }
}
