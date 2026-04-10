<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insertOrIgnore([
            ['key' => 'platform_commission_rate', 'value' => '20', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_payout_amount', 'value' => '50', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency_symbol', 'value' => 'SAR', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
