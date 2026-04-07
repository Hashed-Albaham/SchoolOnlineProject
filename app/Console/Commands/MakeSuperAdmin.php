<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * [v8.0] Genesis Artisan Command
 * Interactive command to promote an existing admin to Super Admin.
 * Usage: php artisan make:super-admin
 */
class MakeSuperAdmin extends Command
{
    protected $signature = 'make:super-admin';
    protected $description = 'Promote an existing admin user to Super Admin';

    public function handle(): int
    {
        $this->info('🛡️  ProSkill Academy - Super Admin Promotion');
        $this->info('============================================');
        $this->newLine();

        $email = $this->ask('Enter the email of the admin user to promote');

        if (!$email) {
            $this->error('❌ Email is required.');
            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ No user found with email: {$email}");
            return self::FAILURE;
        }

        if ($user->role !== 'admin') {
            $this->error("❌ User '{$user->name}' has role '{$user->role}'. Only admin users can be promoted to Super Admin.");
            return self::FAILURE;
        }

        if ($user->is_super_admin) {
            $this->warn("⚠️  User '{$user->name}' is already a Super Admin.");
            return self::SUCCESS;
        }

        // Confirmation
        $this->info("Found admin: {$user->name} ({$user->email})");
        if (!$this->confirm('Do you want to promote this user to Super Admin?')) {
            $this->info('Operation cancelled.');
            return self::SUCCESS;
        }

        $user->is_super_admin = true;
        $user->save();

        $this->newLine();
        $this->info("✅ '{$user->name}' has been promoted to Super Admin successfully!");
        $this->info("   Email: {$user->email}");

        return self::SUCCESS;
    }
}
