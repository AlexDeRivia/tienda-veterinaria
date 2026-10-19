<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id' => '1',
                'description' => 'En proceso',
            ],
            [
                'id' => '2',
                'description' => 'Completado',
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('statuses')->insertOrIgnore($status + ['created_at' => now(), 'updated_at' => now()]);
        }
    }
}
