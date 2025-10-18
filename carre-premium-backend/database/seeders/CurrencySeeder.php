<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert([
            [
                'code' => 'XOF',
                'name' => 'Franc CFA',
                'symbol' => 'CFA',
                'exchange_rate' => 1.000000,
                'is_active' => true,
                'is_default' => true,
                'updated_at' => now(),
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 655.957,
                'is_active' => true,
                'is_default' => false,
                'updated_at' => now(),
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 600.000,
                'is_active' => true,
                'is_default' => false,
                'updated_at' => now(),
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => 760.000,
                'is_active' => true,
                'is_default' => false,
                'updated_at' => now(),
            ],
        ]);
    }
}
