<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\SubjectAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectAllocationController extends Controller
{
    /**
     * Display the subject allocation page.
     */
    public function index()
    {
        $classes = SchoolClass::query()
            ->where('active', true)
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        $allocations = SubjectAllocation::query()
            ->with([
                'schoolClass',
                'stream',
                'subject',
            ])
            ->orderBy('school_class_id')
            ->orderBy('stream_id')
            ->orderBy('subject_id')
            ->get();

        return view('subject_allocations.index', [
            'classes' => $classes,
            'allocations' => $allocations,
        ]);
    }


    /**
     * Load streams belonging to a class.
     */
    public function streams(SchoolClass $schoolClass)
    {
        $streams = Stream::query()
            ->where('school_class_id', $schoolClass->id)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
            ]);

        return response()->json($streams);
    }


    /**
     * Load subjects belonging to the class education level.
     */
    public function subjects(
        SchoolClass $schoolClass,
        Stream $stream
    ) {
        /*
        |--------------------------------------------------------------------------
        | Verify stream belongs to class
        |--------------------------------------------------------------------------
        */

        if ((int) $stream->school_class_id !== (int) $schoolClass->id) {
            return response()->json([
                'success' => false,
                'message' => 'The selected stream does not belong to this class.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Get subjects for education level
        |--------------------------------------------------------------------------
        */

        $subjects = Subject::query()
            ->where('level', $schoolClass->level)
            ->where('active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
                'category',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Get already allocated subjects
        |--------------------------------------------------------------------------
        */

        $allocatedSubjectIds = SubjectAllocation::query()
            ->where('school_class_id', $schoolClass->id)
            ->where('stream_id', $stream->id)
            ->pluck('subject_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Add allocation status
        |--------------------------------------------------------------------------
        */

        $subjects->transform(function ($subject) use ($allocatedSubjectIds) {

            $subject->allocated = in_array(
                (int) $subject->id,
                $allocatedSubjectIds,
                true
            );

            return $subject;
        });


        /*
        |--------------------------------------------------------------------------
        | Return JSON
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'subjects' => $subjects,
        ]);
    }


    /**
     * Allocate selected subjects to a class and stream.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'school_class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],

            'stream_id' => [
                'required',
                'integer',
                'exists:streams,id',
            ],

            'subject_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'subject_ids.*' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get class
        |--------------------------------------------------------------------------
        */

        $schoolClass = SchoolClass::findOrFail(
            $validated['school_class_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Verify stream belongs to class
        |--------------------------------------------------------------------------
        */

        $streamExists = Stream::query()
            ->where('id', $validated['stream_id'])
            ->where(
                'school_class_id',
                $validated['school_class_id']
            )
            ->exists();

        if (!$streamExists) {
            return response()->json([
                'success' => false,
                'message' => 'The selected stream does not belong to the selected class.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Remove duplicate subject IDs
        |--------------------------------------------------------------------------
        */

        $subjectIds = collect($validated['subject_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Verify subjects belong to education level
        |--------------------------------------------------------------------------
        */

        $validSubjectIds = Subject::query()
            ->whereIn('id', $subjectIds)
            ->where('level', $schoolClass->level)
            ->where('active', true)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();


        if (count($validSubjectIds) !== count($subjectIds)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'One or more selected subjects do not belong to the education level of this class.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Allocate subjects
        |--------------------------------------------------------------------------
        */

        $newAllocations = 0;

        DB::transaction(function () use (
            $subjectIds,
            $validated,
            &$newAllocations
        ) {

            foreach ($subjectIds as $subjectId) {

                $allocation = SubjectAllocation::firstOrCreate([
                    'school_class_id' => $validated['school_class_id'],
                    'stream_id' => $validated['stream_id'],
                    'subject_id' => $subjectId,
                ]);

                if ($allocation->wasRecentlyCreated) {
                    $newAllocations++;
                }
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Nothing new allocated
        |--------------------------------------------------------------------------
        */

        if ($newAllocations === 0) {
            return response()->json([
                'success' => false,
                'message' =>
                    'All selected subjects are already allocated to this class and stream.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' =>
                $newAllocations .
                ' subject(s) allocated successfully.',
        ], 200);
    }


    /**
     * Remove a subject allocation.
     */
    public function destroy(
        SubjectAllocation $subjectAllocation
    ) {
        $subjectAllocation->delete();

        return redirect()
            ->route('subject-allocations.index')
            ->with(
                'success',
                'Subject allocation removed successfully.'
            );
    }
}