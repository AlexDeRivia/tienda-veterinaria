<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'ruc' => '00000000001',
                'email' => 'proveedor1@example.test',
                'business_name' => 'Proveedor Demo Uno',
                'country' => 'Peru',
                'city' => 'Lima',
                'address' => 'Dirección de demostración',
            ],
            [
                'ruc' => '00000000002',
                'email' => 'proveedor2@example.test',
                'business_name' => 'Proveedor Demo Dos',
                'country' => 'Peru',
                'city' => 'Lima',
                'address' => 'Dirección de demostración',
            ],
            [
                'ruc' => '00000000003',
                'email' => 'proveedor3@example.test',
                'business_name' => 'Proveedor Demo Tres',
                'country' => 'Peru',
                'city' => 'Lima',
                'address' => 'Dirección de demostración',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['ruc' => $supplier['ruc']], $supplier);
        }
    }
}
