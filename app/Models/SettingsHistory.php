<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * [v8.0] Settings Audit Log - tracks who changed what and when.
 */
class SettingsHistory extends Model
{
    public $timestamps = false;

    protected $table = 'settings_history';

    protected $fillable = [
        'key',
        'old_value',
        'new_value',
        'changed_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who made the change.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
