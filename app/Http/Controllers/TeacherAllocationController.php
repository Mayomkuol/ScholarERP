<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\TeacherAllocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherAllocationController extends Controller
{
    public function index()
    {
        $allocations = TeacherAllocation::with([
            'teacher',
            'subject',
            'schoolClass',
            'stream',
            'academicYear',
            'term'
        ])
        ->latest()
        ->paginate(15);

        return view('teacher_allocations.index', compact('allocations'));
    }

    public function create()
    {
        return view('teacher_allocations.create', [

            'teachers' => Teacher::orderBy('first_name')->get(),

            'subjects' => Subject::orderBy('name')->get(),

            'classes' => SchoolClass::orderBy('name')->get(),

            'streams' => Stream::orderBy('name')->get(),

            'years' => AcademicYear::orderByDesc('id')->get(),

            'terms' => Term::orderBy('name')->get(),

        ]);
    }

    public function store(Request $request)
    {
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

        ]);

        TeacherAllocation::create($validated);

        return redirect()
            ->route('teacher-allocations.index')
            ->with('success', 'Teacher allocated successfully.');
    }

    public function show(TeacherAllocation $teacherAllocation)
    {
        $teacherAllocation->load([
            'teacher',
            'subject',
            'schoolClass',
            'stream',
            'academicYear',
            'term'
        ]);

        return view(
            'teacher_allocations.show',
            compact('teacherAllocation')
        );
    }

    public function edit(TeacherAllocation $teacherAllocation)
    {
        return view('teacher_allocations.edit', [

            'teacherAllocation' => $teacherAllocation,

            'teachers' => Teacher::orderBy('first_name')->get(),

            'subjects' => Subject::orderBy('name')->get(),

            'classes' => SchoolClass::orderBy('name')->get(),

            'streams' => Stream::orderBy('name')->get(),

            'years' => AcademicYear::orderByDesc('id')->get(),

            'terms' => Term::orderBy('name')->get(),

        ]);
    }

    public function update(
        Request $request,
        TeacherAllocation $teacherAllocation
    ) {
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

        ]);

        $teacherAllocation->update($validated);

        return redirect()
            ->route('teacher-allocations.index')
            ->with('success', 'Allocation updated successfully.');
    }

    public function destroy(TeacherAllocation $teacherAllocation)
    {
        $teacherAllocation->delete();

        return back()->with(
            'success',
            'Allocation deleted successfully.'
        );
    }
}