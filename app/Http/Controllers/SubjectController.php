<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'O nome da matéria é obrigatório.',
            'name.max' => 'O nome da matéria não pode ter mais de 255 caracteres.',
        ]);

        Subject::create($request->only('name'));

        return redirect()->route('subjects.index')
            ->with('success', 'Matéria criada com sucesso.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'O nome da matéria é obrigatório.',
            'name.max' => 'O nome da matéria não pode ter mais de 255 caracteres.',
        ]);

        $subject->update($request->only('name'));

        return redirect()->route('subjects.index')
            ->with('success', 'Matéria atualizada com sucesso.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Matéria excluída com sucesso.');
    }
} 