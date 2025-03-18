<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['description' => 'Visa'],
            ['description' => 'MasterCard'],
            ['description' => 'PayPal'],
            ['description' => 'Yape'],
        ];

        foreach ($suppliers as $supplier) {
            PaymentMethod::create($supplier);
        }
    }
}
