<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\StudentPromotion;
use App\Models\StudentResult;
use App\Models\ExamMark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPromotionController extends Controller
{
    /**
     * Display the student promotion page.
     */
    public function index(Request $request)
    {
        $years = AcademicYear::orderBy('name')->get();

        $classes = SchoolClass::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | Load streams with their classes
        |--------------------------------------------------------------------------
        |
        | This allows the system to display:
        |
        | 7 Blue
        | 7 Green
        | 8 Blue
        | 8 Green
        |
        */

        $streams = Stream::with('schoolClass')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        $exams = Exam::orderBy('name')->get();

        $students = collect();

        /*
        |--------------------------------------------------------------------------
        | Load students when examination and stream are selected
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('exam_id') &&
            $request->filled('stream_id')
        ) {

            $stream = Stream::with('schoolClass')
                ->findOrFail($request->stream_id);

            /*
            |--------------------------------------------------------------------------
            | Get students belonging to selected class AND stream
            |--------------------------------------------------------------------------
            */

            $students = Student::with([
                'schoolClass',
                'stream',
                'academicYear',
            ])
                ->where(
                    'school_class_id',
                    $stream->school_class_id
                )
                ->where(
                    'stream_id',
                    $stream->id
                )
                ->orderBy('admission_no')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Load generated results
            |--------------------------------------------------------------------------
            */

            $results = StudentResult::where(
                'exam_id',
                $request->exam_id
            )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->get()
                ->keyBy('student_id');

            /*
            |--------------------------------------------------------------------------
            | Attach result to each student
            |--------------------------------------------------------------------------
            */

            foreach ($students as $student) {

                $student->result =
                    $results->get($student->id);
            }
        }

        return view(
            'student_promotions.index',
            compact(
                'students',
                'classes',
                'streams',
                'years',
                'exams'
            )
        );
    }


    /**
     * Generate examination results from exam marks.
     */
    public function generateResults(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'stream_id' => 'required|exists:streams,id',
        ]);

        $exam = Exam::findOrFail(
            $validated['exam_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Get selected stream
        |--------------------------------------------------------------------------
        */

        $stream = Stream::with('schoolClass')
            ->findOrFail(
                $validated['stream_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Get students in selected class and stream
        |--------------------------------------------------------------------------
        */

        $students = Student::where(
            'school_class_id',
            $stream->school_class_id
        )
            ->where(
                'stream_id',
                $stream->id
            )
            ->get();

        if ($students->isEmpty()) {

            return redirect()
                ->route(
                    'student-promotions.index',
                    [
                        'exam_id' =>
                            $validated['exam_id'],

                        'stream_id' =>
                            $validated['stream_id'],
                    ]
                )
                ->with(
                    'error',
                    'No students were found in the selected class and stream.'
                );
        }

        $generated = 0;

        DB::transaction(function () use (
            $students,
            $exam,
            &$generated
        ) {

            /*
            |--------------------------------------------------------------------------
            | Generate result for each student
            |--------------------------------------------------------------------------
            */

            foreach ($students as $student) {

                /*
                |--------------------------------------------------------------------------
                | Get examination marks
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

                if ($marks->isEmpty()) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Calculate total marks
                |--------------------------------------------------------------------------
                */

                $totalMarks = $marks->sum(
                    function ($mark) {
                        return (float) $mark->marks;
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | Calculate mean score
                |--------------------------------------------------------------------------
                */

                $subjectCount = $marks->count();

                $meanScore = $subjectCount > 0
                    ? round(
                        $totalMarks / $subjectCount,
                        2
                    )
                    : 0;

                /*
                |--------------------------------------------------------------------------
                | Calculate total points
                |--------------------------------------------------------------------------
                */

                $points = $marks->sum(
                    function ($mark) {
                        return (float) (
                            $mark->points ?? 0
                        );
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | Calculate mean grade
                |--------------------------------------------------------------------------
                */

                $meanGrade =
                    $this->calculateGrade(
                        $meanScore
                    );

                /*
                |--------------------------------------------------------------------------
                | Save or update result
                |--------------------------------------------------------------------------
                */

                StudentResult::updateOrCreate(
                    [
                        'exam_id' =>
                            $exam->id,

                        'student_id' =>
                            $student->id,
                    ],
                    [
                        'total_marks' =>
                            $totalMarks,

                        'mean_score' =>
                            $meanScore,

                        'mean_grade' =>
                            $meanGrade,

                        'points' =>
                            $points,

                        'position' =>
                            null,
                    ]
                );

                $generated++;
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate positions
            |--------------------------------------------------------------------------
            |
            | Students are ranked only within the selected stream.
            |
            */

            $results = StudentResult::where(
                'exam_id',
                $exam->id
            )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->orderByDesc('mean_score')
                ->orderBy('student_id')
                ->get();

            $position = 1;

            foreach ($results as $result) {

                $result->update([
                    'position' => $position,
                ]);

                $position++;
            }
        });

        return redirect()
            ->route(
                'student-promotions.index',
                [
                    'exam_id' =>
                        $validated['exam_id'],

                    'stream_id' =>
                        $validated['stream_id'],
                ]
            )
            ->with(
                'success',
                $generated .
                ' student result(s) generated successfully.'
            );
    }


    /**
     * Calculate mean grade.
     */
    private function calculateGrade(
        float $meanScore
    ): string {

        return match (true) {

            $meanScore >= 80 => 'A',

            $meanScore >= 75 => 'A-',

            $meanScore >= 70 => 'B+',

            $meanScore >= 65 => 'B',

            $meanScore >= 60 => 'B-',

            $meanScore >= 55 => 'C+',

            $meanScore >= 50 => 'C',

            $meanScore >= 45 => 'C-',

            $meanScore >= 40 => 'D+',

            $meanScore >= 30 => 'D',

            default => 'E',
        };
    }


    /**
     * Promote selected students.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'exam_id' =>
                'required|exists:exams,id',

            'students' =>
                'required|array|min:1',

            'students.*' =>
                'required|exists:students,id',

            'to_class_id' =>
                'required|exists:school_classes,id',

            'to_stream_id' =>
                'nullable|exists:streams,id',

            'to_academic_year_id' =>
                'required|exists:academic_years,id',

            'promotion_date' =>
                'required|date',

            'remarks' =>
                'nullable|string|max:500',
        ]);

        $promoted = 0;

        DB::transaction(function () use (
            $validated,
            &$promoted
        ) {

            foreach (
                $validated['students']
                as $studentId
            ) {

                $student =
                    Student::findOrFail(
                        $studentId
                    );

                /*
                |--------------------------------------------------------------------------
                | Get examination result
                |--------------------------------------------------------------------------
                */

                $result = StudentResult::where(
                    'exam_id',
                    $validated['exam_id']
                )
                    ->where(
                        'student_id',
                        $student->id
                    )
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | Student must have a result
                |--------------------------------------------------------------------------
                */

                if (!$result) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Promotion requirement
                |--------------------------------------------------------------------------
                |
                | Mean score of 50 or above.
                |
                */

                if ($result->mean_score < 50) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Prevent duplicate promotion
                |--------------------------------------------------------------------------
                */

                $alreadyPromoted =
                    StudentPromotion::where(
                        'student_id',
                        $student->id
                    )
                        ->where(
                            'to_academic_year_id',
                            $validated[
                                'to_academic_year_id'
                            ]
                        )
                        ->exists();

                if ($alreadyPromoted) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Save promotion history
                |--------------------------------------------------------------------------
                */

                StudentPromotion::create([

                    'student_id' =>
                        $student->id,

                    'from_class_id' =>
                        $student->school_class_id,

                    'to_class_id' =>
                        $validated['to_class_id'],

                    'from_stream_id' =>
                        $student->stream_id,

                    'to_stream_id' =>
                        $validated[
                            'to_stream_id'
                        ] ?? null,

                    'from_academic_year_id' =>
                        $student->academic_year_id,

                    'to_academic_year_id' =>
                        $validated[
                            'to_academic_year_id'
                        ],

                    'promotion_date' =>
                        $validated[
                            'promotion_date'
                        ],

                    'remarks' =>
                        $validated[
                            'remarks'
                        ] ?? null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update student's current placement
                |--------------------------------------------------------------------------
                */

                $student->update([

                    'school_class_id' =>
                        $validated[
                            'to_class_id'
                        ],

                    'stream_id' =>
                        $validated[
                            'to_stream_id'
                        ] ?? null,

                    'academic_year_id' =>
                        $validated[
                            'to_academic_year_id'
                        ],
                ]);

                $promoted++;
            }
        });

        return redirect()
            ->route(
                'student-promotions.index'
            )
            ->with(
                'success',
                $promoted .
                ' student(s) promoted successfully.'
            );
    }


    /**
     * Display promotion history.
     */
    public function history()
    {
        $promotions =
            StudentPromotion::with([
                'student',
                'fromClass',
                'toClass',
                'fromYear',
                'toYear',
                'fromStream',
                'toStream',
            ])
                ->latest('promotion_date')
                ->paginate(20);

        return view(
            'student_promotions.history',
            compact('promotions')
        );
    }
}