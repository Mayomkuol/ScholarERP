<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class MarksheetController extends Controller
{
    /**
     * Display the marks entry page.
     */
    public function index(Request $request)
    {
        $exams = Exam::orderBy('name')->get();

        $classes = SchoolClass::orderBy('name')->get();

        $students = collect();

        $subjects = collect();

        $marks = collect();

        $selectedExam = null;

        $selectedClass = null;

        /*
        |--------------------------------------------------------------------------
        | Load selected exam and class
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('exam_id') &&
            $request->filled('school_class_id')
        ) {
            $selectedExam = Exam::find(
                $request->exam_id
            );

            $selectedClass = SchoolClass::find(
                $request->school_class_id
            );

            /*
            |--------------------------------------------------------------------------
            | Students in selected class
            |--------------------------------------------------------------------------
            */

            $students = Student::where(
                'school_class_id',
                $request->school_class_id
            )
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | All subjects
            |--------------------------------------------------------------------------
            */

            $subjects = Subject::orderBy('name')->get();

            /*
            |--------------------------------------------------------------------------
            | Existing marks
            |--------------------------------------------------------------------------
            */

            if ($students->isNotEmpty()) {

                $marks = ExamMark::where(
                    'exam_id',
                    $request->exam_id
                )
                    ->whereIn(
                        'student_id',
                        $students->pluck('id')
                    )
                    ->get();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Class results
        |--------------------------------------------------------------------------
        */

        $results = collect();

        foreach ($students as $student) {

            $studentMarks = $marks->where(
                'student_id',
                $student->id
            );

            $total = 0;

            $subjectCount = 0;

            $points = 0;

            foreach ($studentMarks as $mark) {

                if (
                    !$mark->absent &&
                    $mark->marks !== null
                ) {
                    $total += (float) $mark->marks;

                    $subjectCount++;

                    $points += (float) (
                        $mark->points ?? 0
                    );
                }
            }

            $mean = $subjectCount > 0
                ? round($total / $subjectCount, 2)
                : 0;

            $results->push([
                'student' => $student,
                'total' => $total,
                'mean' => $mean,
                'points' => $points,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Rank students
        |--------------------------------------------------------------------------
        */

        $results = $results
            ->sortByDesc('total')
            ->values();

        $position = 0;

        $rank = 0;

        $previousTotal = null;

        foreach ($results as $result) {

            $position++;

            if (
                $previousTotal === null ||
                $result['total'] != $previousTotal
            ) {
                $rank = $position;
            }

            $result['position'] = $rank;

            $previousTotal = $result['total'];

            $results[$position - 1] = $result;
        }

        /*
        |--------------------------------------------------------------------------
        | Return view
        |--------------------------------------------------------------------------
        */

        return view('marksheets.index', [
            'exams' => $exams,
            'classes' => $classes,
            'students' => $students,
            'subjects' => $subjects,
            'marks' => $marks,
            'results' => $results,
            'selectedExam' => $selectedExam,
            'selectedClass' => $selectedClass,
        ]);
    }


    /**
     * Save marks.
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => [
                'required',
                'exists:exams,id'
            ],

            'school_class_id' => [
                'required',
                'exists:school_classes,id'
            ],

            'marks' => [
                'nullable',
                'array'
            ],

            'marks.*' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100'
            ],

            'absent' => [
                'nullable',
                'array'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Save marks
        |--------------------------------------------------------------------------
        */

        foreach ($request->input('marks', []) as $key => $markValue) {

            /*
             * Key format:
             *
             * student_id-subject_id
             *
             * Example:
             *
             * 1-3
             */

            $parts = explode('-', $key);

            if (count($parts) !== 2) {
                continue;
            }

            $studentId = $parts[0];

            $subjectId = $parts[1];

            /*
            |--------------------------------------------------------------------------
            | Determine absent status
            |--------------------------------------------------------------------------
            */

            $isAbsent = isset(
                $request->absent[$key]
            );

            /*
            |--------------------------------------------------------------------------
            | Convert empty marks to NULL
            |--------------------------------------------------------------------------
            */

            $marks = $markValue !== ''
                ? $markValue
                : null;

            /*
            |--------------------------------------------------------------------------
            | Save or update mark
            |--------------------------------------------------------------------------
            */

            ExamMark::updateOrCreate(
                [
                    'exam_id' => $request->exam_id,

                    'student_id' => $studentId,

                    'subject_id' => $subjectId,
                ],
                [
                    'marks' => $isAbsent
                        ? null
                        : $marks,

                    'absent' => $isAbsent,

                    'points' => 0,
                ]
            );
        }

        return redirect()
            ->route('marksheets.index', [
                'exam_id' => $request->exam_id,

                'school_class_id' =>
                    $request->school_class_id,
            ])
            ->with(
                'success',
                'Marks saved successfully.'
            );
    }
}