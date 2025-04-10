<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('subjects')->get();
        $subjects = Subject::all();
        return view('teachers.index', compact('teachers', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'password' => 'required|string|min:8|confirmed',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id'
        ], [
            'name.required' => 'O nome do professor é obrigatório.',
            'name.max' => 'O nome do professor não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'subjects.required' => 'Selecione pelo menos uma matéria.',
            'subjects.*.exists' => 'Uma ou mais matérias selecionadas são inválidas.',
        ]);

        $teacher = Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $teacher->subjects()->attach($request->subjects);

        return redirect()->route('teachers.index')
            ->with('success', 'Professor criado com sucesso.');
    }

    public function edit(Teacher $teacher)
    {
        $subjects = Subject::all();
        return view('teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id'
        ], [
            'name.required' => 'O nome do professor é obrigatório.',
            'name.max' => 'O nome do professor não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está em uso.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'subjects.required' => 'Selecione pelo menos uma matéria.',
            'subjects.*.exists' => 'Uma ou mais matérias selecionadas são inválidas.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $teacher->update($data);
        $teacher->subjects()->sync($request->subjects);

        return redirect()->route('teachers.index')
            ->with('success', 'Professor atualizado com sucesso.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->subjects()->detach();
        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Professor excluído com sucesso.');
    }
} 