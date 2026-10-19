<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            DB::table('payment_methods')->updateOrInsert(
                ['description' => $supplier['description']],
                ['updated_at' => now()]
            );
        }
    }
}
