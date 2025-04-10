<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica se o usuário admin já existe
        if (!User::where('email', 'admin@senai.com.br')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@senai.com.br',
                'password' => Hash::make('senai123'),
                'role' => 'admin'
            ]);
        }

        if (!User::where('email', 'professor@senai.com.br')->exists()) {
            User::create([
                'name' => 'Professor',
                'email' => 'professor@senai.com.br',
                'password' => Hash::make('senai123'),
                'role' => 'teacher'
            ]);
        }
    }
} 