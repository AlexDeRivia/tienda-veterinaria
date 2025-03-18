<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'jose@gmail.com',
                'password' => static::$password ??= Hash::make('12345'),
                'dni' => '78765432',
                'names' => 'Jose Manuel',
                'surnames' => 'Vasquez Ramos',
                'address' => 'Nuevo Chimbote, Av. Universitaria, Calle 123',
                'phone' => '976543210',
                'birthdate' => '2000-01-01',
                'role_id' => '1',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'email' => 'alex@gmail.com',
                'password' => static::$password ??= Hash::make('12345'),
                'dni' => '76543210',
                'names' => 'Alex Rodolfo',
                'surnames' => 'Alcantara Zuñiga',
                'address' => 'Chimbote, Av. Drenaje, Calle 123',
                'phone' => '954321098',
                'birthdate' => '2000-01-01',
                'role_id' => '2',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'email' => 'leonardo@gmail.com',
                'password' => static::$password ??= Hash::make('12345'),
                'dni' => '77654321',
                'names' => 'Leonardo David',
                'surnames' => 'Rojas Rojas',
                'address' => 'Chimbote, Av. Primavera, Calle 123',
                'phone' => '965432109',
                'birthdate' => '2000-01-01',
                'role_id' => '2',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'email' => 'jhon@gmail.com',
                'password' => static::$password ??= Hash::make('12345'),
                'dni' => '75432109',
                'names' => 'Jhon Angelo',
                'surnames' => 'Sanchez Garcia',
                'address' => 'Rinconada, Av. Carbonel, Calle 123',
                'phone' => '943210987',
                'birthdate' => '2000-01-01',
                'role_id' => '2',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'email' => 'rodrigo@gmail.com',
                'password' => static::$password ??= Hash::make('12345'),
                'dni' => '74321098',
                'names' => 'Rodrigo Emilio',
                'surnames' => 'Trejo Obregon',
                'address' => 'Nuevo Chimbote, Av. Anchoveta, Calle 123',
                'phone' => '932109876',
                'birthdate' => '2000-01-01',
                'role_id' => '2',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
