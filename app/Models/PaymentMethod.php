<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'name_en',
        'type',
        'icon',
        'instructions_ar',
        'instructions_en',
        'account_number',
        'account_name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Courses that use this payment method
     */
    protected static function boot()
{
    parent::boot();

    static::saving(function ($model) {
        // تحديث name_ar ليكون مطابقاً لـ name تلقائياً
        $model->name_ar = $model->name;
    });
}


    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_payment_methods');
    }

    /**
     * Get localized name based on current locale
     */
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && $this->name_en) {
            return $this->name_en;
        }
        return $this->name;
    }

    /**
     * Get localized instructions based on current locale
     */
    public function getLocalizedInstructionsAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && $this->instructions_en) {
            return $this->instructions_en;
        }
        return $this->instructions_ar;
    }

    /**
     * Scope to only active methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
