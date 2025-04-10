<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'room',
        'day_of_week',
        'start_time',
        'end_time',
        'is_substitute',
        'original_teacher_id'
    ];

    protected $casts = [
        'is_substitute' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function originalTeacher()
    {
        return $this->belongsTo(Teacher::class, 'original_teacher_id');
    }

    public static function validateSchedule($data, $excludeId = null)
    {
        $query = self::where('day_of_week', $data['day_of_week'])
            ->where(function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('start_time', '<=', $data['start_time'])
                        ->where('end_time', '>', $data['start_time']);
                })->orWhere(function ($q) use ($data) {
                    $q->where('start_time', '<', $data['end_time'])
                        ->where('end_time', '>=', $data['end_time']);
                })->orWhere(function ($q) use ($data) {
                    $q->where('start_time', '>=', $data['start_time'])
                        ->where('end_time', '<=', $data['end_time']);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Check room conflicts
        $roomConflict = $query->where('room', $data['room'])->exists();
        if ($roomConflict) {
            return [
                'success' => false,
                'message' => 'Esta sala já está ocupada neste horário.'
            ];
        }

        // Check teacher conflicts (only if not a substitute)
        if (!$data['is_substitute']) {
            $teacherConflict = $query->where('teacher_id', $data['teacher_id'])->exists();
            if ($teacherConflict) {
                return [
                    'success' => false,
                    'message' => 'Este professor já está ocupado neste horário.'
                ];
            }
        }

        // Check if the teacher is already a substitute in this time slot
        $substituteConflict = $query->where('teacher_id', $data['teacher_id'])
            ->where('is_substitute', true)
            ->exists();
        if ($substituteConflict) {
            return [
                'success' => false,
                'message' => 'Este professor já está como substituto neste horário.'
            ];
        }

        // Check if the original teacher is available
        if ($data['is_substitute'] && isset($data['original_teacher_id'])) {
            $originalTeacherConflict = $query->where('teacher_id', $data['original_teacher_id'])->exists();
            if ($originalTeacherConflict) {
                return [
                    'success' => false,
                    'message' => 'O professor original não está disponível neste horário.'
                ];
            }
        }

        return ['success' => true];
    }
} 