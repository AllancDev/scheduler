<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'original_teacher_id',
        'room',
        'start_time',
        'end_time',
        'is_substitution',
    ];

    protected $casts = [
        'is_substitution' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the class that owns the schedule.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get the subject that owns the schedule.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher that owns the schedule.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the original teacher that owns the schedule.
     */
    public function originalTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'original_teacher_id');
    }

    /**
     * Get the days for the schedule.
     */
    public function days(): HasMany
    {
        return $this->hasMany(ScheduleDay::class);
    }
} 