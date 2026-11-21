<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Term;
use App\Models\TimetableBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\SubjectAllocation;
use App\Models\TeacherAllocation;
use App\Models\Timetable;

class TimetableBlockController extends Controller
{
    /**
     * Display all timetable blocks.
     */
    public function index(Request $request)
    {
        $query = TimetableBlock::with([
            'academicYear',
            'term',
            'schoolClass',
            'stream',
            'creator',
        ]);

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

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $blocks = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::orderByDesc('id')->get();

        $terms = Term::orderBy('name')->get();

        $classes = SchoolClass::orderBy('name')->get();

        return view(
            'timetables.blocks.index',
            compact(
                'blocks',
                'academicYears',
                'terms',
                'classes'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $academicYears = AcademicYear::orderByDesc('id')->get();

        $terms = Term::orderBy('name')->get();

        $classes = SchoolClass::orderBy('name')->get();

        return view(
            'timetables.blocks.create',
            compact(
                'academicYears',
                'terms',
                'classes'
            )
        );
    }


    /**
     * Store timetable block.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'required',
                'exists:terms,id',
            ],

            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'stream_id' => [
                'nullable',
                'exists:streams,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'archived',
                ]),
            ],

            'effective_from' => [
                'required',
                'date',
            ],

            'effective_to' => [
                'required',
                'date',
                'after_or_equal:effective_from',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Stream Belongs To Class
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
        | Prevent Duplicate Block
        |--------------------------------------------------------------------------
        */

        $duplicate = TimetableBlock::where(
            'academic_year_id',
            $validated['academic_year_id']
        )
            ->where(
                'term_id',
                $validated['term_id']
            )
            ->where(
                'school_class_id',
                $validated['school_class_id']
            )
            ->where(
                'stream_id',
                $validated['stream_id'] ?? null
            )
            ->where(
                'effective_from',
                $validated['effective_from']
            )
            ->where(
                'effective_to',
                $validated['effective_to']
            )
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' =>
                        'A timetable block already exists for this class, stream and period.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Publishing
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        } else {
            $validated['published_at'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Creator
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] = Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        TimetableBlock::create($validated);


        return redirect()
            ->route('timetables.blocks.index')
            ->with(
                'success',
                'Timetable block created successfully.'
            );
    }


    public function show(TimetableBlock $timetableBlock)
{
    $timetableBlock->load([
        'academicYear',
        'term',
        'schoolClass',
        'stream',
        'creator',
        'timetables.period',
        'timetables.subject',
        'timetables.teacher',
    ]);

    $periods = \App\Models\TimetablePeriod::where(
        'is_active',
        true
    )
        ->where(
            'type',
            'lesson'
        )
        ->orderByRaw('period_number IS NULL')
        ->orderBy('period_number')
        ->orderBy('start_time')
        ->get();

    $subjectsQuery = SubjectAllocation::with('subject')
        ->where(
            'school_class_id',
            $timetableBlock->school_class_id
        );

    if ($timetableBlock->stream_id) {
        $subjectsQuery->where(function ($query) use ($timetableBlock) {
            $query->whereNull('stream_id')
                ->orWhere(
                    'stream_id',
                    $timetableBlock->stream_id
                );
        });
    } else {
        $subjectsQuery->whereNull('stream_id');
    }

    $subjects = $subjectsQuery
        ->get()
        ->filter(fn ($allocation) => $allocation->subject)
        ->unique('subject_id')
        ->map(fn ($allocation) => $allocation->subject)
        ->sortBy('name')
        ->values();

    return view(
        'timetables.blocks.show',
        compact(
            'timetableBlock',
            'periods',
            'subjects'
        )
    );
}

    /**
     * Edit timetable block.
     */
    public function edit(TimetableBlock $timetableBlock)
    {
        $academicYears = AcademicYear::orderByDesc('id')->get();

        $terms = Term::orderBy('name')->get();

        $classes = SchoolClass::orderBy('name')->get();

        $streams = collect();

        if ($timetableBlock->school_class_id) {
            $streams = Stream::where(
                'school_class_id',
                $timetableBlock->school_class_id
            )
                ->orderBy('name')
                ->get();
        }

        return view(
            'timetables.blocks.edit',
            compact(
                'timetableBlock',
                'academicYears',
                'terms',
                'classes',
                'streams'
            )
        );
    }


    /**
     * Update timetable block.
     */
    public function update(
        Request $request,
        TimetableBlock $timetableBlock
    ) {
        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'required',
                'exists:terms,id',
            ],

            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'stream_id' => [
                'nullable',
                'exists:streams,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'archived',
                ]),
            ],

            'effective_from' => [
                'required',
                'date',
            ],

            'effective_to' => [
                'required',
                'date',
                'after_or_equal:effective_from',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Stream
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
        | Prevent Duplicate
        |--------------------------------------------------------------------------
        */

        $duplicate = TimetableBlock::where(
            'academic_year_id',
            $validated['academic_year_id']
        )
            ->where(
                'term_id',
                $validated['term_id']
            )
            ->where(
                'school_class_id',
                $validated['school_class_id']
            )
            ->where(
                'stream_id',
                $validated['stream_id'] ?? null
            )
            ->where(
                'effective_from',
                $validated['effective_from']
            )
            ->where(
                'effective_to',
                $validated['effective_to']
            )
            ->where(
                'id',
                '!=',
                $timetableBlock->id
            )
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' =>
                        'Another timetable block already exists for this class, stream and period.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Published Timestamp
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] === 'published') {

            if (!$timetableBlock->published_at) {
                $validated['published_at'] = now();
            }

        } else {
            $validated['published_at'] = null;
        }


        $timetableBlock->update($validated);


        return redirect()
            ->route('timetables.blocks.index')
            ->with(
                'success',
                'Timetable block updated successfully.'
            );
    }


    /**
     * Delete timetable block.
     */
    public function destroy(TimetableBlock $timetableBlock)
    {
        if ($timetableBlock->timetables()->exists()) {

            return back()
                ->withErrors([
                    'block' =>
                        'This timetable block cannot be deleted because timetable lessons have already been generated from it.',
                ]);
        }


        $timetableBlock->delete();


        return back()
            ->with(
                'success',
                'Timetable block deleted successfully.'
            );
    }
    public function storeLesson(
    Request $request,
    TimetableBlock $timetableBlock
) {
    if (!$timetableBlock->isDraft()) {
        return back()->withErrors([
            'block' => 'Lessons can only be added to a timetable block while it is in Draft status.',
        ]);
    }

    $validated = $request->validate([
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

        'subject_id' => [
            'required',
            'exists:subjects,id',
        ],

        'teacher_id' => [
            'required',
            'exists:teachers,id',
        ],

        'room' => [
            'nullable',
            'string',
            'max:100',
        ],

        'lesson_type' => [
            'required',
            Rule::in([
                'regular',
                'double',
                'practical',
                'laboratory',
                'activity',
            ]),
        ],

        'notes' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    $period = \App\Models\TimetablePeriod::findOrFail(
        $validated['period_id']
    );

    if (!$period->is_active || !$period->isLesson()) {
        return back()
            ->withInput()
            ->withErrors([
                'period_id' => 'The selected period is not an active lesson period.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Subject Allocation
    |--------------------------------------------------------------------------
    */

    $subjectAllocationQuery = SubjectAllocation::where(
        'school_class_id',
        $timetableBlock->school_class_id
    )->where(
        'subject_id',
        $validated['subject_id']
    );

    if ($timetableBlock->stream_id) {
        $subjectAllocationQuery->where(function ($query) use ($timetableBlock) {
            $query->whereNull('stream_id')
                ->orWhere(
                    'stream_id',
                    $timetableBlock->stream_id
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
                    'This subject is not allocated to the selected class and stream.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Teacher Allocation
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
            $timetableBlock->school_class_id
        )
        ->where(
            'academic_year_id',
            $timetableBlock->academic_year_id
        )
        ->where(
            'term_id',
            $timetableBlock->term_id
        );

    if ($timetableBlock->stream_id) {
        $teacherAllocationQuery->where(function ($query) use ($timetableBlock) {
            $query->whereNull('stream_id')
                ->orWhere(
                    'stream_id',
                    $timetableBlock->stream_id
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
                    'This teacher is not allocated to teach this subject for the selected class, stream, academic year and term.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Existing Slot
    |--------------------------------------------------------------------------
    */

    $slotExists = Timetable::where(
        'timetable_block_id',
        $timetableBlock->id
    )
        ->where(
            'day',
            $validated['day']
        )
        ->where(
            'period_id',
            $validated['period_id']
        )
        ->exists();

    if ($slotExists) {
        return back()
            ->withInput()
            ->withErrors([
                'period_id' =>
                    'This class already has a lesson scheduled for the selected day and period.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Teacher Clash
    |--------------------------------------------------------------------------
    */

    $teacherClash = Timetable::where(
        'teacher_id',
        $validated['teacher_id']
    )
        ->where(
            'academic_year_id',
            $timetableBlock->academic_year_id
        )
        ->where(
            'term_id',
            $timetableBlock->term_id
        )
        ->where(
            'day',
            $validated['day']
        )
        ->where(
            'period_id',
            $validated['period_id']
        )
        ->exists();

    if ($teacherClash) {
        return back()
            ->withInput()
            ->withErrors([
                'teacher_id' =>
                    'Teacher clash: this teacher is already scheduled for another lesson during this period.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Room Clash
    |--------------------------------------------------------------------------
    */

    if (!empty($validated['room'])) {
        $roomClash = Timetable::where(
            'academic_year_id',
            $timetableBlock->academic_year_id
        )
            ->where(
                'term_id',
                $timetableBlock->term_id
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
                'room',
                $validated['room']
            )
            ->exists();

        if ($roomClash) {
            return back()
                ->withInput()
                ->withErrors([
                    'room' =>
                        'Room clash: this room is already being used during the selected period.',
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Create Lesson
    |--------------------------------------------------------------------------
    */

    Timetable::create([
        'timetable_block_id' => $timetableBlock->id,

        'academic_year_id' =>
            $timetableBlock->academic_year_id,

        'term_id' =>
            $timetableBlock->term_id,

        'school_class_id' =>
            $timetableBlock->school_class_id,

        'stream_id' =>
            $timetableBlock->stream_id,

        'subject_id' =>
            $validated['subject_id'],

        'teacher_id' =>
            $validated['teacher_id'],

        'period_id' =>
            $validated['period_id'],

        'day' =>
            $validated['day'],

        'lesson_no' =>
            $period->period_number,

        'start_time' =>
            $period->start_time,

        'end_time' =>
            $period->end_time,

        'room' =>
            $validated['room'] ?? null,

        'lesson_type' =>
            $validated['lesson_type'],

        'status' =>
            'draft',

        'notes' =>
            $validated['notes'] ?? null,
    ]);

    return redirect()
        ->route(
            'timetables.blocks.show',
            $timetableBlock
        )
        ->with(
            'success',
            'Lesson added to the timetable successfully.'
        );
}
public function destroyLesson(
    TimetableBlock $timetableBlock,
    Timetable $timetable
) {
    if ($timetable->timetable_block_id !== $timetableBlock->id) {
        abort(404);
    }

    if (!$timetableBlock->isDraft()) {
        return back()->withErrors([
            'lesson' =>
                'Lessons cannot be deleted from a published or archived timetable.',
        ]);
    }

    $timetable->delete();

    return back()->with(
        'success',
        'Lesson removed from the timetable successfully.'
    );
}
}