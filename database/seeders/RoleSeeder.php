<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => '1',
                'description' => 'Administrador',
            ],
            [
                'id' => '2',
                'description' => 'Cliente',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role + ['created_at' => now(), 'updated_at' => now()]);
        }
    }
}
