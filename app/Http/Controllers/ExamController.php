<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\SchoolClass;

class ExamController extends Controller
{
    /**
     * Display a listing of exams.
     */
    public function index()
    {
        $exams = Exam::with([
            'type',
            'academicYear',
            'term',
            'schoolClass'
        ])
        ->latest()
        ->paginate(20);

        return view('exams.index', compact('exams'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('exams.create', [
            'types'   => ExamType::where('active', true)->get(),
            'years'   => AcademicYear::all(),
            'terms'   => Term::all(),
            'classes' => SchoolClass::all(),
        ]);
    }

    /**
     * Store a new exam.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_type_id'     => 'required|exists:exam_types,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id'          => 'required|exists:terms,id',
            'school_class_id'  => 'required|exists:school_classes,id',
            'name'             => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ]);

        Exam::create($validated);

        return redirect()
            ->route('exams.index')
            ->with('success', 'Exam created successfully.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Exam $exam)
    {
        return view('exams.edit', [
            'exam'    => $exam,
            'types'   => ExamType::where('active', true)->get(),
            'years'   => AcademicYear::all(),
            'terms'   => Term::all(),
            'classes' => SchoolClass::all(),
        ]);
    }

    /**
     * Update the specified exam.
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'exam_type_id'     => 'required|exists:exam_types,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id'          => 'required|exists:terms,id',
            'school_class_id'  => 'required|exists:school_classes,id',
            'name'             => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'status'           => 'nullable|in:Draft,Open,Closed,Published',
        ]);

        $exam->update($validated);

        return redirect()
            ->route('exams.index')
            ->with('success', 'Exam updated successfully.');
    }


public function show(Exam $exam)
{
    $exam->load([
        'type',
        'academicYear',
        'term',
        'schoolClass',
        'marks'
    ]);

    return view('exams.show', compact('exam'));
}

    /**
     * Remove the specified exam.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()
            ->route('exams.index')
            ->with('success', 'Exam deleted successfully.');
    }
}