<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    private $dayMapping = [
        'monday' => 1,
        'tuesday' => 2,
        'wednesday' => 3,
        'thursday' => 4,
        'friday' => 5
    ];

    private function generateTeacherColors($teachers)
    {
        $colors = [
            'blue' => ['bg-blue-100', 'text-blue-800', 'border-blue-200'],
            'green' => ['bg-green-100', 'text-green-800', 'border-green-200'],
            'yellow' => ['bg-yellow-100', 'text-yellow-800', 'border-yellow-200'],
            'red' => ['bg-red-100', 'text-red-800', 'border-red-200'],
            'purple' => ['bg-purple-100', 'text-purple-800', 'border-purple-200'],
            'pink' => ['bg-pink-100', 'text-pink-800', 'border-pink-200'],
            'indigo' => ['bg-indigo-100', 'text-indigo-800', 'border-indigo-200'],
            'teal' => ['bg-teal-100', 'text-teal-800', 'border-teal-200'],
        ];

        $teacherColors = [];
        $colorKeys = array_keys($colors);
        foreach ($teachers as $index => $teacher) {
            $colorKey = $colorKeys[$index % count($colorKeys)];
            $teacherColors[$teacher->id] = $colors[$colorKey];
        }

        return $teacherColors;
    }

    public function index(Request $request)
    {
        // Define os horários padrão
        $timeSlots = [
            ['start' => '08:00', 'end' => '08:50'],
            ['start' => '08:50', 'end' => '09:40'],
            ['start' => '09:40', 'end' => '10:30'],
            ['start' => '10:50', 'end' => '11:40'],
            ['start' => '11:40', 'end' => '12:30'],
            ['start' => '13:30', 'end' => '14:20'],
            ['start' => '14:20', 'end' => '15:10'],
            ['start' => '15:10', 'end' => '16:00'],
            ['start' => '16:20', 'end' => '17:10'],
            ['start' => '17:10', 'end' => '18:00'],
            ['start' => '18:30', 'end' => '19:20'],
            ['start' => '19:20', 'end' => '20:10'],
            ['start' => '20:20', 'end' => '21:10'],
            ['start' => '21:10', 'end' => '22:00'],
            ['start' => '22:30', 'end' => '22:50'],
        ];

        // Carrega os dados para os filtros
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $classes = ClassModel::orderBy('name')->get();

        // Busca os horários com filtros
        $query = Schedule::with(['subject', 'teacher', 'class', 'days']);

        // Aplica os filtros apenas se algum valor foi selecionado
        if ($request->filled('class_id') && $request->class_id !== '') {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('teacher_id') && $request->teacher_id !== '') {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('subject_id') && $request->subject_id !== '') {
            $query->where('subject_id', $request->subject_id);
        }

        // Pega todos os horários
        $allSchedules = $query->get();

        // Log detalhado dos horários
        Log::info('Detalhes dos horários:', [
            'total' => $allSchedules->count(),
            'horarios' => $allSchedules->map(function($schedule) {
                return [
                    'id' => $schedule->id,
                    'start_time' => $schedule->start_time->format('H:i'),
                    'days' => $schedule->days->pluck('day_of_week'),
                    'teacher' => $schedule->teacher->name,
                    'subject' => $schedule->subject->name,
                    'room' => $schedule->room
                ];
            })
        ]);

        // Inicializa array de horários
        $schedules = [];

        // Organiza os horários por dia e hora
        foreach ($allSchedules as $schedule) {
            $startTime = $schedule->start_time->format('H:i');
            $endTime = $schedule->end_time->format('H:i');
            
            foreach ($schedule->days as $day) {
                $dayNumber = $this->dayMapping[strtolower($day->day_of_week)] ?? null;
                
                if ($dayNumber) {
                    if (!isset($schedules[$dayNumber])) {
                        $schedules[$dayNumber] = [];
                    }
                    
                    // Preenche todos os slots entre o início e fim da aula
                    foreach ($timeSlots as $slot) {
                        $slotStart = $slot['start'];
                        if ($slotStart >= $startTime && $slotStart <= $endTime) {
                            $schedules[$dayNumber][$slotStart] = [
                                'subject' => $schedule->subject->name,
                                'teacher' => $schedule->teacher->name,
                                'teacher_id' => $schedule->teacher->id,
                                'teacher_color' => $schedule->teacher->color,
                                'room' => $schedule->room,
                            ];
                        }
                    }
                }
            }
        }

        // Log da estrutura final dos horários
        Log::info('Estrutura final dos horários:', [
            'dias_disponiveis' => array_keys($schedules),
            'exemplo_horarios' => array_map(function($daySchedules) {
                return array_keys($daySchedules);
            }, $schedules)
        ]);

        return view('calendar', compact('timeSlots', 'schedules', 'teachers', 'subjects', 'classes'));
    }
} 