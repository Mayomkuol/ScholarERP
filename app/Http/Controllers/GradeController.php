<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $grades = Grade::orderByDesc('min_mark')->get();

    return view('grades.index', compact('grades'));
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    return view('grades.create');
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([

        'grade'=>'required',

        'min_mark'=>'required|integer',

        'max_mark'=>'required|integer',

        'points'=>'required|numeric',

        'remarks'=>'required',

        'color'=>'nullable'

    ]);

    Grade::create($validated);

    return redirect()
            ->route('grades.index')
            ->with('success','Grade created.');
}

    /**
     * Show the form for editing the specified resource.
     */
   public function show(Grade $grade)
{
    return view('grades.show', compact('grade'));
}

public function edit(Grade $grade)
{
    return view('grades.edit', compact('grade'));
}

public function update(Request $request, Grade $grade)
{
    $validated = $request->validate([
        'grade' => 'required',
        'min_mark' => 'required|integer',
        'max_mark' => 'required|integer',
        'points' => 'required|numeric',
        'remarks' => 'required',
        'color' => 'nullable',
    ]);

    $grade->update($validated);

    return redirect()
        ->route('grades.index')
        ->with('success', 'Grade updated successfully.');
}

public function destroy(Grade $grade)
{
    $grade->delete();

    return redirect()
        ->route('grades.index')
        ->with('success', 'Grade deleted successfully.');
}
}
