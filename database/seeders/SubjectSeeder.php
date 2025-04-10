<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            'Desenvolvimento de Sistemas',
            'Banco de Dados',
            'Internet of Things',
        ];

        foreach ($subjects as $subject) {
            Subject::create(['name' => $subject]);
        }
    }
} 