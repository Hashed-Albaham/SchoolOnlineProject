<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // أضف قيمة افتراضية لعمولة المنصة إذا لم تكن موجودة
        DB::table('settings')->insertOrIgnore([
            ['key' => 'platform_commission_rate', 'value' => '20', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_payout_amount', 'value' => '50', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency_symbol', 'value' => 'SAR', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'platform_commission_rate',
            'min_payout_amount',
            'currency_symbol',
        ])->delete();
    }
};