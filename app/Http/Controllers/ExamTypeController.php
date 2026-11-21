<?php

namespace App\Http\Controllers;

use App\Models\ExamType;
use Illuminate\Http\Request;

class ExamTypeController extends Controller
{
    public function index()
    {
        $examTypes = ExamType::latest()->paginate(10);

        return view('exam-types.index', compact('examTypes'));
    }

    public function create()
    {
        return view('exam-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'code'       => 'nullable|string|max:20|unique:exam_types,code',
            'percentage' => 'required|integer|min:0|max:100',
            'active'     => 'nullable|boolean',
        ]);

        $validated['active'] = $request->has('active');

        ExamType::create($validated);

        return redirect()
            ->route('exam-types.index')
            ->with('success', 'Exam Type created successfully.');
    }

    public function show(ExamType $examType)
    {
        return view('exam-types.show', compact('examType'));
    }

    public function edit(ExamType $examType)
    {
        return view('exam-types.edit', compact('examType'));
    }

    public function update(Request $request, ExamType $examType)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'code'       => 'nullable|string|max:20|unique:exam_types,code,' . $examType->id,
            'percentage' => 'required|integer|min:0|max:100',
            'active'     => 'nullable|boolean',
        ]);

        $validated['active'] = $request->has('active');

        $examType->update($validated);

        return redirect()
            ->route('exam-types.index')
            ->with('success', 'Exam Type updated successfully.');
    }

    public function destroy(ExamType $examType)
    {
        $examType->delete();

        return redirect()
            ->route('exam-types.index')
            ->with('success', 'Exam Type deleted successfully.');
    }
}