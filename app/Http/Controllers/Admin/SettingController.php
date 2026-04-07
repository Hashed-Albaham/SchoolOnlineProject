<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * [v8.0] Super Admin Settings Controller
 * Manages dynamic system settings with audit logging and cache invalidation.
 * Protected by SuperAdminMiddleware + Throttle.
 */
class SettingController extends Controller
{
    /**
     * Display all settings with audit history.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        $history = SettingsHistory::with('changedBy')
            ->latest('created_at')
            ->take(20)
            ->get();

        return view('admin.settings.index', compact('settings', 'history'));
    }

    /**
     * Update settings with validation, audit logging, and cache invalidation.
     */
    public function update(Request $request)
    {
        $request->validate([
            'min_gpa' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'min_gpa_scale' => ['nullable', 'numeric', 'in:4.0,5.0'],
            'min_step_score' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $settingKeys = ['min_gpa', 'min_gpa_scale', 'min_step_score'];

        foreach ($settingKeys as $key) {
            $newValue = $request->input($key);
            $oldValue = Setting::get($key);

            // Only log and update if value actually changed
            if ((string) $oldValue !== (string) $newValue) {
                // Audit log BEFORE update
                SettingsHistory::create([
                    'key' => $key,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'changed_by' => auth()->id(),
                ]);

                // Update setting and invalidate cache
                Setting::set($key, $newValue);
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', __('site.settings_updated_success'));
    }
}
