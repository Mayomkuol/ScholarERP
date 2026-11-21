<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\SubjectAllocation;
use App\Models\Teacher;
use App\Models\TeacherAllocation;
use App\Models\Term;
use App\Models\Timetable;
use App\Models\TimetablePeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TimetableController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Timetable List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Timetable::with([
            'teacher',
            'subject',
            'schoolClass',
            'stream',
            'academicYear',
            'term',
            'period',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

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

        if ($request->filled('teacher_id')) {
            $query->where(
                'teacher_id',
                $request->teacher_id
            );
        }

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

        /*
        |--------------------------------------------------------------------------
        | School Week Ordering
        |--------------------------------------------------------------------------
        */

        $timetables = $query
            ->orderByRaw("
                CASE day
                    WHEN 'Monday' THEN 1
                    WHEN 'Tuesday' THEN 2
                    WHEN 'Wednesday' THEN 3
                    WHEN 'Thursday' THEN 4
                    WHEN 'Friday' THEN 5
                    WHEN 'Saturday' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('lesson_no')
            ->orderBy('start_time')
            ->get();

        return view('timetables.index', [
            'timetables' => $timetables,

            'teachers' => Teacher::orderBy('first_name')
                ->orderBy('last_name')
                ->get(),

            'classes' => SchoolClass::orderBy('name')
                ->get(),

            'streams' => Stream::orderBy('name')
                ->get(),

            'years' => AcademicYear::orderByDesc('id')
                ->get(),

            'terms' => Term::orderBy('name')
                ->get(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Create Timetable Form
    |--------------------------------------------------------------------------
    |
    | Subjects, teachers and streams are loaded dynamically through AJAX.
    | Only active lesson periods are supplied to the form.
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $periods = TimetablePeriod::where('is_active', true)
            ->where('type', 'lesson')
            ->orderBy('period_number')
            ->orderBy('start_time')
            ->get();

        return view('timetables.create', [

            /*
            |--------------------------------------------------------------------------
            | Dynamically Loaded Data
            |--------------------------------------------------------------------------
            */

            'teachers' => collect(),

            'subjects' => collect(),

            'streams' => collect(),

            /*
            |--------------------------------------------------------------------------
            | Classes
            |--------------------------------------------------------------------------
            */

            'classes' => SchoolClass::orderBy('name')
                ->get(),

            /*
            |--------------------------------------------------------------------------
            | Academic Information
            |--------------------------------------------------------------------------
            */

            'academicYears' => AcademicYear::orderByDesc('id')
                ->get(),

            'terms' => Term::orderBy('name')
                ->get(),

            /*
            |--------------------------------------------------------------------------
            | Active Lesson Periods
            |--------------------------------------------------------------------------
            */

            'periods' => $periods,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Timetable
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],

            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],

            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'stream_id' => [
                'nullable',
                'exists:streams,id',
                Rule::exists('streams', 'id')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'school_class_id',
                            $request->school_class_id
                        );
                    }),
            ],

            'academic_year_id' => [
                'required',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'required',
                'exists:terms,id',
            ],

            'day' => [
                'required',
                Rule::in([
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ]),
            ],

            'period_id' => [
                'required',
                'exists:timetable_periods,id',
            ],

            'room' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Stream/Class Relationship
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['stream_id'])) {

            $streamBelongsToClass = Stream::where(
                'id',
                $validated['stream_id']
            )
                ->where(
                    'school_class_id',
                    $validated['school_class_id']
                )
                ->exists();

            if (!$streamBelongsToClass) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'stream_id' =>
                            'The selected stream does not belong to the selected class.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Selected Period
        |--------------------------------------------------------------------------
        */

        $period = TimetablePeriod::where(
            'id',
            $validated['period_id']
        )
            ->where('is_active', true)
            ->first();

        if (!$period) {

            return back()
                ->withInput()
                ->withErrors([
                    'period_id' =>
                        'The selected timetable period is not active or does not exist.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Only Lesson Periods Can Contain Timetable Lessons
        |--------------------------------------------------------------------------
        */

        if ($period->type !== 'lesson') {

            return back()
                ->withInput()
                ->withErrors([
                    'period_id' =>
                        'Only lesson periods can be assigned to timetable lessons.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Subject Allocation
        |--------------------------------------------------------------------------
        |
        | A timetable subject must already be allocated to the selected
        | class and stream.
        |
        |--------------------------------------------------------------------------
        */

        $subjectAllocationQuery = SubjectAllocation::where(
            'school_class_id',
            $validated['school_class_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Stream Allocation Logic
        |--------------------------------------------------------------------------
        |
        | If a stream is selected:
        |
        | - NULL stream = whole-class subject
        | - matching stream = stream-specific subject
        |
        | If no stream is selected:
        |
        | - only whole-class allocations are valid
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['stream_id'])) {

            $subjectAllocationQuery->where(function ($query) use ($validated) {

                $query->whereNull('stream_id')
                    ->orWhere(
                        'stream_id',
                        $validated['stream_id']
                    );
            });

        } else {

            $subjectAllocationQuery->whereNull('stream_id');
        }

        if (!$subjectAllocationQuery->exists()) {

            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'The selected subject is not allocated to the selected class and stream.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Teacher Allocation
        |--------------------------------------------------------------------------
        |
        | The selected teacher must already be allocated to:
        |
        | Teacher
        | Subject
        | Class
        | Stream
        | Academic Year
        | Term
        |
        |--------------------------------------------------------------------------
        */

        $teacherAllocationQuery = TeacherAllocation::where(
            'teacher_id',
            $validated['teacher_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'school_class_id',
                $validated['school_class_id']
            )
            ->where(
                'academic_year_id',
                $validated['academic_year_id']
            )
            ->where(
                'term_id',
                $validated['term_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Teacher Stream Logic
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['stream_id'])) {

            $teacherAllocationQuery->where(function ($query) use ($validated) {

                $query->whereNull('stream_id')
                    ->orWhere(
                        'stream_id',
                        $validated['stream_id']
                    );
            });

        } else {

            $teacherAllocationQuery->whereNull('stream_id');
        }

        if (!$teacherAllocationQuery->exists()) {

            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'The selected teacher is not allocated to the selected subject, class and stream for this academic year and term.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Lesson Number
        |--------------------------------------------------------------------------
        */

        $lessonNo = $period->period_number;

        if (!$lessonNo) {
            $lessonNo = $period->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Teacher Clash
        |--------------------------------------------------------------------------
        |
        | A teacher cannot teach two lessons at the same time.
        |--------------------------------------------------------------------------
        */

        $teacherConflict = Timetable::where(
            'teacher_id',
            $validated['teacher_id']
        )
            ->where(
                'day',
                $validated['day']
            )
            ->where(
                'period_id',
                $validated['period_id']
            )
            ->where(
                'academic_year_id',
                $validated['academic_year_id']
            )
            ->where(
                'term_id',
                $validated['term_id']
            )
            ->exists();

        if ($teacherConflict) {

            $teacher = Teacher::find(
                $validated['teacher_id']
            );

            $teacherName = $teacher
                ? trim(
                    $teacher->first_name . ' ' .
                    ($teacher->middle_name ?? '') . ' ' .
                    ($teacher->last_name ?? '')
                )
                : 'This teacher';

            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        $teacherName .
                        ' is already assigned to another lesson on ' .
                        $validated['day'] .
                        ' during this period.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Class / Stream Clash
        |--------------------------------------------------------------------------
        |
        | Whole Class:
        |   Cannot coexist with any lesson for the same class.
        |
        | Stream:
        |   Cannot coexist with:
        |   - whole-class lesson
        |   - same-stream lesson
        |
        | Different streams may have lessons simultaneously.
        |--------------------------------------------------------------------------
        */

        $classConflictQuery = Timetable::where(
            'school_class_id',
            $validated['school_class_id']
        )
            ->where(
                'day',
                $validated['day']
            )
            ->where(
                'period_id',
                $validated['period_id']
            )
            ->where(
                'academic_year_id',
                $validated['academic_year_id']
            )
            ->where(
                'term_id',
                $validated['term_id']
            );


        if (empty($validated['stream_id'])) {

            /*
            |--------------------------------------------------------------------------
            | Whole-Class Lesson
            |--------------------------------------------------------------------------
            |
            | Any existing lesson for this class creates a clash.
            |--------------------------------------------------------------------------
            */

            $classConflict = $classConflictQuery->exists();

        } else {

            /*
            |--------------------------------------------------------------------------
            | Stream Lesson
            |--------------------------------------------------------------------------
            |
            | Only a whole-class lesson or the same stream creates a clash.
            |--------------------------------------------------------------------------
            */

            $classConflict = $classConflictQuery
                ->where(function ($query) use ($validated) {

                    $query->whereNull('stream_id')
                        ->orWhere(
                            'stream_id',
                            $validated['stream_id']
                        );
                })
                ->exists();
        }

        if ($classConflict) {

            return back()
                ->withInput()
                ->withErrors([
                    'school_class_id' =>
                        empty($validated['stream_id'])
                        ? 'This class already has a lesson during the selected period.'
                        : 'This class or stream already has a lesson during the selected period.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Room Clash
        |--------------------------------------------------------------------------
        |
        | A room cannot be used by two lessons simultaneously.
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['room'])) {

            $roomConflict = Timetable::where(
                'room',
                $validated['room']
            )
                ->where(
                    'day',
                    $validated['day']
                )
                ->where(
                    'period_id',
                    $validated['period_id']
                )
                ->where(
                    'academic_year_id',
                    $validated['academic_year_id']
                )
                ->where(
                    'term_id',
                    $validated['term_id']
                )
                ->exists();

            if ($roomConflict) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'room' =>
                            'This room is already being used on ' .
                            $validated['day'] .
                            ' during the selected period.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Prepare Timetable Data
        |--------------------------------------------------------------------------
        |
        | Start and end times are always inherited from the selected period.
        |--------------------------------------------------------------------------
        */

        $timetableData = [

            'teacher_id' =>
                $validated['teacher_id'],

            'subject_id' =>
                $validated['subject_id'],

            'school_class_id' =>
                $validated['school_class_id'],

            'stream_id' =>
                $validated['stream_id'] ?? null,

            'academic_year_id' =>
                $validated['academic_year_id'],

            'term_id' =>
                $validated['term_id'],

            'period_id' =>
                $period->id,

            'day' =>
                $validated['day'],

            'lesson_no' =>
                $lessonNo,

            'start_time' =>
                $period->start_time,

            'end_time' =>
                $period->end_time,

            'room' =>
                $validated['room'] ?? null,

            'lesson_type' =>
                'regular',

            'status' =>
                'draft',
        ];


        /*
        |--------------------------------------------------------------------------
        | Create Timetable Lesson
        |--------------------------------------------------------------------------
        */

        Timetable::create($timetableData);


        return redirect()
            ->route('timetables.index')
            ->with(
                'success',
                'Timetable lesson created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Timetable Lesson
    |--------------------------------------------------------------------------
    */

    public function show(Timetable $timetable)
    {
        $timetable->load([
            'teacher',
            'subject',
            'schoolClass',
            'stream',
            'academicYear',
            'term',
            'period',
        ]);

        return view(
            'timetables.show',
            compact('timetable')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Timetable
    |--------------------------------------------------------------------------
    */

    public function edit(Timetable $timetable)
    {
        $periods = TimetablePeriod::where('is_active', true)
            ->where('type', 'lesson')
            ->orderBy('period_number')
            ->orderBy('start_time')
            ->get();

        return view('timetables.edit', [

            'timetable' =>
                $timetable->load([
                    'teacher',
                    'subject',
                    'schoolClass',
                    'stream',
                    'academicYear',
                    'term',
                    'period',
                ]),

            'teachers' => Teacher::orderBy('first_name')
                ->orderBy('last_name')
                ->get(),

            'subjects' => Subject::orderBy('name')
                ->get(),

            'classes' => SchoolClass::orderBy('name')
                ->get(),

            'streams' => Stream::orderBy('name')
                ->get(),

            'years' => AcademicYear::orderByDesc('id')
                ->get(),

            'terms' => Term::orderBy('name')
                ->get(),

            'periods' => $periods,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Timetable
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Timetable $timetable
    ) {
        /*
        |--------------------------------------------------------------------------
        | Basic Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],

            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],

            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'stream_id' => [
                'nullable',
                'exists:streams,id',
                Rule::exists('streams', 'id')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'school_class_id',
                            $request->school_class_id
                        );
                    }),
            ],

            'academic_year_id' => [
                'required',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'required',
                'exists:terms,id',
            ],

            'day' => [
                'required',
                Rule::in([
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ]),
            ],

            'period_id' => [
                'required',
                'exists:timetable_periods,id',
            ],

            'room' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Stream/Class Relationship
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['stream_id'])) {

            $streamBelongsToClass = Stream::where(
                'id',
                $validated['stream_id']
            )
                ->where(
                    'school_class_id',
                    $validated['school_class_id']
                )
                ->exists();

            if (!$streamBelongsToClass) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'stream_id' =>
                            'The selected stream does not belong to the selected class.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Period
        |--------------------------------------------------------------------------
        */

        $period = TimetablePeriod::where(
            'id',
            $validated['period_id']
        )
            ->where('is_active', true)
            ->first();

        if (!$period) {

            return back()
                ->withInput()
                ->withErrors([
                    'period_id' =>
                        'The selected timetable period is not active or does not exist.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Only Lesson Periods
        |--------------------------------------------------------------------------
        */

        if ($period->type !== 'lesson') {

            return back()
                ->withInput()
                ->withErrors([
                    'period_id' =>
                        'Only lesson periods can be assigned to timetable lessons.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Subject Allocation
        |--------------------------------------------------------------------------
        */

        $subjectAllocationQuery = SubjectAllocation::where(
            'school_class_id',
            $validated['school_class_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            );

        if (!empty($validated['stream_id'])) {

            $subjectAllocationQuery->where(function ($query) use ($validated) {

                $query->whereNull('stream_id')
                    ->orWhere(
                        'stream_id',
                        $validated['stream_id']
                    );
            });

        } else {

            $subjectAllocationQuery->whereNull('stream_id');
        }

        if (!$subjectAllocationQuery->exists()) {

            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'The selected subject is not allocated to the selected class and stream.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Teacher Allocation
        |--------------------------------------------------------------------------
        */

        $teacherAllocationQuery = TeacherAllocation::where(
            'teacher_id',
            $validated['teacher_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'school_class_id',
                $validated['school_class_id']
            )
            ->where(
                'academic_year_id',
                $validated['academic_year_id']
            )
            ->where(
                'term_id',
                $validated['term_id']
            );

        if (!empty($validated['stream_id'])) {

            $teacherAllocationQuery->where(function ($query) use ($validated) {

                $query->whereNull('stream_id')
                    ->orWhere(
                        'stream_id',
                        $validated['stream_id']
                    );
            });

        } else {

            $teacherAllocationQuery->whereNull('stream_id');
        }

        if (!$teacherAllocationQuery->exists()) {

            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'The selected teacher is not allocated to the selected subject, class and stream for this academic year and term.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Teacher Clash
        |--------------------------------------------------------------------------
        */

        $teacherConflict = Timetable::where(
            'teacher_id',
            $validated['teacher_id']
        )
            ->where(
                'day',
                $validated['day']
            )
            ->where(
                'period_id',
                $validated['period_id']
            )
            ->where(
                'academic_year_id',
                $validated['academic_year_id']
            )
            ->where(
                'term_id',
                $validated['term_id']
            )
            ->where(
                'id',
                '!=',
                $timetable->id
            )
            ->exists();

        if ($teacherConflict) {

            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'This teacher is already assigned to another lesson during the selected period.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Class / Stream Clash
        |--------------------------------------------------------------------------
        */

        $classConflictQuery = Timetable::where(
            'school_class_id',
            $validated['school_class_id']
        )
            ->where(
                'day',
                $validated['day']
            )
            ->where(
                'period_id',
                $validated['period_id']
            )
            ->where(
                'academic_year_id',
                $validated['academic_year_id']
            )
            ->where(
                'term_id',
                $validated['term_id']
            )
            ->where(
                'id',
                '!=',
                $timetable->id
            );


        if (empty($validated['stream_id'])) {

            /*
            |--------------------------------------------------------------------------
            | Whole-Class Lesson
            |--------------------------------------------------------------------------
            */

            $classConflict = $classConflictQuery->exists();

        } else {

            /*
            |--------------------------------------------------------------------------
            | Stream Lesson
            |--------------------------------------------------------------------------
            */

            $classConflict = $classConflictQuery
                ->where(function ($query) use ($validated) {

                    $query->whereNull('stream_id')
                        ->orWhere(
                            'stream_id',
                            $validated['stream_id']
                        );
                })
                ->exists();
        }

        if ($classConflict) {

            return back()
                ->withInput()
                ->withErrors([
                    'school_class_id' =>
                        empty($validated['stream_id'])
                        ? 'This class already has a lesson during the selected period.'
                        : 'This class or stream already has a lesson during the selected period.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Room Clash
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['room'])) {

            $roomConflict = Timetable::where(
                'room',
                $validated['room']
            )
                ->where(
                    'day',
                    $validated['day']
                )
                ->where(
                    'period_id',
                    $validated['period_id']
                )
                ->where(
                    'academic_year_id',
                    $validated['academic_year_id']
                )
                ->where(
                    'term_id',
                    $validated['term_id']
                )
                ->where(
                    'id',
                    '!=',
                    $timetable->id
                )
                ->exists();

            if ($roomConflict) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'room' =>
                            'This room is already being used during the selected period.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Lesson Number
        |--------------------------------------------------------------------------
        */

        $lessonNo = $period->period_number;

        if (!$lessonNo) {
            $lessonNo = $period->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Timetable
        |--------------------------------------------------------------------------
        */

        $timetable->update([

            'teacher_id' =>
                $validated['teacher_id'],

            'subject_id' =>
                $validated['subject_id'],

            'school_class_id' =>
                $validated['school_class_id'],

            'stream_id' =>
                $validated['stream_id'] ?? null,

            'academic_year_id' =>
                $validated['academic_year_id'],

            'term_id' =>
                $validated['term_id'],

            'period_id' =>
                $period->id,

            'day' =>
                $validated['day'],

            'lesson_no' =>
                $lessonNo,

            'start_time' =>
                $period->start_time,

            'end_time' =>
                $period->end_time,

            'room' =>
                $validated['room'] ?? null,
        ]);


        return redirect()
            ->route('timetables.index')
            ->with(
                'success',
                'Timetable updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Timetable
    |--------------------------------------------------------------------------
    */

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return back()
            ->with(
                'success',
                'Timetable lesson deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX: Get Streams
    |--------------------------------------------------------------------------
    |
    | Returns only streams belonging to the selected class.
    |--------------------------------------------------------------------------
    */

    public function getStreams(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],
        ]);

        $streams = Stream::where(
            'school_class_id',
            $validated['school_class_id']
        )
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return response()->json($streams);
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX: Get Subjects
    |--------------------------------------------------------------------------
    |
    | Returns only subjects allocated to the selected class/stream.
    |--------------------------------------------------------------------------
    */

    public function getSubjects(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'stream_id' => [
                'nullable',
                'exists:streams,id',
            ],

            'academic_year_id' => [
                'required',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'required',
                'exists:terms,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Stream/Class Relationship
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['stream_id'])) {

            $streamExists = Stream::where(
                'id',
                $validated['stream_id']
            )
                ->where(
                    'school_class_id',
                    $validated['school_class_id']
                )
                ->exists();

            if (!$streamExists) {

                return response()->json([
                    'message' =>
                        'The selected stream does not belong to this class.',
                ], 422);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Get Allocated Subjects
        |--------------------------------------------------------------------------
        */

        $query = SubjectAllocation::with('subject')
            ->where(
                'school_class_id',
                $validated['school_class_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Stream Filtering
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['stream_id'])) {

            $query->where(function ($q) use ($validated) {

                $q->whereNull('stream_id')
                    ->orWhere(
                        'stream_id',
                        $validated['stream_id']
                    );
            });

        } else {

            $query->whereNull('stream_id');
        }


        /*
        |--------------------------------------------------------------------------
        | Build Subject Response
        |--------------------------------------------------------------------------
        */

        $allocations = $query
            ->get()
            ->filter(function ($allocation) {

                return $allocation->subject !== null;
            })
            ->unique('subject_id')
            ->values();


        $subjects = $allocations
            ->map(function ($allocation) {

                return [
                    'id' =>
                        $allocation->subject->id,

                    'name' =>
                        $allocation->subject->name,

                    'code' =>
                        $allocation->subject->code ?? null,
                ];
            })
            ->values();


        return response()->json($subjects);
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX: Get Teachers
    |--------------------------------------------------------------------------
    |
    | Returns teachers allocated to the selected:
    |
    | Class
    | Stream
    | Subject
    | Academic Year
    | Term
    |--------------------------------------------------------------------------
    */

    public function getTeachers(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'stream_id' => [
                'nullable',
                'exists:streams,id',
            ],

            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],

            'academic_year_id' => [
                'required',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'required',
                'exists:terms,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Stream/Class Relationship
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['stream_id'])) {

            $streamExists = Stream::where(
                'id',
                $validated['stream_id']
            )
                ->where(
                    'school_class_id',
                    $validated['school_class_id']
                )
                ->exists();

            if (!$streamExists) {

                return response()->json([
                    'message' =>
                        'The selected stream does not belong to this class.',
                ], 422);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Subject Allocation
        |--------------------------------------------------------------------------
        */

        $subjectAllocationQuery = SubjectAllocation::where(
            'school_class_id',
            $validated['school_class_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            );

        if (!empty($validated['stream_id'])) {

            $subjectAllocationQuery->where(function ($query) use ($validated) {

                $query->whereNull('stream_id')
                    ->orWhere(
                        'stream_id',
                        $validated['stream_id']
                    );
            });

        } else {

            $subjectAllocationQuery->whereNull('stream_id');
        }

        if (!$subjectAllocationQuery->exists()) {

            return response()->json([
                'message' =>
                    'This subject is not allocated to the selected class and stream.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Find Teacher Allocations
        |--------------------------------------------------------------------------
        */

        $query = TeacherAllocation::with('teacher')
            ->where(
                'school_class_id',
                $validated['school_class_id']
            )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'academic_year_id',
                $validated['academic_year_id']
            )
            ->where(
                'term_id',
                $validated['term_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Stream Logic
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['stream_id'])) {

            $query->where(function ($q) use ($validated) {

                $q->whereNull('stream_id')
                    ->orWhere(
                        'stream_id',
                        $validated['stream_id']
                    );
            });

        } else {

            $query->whereNull('stream_id');
        }


        /*
        |--------------------------------------------------------------------------
        | Build Teacher Response
        |--------------------------------------------------------------------------
        */

        $allocations = $query
            ->get()
            ->filter(function ($allocation) {

                return $allocation->teacher !== null;
            })
            ->unique('teacher_id')
            ->values();


        $teachers = $allocations
            ->map(function ($allocation) {

                $teacher = $allocation->teacher;

                return [
                    'id' =>
                        $teacher->id,

                    'name' =>
                        trim(
                            $teacher->first_name . ' ' .
                            ($teacher->middle_name ?? '') . ' ' .
                            ($teacher->last_name ?? '')
                        ),
                ];
            })
            ->values();


        return response()->json($teachers);
    }
}
