<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter ENUM safely
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'pending_tutor_approval', 'confirmed', 'rejected_by_tutor', 'failed', 'cancelled', 'refunded') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'refunded') NOT NULL DEFAULT 'pending'");
    }
};
