<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserController;

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
    // Dashboard e Logout
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Rotas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de administrador
    Route::middleware('can:admin')->group(function () {
        // CRUD de professores
        Route::resource('teachers', TeacherController::class);
        
        // CRUD de matérias
        Route::resource('subjects', SubjectController::class);
        
        // CRUD de turmas e horários
        Route::resource('classes', ClassController::class);
        Route::get('/classes/{class}/schedules', [ScheduleController::class, 'index'])->name('classes.schedules');
        Route::get('/classes/{class}/schedules/create', [ScheduleController::class, 'create'])->name('classes.schedules.create');
        Route::post('/classes/{class}/schedules', [ScheduleController::class, 'store'])->name('classes.schedules.store');
        Route::get('/classes/{class}/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('classes.schedules.edit');
        Route::put('/classes/{class}/schedules/{schedule}', [ScheduleController::class, 'update'])->name('classes.schedules.update');
        Route::delete('/classes/{class}/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('classes.schedules.destroy');

        // CRUD de usuários
        Route::resource('users', UserController::class);
    });

    // Rota do calendário (acessível por admin e professor)
    Route::get('/calendar', [CalendarController::class, 'index'])
        ->middleware('can:view-calendar')
        ->name('calendar.index');
});
