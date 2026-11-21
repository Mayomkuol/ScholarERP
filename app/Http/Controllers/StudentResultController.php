<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\ExamMark;
use App\Models\StudentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentResultController extends Controller
{
    /**
     * Display result generation page.
     */
    public function index(Request $request)
    {
        $exams = Exam::orderBy('name')->get();

        $classes = SchoolClass::orderBy('name')->get();

        $results = collect();

        if ($request->filled('exam_id') && $request->filled('class_id')) {

            $exam = Exam::findOrFail($request->exam_id);

            $students = Student::where(
                    'school_class_id',
                    $request->class_id
                )
                ->orderBy('admission_no')
                ->get();

            foreach ($students as $student) {

                $marks = ExamMark::where('exam_id', $exam->id)
                    ->where('student_id', $student->id)
                    ->get();

                if ($marks->count() > 0) {

                    $totalMarks = $marks->sum('marks');

                    $meanScore = $marks->avg('marks');

                    $totalPoints = $marks->sum(function ($mark) {

                        return $mark->points ?? 0;

                    });

                    $results->push([
                        'student' => $student,
                        'marks_count' => $marks->count(),
                        'total_marks' => $totalMarks,
                        'mean_score' => $meanScore,
                        'total_points' => $totalPoints,
                    ]);
                }
            }
        }

        return view(
            'student_results.index',
            compact(
                'exams',
                'classes',
                'results'
            )
        );
    }


    /**
     * Generate and save student results.
     */
    public function generate(Request $request)
    {
        $request->validate([

            'exam_id' => 'required|exists:exams,id',

            'class_id' => 'required|exists:school_classes,id',

        ]);

        $exam = Exam::findOrFail($request->exam_id);

        $students = Student::where(
                'school_class_id',
                $request->class_id
            )
            ->get();

        $generated = 0;

        DB::transaction(function () use (
            $exam,
            $students,
            &$generated
        ) {

            foreach ($students as $student) {

                /*
                |--------------------------------------------------------------------------
                | Get Student Marks
                |--------------------------------------------------------------------------
                */

                $marks = ExamMark::where(
                        'exam_id',
                        $exam->id
                    )
                    ->where(
                        'student_id',
                        $student->id
                    )
                    ->get();

                /*
                |--------------------------------------------------------------------------
                | Skip students without marks
                |--------------------------------------------------------------------------
                */

                if ($marks->count() === 0) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Calculate Total
                |--------------------------------------------------------------------------
                */

                $totalMarks = $marks->sum('marks');

                /*
                |--------------------------------------------------------------------------
                | Calculate Mean
                |--------------------------------------------------------------------------
                */

                $meanScore = $marks->avg('marks');

                /*
                |--------------------------------------------------------------------------
                | Calculate Points
                |--------------------------------------------------------------------------
                */

                $points = $marks->sum(function ($mark) {

                    return $mark->points ?? 0;

                });

                /*
                |--------------------------------------------------------------------------
                | Calculate Overall Grade
                |--------------------------------------------------------------------------
                */

                $meanGrade = $this->calculateGrade($meanScore);

                /*
                |--------------------------------------------------------------------------
                | Save / Update Student Result
                |--------------------------------------------------------------------------
                */

                StudentResult::updateOrCreate(

                    [
                        'exam_id' => $exam->id,

                        'student_id' => $student->id,
                    ],

                    [
                        'total_marks' => $totalMarks,

                        'mean_score' => $meanScore,

                        'mean_grade' => $meanGrade,

                        'points' => $points,

                        'position' => null,
                    ]

                );

                $generated++;
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate Positions
            |--------------------------------------------------------------------------
            */

            $this->calculatePositions(
                $exam->id,
                $students
            );
        });

        return redirect()
            ->route(
                'student-results.index',
                [
                    'exam_id' => $request->exam_id,
                    'class_id' => $request->class_id,
                ]
            )
            ->with(
                'success',
                $generated . ' student result(s) generated successfully.'
            );
    }


    /**
     * Calculate positions.
     */
    private function calculatePositions(
        $examId,
        $students
    ) {

        $studentIds = $students
            ->pluck('id');

        $results = StudentResult::where(
                'exam_id',
                $examId
            )
            ->whereIn(
                'student_id',
                $studentIds
            )
            ->orderByDesc('mean_score')
            ->get();

        $position = 1;

        foreach ($results as $result) {

            $result->update([
                'position' => $position
            ]);

            $position++;
        }
    }


    /**
     * Calculate overall grade.
     */
    private function calculateGrade($mean)
    {
        if ($mean >= 80) {
            return 'A';
        }

        if ($mean >= 75) {
            return 'A-';
        }

        if ($mean >= 70) {
            return 'B+';
        }

        if ($mean >= 65) {
            return 'B';
        }

        if ($mean >= 60) {
            return 'B-';
        }

        if ($mean >= 55) {
            return 'C+';
        }

        if ($mean >= 50) {
            return 'C';
        }

        if ($mean >= 45) {
            return 'C-';
        }

        if ($mean >= 40) {
            return 'D+';
        }

        if ($mean >= 30) {
            return 'D';
        }

        return 'E';
    }
}