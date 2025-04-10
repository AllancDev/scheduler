<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\ScheduleDay;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ClassModel $class)
    {
        $schedules = $class->schedules()
            ->with(['subject', 'teacher', 'originalTeacher', 'days'])
            ->orderBy('start_time')
            ->paginate(10);

        return view('classes.schedules', compact('class', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ClassModel $class)
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('classes.schedules.create', compact('class', 'subjects', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room' => 'required|string|max:255',
            'days' => 'required|array|min:1',
            'days.*' => 'required|in:monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_substitute' => 'nullable',
            'original_teacher_id' => 'required_if:is_substitute,1|exists:teachers,id|nullable',
        ]);

        // Preparar os dados para o Schedule
        $scheduleData = [
            'class_id' => $class->id,
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'room' => $validated['room'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_substitution' => !empty($validated['is_substitute']),
        ];

        // Adicionar professor original apenas se for substituição
        if (!empty($validated['is_substitute']) && !empty($validated['original_teacher_id'])) {
            $scheduleData['original_teacher_id'] = $validated['original_teacher_id'];
        }

        // Criar o Schedule
        $schedule = Schedule::create($scheduleData);

        // Criar os ScheduleDays
        foreach ($validated['days'] as $day) {
            ScheduleDay::create([
                'schedule_id' => $schedule->id,
                'day_of_week' => $day,
            ]);
        }

        return redirect()->route('classes.schedules', $class)
            ->with('success', 'Horário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassModel $class, Schedule $schedule)
    {
        return view('classes.schedules.show', compact('class', 'schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassModel $class, Schedule $schedule)
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('classes.schedules.edit', compact('class', 'schedule', 'subjects', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassModel $class, Schedule $schedule)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room' => 'required|string|max:255',
            'days' => 'required|array|min:1',
            'days.*' => 'required|in:monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_substitute' => 'nullable|in:0,1',
            'original_teacher_id' => 'required_if:is_substitute,1|exists:teachers,id',
        ]);

        $validated['is_substitution'] = $validated['is_substitute'] ?? false;
        unset($validated['is_substitute']);
        unset($validated['days']);

        $schedule->update($validated);

        // Remove existing days
        $schedule->days()->delete();

        // Add new days
        foreach ($request->days as $day) {
            ScheduleDay::create([
                'schedule_id' => $schedule->id,
                'day_of_week' => $day,
            ]);
        }

        return redirect()->route('classes.schedules', $class)
            ->with('success', 'Horário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassModel $class, Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('classes.schedules', $class)
            ->with('success', 'Horário removido com sucesso!');
    }
} 