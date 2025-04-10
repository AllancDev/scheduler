<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return redirect()->route('login');
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
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Rotas de Professores
    Route::resource('teachers', TeacherController::class);

    // Rotas de Matérias
    Route::resource('subjects', SubjectController::class);

    // Rotas de Turmas
    Route::resource('classes', ClassController::class);

    // Rotas de Horários
    Route::get('/classes/{class}/schedules', [ScheduleController::class, 'index'])->name('classes.schedules');
    Route::get('/classes/{class}/schedules/create', [ScheduleController::class, 'create'])->name('classes.schedules.create');
    Route::post('/classes/{class}/schedules', [ScheduleController::class, 'store'])->name('classes.schedules.store');
    Route::get('/classes/{class}/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('classes.schedules.edit');
    Route::put('/classes/{class}/schedules/{schedule}', [ScheduleController::class, 'update'])->name('classes.schedules.update');
    Route::delete('/classes/{class}/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('classes.schedules.destroy');

    // Rota do Calendário
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
});
