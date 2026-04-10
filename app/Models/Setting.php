<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * [v8.0] Dynamic Settings model with cache optimization.
 * Uses Cache::remember with 1-hour TTL as a backend defense line.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value with cache optimization.
     * Reads from cache first, falls back to DB, with configurable default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value and invalidate cache immediately.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Mandatory cache invalidation
        Cache::forget("setting_{$key}");
    }
}
