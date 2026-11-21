<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display attendance history.
     */
    public function index(Request $request)
    {
        $query = Attendance::with([
            'student',
            'schoolClass',
            'stream',
            'academicYear',
            'term',
            'user',
        ]);

        $this->applyFilters($query, $request);

        $records = $query
            ->latest('attendance_date')
            ->paginate(20)
            ->withQueryString();

        $baseQuery = clone $query;

        $stats = [
            'present' => (clone $baseQuery)
                ->where('status', 'Present')
                ->count(),

            'absent' => (clone $baseQuery)
                ->where('status', 'Absent')
                ->count(),

            'late' => (clone $baseQuery)
                ->where('status', 'Late')
                ->count(),

            'excused' => (clone $baseQuery)
                ->where('status', 'Excused')
                ->count(),

            'sick' => (clone $baseQuery)
                ->where('status', 'Sick')
                ->count(),

            'total' => (clone $baseQuery)->count(),
        ];

        $attendanceRate = $stats['total'] > 0
            ? round(($stats['present'] / $stats['total']) * 100, 1)
            : 0;

        return view('attendance.index', [
            'records' => $records,
            'stats' => $stats,
            'attendanceRate' => $attendanceRate,

            'years' => AcademicYear::orderBy('name')->get(),
            'terms' => Term::orderBy('name')->get(),

            'classes' => SchoolClass::orderBy('name')->get(),

            'streams' => Stream::with('schoolClass')
                ->orderBy('school_class_id')
                ->orderBy('name')
                ->get(),
        ]);
    }


    /**
     * Apply attendance filters.
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('academic_year_id')) {
            $query->where(
                'academic_year_id',
                $request->academic_year_id
            );
        }

        if ($request->filled('term_id')) {
            $query->where(
                'term_id',
                $request->term_id
            );
        }

        if ($request->filled('school_class_id')) {
            $query->where(
                'school_class_id',
                $request->school_class_id
            );
        }

        if ($request->filled('stream_id')) {
            $query->where(
                'stream_id',
                $request->stream_id
            );
        }

        if ($request->filled('attendance_date')) {
            $query->whereDate(
                'attendance_date',
                $request->attendance_date
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        return $query;
    }


    /**
 * Print attendance report.
 *
 * Supports:
 * - Daily
 * - Weekly
 * - Monthly
 * - Custom date range
 *
 * Supports:
 * - Class
 * - Stream
 */
/**
 * Print attendance report.
 *
 * IMPORTANT:
 * One report always belongs to ONE class/Form.
 *
 * Supports:
 * - Daily
 * - Weekly
 * - Monthly
 * - Custom date range
 *
 * Supports:
 * - One Class/Form
 * - Optional Stream
 */
public function print(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'academic_year_id' => [
            'nullable',
            'exists:academic_years,id',
        ],

        'term_id' => [
            'nullable',
            'exists:terms,id',
        ],

        /*
        |--------------------------------------------------------------------------
        | CLASS IS REQUIRED
        |--------------------------------------------------------------------------
        |
        | This is the most important change.
        | A report can no longer contain multiple classes.
        |
        */

        'school_class_id' => [
            'required',
            'exists:school_classes,id',
        ],

        'stream_id' => [
            'nullable',
            'exists:streams,id',
        ],

        'report_type' => [
            'nullable',
            'in:daily,weekly,monthly,custom',
        ],

        'attendance_date' => [
            'nullable',
            'date',
        ],

        'start_date' => [
            'nullable',
            'date',
        ],

        'end_date' => [
            'nullable',
            'date',
            'after_or_equal:start_date',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | REPORT TYPE
    |--------------------------------------------------------------------------
    */

    $reportType = $request->input(
        'report_type',
        'weekly'
    );


    /*
    |--------------------------------------------------------------------------
    | TODAY
    |--------------------------------------------------------------------------
    */

    $today = now()->startOfDay();


    /*
    |--------------------------------------------------------------------------
    | DATE RANGE
    |--------------------------------------------------------------------------
    */

    if ($reportType === 'daily') {

        $selectedDate = $request->filled('attendance_date')
            ? \Carbon\Carbon::parse(
                $request->attendance_date
            )
            : $today->copy();

        $startDate = $selectedDate
            ->copy()
            ->startOfDay();

        $endDate = $selectedDate
            ->copy()
            ->endOfDay();
    }

    elseif ($reportType === 'monthly') {

        $selectedDate = $request->filled('attendance_date')
            ? \Carbon\Carbon::parse(
                $request->attendance_date
            )
            : $today->copy();

        $startDate = $selectedDate
            ->copy()
            ->startOfMonth()
            ->startOfDay();

        $endDate = $selectedDate
            ->copy()
            ->endOfMonth()
            ->endOfDay();
    }

    elseif ($reportType === 'custom') {

        $startDate = $request->filled('start_date')
            ? \Carbon\Carbon::parse(
                $request->start_date
            )->startOfDay()
            : $today->copy()->startOfWeek();

        $endDate = $request->filled('end_date')
            ? \Carbon\Carbon::parse(
                $request->end_date
            )->endOfDay()
            : $today->copy()->endOfWeek();
    }

    else {

        $selectedDate = $request->filled('attendance_date')
            ? \Carbon\Carbon::parse(
                $request->attendance_date
            )
            : $today->copy();

        $startDate = $selectedDate
            ->copy()
            ->startOfWeek()
            ->startOfDay();

        $endDate = $selectedDate
            ->copy()
            ->endOfWeek()
            ->endOfDay();

        $reportType = 'weekly';
    }


    /*
    |--------------------------------------------------------------------------
    | SELECTED CLASS
    |--------------------------------------------------------------------------
    |
    | The class is REQUIRED.
    |
    */

    $class = SchoolClass::findOrFail(
        $request->school_class_id
    );


    /*
    |--------------------------------------------------------------------------
    | SELECTED STREAM
    |--------------------------------------------------------------------------
    |
    | If a stream was selected, make sure it belongs to the
    | selected class.
    |
    */

    $stream = null;

    if ($request->filled('stream_id')) {

        $stream = Stream::where('id', $request->stream_id)
            ->where(
                'school_class_id',
                $request->school_class_id
            )
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

    $year = $request->filled('academic_year_id')
        ? AcademicYear::find(
            $request->academic_year_id
        )
        : null;


    /*
    |--------------------------------------------------------------------------
    | TERM
    |--------------------------------------------------------------------------
    */

    $term = $request->filled('term_id')
        ? Term::find(
            $request->term_id
        )
        : null;


    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | Students are ALWAYS restricted to the selected class.
    |
    | If a stream is selected, they are additionally restricted
    | to that stream.
    |
    */

    $studentsQuery = Student::with([
        'schoolClass',
        'stream',
    ])
        ->where(
            'school_class_id',
            $class->id
        );


    if ($stream) {

        $studentsQuery->where(
            'stream_id',
            $stream->id
        );
    }


    $students = $studentsQuery
        ->orderBy('admission_no')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | DATE COLLECTION
    |--------------------------------------------------------------------------
    */

    $dates = collect();

    $currentDate = $startDate
        ->copy()
        ->startOfDay();


    while (
        $currentDate->lte(
            $endDate->copy()->startOfDay()
        )
    ) {

        $dates->push(
            $currentDate->copy()
        );

        $currentDate->addDay();
    }


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    |
    | Again, attendance is restricted to ONE class.
    |
    */

    $attendanceQuery = Attendance::with([
        'student',
        'schoolClass',
        'stream',
        'academicYear',
        'term',
        'user',
    ])
        ->whereBetween(
            'attendance_date',
            [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ]
        )
        ->where(
            'school_class_id',
            $class->id
        );


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR FILTER
    |--------------------------------------------------------------------------
    */

    if ($request->filled('academic_year_id')) {

        $attendanceQuery->where(
            'academic_year_id',
            $request->academic_year_id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TERM FILTER
    |--------------------------------------------------------------------------
    */

    if ($request->filled('term_id')) {

        $attendanceQuery->where(
            'term_id',
            $request->term_id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STREAM FILTER
    |--------------------------------------------------------------------------
    */

    if ($stream) {

        $attendanceQuery->where(
            'stream_id',
            $stream->id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET ATTENDANCE
    |--------------------------------------------------------------------------
    */

    $records = $attendanceQuery
        ->orderBy('attendance_date')
        ->orderBy('student_id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE MAP
    |--------------------------------------------------------------------------
    |
    | KEY:
    |
    | student_id_YYYY-MM-DD
    |
    */

    $attendanceMap = [];


    foreach ($records as $attendance) {

        if (
            !$attendance->student_id ||
            !$attendance->attendance_date
        ) {
            continue;
        }


        $dateKey = \Carbon\Carbon::parse(
            $attendance->attendance_date
        )->format('Y-m-d');


        $key =
            $attendance->student_id .
            '_' .
            $dateKey;


        $attendanceMap[$key] = $attendance;
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT STATISTICS
    |--------------------------------------------------------------------------
    */

    $studentStats = [];


    foreach ($students as $student) {

        $present = 0;

        $absent = 0;

        $late = 0;

        $excused = 0;

        $sick = 0;


        foreach ($dates as $date) {

            $key =
                $student->id .
                '_' .
                $date->format('Y-m-d');


            if (!isset($attendanceMap[$key])) {
                continue;
            }


            $attendance =
                $attendanceMap[$key];


            $status = strtolower(
                trim(
                    $attendance->status ?? ''
                )
            );


            switch ($status) {

                case 'present':

                    $present++;

                    break;


                case 'absent':

                    $absent++;

                    break;


                case 'late':

                    $late++;

                    break;


                case 'excused':

                    $excused++;

                    break;


                case 'sick':

                    $sick++;

                    break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL RECORDED
        |--------------------------------------------------------------------------
        */

        $recorded =
            $present +
            $absent +
            $late +
            $excused +
            $sick;


        /*
        |--------------------------------------------------------------------------
        | STUDENT ATTENDANCE RATE
        |--------------------------------------------------------------------------
        |
        | Present + Late = attended.
        |
        */

        $rate = $recorded > 0

            ? round(
                (
                    (
                        $present +
                        $late
                    )
                    /
                    $recorded
                ) * 100,
                1
            )

            : 0;


        $studentStats[$student->id] = [

            'present' =>
                $present,

            'absent' =>
                $absent,

            'late' =>
                $late,

            'excused' =>
                $excused,

            'sick' =>
                $sick,

            'recorded' =>
                $recorded,

            'rate' =>
                $rate,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | OVERALL STATISTICS
    |--------------------------------------------------------------------------
    */

    $stats = [

        'present' =>
            $records
                ->where('status', 'Present')
                ->count(),

        'absent' =>
            $records
                ->where('status', 'Absent')
                ->count(),

        'late' =>
            $records
                ->where('status', 'Late')
                ->count(),

        'excused' =>
            $records
                ->where('status', 'Excused')
                ->count(),

        'sick' =>
            $records
                ->where('status', 'Sick')
                ->count(),

        'total' =>
            $records->count(),
    ];


    /*
    |--------------------------------------------------------------------------
    | OVERALL ATTENDANCE RATE
    |--------------------------------------------------------------------------
    */

    $attendanceRate =
        $stats['total'] > 0

        ? round(
            (
                (
                    $stats['present'] +
                    $stats['late']
                )
                /
                $stats['total']
            ) * 100,
            1
        )

        : 0;


    /*
    |--------------------------------------------------------------------------
    | REPORT TITLE
    |--------------------------------------------------------------------------
    */

    if ($reportType === 'daily') {

        $periodTitle =
            'Daily Attendance Report';
    }

    elseif ($reportType === 'monthly') {

        $periodTitle =
            'Monthly Attendance Report';
    }

    elseif ($reportType === 'custom') {

        $periodTitle =
            'Attendance Report';
    }

    else {

        $periodTitle =
            'Weekly Attendance Report';
    }


    /*
    |--------------------------------------------------------------------------
    | CLASS / STREAM TITLE
    |--------------------------------------------------------------------------
    */

    $className = strtoupper(
        trim($class->name)
    );


    if ($stream) {

        $reportTitle =
            $className .
            ' - ' .
            strtoupper(trim($stream->name)) .
            ' ' .
            $periodTitle;
    }

    else {

        $reportTitle =
            $className .
            ' ' .
            $periodTitle;
    }


    /*
    |--------------------------------------------------------------------------
    | RETURN REPORT
    |--------------------------------------------------------------------------
    */

    return view(
        'attendance.print',
        [

            'students' =>
                $students,

            'dates' =>
                $dates,

            'records' =>
                $records,

            'attendanceMap' =>
                $attendanceMap,

            'studentStats' =>
                $studentStats,

            'stats' =>
                $stats,

            'attendanceRate' =>
                $attendanceRate,

            'year' =>
                $year,

            'term' =>
                $term,

            'class' =>
                $class,

            'stream' =>
                $stream,

            'startDate' =>
                $startDate,

            'endDate' =>
                $endDate,

            'reportType' =>
                $reportType,

            'reportTitle' =>
                $reportTitle,
        ]
    );
}

    /**
     * Export attendance records to CSV.
     */
    public function export(Request $request)
    {
        $query = Attendance::with([
            'student',
            'schoolClass',
            'stream',
            'academicYear',
            'term',
            'user',
        ]);

        $this->applyFilters($query, $request);

        $records = $query
            ->latest('attendance_date')
            ->orderBy('student_id')
            ->get();

        $filename =
            'attendance_' .
            now()->format('Y-m-d_H-i-s') .
            '.csv';

        return response()->streamDownload(
            function () use ($records) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /*
                |--------------------------------------------------------------------------
                | CSV Header
                |--------------------------------------------------------------------------
                */

                fputcsv($handle, [
                    'Admission No',
                    'Student Name',
                    'Class',
                    'Stream',
                    'Academic Year',
                    'Term',
                    'Attendance Date',
                    'Status',
                    'Arrival Time',
                    'Remarks',
                    'Recorded By',
                ]);

                /*
                |--------------------------------------------------------------------------
                | CSV Records
                |--------------------------------------------------------------------------
                */

                foreach ($records as $attendance) {

                    $studentName = trim(
                        ($attendance->student->first_name ?? '') . ' ' .
                        ($attendance->student->middle_name ?? '') . ' ' .
                        ($attendance->student->last_name ?? '')
                    );

                    fputcsv($handle, [
                        $attendance->student->admission_no ?? '',

                        $studentName,

                        $attendance->schoolClass->name ?? '',

                        $attendance->stream->name ?? '',

                        $attendance->academicYear->name ?? '',

                        $attendance->term->name ?? '',

                        $attendance->attendance_date
                            ? date(
                                'd/m/Y',
                                strtotime(
                                    $attendance->attendance_date
                                )
                            )
                            : '',

                        $attendance->status ?? '',

                        $attendance->arrival_time ?? '',

                        $attendance->remarks ?? '',

                        $attendance->user->name ?? '',
                    ]);
                }

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8',
            ]
        );
    }


    /**
     * Display attendance form.
     */
    public function create(Request $request)
    {
        $students = collect();

        if ($request->filled('school_class_id')) {

            $students = Student::with([
                'schoolClass',
                'stream',
            ])
                ->where(
                    'school_class_id',
                    $request->school_class_id
                )
                ->when(
                    $request->filled('stream_id'),
                    function ($query) use ($request) {
                        $query->where(
                            'stream_id',
                            $request->stream_id
                        );
                    }
                )
                ->orderBy('admission_no')
                ->get();
        }

        $streams = Stream::with('schoolClass')
            ->when(
                $request->filled('school_class_id'),
                function ($query) use ($request) {
                    $query->where(
                        'school_class_id',
                        $request->school_class_id
                    );
                }
            )
            ->orderBy('name')
            ->get();

        return view('attendance.create', [

            'students' => $students,

            'years' => AcademicYear::orderBy('name')->get(),

            'terms' => Term::orderBy('name')->get(),

            'classes' => SchoolClass::orderBy('name')->get(),

            'streams' => $streams,
        ]);
    }


    /**
     * Save attendance.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'academic_year_id' =>
                'required|exists:academic_years,id',

            'term_id' =>
                'required|exists:terms,id',

            'school_class_id' =>
                'required|exists:school_classes,id',

            'stream_id' =>
                'nullable|exists:streams,id',

            'attendance_date' =>
                'required|date',

            'student_id' =>
                'required|array|min:1',

            'student_id.*' =>
                'required|exists:students,id',

            'status' =>
                'nullable|array',

            'arrival_time' =>
                'nullable|array',

            'remarks' =>
                'nullable|array',
        ]);

        DB::transaction(function () use ($request) {

            foreach ($request->student_id as $studentId) {

                Attendance::updateOrCreate(

                    [
                        'student_id' =>
                            $studentId,

                        'attendance_date' =>
                            $request->attendance_date,
                    ],

                    [
                        'academic_year_id' =>
                            $request->academic_year_id,

                        'term_id' =>
                            $request->term_id,

                        'school_class_id' =>
                            $request->school_class_id,

                        'stream_id' =>
                            $request->stream_id,

                        'status' =>
                            $request->status[$studentId]
                            ?? 'Present',

                        'arrival_time' =>
                            $request->arrival_time[$studentId]
                            ?? null,

                        'remarks' =>
                            $request->remarks[$studentId]
                            ?? null,

                        'recorded_by' =>
                            auth()->id(),
                    ]
                );
            }
        });

        return redirect()
            ->route('attendance.index')
            ->with(
                'success',
                'Attendance recorded successfully.'
            );
    }


    /**
     * Display attendance details.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load([
            'student',
            'schoolClass',
            'stream',
            'academicYear',
            'term',
            'user',
        ]);

        return view(
            'attendance.show',
            compact('attendance')
        );
    }


    /**
     * Edit attendance.
     */
    public function edit(Attendance $attendance)
    {
        $attendance->load([
            'student',
            'schoolClass',
            'stream',
            'academicYear',
            'term',
        ]);

        $streams = Stream::with('schoolClass')
            ->where(
                'school_class_id',
                $attendance->school_class_id
            )
            ->orderBy('name')
            ->get();

        return view('attendance.edit', [

            'attendance' => $attendance,

            'years' =>
                AcademicYear::orderBy('name')->get(),

            'terms' =>
                Term::orderBy('name')->get(),

            'classes' =>
                SchoolClass::orderBy('name')->get(),

            'streams' =>
                $streams,
        ]);
    }


    /**
     * Update attendance.
     */
    public function update(
        Request $request,
        Attendance $attendance
    ) {
        $validated = $request->validate([

            'status' =>
                'required|in:Present,Absent,Late,Excused,Sick',

            'arrival_time' =>
                'nullable',

            'remarks' =>
                'nullable|string|max:255',
        ]);

        $attendance->update([

            'status' =>
                $validated['status'],

            'arrival_time' =>
                $validated['arrival_time'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('attendance.index')
            ->with(
                'success',
                'Attendance updated successfully.'
            );
    }


    /**
     * Delete attendance.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('attendance.index')
            ->with(
                'success',
                'Attendance deleted successfully.'
            );
    }


    /**
     * Display attendance for one student.
     */
    public function student(Student $student)
    {
        $student->load([
            'schoolClass',
            'stream',
        ]);

        $attendance = Attendance::with([
            'academicYear',
            'term',
            'schoolClass',
            'stream',
        ])
            ->where(
                'student_id',
                $student->id
            )
            ->latest('attendance_date')
            ->paginate(30);

        return view(
            'attendance.student',
            compact(
                'student',
                'attendance'
            )
        );
    }


    /**
     * Attendance register.
     */
    public function register(Request $request)
    {
        $students = collect();

        $days = [];

        if (
            $request->filled('school_class_id') &&
            $request->filled('month') &&
            $request->filled('year')
        ) {

            $students = Student::with([
                'schoolClass',
                'stream',
            ])
                ->where(
                    'school_class_id',
                    $request->school_class_id
                )
                ->orderBy('admission_no')
                ->get();

            $daysInMonth = cal_days_in_month(
                CAL_GREGORIAN,
                $request->month,
                $request->year
            );

            for (
                $i = 1;
                $i <= $daysInMonth;
                $i++
            ) {

                $days[] = sprintf(
                    '%02d',
                    $i
                );
            }

            foreach ($students as $student) {

                $student->attendance =
                    Attendance::where(
                        'student_id',
                        $student->id
                    )
                    ->whereYear(
                        'attendance_date',
                        $request->year
                    )
                    ->whereMonth(
                        'attendance_date',
                        $request->month
                    )
                    ->get()
                    ->keyBy(function ($item) {

                        return date(
                            'd',
                            strtotime(
                                $item->attendance_date
                            )
                        );
                    });
            }
        }

        return view('attendance.register', [

            'students' => $students,

            'days' => $days,

            'classes' =>
                SchoolClass::orderBy('name')->get(),
        ]);
    }

    public function report()
{
    $years = AcademicYear::orderByDesc('id')->get();

    $terms = Term::orderBy('id')->get();

    $classes = SchoolClass::orderBy('name')->get();

    $streams = Stream::orderBy('name')->get();

    return view('attendance.report', compact(
        'years',
        'terms',
        'classes',
        'streams'
    ));
}
}
