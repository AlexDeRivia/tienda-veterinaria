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
                'ruc' => '20489651236',
                'email' => 'contact@petcenter.com',
                'business_name' => 'Pet Center',
                'country' => 'Peru',
                'city' => 'Lima',
                'address' => 'Lima, Av. Angamos, Calle 123',
            ],
            [
                'ruc' => '10480687236',
                'email' => 'contact@dogavet.com',
                'business_name' => 'Grupo Drogavet',
                'country' => 'Argentina',
                'city' => 'Santa Fe',
                'address' => 'Rosario, Av. Cuccittini, Calle 123',
            ],
            [
                'ruc' => '30428600201',
                'email' => 'contact@purina.com',
                'business_name' => 'Purina',
                'country' => 'Chile',
                'city' => 'Santiago de Chile',
                'address' => 'San Bernardo, Av. Vidal, Calle 123',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
