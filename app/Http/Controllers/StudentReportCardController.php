<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Grade;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentReportCardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        return view('student-report-card.index', [
            'exams' => $this->accessibleExams($user),

            'classes' => $this->accessibleClasses($user),

            'streams' => collect(),

            'students' => collect(),

            'selectedExam' => null,
            'selectedClass' => null,
            'selectedStream' => null,
            'selectedStudent' => null,

            'report' => null,

            'school' => School::first(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD EXAMS BY CLASS
    |--------------------------------------------------------------------------
    |
    | Used by the report-card form to make Examination dependent on
    | the selected School Class.
    |
    */

    public function examsByClass(SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        $user = Auth::user();

        $exams = $this->accessibleExams($user)
            ->where('school_class_id', $schoolClass->id)
            ->values()
            ->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'name' => $exam->name,
                    'school_class_id' => $exam->school_class_id,
                    'academic_year' => $exam->academicYear?->name,
                    'term' => $exam->term?->name,
                    'start_date' => $exam->start_date,
                    'end_date' => $exam->end_date,
                ];
            });

        return response()->json($exams);
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD STREAMS BY CLASS
    |--------------------------------------------------------------------------
    */

    public function streamsByClass(SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        $streams = $this->accessibleStreams($schoolClass);

        return response()->json(
            $streams->map(function ($stream) {
                return [
                    'id' => $stream->id,
                    'name' => $stream->name,
                    'code' => $stream->code,
                    'school_class_id' => $stream->school_class_id,
                ];
            })->values()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENTS BY CLASS
    |--------------------------------------------------------------------------
    */

    public function studentsByClass(SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        $students = Student::query()
            ->where(
                'school_class_id',
                $schoolClass->id
            )
            ->where(
                'status',
                'Active'
            )
            ->orderByRaw(
                'COALESCE(admission_number, admission_no)'
            )
            ->orderBy('first_name')
            ->get([
                'id',
                'admission_number',
                'admission_no',
                'first_name',
                'middle_name',
                'last_name',
                'stream_id',
            ]);

        return response()->json(
            $students->map(function ($student) {
                return [
                    'id' => $student->id,

                    'admission_number' =>
                        $student->admission_number
                        ?? $student->admission_no,

                    'name' => $student->full_name,

                    'first_name' => $student->first_name,

                    'middle_name' => $student->middle_name,

                    'last_name' => $student->last_name,

                    'stream_id' => $student->stream_id,
                ];
            })->values()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENTS BY STREAM
    |--------------------------------------------------------------------------
    */

    public function studentsByStream(
        SchoolClass $schoolClass,
        Stream $stream
    ) {
        $this->authorizeClass($schoolClass);

        $this->authorizeStream(
            $schoolClass,
            $stream
        );

        $students = Student::query()
            ->where(
                'school_class_id',
                $schoolClass->id
            )
            ->where(
                'stream_id',
                $stream->id
            )
            ->where(
                'status',
                'Active'
            )
            ->orderByRaw(
                'COALESCE(admission_number, admission_no)'
            )
            ->orderBy('first_name')
            ->get([
                'id',
                'admission_number',
                'admission_no',
                'first_name',
                'middle_name',
                'last_name',
                'stream_id',
            ]);

        return response()->json(
            $students->map(function ($student) {
                return [
                    'id' => $student->id,

                    'admission_number' =>
                        $student->admission_number
                        ?? $student->admission_no,

                    'name' => $student->full_name,

                    'first_name' => $student->first_name,

                    'middle_name' => $student->middle_name,

                    'last_name' => $student->last_name,

                    'stream_id' => $student->stream_id,
                ];
            })->values()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE REPORT CARD
    |--------------------------------------------------------------------------
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

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],
        ]);


        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | LOAD EXAM
        |--------------------------------------------------------------------------
        */

        $selectedExam = Exam::with([
            'academicYear',
            'term',
            'schoolClass',
        ])->findOrFail(
            $validated['exam_id']
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD CLASS
        |--------------------------------------------------------------------------
        */

        $selectedClass = SchoolClass::findOrFail(
            $validated['school_class_id']
        );


        /*
        |--------------------------------------------------------------------------
        | AUTHORIZE CLASS
        |--------------------------------------------------------------------------
        */

        $this->authorizeClass(
            $selectedClass
        );


        /*
        |--------------------------------------------------------------------------
        | VERIFY EXAM BELONGS TO CLASS
        |--------------------------------------------------------------------------
        |
        | NEVER remove this validation.
        |
        | It prevents a Form 1 examination from being used with Class 8,
        | Class 7, etc.
        |
        */

        abort_unless(
            (int) $selectedExam->school_class_id ===
            (int) $selectedClass->id,
            422,
            'The selected examination does not belong to the selected class.'
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD STUDENT
        |--------------------------------------------------------------------------
        */

        $selectedStudent = Student::query()
            ->where(
                'id',
                $validated['student_id']
            )
            ->where(
                'school_class_id',
                $selectedClass->id
            )
            ->where(
                'status',
                'Active'
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | DETERMINE STUDENT STREAM
        |--------------------------------------------------------------------------
        */

        $selectedStream = null;

        if ($selectedStudent->stream_id) {

            $selectedStream = Stream::query()
                ->where(
                    'id',
                    $selectedStudent->stream_id
                )
                ->where(
                    'school_class_id',
                    $selectedClass->id
                )
                ->first();

            if ($selectedStream) {

                $this->authorizeStream(
                    $selectedClass,
                    $selectedStream
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD SUBJECTS
        |--------------------------------------------------------------------------
        |
        | Only subjects allocated to this class/stream are displayed.
        |
        | This prevents:
        |
        | Class 7 subjects -> appearing in Form 1
        | Primary subjects -> appearing in Secondary
        | North subjects  -> appearing in West
        |
        */

        $subjectQuery = Subject::query()
            ->whereIn(
                'id',
                function ($query) use (
                    $selectedClass,
                    $selectedStream
                ) {

                    $query->select(
                        'subject_id'
                    )
                    ->from(
                        'subject_allocations'
                    )
                    ->where(
                        'school_class_id',
                        $selectedClass->id
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | STREAM-SPECIFIC ALLOCATION
                    |--------------------------------------------------------------------------
                    */

                    if ($selectedStream) {

                        $query->where(
                            'stream_id',
                            $selectedStream->id
                        );
                    }
                }
            )
            ->orderBy('name');

        $subjects = $subjectQuery->get();


        /*
        |--------------------------------------------------------------------------
        | LOAD GRADING SYSTEM
        |--------------------------------------------------------------------------
        */

        $grades = Grade::query()
            ->orderByDesc('min_mark')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ACTIVE STUDENTS IN CLASS
        |--------------------------------------------------------------------------
        */

        $classStudentIds = Student::query()
            ->where(
                'school_class_id',
                $selectedClass->id
            )
            ->where(
                'status',
                'Active'
            )
            ->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | LOAD ALL EXAM MARKS FOR CLASS
        |--------------------------------------------------------------------------
        */

        $allClassMarks = ExamMark::query()
            ->where(
                'exam_id',
                $selectedExam->id
            )
            ->whereIn(
                'student_id',
                $classStudentIds
            )
            ->get()
            ->groupBy('student_id');


        /*
        |--------------------------------------------------------------------------
        | SELECTED STUDENT MARKS
        |--------------------------------------------------------------------------
        */

        $studentMarks = $allClassMarks
            ->get(
                $selectedStudent->id,
                collect()
            )
            ->keyBy('subject_id');


        /*
        |--------------------------------------------------------------------------
        | SUBJECT RESULTS
        |--------------------------------------------------------------------------
        */

        $subjectResults = [];

        $totalMarks = 0;

        $subjectsAttempted = 0;

        $totalPoints = 0;


        foreach ($subjects as $subject) {

            $markRecord = $studentMarks->get(
                $subject->id
            );


            /*
            |--------------------------------------------------------------------------
            | DEFAULT RESULT
            |--------------------------------------------------------------------------
            */

            $result = [
                'mark' => null,

                'grade' => null,

                'points' => 0,

                'remarks' => null,

                'absent' => false,
            ];


            /*
            |--------------------------------------------------------------------------
            | NO MARK RECORD
            |--------------------------------------------------------------------------
            */

            if (!$markRecord) {

                $subjectResults[$subject->id] =
                    $result;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | REMARKS
            |--------------------------------------------------------------------------
            */

            $remarks = trim(
                (string) $markRecord->remarks
            );


            /*
            |--------------------------------------------------------------------------
            | ABSENT
            |--------------------------------------------------------------------------
            */

            $isAbsent =
                (bool) $markRecord->absent ||
                strtolower($remarks) === 'absent';


            /*
            |--------------------------------------------------------------------------
            | NO MARK
            |--------------------------------------------------------------------------
            */

            if ($markRecord->marks === null) {

                $subjectResults[$subject->id] = [

                    'mark' => null,

                    'grade' => null,

                    'points' => 0,

                    'remarks' =>
                        $remarks ?: null,

                    'absent' => $isAbsent,
                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | MARK
            |--------------------------------------------------------------------------
            */

            $mark = (float) $markRecord->marks;


            /*
            |--------------------------------------------------------------------------
            | CALCULATE GRADE
            |--------------------------------------------------------------------------
            */

            $gradeData = $this->calculateGrade(
                $mark,
                $grades
            );


            /*
            |--------------------------------------------------------------------------
            | TOTALS
            |--------------------------------------------------------------------------
            */

            $totalMarks += $mark;

            $subjectsAttempted++;

            $totalPoints += $gradeData['points'];


            /*
            |--------------------------------------------------------------------------
            | FINAL REMARKS
            |--------------------------------------------------------------------------
            */

            $finalRemarks =
                $gradeData['remarks']
                ?? ($remarks ?: null);


            /*
            |--------------------------------------------------------------------------
            | STORE RESULT
            |--------------------------------------------------------------------------
            */

            $subjectResults[$subject->id] = [

                'mark' => $mark,

                'grade' =>
                    $gradeData['grade'],

                'points' =>
                    $gradeData['points'],

                'remarks' =>
                    $finalRemarks,

                'absent' =>
                    $isAbsent,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | MEAN
        |--------------------------------------------------------------------------
        */

        $mean = $subjectsAttempted > 0
            ? round(
                $totalMarks /
                $subjectsAttempted,
                2
            )
            : null;


        /*
        |--------------------------------------------------------------------------
        | OVERALL GRADE
        |--------------------------------------------------------------------------
        */

        $overallGradeData = $mean !== null
            ? $this->calculateGrade(
                $mean,
                $grades
            )
            : [
                'grade' => null,
                'points' => 0,
                'remarks' => null,
                'color' => null,
            ];


        $overallGrade =
            $overallGradeData['grade'];


        /*
        |--------------------------------------------------------------------------
        | CLASS PERFORMANCE
        |--------------------------------------------------------------------------
        */

        $classResults = [];


        foreach ($classStudentIds as $studentId) {

            $marks = $allClassMarks
                ->get(
                    $studentId,
                    collect()
                )
                ->whereNotNull('marks');


            /*
            |--------------------------------------------------------------------------
            | NO MARKS
            |--------------------------------------------------------------------------
            */

            if ($marks->isEmpty()) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            $studentTotal =
                (float) $marks->sum(
                    'marks'
                );


            /*
            |--------------------------------------------------------------------------
            | MEAN
            |--------------------------------------------------------------------------
            */

            $studentMean = round(
                $studentTotal /
                $marks->count(),
                2
            );


            /*
            |--------------------------------------------------------------------------
            | POINTS
            |--------------------------------------------------------------------------
            */

            $studentPoints = 0;


            foreach ($marks as $markRecord) {

                $markGrade =
                    $this->calculateGrade(
                        (float) $markRecord->marks,
                        $grades
                    );

                $studentPoints +=
                    $markGrade['points'];
            }


            /*
            |--------------------------------------------------------------------------
            | STORE
            |--------------------------------------------------------------------------
            */

            $classResults[] = [

                'student_id' =>
                    $studentId,

                'total' =>
                    $studentTotal,

                'mean' =>
                    $studentMean,

                'points' =>
                    $studentPoints,

                'subjects' =>
                    $marks->count(),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | SORT CLASS RESULTS
        |--------------------------------------------------------------------------
        */

        usort(
            $classResults,
            function ($a, $b) {

                return [
                    $b['total'],
                    $b['mean'],
                    $b['points'],
                ]
                <=>
                [
                    $a['total'],
                    $a['mean'],
                    $a['points'],
                ];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CALCULATE POSITION
        |--------------------------------------------------------------------------
        */

        $position = null;

        $previous = null;

        $currentPosition = 0;


        foreach (
            $classResults
            as $index => $result
        ) {

            $current = [
                $result['total'],
                $result['mean'],
                $result['points'],
            ];


            if ($previous !== $current) {

                $currentPosition =
                    $index + 1;
            }


            if (
                (int) $result['student_id'] ===
                (int) $selectedStudent->id
            ) {

                $position =
                    $currentPosition;
            }


            $previous = $current;
        }


        /*
        |--------------------------------------------------------------------------
        | CLASS STATISTICS
        |--------------------------------------------------------------------------
        */

        $classCollection =
            collect($classResults);


        $classSize =
            $classCollection->count();


        $classAverage =
            $classCollection->isNotEmpty()
                ? round(
                    $classCollection->avg('mean'),
                    2
                )
                : null;


        $classHighest =
            $classCollection->isNotEmpty()
                ? $classCollection->max('total')
                : null;


        $classLowest =
            $classCollection->isNotEmpty()
                ? $classCollection->min('total')
                : null;


        /*
        |--------------------------------------------------------------------------
        | STREAM SIZE
        |--------------------------------------------------------------------------
        */

        $streamSize = 0;


        if ($selectedStream) {

            $streamSize = Student::query()
                ->where(
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
                ->count();
        }


        /*
        |--------------------------------------------------------------------------
        | REPORT DATA
        |--------------------------------------------------------------------------
        */

        $report = [

            'student' =>
                $selectedStudent,

            'subjects' =>
                $subjects,

            'subjectResults' =>
                $subjectResults,

            'total' =>
                $totalMarks,

            'mean' =>
                $mean,

            'grade' =>
                $overallGrade,

            'gradeRemarks' =>
                $overallGradeData['remarks'],

            'gradeColor' =>
                $overallGradeData['color'],

            'points' =>
                $totalPoints,

            'position' =>
                $position,

            'classSize' =>
                $classSize,

            'stream' =>
                $selectedStream,

            'streamSize' =>
                $streamSize,

            'classAverage' =>
                $classAverage,

            'classHighest' =>
                $classHighest,

            'classLowest' =>
                $classLowest,
        ];


        /*
        |--------------------------------------------------------------------------
        | FORM DATA
        |--------------------------------------------------------------------------
        */

        $exams =
            $this->accessibleExams(
                $user
            );


        $classes =
            $this->accessibleClasses(
                $user
            );


        $streams =
            $this->accessibleStreams(
                $selectedClass
            );


        $students = Student::query()
            ->where(
                'school_class_id',
                $selectedClass->id
            )
            ->where(
                'status',
                'Active'
            )
            ->orderByRaw(
                'COALESCE(admission_number, admission_no)'
            )
            ->orderBy('first_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'student-report-card.index',
            [
                'exams' =>
                    $exams,

                'classes' =>
                    $classes,

                'streams' =>
                    $streams,

                'students' =>
                    $students,

                'selectedExam' =>
                    $selectedExam,

                'selectedClass' =>
                    $selectedClass,

                'selectedStream' =>
                    $selectedStream,

                'selectedStudent' =>
                    $selectedStudent,

                'report' =>
                    $report,

                'school' =>
                    School::first(),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DYNAMIC GRADING
    |--------------------------------------------------------------------------
    */

    private function calculateGrade(
        float $mark,
        $grades = null
    ): array {

        /*
        |--------------------------------------------------------------------------
        | LOAD GRADES
        |--------------------------------------------------------------------------
        */

        if ($grades === null) {

            $grades = Grade::query()
                ->orderByDesc('min_mark')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | FIND MATCHING GRADE
        |--------------------------------------------------------------------------
        */

        $grade = $grades->first(
            function ($grade) use ($mark) {

                return
                    $mark >=
                    (float) $grade->min_mark
                    &&
                    $mark <=
                    (float) $grade->max_mark;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NO GRADE
        |--------------------------------------------------------------------------
        */

        if (!$grade) {

            return [

                'grade' => null,

                'points' => 0,

                'remarks' => null,

                'color' => null,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RETURN GRADE
        |--------------------------------------------------------------------------
        */

        return [

            'grade' =>
                $grade->grade,

            'points' =>
                (float) $grade->points,

            'remarks' =>
                $grade->remarks,

            'color' =>
                $grade->color,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATOR
    |--------------------------------------------------------------------------
    */

    private function isAdministrator($user): bool
    {
        return $user->hasAnyRole([
            'Super Admin',
            'Admin',
            'School Admin',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSIBLE CLASSES
    |--------------------------------------------------------------------------
    */

    private function accessibleClasses($user)
    {
        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATORS
        |--------------------------------------------------------------------------
        */

        if ($this->isAdministrator($user)) {

            return SchoolClass::query()
                ->orderBy('name')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | TEACHER
        |--------------------------------------------------------------------------
        */

        if ($user->teacher) {

            return SchoolClass::query()
                ->whereHas(
                    'streams',
                    function ($query) use ($user) {

                        $query->whereHas(
                            'teacherAllocations',
                            function (
                                $allocationQuery
                            ) use ($user) {

                                $allocationQuery->where(
                                    'teacher_id',
                                    $user->teacher->id
                                );
                            }
                        );
                    }
                )
                ->orderBy('name')
                ->get();
        }


        return collect();
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSIBLE STREAMS
    |--------------------------------------------------------------------------
    */

    private function accessibleStreams(
        SchoolClass $schoolClass
    ) {

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATORS
        |--------------------------------------------------------------------------
        */

        if ($this->isAdministrator($user)) {

            return Stream::query()
                ->where(
                    'school_class_id',
                    $schoolClass->id
                )
                ->orderBy('name')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | NO TEACHER
        |--------------------------------------------------------------------------
        */

        if (!$user->teacher) {

            return collect();
        }


        /*
        |--------------------------------------------------------------------------
        | TEACHER STREAMS
        |--------------------------------------------------------------------------
        */

        return Stream::query()
            ->where(
                'school_class_id',
                $schoolClass->id
            )
            ->whereHas(
                'teacherAllocations',
                function ($query) use ($user) {

                    $query->where(
                        'teacher_id',
                        $user->teacher->id
                    );
                }
            )
            ->orderBy('name')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE CLASS
    |--------------------------------------------------------------------------
    */

    private function authorizeClass(
        SchoolClass $schoolClass
    ): void {

        abort_unless(
            $this->accessibleClasses(
                Auth::user()
            )->contains(
                'id',
                $schoolClass->id
            ),
            403,
            'You are not authorized to access this class.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE STREAM
    |--------------------------------------------------------------------------
    */

    private function authorizeStream(
        SchoolClass $schoolClass,
        Stream $stream
    ): void {

        /*
        |--------------------------------------------------------------------------
        | STREAM MUST BELONG TO CLASS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $stream->school_class_id ===
            (int) $schoolClass->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | USER MUST HAVE ACCESS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $this->accessibleStreams(
                $schoolClass
            )->contains(
                'id',
                $stream->id
            ),
            403,
            'You are not authorized to access this stream.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSIBLE EXAMS
    |--------------------------------------------------------------------------
    */

    private function accessibleExams($user)
    {
        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATORS
        |--------------------------------------------------------------------------
        */

        if ($this->isAdministrator($user)) {

            return $this->getExams();
        }


        /*
        |--------------------------------------------------------------------------
        | TEACHER CLASS ACCESS
        |--------------------------------------------------------------------------
        */

        $classIds =
            $this->accessibleClasses(
                $user
            )->pluck('id');


        return Exam::with([
            'academicYear',
            'term',
            'schoolClass',
        ])
        ->whereIn(
            'school_class_id',
            $classIds
        )
        ->orderByDesc('start_date')
        ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | GET ALL EXAMS
    |--------------------------------------------------------------------------
    */

    private function getExams()
    {
        return Exam::with([
            'academicYear',
            'term',
            'schoolClass',
        ])
        ->orderByDesc('start_date')
        ->get();
    }
}