<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\Term;
use Illuminate\Http\Request;

class StudentFeeController extends Controller
{
    public function index()
    {
        $studentFees = StudentFee::with([
            'student',
            'academicYear',
            'term'
        ])
        ->latest()
        ->paginate(20);

        return view('student_fees.index', compact('studentFees'));
    }

    public function create(Request $request)
    {
        $students = collect();

        if ($request->filled('school_class_id')) {

            $students = Student::where(
                'school_class_id',
                $request->school_class_id
            )
            ->orderBy('admission_no')
            ->get();
        }

        return view('student_fees.create', [

            'students' => $students,

            'years' => AcademicYear::orderBy('name')->get(),

            'terms' => Term::orderBy('name')->get(),

            'classes' => SchoolClass::orderBy('name')->get(),

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([

            'academic_year_id'=>'required|exists:academic_years,id',

            'term_id'=>'required|exists:terms,id',

            'school_class_id'=>'required|exists:school_classes,id',

            'students'=>'required|array',

            'students.*'=>'exists:students,id',

        ]);

        $totalFee = FeeStructure::where('academic_year_id',$request->academic_year_id)
            ->where('term_id',$request->term_id)
            ->where('school_class_id',$request->school_class_id)
            ->sum('amount');

        foreach ($request->students as $studentId) {

            $discount = $request->discount[$studentId] ?? 0;

            StudentFee::updateOrCreate(

                [

                    'student_id'=>$studentId,

                    'academic_year_id'=>$request->academic_year_id,

                    'term_id'=>$request->term_id,

                ],

                [

                    'total_fee'=>$totalFee,

                    'discount'=>$discount,

                    'net_fee'=>$totalFee-$discount,

                ]

            );
        }

        return redirect()
            ->route('student-fees.index')
            ->with('success','Student fees assigned successfully.');
    }

    public function edit(StudentFee $studentFee)
    {
        return view('student_fees.edit', [

            'studentFee'=>$studentFee,

            'years'=>AcademicYear::all(),

            'terms'=>Term::all(),

            'classes'=>SchoolClass::all(),

        ]);
    }

    public function update(Request $request, StudentFee $studentFee)
    {
        $request->validate([

            'discount'=>'nullable|numeric|min:0',

        ]);

        $discount = $request->discount ?? 0;

        $studentFee->update([

            'discount'=>$discount,

            'net_fee'=>$studentFee->total_fee-$discount,

        ]);

        return redirect()
            ->route('student-fees.index')
            ->with('success','Student fee updated successfully.');
    }

    public function destroy(StudentFee $studentFee)
    {
        $studentFee->delete();

        return back()->with(
            'success',
            'Student fee deleted successfully.'
        );
    }
}