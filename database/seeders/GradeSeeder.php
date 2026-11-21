<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [

            [
                'grade' => 'A',
                'min_mark' => 80,
                'max_mark' => 100,
                'points' => 12,
                'remarks' => 'Excellent',
                'color' => '#28a745'
            ],

            [
                'grade' => 'A-',
                'min_mark' => 75,
                'max_mark' => 79,
                'points' => 11,
                'remarks' => 'Excellent',
                'color' => '#3CB371'
            ],

            [
                'grade' => 'B+',
                'min_mark' => 70,
                'max_mark' => 74,
                'points' => 10,
                'remarks' => 'Very Good',
                'color' => '#17a2b8'
            ],

            [
                'grade' => 'B',
                'min_mark' => 65,
                'max_mark' => 69,
                'points' => 9,
                'remarks' => 'Good',
                'color' => '#20c997'
            ],

            [
                'grade' => 'B-',
                'min_mark' => 60,
                'max_mark' => 64,
                'points' => 8,
                'remarks' => 'Good',
                'color' => '#6f42c1'
            ],

            [
                'grade' => 'C+',
                'min_mark' => 55,
                'max_mark' => 59,
                'points' => 7,
                'remarks' => 'Credit',
                'color' => '#ffc107'
            ],

            [
                'grade' => 'C',
                'min_mark' => 50,
                'max_mark' => 54,
                'points' => 6,
                'remarks' => 'Credit',
                'color' => '#fd7e14'
            ],

            [
                'grade' => 'C-',
                'min_mark' => 45,
                'max_mark' => 49,
                'points' => 5,
                'remarks' => 'Pass',
                'color' => '#dc3545'
            ],

            [
                'grade' => 'D+',
                'min_mark' => 40,
                'max_mark' => 44,
                'points' => 4,
                'remarks' => 'Pass',
                'color' => '#e83e8c'
            ],

            [
                'grade' => 'D',
                'min_mark' => 35,
                'max_mark' => 39,
                'points' => 3,
                'remarks' => 'Weak',
                'color' => '#6c757d'
            ],

            [
                'grade' => 'E',
                'min_mark' => 0,
                'max_mark' => 34,
                'points' => 1,
                'remarks' => 'Fail',
                'color' => '#000000'
            ]

        ];

        foreach ($grades as $grade) {

    Grade::updateOrCreate(

        ['grade' => $grade['grade']],

        $grade

    );

        }
    }
}