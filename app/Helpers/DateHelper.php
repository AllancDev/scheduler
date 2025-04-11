<?php

namespace App\Helpers;

class DateHelper
{
    public static function translateDayOfWeek($day)
    {
        $translations = [
            'monday' => 'Segunda',
            'tuesday' => 'Terça',
            'wednesday' => 'Quarta',
            'thursday' => 'Quinta',
            'friday' => 'Sexta',
        ];

        return $translations[$day] ?? $day;
    }
} 