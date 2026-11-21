<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Grade;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectAllocation;
use Illuminate\Http\Request;

class ClassMarkSheetController extends Controller
{
    /**
     * Display the class mark sheet page.
     */
    public function index()
    {
        return view('class-mark-sheet.index', [
            'exams' => Exam::orderBy('name')->get(),

            'classes' => SchoolClass::orderBy('name')->get(),

            'subjects' => collect(),

            'selectedExam' => null,

            'selectedClass' => null,

            'students' => collect(),

            'results' => collect(),

            'subjectStatistics' => [],

            'classTotal' => 0,

            'classMean' => null,

            'classGrade' => null,

            'classTotalPoints' => 0,

            'grades' => Grade::orderByDesc('min_mark')->get(),

            'school' => School::first(),
        ]);
    }


    /**
     * Load examination results for a class.
     */
    public function load(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE REQUEST
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'exam_id' => [
                'required',
                'integer',
                'exists:exams,id',
            ],

            'school_class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | SELECTED EXAM
        |--------------------------------------------------------------------------
        */

        $selectedExam = Exam::with([
            'academicYear',
            'term',
            'schoolClass',
        ])->findOrFail($validated['exam_id']);


        /*
        |--------------------------------------------------------------------------
        | SELECTED CLASS
        |--------------------------------------------------------------------------
        */

        $selectedClass = SchoolClass::findOrFail(
            $validated['school_class_id']
        );


        /*
        |--------------------------------------------------------------------------
        | SCHOOL
        |--------------------------------------------------------------------------
        */

        $school = School::first();


        /*
        |--------------------------------------------------------------------------
        | SUBJECTS
        |--------------------------------------------------------------------------
        |
        | Subject allocations do NOT have an active column.
        |
        */

        $subjectIds = SubjectAllocation::where(
            'school_class_id',
            $selectedClass->id
        )
        ->pluck('subject_id')
        ->unique();


        $subjects = Subject::whereIn(
            'id',
            $subjectIds
        )
        ->orderBy('name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | STUDENTS
        |--------------------------------------------------------------------------
        */

        $students = Student::where(
            'school_class_id',
            $selectedClass->id
        )
        ->orderBy('admission_number')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | STUDENT IDS
        |--------------------------------------------------------------------------
        */

        $studentIds = $students->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | EXAM MARKS
        |--------------------------------------------------------------------------
        */

        $marks = ExamMark::where(
            'exam_id',
            $selectedExam->id
        )
        ->whereIn(
            'student_id',
            $studentIds
        )
        ->get();


        /*
        |--------------------------------------------------------------------------
        | GROUP MARKS BY STUDENT
        |--------------------------------------------------------------------------
        */

        $marksByStudent = $marks->groupBy('student_id');


        /*
        |--------------------------------------------------------------------------
        | INITIALISE RESULTS
        |--------------------------------------------------------------------------
        */

        $results = collect();


        /*
        |--------------------------------------------------------------------------
        | SUBJECT STATISTICS
        |--------------------------------------------------------------------------
        */

        $subjectStatistics = [];

        foreach ($subjects as $subject) {

            $subjectStatistics[$subject->id] = [
                'total' => 0,
                'count' => 0,
                'average' => null,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | PROCESS STUDENTS
        |--------------------------------------------------------------------------
        */

        foreach ($students as $student) {

            $studentMarks = $marksByStudent->get(
                $student->id,
                collect()
            );


            $subjectResults = [];

            $totalMarks = 0;

            $subjectsAttempted = 0;

            $totalPoints = 0;


            /*
            |--------------------------------------------------------------------------
            | PROCESS SUBJECTS
            |--------------------------------------------------------------------------
            */

            foreach ($subjects as $subject) {

                $markRecord = $studentMarks->firstWhere(
                    'subject_id',
                    $subject->id
                );


                /*
                |------------------------------------------------------------------
                | DEFAULT RESULT
                |------------------------------------------------------------------
                */

                $subjectResults[$subject->id] = [
                    'mark' => null,
                    'grade' => null,
                    'points' => null,
                    'remarks' => null,
                    'absent' => false,
                ];


                /*
                |------------------------------------------------------------------
                | NO MARK RECORD
                |------------------------------------------------------------------
                */

                if (!$markRecord) {
                    continue;
                }


                /*
                |------------------------------------------------------------------
                | REMARKS
                |------------------------------------------------------------------
                */

                $rawRemarks = trim(
                    (string) ($markRecord->remarks ?? '')
                );

                $remarks = strtolower($rawRemarks);


                /*
                |------------------------------------------------------------------
                | ABSENT
                |------------------------------------------------------------------
                */

                $isAbsent = in_array(
                    $remarks,
                    [
                        'abs',
                        'absent',
                    ],
                    true
                );


                /*
                |------------------------------------------------------------------
                | NULL MARK
                |------------------------------------------------------------------
                */

                if ($markRecord->marks === null) {

                    $subjectResults[$subject->id] = [
                        'mark' => null,
                        'grade' => null,
                        'points' => null,
                        'remarks' => $rawRemarks,
                        'absent' => $isAbsent,
                    ];

                    continue;
                }


                /*
                |------------------------------------------------------------------
                | NUMERICAL MARK
                |------------------------------------------------------------------
                */

                $mark = (float) $markRecord->marks;


                /*
                |------------------------------------------------------------------
                | CALCULATE GRADE
                |------------------------------------------------------------------
                */

                $gradeData = $this->calculateGrade($mark);


                /*
                |------------------------------------------------------------------
                | STUDENT TOTAL
                |------------------------------------------------------------------
                */

                $totalMarks += $mark;


                /*
                |------------------------------------------------------------------
                | SUBJECTS ATTEMPTED
                |------------------------------------------------------------------
                */

                $subjectsAttempted++;


                /*
                |------------------------------------------------------------------
                | TOTAL POINTS
                |------------------------------------------------------------------
                */

                $totalPoints += $gradeData['points'];


                /*
                |------------------------------------------------------------------
                | SUBJECT STATISTICS
                |------------------------------------------------------------------
                */

                $subjectStatistics[$subject->id]['total'] += $mark;

                $subjectStatistics[$subject->id]['count']++;


                /*
                |------------------------------------------------------------------
                | SAVE SUBJECT RESULT
                |------------------------------------------------------------------
                */

                $subjectResults[$subject->id] = [
                    'mark' => $mark,

                    'grade' => $gradeData['grade'],

                    'points' => $gradeData['points'],

                    'remarks' => $rawRemarks,

                    'absent' => $isAbsent,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | STUDENT MEAN
            |--------------------------------------------------------------------------
            */

            $mean = $subjectsAttempted > 0
                ? round(
                    $totalMarks / $subjectsAttempted,
                    2
                )
                : null;


            /*
            |--------------------------------------------------------------------------
            | OVERALL GRADE
            |--------------------------------------------------------------------------
            */

            $overallGrade = $mean !== null
                ? $this->calculateGrade($mean)['grade']
                : null;


            /*
            |--------------------------------------------------------------------------
            | STORE RESULT
            |--------------------------------------------------------------------------
            */

            $results->push([
                'student' => $student,

                'subjects' => $subjectResults,

                'total' => $totalMarks,

                'mean' => $mean,

                'grade' => $overallGrade,

                'points' => $totalPoints,

                'subjects_attempted' => $subjectsAttempted,

                'position' => null,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SUBJECT AVERAGES
        |--------------------------------------------------------------------------
        */

        foreach ($subjectStatistics as $subjectId => &$statistics) {

            if ($statistics['count'] > 0) {

                $statistics['average'] = round(
                    $statistics['total'] /
                    $statistics['count'],
                    2
                );
            }
        }

        unset($statistics);


        /*
        |--------------------------------------------------------------------------
        | RANK STUDENTS
        |--------------------------------------------------------------------------
        |
        | Competition ranking:
        |
        | 450 = 1
        | 420 = 2
        | 420 = 2
        | 390 = 4
        |
        */

        $rankedResults = $results
            ->sortByDesc('total')
            ->values();


        $positions = [];

        $previousTotal = null;

        $currentPosition = 0;


        foreach ($rankedResults as $index => $result) {

            $currentTotal = (float) $result['total'];


            if (
                $previousTotal === null ||
                $currentTotal < $previousTotal
            ) {
                $currentPosition = $index + 1;
            }


            $positions[
                $result['student']->id
            ] = $currentPosition;


            $previousTotal = $currentTotal;
        }


        /*
        |--------------------------------------------------------------------------
        | ATTACH POSITIONS
        |--------------------------------------------------------------------------
        */

        $results = $results->map(
            function ($result) use ($positions) {

                $studentId = $result['student']->id;

                $result['position'] =
                    $positions[$studentId] ?? null;

                return $result;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SORT BY POSITION
        |--------------------------------------------------------------------------
        */

        $results = $results
            ->sortBy('position')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | CLASS TOTAL
        |--------------------------------------------------------------------------
        */

        $classTotal = $results->sum(
            fn ($result) => $result['total']
        );


        /*
        |--------------------------------------------------------------------------
        | CLASS MEAN
        |--------------------------------------------------------------------------
        */

        $studentMeans = $results
            ->pluck('mean')
            ->filter(
                fn ($mean) => $mean !== null
            );


        $classMean = $studentMeans->count() > 0
            ? round(
                $studentMeans->avg(),
                2
            )
            : null;


        /*
        |--------------------------------------------------------------------------
        | CLASS TOTAL POINTS
        |--------------------------------------------------------------------------
        */

        $classTotalPoints = $results->sum(
            fn ($result) => $result['points']
        );


        /*
        |--------------------------------------------------------------------------
        | CLASS GRADE
        |--------------------------------------------------------------------------
        */

        $classGrade = $classMean !== null
            ? $this->calculateGrade($classMean)['grade']
            : null;


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('class-mark-sheet.index', [

            'exams' => Exam::orderBy('name')->get(),

            'classes' => SchoolClass::orderBy('name')->get(),

            'subjects' => $subjects,

            'selectedExam' => $selectedExam,

            'selectedClass' => $selectedClass,

            'students' => $students,

            'results' => $results,

            'school' => $school,

            'subjectStatistics' => $subjectStatistics,

            'classTotal' => $classTotal,

            'classMean' => $classMean,

            'classGrade' => $classGrade,

            'classTotalPoints' => $classTotalPoints,

            'grades' => Grade::orderByDesc('min_mark')->get(),
        ]);
    }


    /**
     * Calculate grade and points from database grading scale.
     */
    private function calculateGrade(float $mark): array
    {
        $grade = Grade::where(
            'min_mark',
            '<=',
            $mark
        )
        ->orderByDesc('min_mark')
        ->first();


        /*
        |--------------------------------------------------------------------------
        | SAFETY FALLBACK
        |--------------------------------------------------------------------------
        */

        if (!$grade) {

            return [
                'grade' => 'E',

                'points' => 1,
            ];
        }


        return [
            'grade' => $grade->grade,

            'points' => $grade->points,
        ];
    }
}
