<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            return;
        }

        $password = Hash::make('DemoPass123!');
        $users = [
            ['email' => 'admin@example.test', 'dni' => '10000001', 'names' => 'Admin', 'surnames' => 'Demo', 'role_id' => 1],
            ['email' => 'cliente1@example.test', 'dni' => '10000002', 'names' => 'Cliente', 'surnames' => 'Uno', 'role_id' => 2],
            ['email' => 'cliente2@example.test', 'dni' => '10000003', 'names' => 'Cliente', 'surnames' => 'Dos', 'role_id' => 2],
            ['email' => 'cliente3@example.test', 'dni' => '10000004', 'names' => 'Cliente', 'surnames' => 'Tres', 'role_id' => 2],
            ['email' => 'cliente4@example.test', 'dni' => '10000005', 'names' => 'Cliente', 'surnames' => 'Cuatro', 'role_id' => 2],
        ];

        foreach ($users as $user) {
            if (User::where('email', $user['email'])->exists()) {
                continue;
            }
            User::forceCreate($user + [
                'password' => $password,
                'address' => 'Dirección de demostración',
                'phone' => '900000000',
                'birthdate' => '2000-01-01',
                'email_verified_at' => now(),
            ]);
        }
    }
}
