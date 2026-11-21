<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentDiscipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StudentDisciplineController extends Controller
{
    /**
     * Display all discipline records for a student.
     */
    public function index(Student $student)
    {
        Gate::authorize('viewAny', [StudentDiscipline::class, $student]);

        $disciplines = $student->disciplines()
            ->latest('incident_date')
            ->paginate(15);

        return view('student-discipline.index', compact(
            'student',
            'disciplines'
        ));
    }

    /**
     * Show the discipline creation form.
     */
    public function create(Student $student)
    {
        Gate::authorize('create', [StudentDiscipline::class, $student]);

        return view('student-discipline.create', compact('student'));
    }

    /**
     * Store a new discipline record.
     */
    public function store(Request $request, Student $student)
    {
        Gate::authorize('create', [StudentDiscipline::class, $student]);

        $validated = $request->validate([
            'incident_date' => [
                'required',
                'date',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'incident' => [
                'required',
                'string',
                'max:5000',
            ],

            'action_taken' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'status' => [
                'required',
                'string',
                'in:Pending,Resolved',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Never trust reported_by from the browser
        |--------------------------------------------------------------------------
        */
        $validated['reported_by'] =
            $request->user()->name
            ?? $request->user()->email;

        /*
        |--------------------------------------------------------------------------
        | Always associate the discipline record with the route student
        |--------------------------------------------------------------------------
        */
        $student->disciplines()->create($validated);

        return redirect()
            ->route('students.discipline.index', $student)
            ->with('success', 'Discipline record added successfully.');
    }

    /**
     * Display a discipline record.
     */
    public function show(
        Student $student,
        StudentDiscipline $discipline
    ) {
        Gate::authorize(
            'view',
            [StudentDiscipline::class, $student, $discipline]
        );

        return view('student-discipline.show', compact(
            'student',
            'discipline'
        ));
    }

    /**
     * Show the edit form.
     */
    public function edit(
        Student $student,
        StudentDiscipline $discipline
    ) {
        Gate::authorize(
            'update',
            [StudentDiscipline::class, $student, $discipline]
        );

        return view('student-discipline.edit', compact(
            'student',
            'discipline'
        ));
    }

    /**
     * Update a discipline record.
     */
    public function update(
        Request $request,
        Student $student,
        StudentDiscipline $discipline
    ) {
        Gate::authorize(
            'update',
            [StudentDiscipline::class, $student, $discipline]
        );

        $validated = $request->validate([
            'incident_date' => [
                'required',
                'date',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'incident' => [
                'required',
                'string',
                'max:5000',
            ],

            'action_taken' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'status' => [
                'required',
                'string',
                'in:Pending,Resolved',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Do not allow reported_by to be changed from the browser
        |--------------------------------------------------------------------------
        */
        $discipline->update($validated);

        return redirect()
            ->route('students.discipline.index', $student)
            ->with('success', 'Discipline record updated successfully.');
    }

    /**
     * Delete a discipline record.
     */
    public function destroy(
        Student $student,
        StudentDiscipline $discipline
    ) {
        Gate::authorize(
            'delete',
            [StudentDiscipline::class, $student, $discipline]
        );

        $discipline->delete();

        return redirect()
            ->route('students.discipline.index', $student)
            ->with('success', 'Discipline record deleted successfully.');
    }
}
