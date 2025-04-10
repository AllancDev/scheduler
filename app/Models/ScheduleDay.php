<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'day_of_week',
    ];

    /**
     * Get the schedule that owns the day.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
} 