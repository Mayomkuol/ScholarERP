<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display all subjects.
     */
    public function index()
    {
        $subjects = Subject::query()
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        return view(
            'subjects.index',
            compact('subjects')
        );
    }

    /**
     * Store a new subject.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'code' => [
                'required',
                'string',
                'max:30',
                'unique:subjects,code',
            ],

            'level' => [
                'required',
                Rule::in([
                    'Primary',
                    'Secondary',
                    'College',
                ]),
            ],

            'category' => [
                'required',
                Rule::in([
                    'Compulsory',
                    'Optional',
                ]),
            ],

        ]);

        Subject::create([
            'name' => trim($validated['name']),

            'code' => strtoupper(
                trim($validated['code'])
            ),

            'level' => $validated['level'],

            'category' => $validated['category'],

            'active' => true,
        ]);

        return redirect()
            ->route('subjects.index')
            ->with(
                'success',
                'Subject added successfully.'
            );
    }

    /**
     * Delete a subject.
     */
    public function destroy(Subject $subject)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent deletion if already allocated
        |--------------------------------------------------------------------------
        */

        if (
            $subject->subjectAllocations()->exists()
            ||
            $subject->teacherAllocations()->exists()
        ) {
            return back()->with(
                'error',
                'This subject cannot be deleted because it is already in use.'
            );
        }

        $subject->delete();

        return back()->with(
            'success',
            'Subject deleted successfully.'
        );
    }
}