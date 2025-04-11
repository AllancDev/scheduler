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
        ], [
            'days.required' => 'Selecione pelo menos um dia da semana.',
            'start_time.required' => 'O horário de início é obrigatório.',
            'end_time.required' => 'O horário de término é obrigatório.',
            'end_time.after' => 'O horário de término deve ser após o horário de início.',
        ]);


        foreach ($validated['days'] as $day) {
            $roomConflict = Schedule::whereHas('days', function ($query) use ($day) {
                    $query->where('day_of_week', $day);
                })
                ->where('class_id', $class->id)
                ->where('room', $validated['room'])
                ->where(function ($query) use ($validated) {
                    $query->where(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>', $validated['start_time']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<', $validated['end_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '>=', $validated['start_time'])
                          ->where('end_time', '<=', $validated['end_time']);
                    });
                })
                ->exists();

            if ($roomConflict) {
                return back()
                    ->withErrors(['days' => "Já existe uma aula agendada neste horário para o dia selecionado."])
                    ->withInput();
            }

            // Verificar conflitos de professor no mesmo dia e horário
            $teacherConflict = Schedule::whereHas('days', function ($query) use ($day) {
                    $query->where('day_of_week', $day);
                })
                ->where(function ($query) use ($validated) {
                    $query->where('teacher_id', $validated['teacher_id'])
                          ->orWhere('original_teacher_id', $validated['teacher_id']);
                })
                ->where(function ($query) use ($validated) {
                    $query->where(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>', $validated['start_time']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<', $validated['end_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '>=', $validated['start_time'])
                          ->where('end_time', '<=', $validated['end_time']);
                    });
                })
                ->exists();

            if ($teacherConflict) {
                return back()
                    ->withErrors(['teacher_id' => "O professor já tem uma aula agendada neste horário para o dia selecionado."])
                    ->withInput();
            }
        }

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
            'original_teacher_id' => 'required_if:is_substitute,1|exists:teachers,id|nullable',
        ], [
            'days.required' => 'Selecione pelo menos um dia da semana.',
            'start_time.required' => 'O horário de início é obrigatório.',
            'end_time.required' => 'O horário de término é obrigatório.',
            'end_time.after' => 'O horário de término deve ser após o horário de início.',
        ]);

        // Verificar conflitos de horário para cada dia selecionado
        foreach ($validated['days'] as $day) {
            // Verificar conflitos de sala no mesmo dia e horário
            $roomConflict = Schedule::whereHas('days', function ($query) use ($day) {
                    $query->where('day_of_week', $day);
                })
                ->where('id', '!=', $schedule->id)
                ->where('class_id', $class->id)
                ->where(function ($query) use ($validated) {
                    $query->where(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>', $validated['start_time']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<', $validated['end_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '>=', $validated['start_time'])
                          ->where('end_time', '<=', $validated['end_time']);
                    });
                })
                ->exists();

            if ($roomConflict) {
                return back()
                    ->withErrors(['days' => "Já existe uma aula agendada neste horário para o dia selecionado."])
                    ->withInput();
            }

            // Verificar conflitos de professor no mesmo dia e horário
            $teacherConflict = Schedule::whereHas('days', function ($query) use ($day) {
                    $query->where('day_of_week', $day);
                })
                ->where('id', '!=', $schedule->id)
                ->where(function ($query) use ($validated) {
                    $query->where('teacher_id', $validated['teacher_id'])
                          ->orWhere('original_teacher_id', $validated['teacher_id']);
                })
                ->where(function ($query) use ($validated) {
                    $query->where(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>', $validated['start_time']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<', $validated['end_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '>=', $validated['start_time'])
                          ->where('end_time', '<=', $validated['end_time']);
                    });
                })
                ->exists();

            if ($teacherConflict) {
                return back()
                    ->withErrors(['teacher_id' => "O professor já tem uma aula agendada neste horário para o dia selecionado."])
                    ->withInput();
            }
        }

        // Preparar os dados para o Schedule
        $scheduleData = [
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
        } else {
            $scheduleData['original_teacher_id'] = null;
        }

        // Atualizar o Schedule
        $schedule->update($scheduleData);

        // Remove existing days
        $schedule->days()->delete();

        // Add new days
        foreach ($validated['days'] as $day) {
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