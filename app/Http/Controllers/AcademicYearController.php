<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::latest()->paginate(10);

        return view('academic-years.index', compact('years'));
    }

    public function create()
    {
        return view('academic-years.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:academic_years',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required',
        ]);

        $validated['is_current'] = $request->has('is_current');

        if ($validated['is_current']) {
            AcademicYear::query()->update([
                'is_current' => false
            ]);
        }

        AcademicYear::create($validated);

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic Year created successfully.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|unique:academic_years,name,' . $academicYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required',
        ]);

        $validated['is_current'] = $request->has('is_current');

        if ($validated['is_current']) {
            AcademicYear::query()->update([
                'is_current' => false
            ]);
        }

        $academicYear->update($validated);

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic Year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic Year deleted successfully.');
    }
}