<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            Status::create($status);
        }
    }
}
