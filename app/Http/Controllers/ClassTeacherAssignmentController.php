<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassTeacherAssignment;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Teacher;
use App\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClassTeacherAssignmentController extends Controller
{
    /**
     * Display all class teacher assignments.
     */
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            auth()->user()->can('teachers.view'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Assignments
        |--------------------------------------------------------------------------
        */

        $assignments = ClassTeacherAssignment::query()
            ->with([
                'teacher',
                'schoolClass',
                'stream',
                'academicYear',
                'term',
                'assignedBy',
            ])
            ->latest()
            ->paginate(15);

        return view(
            'class_teacher_assignments.index',
            compact('assignments')
        );
    }


    /**
     * Show the assignment form.
     */
    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            auth()->user()->can('teachers.create'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Active Teachers
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::query()
            ->where('status', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Academic Years
        |--------------------------------------------------------------------------
        */

        $academicYears = AcademicYear::query()
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Active School Classes
        |--------------------------------------------------------------------------
        */

        $schoolClasses = SchoolClass::query()
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Terms
        |--------------------------------------------------------------------------
        */

        $terms = Term::query()
            ->orderByDesc('is_current')
            ->orderBy('start_date')
            ->get();

        return view(
            'class_teacher_assignments.create',
            compact(
                'teachers',
                'academicYears',
                'schoolClasses',
                'terms'
            )
        );
    }


    /**
     * Store a new class teacher assignment.
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $request->user()->can('teachers.create'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'integer',
                Rule::exists('teachers', 'id')
                    ->where(fn ($query) => $query->where('status', 1)),
            ],

            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'required',
                'integer',
                'exists:terms,id',
            ],

            'school_class_id' => [
                'required',
                'integer',
                Rule::exists('school_classes', 'id')
                    ->where(fn ($query) => $query->where('active', 1)),
            ],

            'stream_id' => [
                'nullable',
                'integer',
                'exists:streams,id',
            ],

            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],

            'assigned_at' => [
                'nullable',
                'date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Term Belongs To Academic Year
        |--------------------------------------------------------------------------
        */

        $termBelongsToYear = Term::query()
            ->whereKey($validated['term_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->exists();

        if (!$termBelongsToYear) {
            return back()
                ->withInput()
                ->withErrors([
                    'term_id' =>
                        'The selected term does not belong to the selected academic year.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Stream Belongs To Selected Class
        |--------------------------------------------------------------------------
        */

        if ($validated['stream_id'] !== null) {
            $streamBelongsToClass = Stream::query()
                ->whereKey($validated['stream_id'])
                ->where('school_class_id', $validated['school_class_id'])
                ->where('status', 'Active')
                ->exists();

            if (!$streamBelongsToClass) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'stream_id' =>
                            'The selected stream does not belong to the selected class or is inactive.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Active Teacher Assignment
        |--------------------------------------------------------------------------
        |
        | A teacher can only be the active class teacher for one assignment
        | during the same academic year and term.
        |
        */

        if ($validated['status'] === 'Active') {
            $teacherAlreadyAssigned = ClassTeacherAssignment::query()
                ->where('status', 'Active')
                ->where('teacher_id', $validated['teacher_id'])
                ->where('academic_year_id', $validated['academic_year_id'])
                ->where('term_id', $validated['term_id'])
                ->exists();

            if ($teacherAlreadyAssigned) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'This teacher already has an active class teacher assignment for the selected academic year and term.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Class/Stream Assignment
        |--------------------------------------------------------------------------
        |
        | Rules:
        |
        | 1. Same class + same stream = conflict.
        | 2. Class-level assignment (NULL stream) conflicts with every
        |    stream assignment in that class.
        | 3. Different streams may have different class teachers.
        |
        */

        if ($validated['status'] === 'Active') {
            $classAlreadyAssigned = ClassTeacherAssignment::query()
                ->where('status', 'Active')
                ->where('school_class_id', $validated['school_class_id'])
                ->where('academic_year_id', $validated['academic_year_id'])
                ->where('term_id', $validated['term_id'])
                ->where(function ($query) use ($validated) {
                    if ($validated['stream_id'] !== null) {
                        $query
                            ->where('stream_id', $validated['stream_id'])
                            ->orWhereNull('stream_id');
                    } else {
                        $query->whereNull('stream_id');
                    }
                })
                ->exists();

            if ($classAlreadyAssigned) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'stream_id' =>
                            'This class or stream already has an active class teacher.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Create Assignment
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $request): void {
            ClassTeacherAssignment::create([
                'teacher_id' => $validated['teacher_id'],
                'school_class_id' => $validated['school_class_id'],
                'stream_id' => $validated['stream_id'] ?? null,
                'academic_year_id' => $validated['academic_year_id'],
                'term_id' => $validated['term_id'],
                'status' => $validated['status'],
                'assigned_at' => $validated['assigned_at']
                    ?? now()->toDateString(),
                'assigned_by' => $request->user()->id,
            ]);
        });

        return redirect()
            ->route('class-teacher-assignments.index')
            ->with(
                'success',
                'Class teacher assigned successfully.'
            );
    }


    /**
     * Display a single assignment.
     */
    public function show(
        ClassTeacherAssignment $classTeacherAssignment
    ): View {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            auth()->user()->can('teachers.view'),
            403
        );

        $classTeacherAssignment->load([
            'teacher',
            'schoolClass',
            'stream',
            'academicYear',
            'term',
            'assignedBy',
        ]);

        return view(
            'class_teacher_assignments.show',
            compact('classTeacherAssignment')
        );
    }


    /**
     * Show the edit form.
     */
    public function edit(
        ClassTeacherAssignment $classTeacherAssignment
    ): View {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            auth()->user()->can('teachers.edit'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Active Teachers
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::query()
            ->where('status', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Academic Years
        |--------------------------------------------------------------------------
        */

        $academicYears = AcademicYear::query()
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Active School Classes
        |--------------------------------------------------------------------------
        */

        $schoolClasses = SchoolClass::query()
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Terms
        |--------------------------------------------------------------------------
        */

        $terms = Term::query()
            ->orderByDesc('is_current')
            ->orderBy('start_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Streams For Current Class
        |--------------------------------------------------------------------------
        */

        $streams = Stream::query()
            ->where(
                'school_class_id',
                $classTeacherAssignment->school_class_id
            )
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view(
            'class_teacher_assignments.edit',
            compact(
                'classTeacherAssignment',
                'teachers',
                'academicYears',
                'schoolClasses',
                'terms',
                'streams'
            )
        );
    }


    /**
     * Update an assignment.
     */
    public function update(
        Request $request,
        ClassTeacherAssignment $classTeacherAssignment
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $request->user()->can('teachers.edit'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'integer',
                Rule::exists('teachers', 'id')
                    ->where(fn ($query) => $query->where('status', 1)),
            ],

            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'required',
                'integer',
                'exists:terms,id',
            ],

            'school_class_id' => [
                'required',
                'integer',
                Rule::exists('school_classes', 'id')
                    ->where(fn ($query) => $query->where('active', 1)),
            ],

            'stream_id' => [
                'nullable',
                'integer',
                'exists:streams,id',
            ],

            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],

            'assigned_at' => [
                'nullable',
                'date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Term Belongs To Academic Year
        |--------------------------------------------------------------------------
        */

        $termBelongsToYear = Term::query()
            ->whereKey($validated['term_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->exists();

        if (!$termBelongsToYear) {
            return back()
                ->withInput()
                ->withErrors([
                    'term_id' =>
                        'The selected term does not belong to the selected academic year.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Stream Belongs To Selected Class
        |--------------------------------------------------------------------------
        */

        if ($validated['stream_id'] !== null) {
            $streamBelongsToClass = Stream::query()
                ->whereKey($validated['stream_id'])
                ->where('school_class_id', $validated['school_class_id'])
                ->where('status', 'Active')
                ->exists();

            if (!$streamBelongsToClass) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'stream_id' =>
                            'The selected stream does not belong to the selected class or is inactive.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Teacher Assignment
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] === 'Active') {
            $teacherAlreadyAssigned = ClassTeacherAssignment::query()
                ->where('status', 'Active')
                ->where('teacher_id', $validated['teacher_id'])
                ->where('academic_year_id', $validated['academic_year_id'])
                ->where('term_id', $validated['term_id'])
                ->whereKeyNot($classTeacherAssignment->id)
                ->exists();

            if ($teacherAlreadyAssigned) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'This teacher already has another active class teacher assignment for the selected academic year and term.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Class/Stream Assignment
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] === 'Active') {
            $classAlreadyAssigned = ClassTeacherAssignment::query()
                ->where('status', 'Active')
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
                )
                ->whereKeyNot($classTeacherAssignment->id)
                ->where(function ($query) use ($validated) {
                    if ($validated['stream_id'] !== null) {
                        $query
                            ->where('stream_id', $validated['stream_id'])
                            ->orWhereNull('stream_id');
                    } else {
                        $query->whereNull('stream_id');
                    }
                })
                ->exists();

            if ($classAlreadyAssigned) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'stream_id' =>
                            'This class or stream already has an active class teacher.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Assignment
        |--------------------------------------------------------------------------
        |
        | assigned_by is intentionally NOT changed.
        |
        */

        DB::transaction(function () use (
            $validated,
            $classTeacherAssignment
        ): void {
            $classTeacherAssignment->update([
                'teacher_id' => $validated['teacher_id'],
                'school_class_id' => $validated['school_class_id'],
                'stream_id' => $validated['stream_id'] ?? null,
                'academic_year_id' => $validated['academic_year_id'],
                'term_id' => $validated['term_id'],
                'status' => $validated['status'],
                'assigned_at' => $validated['assigned_at']
                    ?? $classTeacherAssignment->assigned_at,
            ]);
        });

        return redirect()
            ->route('class-teacher-assignments.index')
            ->with(
                'success',
                'Class teacher assignment updated successfully.'
            );
    }


    /**
     * Delete an assignment.
     */
    public function destroy(
        Request $request,
        ClassTeacherAssignment $classTeacherAssignment
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $request->user()->can('teachers.delete'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($classTeacherAssignment): void {
            $classTeacherAssignment->delete();
        });

        return redirect()
            ->route('class-teacher-assignments.index')
            ->with(
                'success',
                'Class teacher assignment deleted successfully.'
            );
    }


    /**
     * Get active streams belonging to a school class.
     */
    public function streams(
        Request $request,
        int $schoolClassId
    ): JsonResponse {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $request->user()->can('teachers.view'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Class
        |--------------------------------------------------------------------------
        */

        $schoolClassExists = SchoolClass::query()
            ->whereKey($schoolClassId)
            ->where('active', 1)
            ->exists();

        if (!$schoolClassExists) {
            return response()->json(
                [
                    'message' => 'The selected school class was not found or is inactive.',
                ],
                404
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Active Streams
        |--------------------------------------------------------------------------
        */

        $streams = Stream::query()
            ->where('school_class_id', $schoolClassId)
            ->where('status', 'Active')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
            ]);

        return response()->json($streams);
    }
}