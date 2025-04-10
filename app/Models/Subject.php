<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function schedules()
    {
        return $this->hasMany(SubjectSchedule::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_schedules')
            ->withPivot(['room', 'day_of_week', 'start_time', 'end_time', 'is_substitute', 'original_teacher_id'])
            ->withTimestamps();
    }
} 