<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\Term;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with([
            'academicYear',
            'term',
            'schoolClass',
            'feeCategory'
        ])
        ->latest()
        ->paginate(15);

        return view('fee_structures.index', compact('feeStructures'));
    }

    public function create()
    {
        return view('fee_structures.create', [
            'years' => AcademicYear::orderBy('name')->get(),
            'terms' => Term::orderBy('name')->get(),
            'classes' => SchoolClass::orderBy('name')->get(),
            'categories' => FeeCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id'          => 'required|exists:terms,id',
            'school_class_id'  => 'required|exists:school_classes,id',
            'fee_category_id'  => 'required|exists:fee_categories,id',
            'amount'           => 'required|numeric|min:0',
        ]);

        FeeStructure::create($validated);

        return redirect()
            ->route('fee-structures.index')
            ->with('success', 'Fee structure created successfully.');
    }

    public function edit(FeeStructure $feeStructure)
    {
        return view('fee_structures.edit', [
            'feeStructure' => $feeStructure,
            'years' => AcademicYear::orderBy('name')->get(),
            'terms' => Term::orderBy('name')->get(),
            'classes' => SchoolClass::orderBy('name')->get(),
            'categories' => FeeCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id'          => 'required|exists:terms,id',
            'school_class_id'  => 'required|exists:school_classes,id',
            'fee_category_id'  => 'required|exists:fee_categories,id',
            'amount'           => 'required|numeric|min:0',
        ]);

        $feeStructure->update($validated);

        return redirect()
            ->route('fee-structures.index')
            ->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();

        return redirect()
            ->route('fee-structures.index')
            ->with('success', 'Fee structure deleted successfully.');
    }
}