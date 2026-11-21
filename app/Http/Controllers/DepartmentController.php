<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $departments = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Department::count(),
            'active' => Department::where('status', 1)->count(),
            'inactive' => Department::where('status', 0)->count(),
        ];

        return view('departments.index', compact('departments', 'stats'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:departments,name',
            'code' => 'required|max:20|unique:departments,code',
            'description' => 'nullable',
            'status' => 'required|boolean',
        ]);

        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|unique:departments,name,' . $department->id,
            'code' => 'required|max:20|unique:departments,code,' . $department->id,
            'description' => 'nullable',
            'status' => 'required|boolean',
        ]);

        $department->update($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}