<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectScheduleController extends Controller
{
    public function index(Subject $subject)
    {
        $schedules = $subject->schedules()->with(['teacher', 'originalTeacher'])->get();
        $teachers = Teacher::all();
        return view('subjects.schedules.index', compact('subject', 'schedules', 'teachers'));
    }

    public function store(Request $request, Subject $subject)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'room' => 'required|string|max:50',
            'day_of_week' => 'required|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_substitute' => 'boolean',
            'original_teacher_id' => [
                'nullable',
                'exists:teachers,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->is_substitute && empty($value)) {
                        $fail('O professor original é obrigatório quando é um substituto.');
                    }
                }
            ]
        ], [
            'teacher_id.required' => 'O professor é obrigatório.',
            'teacher_id.exists' => 'O professor selecionado não existe.',
            'room.required' => 'A sala é obrigatória.',
            'room.max' => 'A sala não pode ter mais de 50 caracteres.',
            'day_of_week.required' => 'O dia da semana é obrigatório.',
            'day_of_week.in' => 'O dia da semana selecionado é inválido.',
            'start_time.required' => 'O horário de início é obrigatório.',
            'start_time.date_format' => 'O formato do horário de início é inválido.',
            'end_time.required' => 'O horário de término é obrigatório.',
            'end_time.date_format' => 'O formato do horário de término é inválido.',
            'end_time.after' => 'O horário de término deve ser após o horário de início.',
            'original_teacher_id.exists' => 'O professor original selecionado não existe.'
        ]);

        $data = $request->all();
        $data['subject_id'] = $subject->id;

        // Validate schedule conflicts
        $validation = SubjectSchedule::validateSchedule($data);
        if (!$validation['success']) {
            return back()->with('error', $validation['message']);
        }

        SubjectSchedule::create($data);

        return redirect()->route('subjects.schedules.index', $subject)
            ->with('success', 'Horário cadastrado com sucesso!');
    }

    public function update(Request $request, Subject $subject, SubjectSchedule $schedule)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'room' => 'required|string|max:50',
            'day_of_week' => 'required|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_substitute' => 'boolean',
            'original_teacher_id' => [
                'nullable',
                'exists:teachers,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->is_substitute && empty($value)) {
                        $fail('O professor original é obrigatório quando é um substituto.');
                    }
                }
            ]
        ], [
            'teacher_id.required' => 'O professor é obrigatório.',
            'teacher_id.exists' => 'O professor selecionado não existe.',
            'room.required' => 'A sala é obrigatória.',
            'room.max' => 'A sala não pode ter mais de 50 caracteres.',
            'day_of_week.required' => 'O dia da semana é obrigatório.',
            'day_of_week.in' => 'O dia da semana selecionado é inválido.',
            'start_time.required' => 'O horário de início é obrigatório.',
            'start_time.date_format' => 'O formato do horário de início é inválido.',
            'end_time.required' => 'O horário de término é obrigatório.',
            'end_time.date_format' => 'O formato do horário de término é inválido.',
            'end_time.after' => 'O horário de término deve ser após o horário de início.',
            'original_teacher_id.exists' => 'O professor original selecionado não existe.'
        ]);

        $data = $request->all();

        // Validate schedule conflicts (excluding current schedule)
        $validation = SubjectSchedule::validateSchedule($data, $schedule->id);
        if (!$validation['success']) {
            return back()->with('error', $validation['message']);
        }

        $schedule->update($data);

        return redirect()->route('subjects.schedules.index', $subject)
            ->with('success', 'Horário atualizado com sucesso!');
    }

    public function destroy(Subject $subject, SubjectSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('subjects.schedules.index', $subject)
            ->with('success', 'Horário excluído com sucesso!');
    }
} 