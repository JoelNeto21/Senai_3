<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user Joel
        $admin = User::firstOrCreate(
            ['email' => 'joel@email.com'],
            [
                'name' => 'Joel',
                'password' => Hash::make('111'),
            ]
        );

        $admin->assignRole('Admin');

        // Default customer user
        $clienteUser = User::query()
            ->where('email', 'user@email.com')
            ->first()
            ?? User::query()->where('email', 'user@email')->first()
            ?? new User();

        $clienteUser->fill([
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => Hash::make('222'),
        ]);
        $clienteUser->save();

        $clienteUser->assignRole('Cliente');

        $cliente = Cliente::query()
            ->where('email', 'user@email.com')
            ->first()
            ?? Cliente::query()->where('email', 'user@email')->first()
            ?? new Cliente();

        $cliente->fill([
            'nome' => 'User',
            'email' => 'user@email.com',
            'cpf' => '00000000001',
            'telefone' => '19999999999',
        ]);
        $cliente->save();
    }
}
