<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::with('academicYear')
                    ->latest()
                    ->paginate(10);

        return view('terms.index', compact('terms'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('name')->get();

        return view('terms.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'required|max:100',
            'code'             => 'nullable|max:20',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'status'           => 'required|in:Open,Closed',
        ]);

        $validated['is_current'] = $request->has('is_current');

        if ($validated['is_current']) {
            Term::query()->update(['is_current' => false]);
        }

        Term::create($validated);

        return redirect()
            ->route('terms.index')
            ->with('success', 'Term created successfully.');
    }

    public function edit(Term $term)
    {
        $academicYears = AcademicYear::orderBy('name')->get();

        return view('terms.edit', compact('term', 'academicYears'));
    }

    public function update(Request $request, Term $term)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'required|max:100',
            'code'             => 'nullable|max:20',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'status'           => 'required|in:Open,Closed',
        ]);

        $validated['is_current'] = $request->has('is_current');

        if ($validated['is_current']) {
            Term::query()->update(['is_current' => false]);
        }

        $term->update($validated);

        return redirect()
            ->route('terms.index')
            ->with('success', 'Term updated successfully.');
    }

    public function destroy(Term $term)
    {
        $term->delete();

        return redirect()
            ->route('terms.index')
            ->with('success', 'Term deleted successfully.');
    }
}