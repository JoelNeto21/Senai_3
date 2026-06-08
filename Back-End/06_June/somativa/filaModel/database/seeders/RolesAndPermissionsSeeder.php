<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions
        $perms = ['criar', 'editar', 'visualizar', 'excluir'];

        foreach ($perms as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $gerente = Role::firstOrCreate(['name' => 'Gerente']);
        $estoquista = Role::firstOrCreate(['name' => 'Estoquista']);
        $vendedor = Role::firstOrCreate(['name' => 'Vendedor']);
        $cliente = Role::firstOrCreate(['name' => 'Cliente']);

        // Assign permissions
        $admin->givePermissionTo(Permission::all());
        $gerente->givePermissionTo(['criar', 'editar', 'visualizar', 'excluir']);
        $estoquista->givePermissionTo(['criar', 'editar', 'visualizar']);
        $vendedor->givePermissionTo(['criar', 'visualizar']);
        $cliente->givePermissionTo(['criar', 'visualizar']);
    }
}
