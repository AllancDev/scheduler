<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = ClassModel::orderBy('name')->paginate(10);
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
        ]);

        ClassModel::create($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassModel $class)
    {
        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassModel $class)
    {
        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
        ]);

        $class->update($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassModel $class)
    {
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Turma excluída com sucesso!');
    }
} 