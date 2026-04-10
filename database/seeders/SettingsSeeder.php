<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * [v8.0] Seed default settings.
 * Values are empty by default - super admin sets them from Settings panel.
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'min_gpa' => null,
            'min_gpa_scale' => '4.0',
            'min_step_score' => null,
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->command->info('⚙️ Default settings seeded (values empty - set from admin panel)');
    }
}
