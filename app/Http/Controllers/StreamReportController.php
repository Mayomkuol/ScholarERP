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

class StreamReportController extends Controller
{
    /**
     * Display stream performance report page.
     */
    public function index()
    {
        $user = Auth::user();

        return view('stream-report.index', [
            'exams' => $this->accessibleExams($user),

            'classes' => $this->accessibleClasses($user),

            'streams' => collect(),

            'report' => null,

            'selectedExam' => null,

            'selectedClass' => null,

            'selectedStream' => null,

            'school' => School::first(),
        ]);
    }


    /**
     * Return streams available for a class.
     */
    public function streamsByClass(SchoolClass $schoolClass)
    {
        $user = Auth::user();

        abort_unless(
            $this->canAccessClass($user, $schoolClass),
            403,
            'You are not authorized to view streams for this class.'
        );

        return response()->json(
            $this->accessibleStreams($user, $schoolClass)
                ->map(function ($stream) {
                    return [
                        'id' => $stream->id,
                        'name' => $stream->name,
                    ];
                })
                ->values()
        );
    }


    /**
     * Generate stream performance report.
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

            'stream_id' => [
                'required',
                'integer',
                'exists:streams,id',
            ],
        ]);


        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | EXAM
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
        | CLASS
        |--------------------------------------------------------------------------
        */

        $selectedClass = SchoolClass::findOrFail(
            $validated['school_class_id']
        );


        /*
        |--------------------------------------------------------------------------
        | VERIFY EXAM BELONGS TO CLASS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $selectedExam->school_class_id ===
            (int) $selectedClass->id,
            422,
            'The selected examination does not belong to the selected class.'
        );


        /*
        |--------------------------------------------------------------------------
        | USER CLASS ACCESS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $this->canAccessClass(
                $user,
                $selectedClass
            ),
            403,
            'You are not authorized to view reports for this class.'
        );


        /*
        |--------------------------------------------------------------------------
        | STREAM
        |--------------------------------------------------------------------------
        */

        $selectedStream = Stream::query()
            ->whereKey(
                $validated['stream_id']
            )
            ->where(
                'school_class_id',
                $selectedClass->id
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | USER STREAM ACCESS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $this->canAccessStream(
                $user,
                $selectedStream,
                $selectedExam
            ),
            403,
            'You are not authorized to view this stream.'
        );


        /*
        |--------------------------------------------------------------------------
        | SUBJECTS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Only subjects allocated to the selected class are loaded.
        |
        | If a subject is specifically allocated to the selected stream,
        | it is included.
        |
        | If a subject is allocated to the whole class (stream_id NULL),
        | it is also included.
        |
        | This prevents Primary subjects from appearing in Secondary
        | reports simply because they exist in the subjects table.
        |
        */

        $subjects = Subject::query()
            ->whereIn('id', function ($query) use (
                $selectedClass,
                $selectedStream
            ) {

                $query->select('subject_id')
                    ->from('subject_allocations')
                    ->where(
                        'school_class_id',
                        $selectedClass->id
                    )
                    ->where(function ($q) use ($selectedStream) {

                        $q->where(
                            'stream_id',
                            $selectedStream->id
                        )
                        ->orWhereNull('stream_id');

                    });

            })
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STUDENTS
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
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
            ->orderBy(
                'admission_no'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NO STUDENTS
        |--------------------------------------------------------------------------
        */

        if ($students->isEmpty()) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No active students found in this stream.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | MARKS
        |--------------------------------------------------------------------------
        */

        $marks = ExamMark::query()
            ->where(
                'exam_id',
                $selectedExam->id
            )
            ->whereIn(
                'student_id',
                $students->pluck('id')
            )
            ->get()
            ->groupBy(
                'student_id'
            );


        /*
        |--------------------------------------------------------------------------
        | STUDENT RESULTS
        |--------------------------------------------------------------------------
        */

        $studentResults = [];


        foreach ($students as $student) {

            $studentMarks = $marks->get(
                $student->id,
                collect()
            );


            $total = 0;

            $subjectsAttempted = 0;

            $totalPoints = 0;

            $subjectResults = [];


            /*
            |--------------------------------------------------------------------------
            | SUBJECT RESULTS
            |--------------------------------------------------------------------------
            */

            foreach ($subjects as $subject) {

                $markRecord = $studentMarks->firstWhere(
                    'subject_id',
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

                    $subjectResults[
                        $subject->id
                    ] = $result;

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | REMARKS
                |--------------------------------------------------------------------------
                */

                $remarks = trim(
                    (string) (
                        $markRecord->remarks ?? ''
                    )
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

                    $subjectResults[
                        $subject->id
                    ] = [

                        'mark' => null,

                        'grade' => null,

                        'points' => 0,

                        'remarks' => $remarks ?: null,

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
                | GRADE
                |--------------------------------------------------------------------------
                |
                | Grade is calculated from App\Models\Grade.
                |
                */

                $gradeData = $this->calculateGrade(
                    $mark
                );


                /*
                |--------------------------------------------------------------------------
                | TOTALS
                |--------------------------------------------------------------------------
                */

                $total += $mark;

                $subjectsAttempted++;

                $totalPoints += $gradeData['points'];


                /*
                |--------------------------------------------------------------------------
                | SUBJECT RESULT
                |--------------------------------------------------------------------------
                */

                $subjectResults[
                    $subject->id
                ] = [

                    'mark' => $mark,

                    'grade' => $gradeData['grade'],

                    'points' => $gradeData['points'],

                    'remarks' =>
                        $gradeData['remarks']
                        ?: ($remarks ?: null),

                    'absent' => $isAbsent,

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | MEAN
            |--------------------------------------------------------------------------
            */

            $mean = $subjectsAttempted > 0

                ? round(
                    $total / $subjectsAttempted,
                    2
                )

                : null;


            /*
            |--------------------------------------------------------------------------
            | OVERALL GRADE
            |--------------------------------------------------------------------------
            */

            $overallGrade = $mean !== null

                ? $this->calculateGrade(
                    $mean
                )['grade']

                : null;


            /*
            |--------------------------------------------------------------------------
            | STUDENT RESULT
            |--------------------------------------------------------------------------
            |
            | Admission number is explicitly included here.
            |
            */

            $studentResults[] = [

                'student' => $student,

                'admission_no' =>
                    $student->admission_number,

                'subjects' => $subjectResults,

                'total' => $total,

                'mean' => $mean,

                'grade' => $overallGrade,

                'points' => $totalPoints,

                'position' => null,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RANK STUDENTS
        |--------------------------------------------------------------------------
        */

        usort(
            $studentResults,
            function ($a, $b) {

                if ($a['total'] != $b['total']) {

                    return $b['total']
                        <=> $a['total'];
                }


                if ($a['mean'] != $b['mean']) {

                    return $b['mean']
                        <=> $a['mean'];
                }


                return $b['points']
                    <=> $a['points'];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | COMPETITION RANKING
        |--------------------------------------------------------------------------
        */

        $previousTotal = null;

        $previousMean = null;

        $previousPoints = null;

        $position = 0;


        foreach (
            $studentResults
            as $index => &$result
        ) {

            $isNewPosition =
                $previousTotal === null ||
                $result['total'] != $previousTotal ||
                $result['mean'] != $previousMean ||
                $result['points'] != $previousPoints;


            if ($isNewPosition) {

                $position = $index + 1;
            }


            $result['position'] = $position;


            $previousTotal =
                $result['total'];

            $previousMean =
                $result['mean'];

            $previousPoints =
                $result['points'];
        }


        unset($result);


        /*
        |--------------------------------------------------------------------------
        | SUBJECT PERFORMANCE
        |--------------------------------------------------------------------------
        */

        $subjectPerformance = [];


        foreach ($subjects as $subject) {

            $subjectMarks = collect(
                $studentResults
            )
                ->map(
                    function ($result) use ($subject) {

                        return
                            $result['subjects']
                                [$subject->id]['mark']
                                ?? null;
                    }
                )
                ->filter(
                    fn ($mark) =>
                        $mark !== null
                )
                ->values();


            $subjectPerformance[
                $subject->id
            ] = [

                'count' =>
                    $subjectMarks->count(),

                'average' =>
                    $subjectMarks->isNotEmpty()

                        ? round(
                            $subjectMarks->average(),
                            2
                        )

                        : null,

                'highest' =>
                    $subjectMarks->isNotEmpty()

                        ? $subjectMarks->max()

                        : null,

                'lowest' =>
                    $subjectMarks->isNotEmpty()

                        ? $subjectMarks->min()

                        : null,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | STREAM AVERAGE
        |--------------------------------------------------------------------------
        */

        $validMeans = collect(
            $studentResults
        )
            ->pluck('mean')
            ->filter(
                fn ($mean) =>
                    $mean !== null
            );


        $streamAverage = $validMeans->isNotEmpty()

            ? round(
                $validMeans->average(),
                2
            )

            : null;


        /*
        |--------------------------------------------------------------------------
        | REPORT
        |--------------------------------------------------------------------------
        */

        $report = [

            'students' =>
                $studentResults,

            'subjects' =>
                $subjects,

            'subjectPerformance' =>
                $subjectPerformance,

            'streamAverage' =>
                $streamAverage,

            'studentCount' =>
                count($studentResults),
        ];


        /*
        |--------------------------------------------------------------------------
        | RETURN REPORT
        |--------------------------------------------------------------------------
        */

        return view(
            'stream-report.index',
            [

                'exams' =>
                    $this->accessibleExams(
                        $user
                    ),

                'classes' =>
                    $this->accessibleClasses(
                        $user
                    ),

                'streams' =>
                    $this->accessibleStreams(
                        $user,
                        $selectedClass
                    ),

                'selectedExam' =>
                    $selectedExam,

                'selectedClass' =>
                    $selectedClass,

                'selectedStream' =>
                    $selectedStream,

                'report' =>
                    $report,

                'school' =>
                    School::first(),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    private function isAdministrator($user): bool
    {
        return $user->hasAnyRole([
            'Super Admin',
            'Admin',
        ]);
    }


    private function accessibleClasses($user)
    {
        if ($this->isAdministrator($user)) {

            return SchoolClass::query()
                ->orderBy('name')
                ->get();
        }


        $teacher = $user->teacher;


        if (!$teacher) {

            return collect();
        }


        return SchoolClass::query()
            ->whereHas(
                'teacherAllocations',
                function ($query) use ($teacher) {

                    $query->where(
                        'teacher_id',
                        $teacher->id
                    );
                }
            )
            ->orderBy('name')
            ->get();
    }


    private function accessibleStreams(
        $user,
        SchoolClass $schoolClass
    ) {

        if ($this->isAdministrator($user)) {

            return Stream::query()
                ->where(
                    'school_class_id',
                    $schoolClass->id
                )
                ->orderBy('name')
                ->get();
        }


        $teacher = $user->teacher;


        if (!$teacher) {

            return collect();
        }


        $allocations = $teacher->allocations()
            ->where(
                'school_class_id',
                $schoolClass->id
            );


        /*
        |--------------------------------------------------------------------------
        | CLASS-WIDE ALLOCATION
        |--------------------------------------------------------------------------
        */

        $hasClassWideAllocation =
            (clone $allocations)
                ->whereNull('stream_id')
                ->exists();


        if ($hasClassWideAllocation) {

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
        | STREAM-SPECIFIC ALLOCATION
        |--------------------------------------------------------------------------
        */

        $streamIds =
            (clone $allocations)
                ->whereNotNull('stream_id')
                ->pluck('stream_id');


        return Stream::query()
            ->where(
                'school_class_id',
                $schoolClass->id
            )
            ->whereIn(
                'id',
                $streamIds
            )
            ->orderBy('name')
            ->get();
    }


    private function canAccessClass(
        $user,
        SchoolClass $schoolClass
    ): bool {

        if ($this->isAdministrator($user)) {

            return true;
        }


        $teacher = $user->teacher;


        if (!$teacher) {

            return false;
        }


        return $teacher->allocations()
            ->where(
                'school_class_id',
                $schoolClass->id
            )
            ->exists();
    }


    private function canAccessStream(
        $user,
        Stream $stream,
        Exam $exam
    ): bool {

        if ($this->isAdministrator($user)) {

            return true;
        }


        $teacher = $user->teacher;


        if (!$teacher) {

            return false;
        }


        return $teacher->allocations()

            ->where(
                'school_class_id',
                $stream->school_class_id
            )

            ->where(
                'academic_year_id',
                $exam->academic_year_id
            )

            ->where(
                'term_id',
                $exam->term_id
            )

            ->where(
                function ($query) use ($stream) {

                    $query
                        ->where(
                            'stream_id',
                            $stream->id
                        )
                        ->orWhereNull(
                            'stream_id'
                        );
                }
            )

            ->exists();
    }


    private function accessibleExams($user)
    {
        if ($this->isAdministrator($user)) {

            return Exam::with([
                'academicYear',
                'term',
                'schoolClass',
            ])
                ->orderByDesc('start_date')
                ->get();
        }


        $classIds =
            $this->accessibleClasses($user)
                ->pluck('id');


        if ($classIds->isEmpty()) {

            return collect();
        }


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


    /**
     * Calculate grade and points.
     *
     * Uses App\Models\Grade.
     *
     * The Grade model contains:
     *
     * min_mark
     * max_mark
     * grade
     * points
     * remarks
     * color
     */
    private function calculateGrade(float $mark): array
    {
        $grade = Grade::calculate($mark);


        if (!$grade) {

            return [

                'grade' => null,

                'points' => 0,

                'remarks' => null,

                'color' => null,
            ];
        }


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
}

