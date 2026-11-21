<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::latest()->paginate(10);

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|unique:school_classes',
            'level' => 'required',
            'capacity' => 'required|integer|min:1',
        ]);

        $validated['active'] = $request->has('active');

        SchoolClass::create($validated);

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function edit(SchoolClass $class)
    {
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|unique:school_classes,code,' . $class->id,
            'level' => 'required',
            'capacity' => 'required|integer|min:1',
        ]);

        $validated['active'] = $request->has('active');

        $class->update($validated);

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}
