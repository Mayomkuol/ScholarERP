<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StreamController extends Controller
{
    /**
     * Display all streams.
     */
    public function index()
    {
        $streams = Stream::with('schoolClass')
            ->withCount('students')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        $classes = SchoolClass::orderBy('name')->get();

        return view(
            'streams.index',
            compact('streams', 'classes')
        );
    }

    /**
     * Store new stream.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'code' => [
                'required',
                'string',
                'max:20',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Clean stream name and code
        |--------------------------------------------------------------------------
        */

        $validated['name'] = ucwords(
            strtolower(trim($validated['name']))
        );

        $validated['code'] = strtoupper(
            trim($validated['code'])
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate stream name in same class
        |--------------------------------------------------------------------------
        */

        $nameExists = Stream::where(
            'school_class_id',
            $validated['school_class_id']
        )
        ->whereRaw(
            'LOWER(name) = ?',
            [strtolower($validated['name'])]
        )
        ->exists();

        if ($nameExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'name' =>
                        'This stream already exists for the selected class.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate stream code in same class
        |--------------------------------------------------------------------------
        */

        $codeExists = Stream::where(
            'school_class_id',
            $validated['school_class_id']
        )
        ->whereRaw(
            'LOWER(code) = ?',
            [strtolower($validated['code'])]
        )
        ->exists();

        if ($codeExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'code' =>
                        'This stream code already exists for the selected class.'
                ]);
        }

        Stream::create($validated);

        return redirect()
            ->route('streams.index')
            ->with(
                'success',
                'Stream created successfully.'
            );
    }

    /**
     * Show edit form.
     */
    public function edit(Stream $stream)
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view(
            'streams.edit',
            compact('stream', 'classes')
        );
    }

    /**
     * Update stream.
     */
    public function update(
        Request $request,
        Stream $stream
    ) {
        $validated = $request->validate([

            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'code' => [
                'required',
                'string',
                'max:20',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Clean name and code
        |--------------------------------------------------------------------------
        */

        $validated['name'] = ucwords(
            strtolower(trim($validated['name']))
        );

        $validated['code'] = strtoupper(
            trim($validated['code'])
        );

        /*
        |--------------------------------------------------------------------------
        | Current student count
        |--------------------------------------------------------------------------
        */

        $studentCount = $stream->students()->count();

        if ($validated['capacity'] < $studentCount) {

            return back()
                ->withInput()
                ->withErrors([
                    'capacity' =>
                        "Capacity cannot be less than the current student count ({$studentCount})."
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate stream name
        |--------------------------------------------------------------------------
        */

        $nameExists = Stream::where(
            'school_class_id',
            $validated['school_class_id']
        )
        ->whereRaw(
            'LOWER(name) = ?',
            [strtolower($validated['name'])]
        )
        ->where('id', '!=', $stream->id)
        ->exists();

        if ($nameExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'name' =>
                        'This stream already exists for the selected class.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate stream code
        |--------------------------------------------------------------------------
        */

        $codeExists = Stream::where(
            'school_class_id',
            $validated['school_class_id']
        )
        ->whereRaw(
            'LOWER(code) = ?',
            [strtolower($validated['code'])]
        )
        ->where('id', '!=', $stream->id)
        ->exists();

        if ($codeExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'code' =>
                        'This stream code already exists for the selected class.'
                ]);
        }

        $stream->update($validated);

        return redirect()
            ->route('streams.index')
            ->with(
                'success',
                'Stream updated successfully.'
            );
    }

    /**
     * Delete stream.
     */
    public function destroy(Stream $stream)
    {
        /*
        |--------------------------------------------------------------------------
        | Don't delete streams containing students
        |--------------------------------------------------------------------------
        */

        if ($stream->students()->exists()) {

            return back()->with(
                'error',
                'This stream cannot be deleted because students are assigned to it.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Don't delete streams with subject allocations
        |--------------------------------------------------------------------------
        */

        if ($stream->subjectAllocations()->exists()) {

            return back()->with(
                'error',
                'This stream cannot be deleted because subject allocations exist.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Don't delete streams with teacher allocations
        |--------------------------------------------------------------------------
        */

        if ($stream->teacherAllocations()->exists()) {

            return back()->with(
                'error',
                'This stream cannot be deleted because teacher allocations exist.'
            );
        }

        $stream->delete();

        return back()->with(
            'success',
            'Stream deleted successfully.'
        );
    }
}