<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * [v8.0] Add super admin flag + settings tables + settings_history
     */
    public function up(): void
    {
        // 1.1 Add is_super_admin to users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('role');
        });

        // Auto-assign admin@proskill.com as super admin
        DB::table('users')
            ->where('email', 'admin@proskill.com')
            ->where('role', 'admin')
            ->update(['is_super_admin' => true]);

        // 1.3 Create settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 1.3 Create settings_history (audit log)
        Schema::create('settings_history', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings_history');
        Schema::dropIfExists('settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_super_admin');
        });
    }
};
