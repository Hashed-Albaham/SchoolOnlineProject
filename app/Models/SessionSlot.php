<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionSlot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tutor_id',
        'course_id',
        'type',
        'price',
        'max_participants',
        'start_time',
        'end_time',
        'meeting_link',
        // [BUG-02 FIX] 'status' removed from $fillable — Mass Assignment protection
        // Set explicitly: $slot->status = 'scheduled'; $slot->save();
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
        'max_participants' => 'integer',
    ];

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
