<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * [V3]  Add 'booking' to transactions.type enum
 * [V10] Add soft_deletes to transactions table
 */
return new class extends Migration {
    public function up(): void
    {
        // [V3] Expand enum to include 'booking'
        DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `type` ENUM('enrollment','payout','refund','adjustment','booking') NOT NULL");

        // [V10] Add soft deletes
        if (!Schema::hasColumn('transactions', 'deleted_at')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        // Revert enum
        DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `type` ENUM('enrollment','payout','refund','adjustment') NOT NULL");

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
