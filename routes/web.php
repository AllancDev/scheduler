<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do CRUD de professores e matérias (apenas para admin)
    Route::middleware(['auth', CheckAdmin::class])->group(function () {
        Route::resource('teachers', TeacherController::class);
        Route::resource('subjects', SubjectController::class);
        
        // Rotas de gerenciamento de horários
        Route::get('subjects/{subject}/schedules', [SubjectScheduleController::class, 'index'])
            ->name('subjects.schedules.index');
        Route::post('subjects/{subject}/schedules', [SubjectScheduleController::class, 'store'])
            ->name('subjects.schedules.store');
        Route::put('subjects/{subject}/schedules/{schedule}', [SubjectScheduleController::class, 'update'])
            ->name('subjects.schedules.update');
        Route::delete('subjects/{subject}/schedules/{schedule}', [SubjectScheduleController::class, 'destroy'])
            ->name('subjects.schedules.destroy');
    });
});
