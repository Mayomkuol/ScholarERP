<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExamMarkController extends Controller
{
    /**
     * ============================================================
     * DISPLAY EXAMINATION MARKS
     * ============================================================
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filter Data
        |--------------------------------------------------------------------------
        */

        $schoolClasses = SchoolClass::orderBy('name')->get();

        $streams = Stream::with('schoolClass')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        $exams = Exam::orderBy('name')->get();

        $subjects = Subject::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | Examination Marks Query
        |--------------------------------------------------------------------------
        */

        $query = ExamMark::with([
            'exam',
            'student.schoolClass',
            'student.stream',
            'subject',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Filter By Class
        |--------------------------------------------------------------------------
        */

        if ($request->filled('school_class_id')) {

            $query->whereHas('student', function ($q) use ($request) {

                $q->where(
                    'school_class_id',
                    $request->school_class_id
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Filter By Stream
        |--------------------------------------------------------------------------
        */

        if ($request->filled('stream_id')) {

            $query->whereHas('student', function ($q) use ($request) {

                $q->where(
                    'stream_id',
                    $request->stream_id
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Filter By Examination
        |--------------------------------------------------------------------------
        */

        if ($request->filled('exam_id')) {

            $query->where(
                'exam_id',
                $request->exam_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filter By Subject
        |--------------------------------------------------------------------------
        */

        if ($request->filled('subject_id')) {

            $query->where(
                'subject_id',
                $request->subject_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Results
        |--------------------------------------------------------------------------
        */

        $examMarks = $query
            ->orderBy('created_at', 'desc')
            ->get();


        return view(
            'exam-marks.index',
            compact(
                'schoolClasses',
                'streams',
                'exams',
                'subjects',
                'examMarks'
            )
        );
    }


    /**
     * ============================================================
     * PRINT CLASS MARK SHEET
     * ============================================================
     */
    public function print(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $request->validate([

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

            'stream_id' => [
                'nullable',
                'integer',
                'exists:streams,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Selected Examination
        |--------------------------------------------------------------------------
        */

        $exam = Exam::findOrFail(
            $request->exam_id
        );


        /*
        |--------------------------------------------------------------------------
        | Selected Class
        |--------------------------------------------------------------------------
        */

        $schoolClass = SchoolClass::findOrFail(
            $request->school_class_id
        );


        /*
        |--------------------------------------------------------------------------
        | Selected Stream
        |--------------------------------------------------------------------------
        */

        $stream = null;

        if ($request->filled('stream_id')) {

            $stream = Stream::where(
                'id',
                $request->stream_id
            )
            ->where(
                'school_class_id',
                $schoolClass->id
            )
            ->firstOrFail();
        }


        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        */

        $subjects = Subject::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        */

        $studentsQuery = Student::where(
            'school_class_id',
            $schoolClass->id
        );


        if ($stream) {

            $studentsQuery->where(
                'stream_id',
                $stream->id
            );
        }


        $students = $studentsQuery
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Existing Marks
        |--------------------------------------------------------------------------
        */

        $marks = ExamMark::where(
            'exam_id',
            $exam->id
        )
        ->whereIn(
            'student_id',
            $students->pluck('id')
        )
        ->get()
        ->keyBy(function ($mark) {

            return $mark->student_id
                . '-'
                . $mark->subject_id;

        });


        /*
        |--------------------------------------------------------------------------
        | Build Results
        |--------------------------------------------------------------------------
        */

        $results = collect();


        foreach ($students as $student) {

            $total = 0;

            $points = 0;

            $count = 0;

            $studentSubjects = [];


            foreach ($subjects as $subject) {

                $key =
                    $student->id .
                    '-' .
                    $subject->id;


                $mark = $marks->get($key);


                /*
                |--------------------------------------------------------------------------
                | No Mark
                |--------------------------------------------------------------------------
                */

                if (!$mark) {

                    $studentSubjects[$subject->id] = [

                        'mark' => null,

                        'grade' => null,

                        'points' => 0,

                        'remarks' => null,

                        'colour' => null,

                        'absent' => false,

                    ];

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Absent
                |--------------------------------------------------------------------------
                */

                if ($mark->absent) {

                    $studentSubjects[$subject->id] = [

                        'mark' => null,

                        'grade' => null,

                        'points' => 0,

                        'remarks' => 'Absent',

                        'colour' => null,

                        'absent' => true,

                    ];

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Mark
                |--------------------------------------------------------------------------
                */

                $markValue = $mark->marks !== null
                    ? (float) $mark->marks
                    : null;


                /*
                |--------------------------------------------------------------------------
                | Grade Setting
                |--------------------------------------------------------------------------
                */

                $grading = $this->getGradeSetting(
                    $markValue
                );


                /*
                |--------------------------------------------------------------------------
                | Totals
                |--------------------------------------------------------------------------
                */

                if ($markValue !== null) {

                    $total += $markValue;

                    $count++;
                }


                if ($grading) {

                    $points += (float) $grading->points;
                }


                /*
                |--------------------------------------------------------------------------
                | Subject Result
                |--------------------------------------------------------------------------
                */

                $studentSubjects[$subject->id] = [

                    'mark' => $markValue,

                    'grade' => $grading?->grade
                        ?? $mark->grade,

                    'points' => $grading
                        ? (float) $grading->points
                        : (float) ($mark->points ?? 0),

                    'remarks' => $grading?->remarks
                        ?? $mark->remarks,

                    'colour' => $grading?->colour,

                    'absent' => false,

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Mean
            |--------------------------------------------------------------------------
            */

            $mean = $count > 0
                ? round($total / $count, 2)
                : null;


            /*
            |--------------------------------------------------------------------------
            | Overall Grade
            |--------------------------------------------------------------------------
            */

            $overallGrade = $this->getGradeSetting(
                $mean
            );


            /*
            |--------------------------------------------------------------------------
            | Push Result
            |--------------------------------------------------------------------------
            */

            $results->push([

                'student' => $student,

                'subjects' => $studentSubjects,

                'total' => $total,

                'mean' => $mean,

                'grade' => $overallGrade?->grade,

                'points' => $points,

                'position' => null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Rank Students
        |--------------------------------------------------------------------------
        */

        $results = $results
            ->sortByDesc('total')
            ->values();


        $position = 0;

        $rank = 0;

        $previousTotal = null;


        foreach ($results as $index => $result) {

            $position++;

            $currentTotal = $result['total'];


            if (
                $previousTotal === null ||
                $currentTotal != $previousTotal
            ) {

                $rank = $position;
            }


            $results[$index]['position'] = $rank;

            $previousTotal = $currentTotal;
        }


        /*
        |--------------------------------------------------------------------------
        | Academic Year / Term
        |--------------------------------------------------------------------------
        */

        $academicYear = null;

        $term = null;


        if (
            method_exists($exam, 'academicYear')
        ) {

            $academicYear = $exam->academicYear;
        }


        if (
            method_exists($exam, 'term')
        ) {

            $term = $exam->term;
        }


        $academicYearName =
            $academicYear->name
            ?? $academicYear->year
            ?? date('Y');


        $termName =
            $term->name
            ?? null;


        return view(
            'class-mark-sheet.print',
            compact(
                'exam',
                'schoolClass',
                'stream',
                'subjects',
                'students',
                'results',
                'academicYearName',
                'termName'
            )
        );
    }


    /**
     * ============================================================
     * CREATE / ENTER MARKS
     * ============================================================
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Examinations
        |--------------------------------------------------------------------------
        */

        $exams = Exam::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | School Classes
        |--------------------------------------------------------------------------
        */

        $schoolClasses = SchoolClass::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | All Streams
        |--------------------------------------------------------------------------
        */

        $streams = Stream::with('schoolClass')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        */

        $subjects = Subject::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $selectedExam = null;

        $selectedClass = null;

        $selectedStream = null;

        $selectedSubject = null;

        $students = collect();

        $existingMarks = collect();


        return view(
            'exam-marks.create',
            compact(
                'exams',
                'schoolClasses',
                'streams',
                'subjects',
                'students',
                'existingMarks',
                'selectedExam',
                'selectedClass',
                'selectedStream',
                'selectedSubject'
            )
        );
    }


    /**
     * ============================================================
     * LOAD STUDENTS
     * ============================================================
     */
    public function loadStudents(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $request->validate([

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

            'stream_id' => [

                'required',

                'integer',

                Rule::exists(
                    'streams',
                    'id'
                )->where(function ($query) use ($request) {

                    $query->where(
                        'school_class_id',
                        $request->school_class_id
                    );

                }),

            ],

            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Exams
        |--------------------------------------------------------------------------
        */

        $exams = Exam::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $schoolClasses = SchoolClass::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | Selected Class
        |--------------------------------------------------------------------------
        */

        $selectedClass = SchoolClass::findOrFail(
            $request->school_class_id
        );


        /*
        |--------------------------------------------------------------------------
        | Streams For Selected Class
        |--------------------------------------------------------------------------
        */

        $streams = Stream::where(
            'school_class_id',
            $selectedClass->id
        )
        ->orderBy('name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Selected Stream
        |--------------------------------------------------------------------------
        */

        $selectedStream = Stream::where(
            'id',
            $request->stream_id
        )
        ->where(
            'school_class_id',
            $selectedClass->id
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        */

        $subjects = Subject::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | Selected Examination
        |--------------------------------------------------------------------------
        */

        $selectedExam = Exam::findOrFail(
            $request->exam_id
        );


        /*
        |--------------------------------------------------------------------------
        | Selected Subject
        |--------------------------------------------------------------------------
        */

        $selectedSubject = Subject::findOrFail(
            $request->subject_id
        );


        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        */

        $students = Student::where(
            'school_class_id',
            $selectedClass->id
        )
        ->where(
            'stream_id',
            $selectedStream->id
        )
        ->where(
            'status',
            'Active'
        )
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Existing Marks
        |--------------------------------------------------------------------------
        */

        $existingMarks = ExamMark::where(
            'exam_id',
            $selectedExam->id
        )
        ->where(
            'subject_id',
            $selectedSubject->id
        )
        ->whereIn(
            'student_id',
            $students->pluck('id')
        )
        ->get()
        ->keyBy('student_id');


        return view(
            'exam-marks.create',
            compact(
                'exams',
                'schoolClasses',
                'streams',
                'subjects',
                'students',
                'existingMarks',
                'selectedExam',
                'selectedClass',
                'selectedStream',
                'selectedSubject'
            )
        );
    }


    /**
     * ============================================================
     * STORE EXAMINATION MARKS
     * ============================================================
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $request->validate([

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

            'stream_id' => [

                'required',

                'integer',

                Rule::exists(
                    'streams',
                    'id'
                )->where(function ($query) use ($request) {

                    $query->where(
                        'school_class_id',
                        $request->school_class_id
                    );

                }),

            ],

            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],

            'marks' => [
                'required',
                'array',
            ],

            'marks.*' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Students
        |--------------------------------------------------------------------------
        */

        $students = Student::where(
            'school_class_id',
            $request->school_class_id
        )
        ->where(
            'stream_id',
            $request->stream_id
        )
        ->where(
            'status',
            'Active'
        )
        ->whereIn(
            'id',
            array_keys($request->marks)
        )
        ->get()
        ->keyBy('id');


        /*
        |--------------------------------------------------------------------------
        | Save In Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $request,
            $students
        ) {

            foreach ($request->marks as $studentId => $mark) {

                /*
                |--------------------------------------------------------------------------
                | Security Check
                |--------------------------------------------------------------------------
                */

                if (!$students->has($studentId)) {

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Ignore Empty Marks
                |--------------------------------------------------------------------------
                */

                if (
                    $mark === null ||
                    $mark === ''
                ) {

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Convert Mark
                |--------------------------------------------------------------------------
                */

                $mark = (float) $mark;


                /*
                |--------------------------------------------------------------------------
                | Find Grade Setting
                |--------------------------------------------------------------------------
                */

               $grading = $this->getGrade($mark);


                /*
                |--------------------------------------------------------------------------
                | Grade Setting Must Exist
                |--------------------------------------------------------------------------
                */

                if (!$grading) {

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Save / Update Mark
                |--------------------------------------------------------------------------
                */

                ExamMark::updateOrCreate(

                    [

                        'exam_id' => $request->exam_id,

                        'student_id' => $studentId,

                        'subject_id' => $request->subject_id,

                    ],

                    [

                        'marks' => $mark,

                        'grade' => $grading->grade,

                        'points' => $grading->points,

                        'remarks' => $grading->remarks,

                        'absent' => false,

                    ]

                );
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()

            ->route(
                'exam-marks.index'
            )

            ->with(
                'success',
                'Examination marks saved successfully.'
            );
    }


    /**
     * ============================================================
     * FIND GRADE SETTING
     * ============================================================
     *
     * Finds the grade whose minimum and maximum contain the mark.
     */
   private function getGrade(?float $mark): ?Grade
{
    if ($mark === null) {
        return null;
    }

    return Grade::where(
        'min_mark',
        '<=',
        $mark
    )
    ->where(
        'max_mark',
        '>=',
        $mark
    )
    ->orderBy(
        'min_mark',
        'desc'
    )
    ->first();
}
}