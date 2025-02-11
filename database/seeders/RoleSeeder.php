<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = ['Admin', 'Customer', 'Reseller'];

        foreach ($roles as $role) {
            $roleInstance = Role::firstOrCreate(['name' => $role]);

            if ($role === 'Admin') {
                $roleInstance->syncPermissions(Permission::all());
            } elseif ($role === 'Customer') {
                $roleInstance->syncPermissions(['view']);
            } elseif ($role === 'Reseller') {
                $roleInstance->syncPermissions(['view', 'edit']);
            }
        }

        $this->command->info('Roles and permissions assigned!');
    }
}
