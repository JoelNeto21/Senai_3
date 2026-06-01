<?php

namespace Database\Seeders;

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
    }
}
