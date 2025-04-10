@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 p-4">
                <h1 class="text-2xl font-semibold text-gray-900">Bem-vindo, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-gray-600">Você está logado no sistema de agendamento do SENAI.</p>
            </div>
        </div>
    </div>
@endsection 