<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        // Define os horários padrão (pode ser configurável posteriormente)
        $timeSlots = [
            ['start' => '07:30', 'end' => '08:20'],
            ['start' => '08:20', 'end' => '09:10'],
            ['start' => '09:10', 'end' => '10:00'],
            ['start' => '10:20', 'end' => '11:10'],
            ['start' => '11:10', 'end' => '12:00'],
            ['start' => '13:30', 'end' => '14:20'],
            ['start' => '14:20', 'end' => '15:10'],
            ['start' => '15:10', 'end' => '16:00'],
            ['start' => '16:20', 'end' => '17:10'],
            ['start' => '17:10', 'end' => '18:00'],
        ];

        // Busca todos os horários
        $schedules = Schedule::with(['subject', 'teacher', 'class'])
            ->get()
            ->groupBy('day_of_week')
            ->map(function ($daySchedules) {
                return $daySchedules->groupBy('start_time')
                    ->map(function ($timeSchedules) {
                        $schedule = $timeSchedules->first();
                        return [
                            'subject' => $schedule->subject->name,
                            'teacher' => $schedule->teacher->name,
                            'room' => $schedule->class->room,
                        ];
                    });
            });

        return view('calendar', compact('timeSlots', 'schedules'));
    }
} 