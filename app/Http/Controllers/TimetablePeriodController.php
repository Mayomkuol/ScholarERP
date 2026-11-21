<?php

namespace App\Http\Controllers;

use App\Models\TimetablePeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TimetablePeriodController extends Controller
{
    /**
     * Display all timetable periods.
     */
    public function index()
    {
        $periods = TimetablePeriod::orderByRaw('period_number IS NULL')
            ->orderBy('period_number')
            ->orderBy('start_time')
            ->get();

        return view('timetables.periods.index', compact('periods'));
    }

    /**
     * Show the form for creating a new period.
     */
    public function create()
    {
        return view('timetables.periods.create');
    }

    /**
     * Store a newly created period.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'period_number' => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
                'unique:timetable_periods,period_number',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],

            'type' => [
                'required',
                Rule::in([
                    'lesson',
                    'break',
                    'lunch',
                    'assembly',
                    'activity',
                ]),
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent overlapping periods
        |--------------------------------------------------------------------------
        */

        $overlap = TimetablePeriod::where(function ($query) use ($validated) {
            $query
                ->where('start_time', '<', $validated['end_time'])
                ->where('end_time', '>', $validated['start_time']);
        })->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'This time overlaps with an existing timetable period.',
                ]);
        }

        $validated['is_active'] = $request->boolean('is_active');

        TimetablePeriod::create($validated);

        return redirect()
            ->route('timetables.periods.index')
            ->with('success', 'Timetable period created successfully.');
    }

    /**
     * Show a specific period.
     */
    public function show(TimetablePeriod $timetablePeriod)
    {
        $timetablePeriod->load('timetables');

        return view(
            'timetables.periods.show',
            compact('timetablePeriod')
        );
    }

    /**
     * Show the form for editing a period.
     */
    public function edit(TimetablePeriod $timetablePeriod)
    {
        return view(
            'timetables.periods.edit',
            compact('timetablePeriod')
        );
    }

    /**
     * Update an existing period.
     */
    public function update(
        Request $request,
        TimetablePeriod $timetablePeriod
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'period_number' => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
                Rule::unique('timetable_periods', 'period_number')
                    ->ignore($timetablePeriod->id),
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],

            'type' => [
                'required',
                Rule::in([
                    'lesson',
                    'break',
                    'lunch',
                    'assembly',
                    'activity',
                ]),
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent overlapping periods
        |--------------------------------------------------------------------------
        */

        $overlap = TimetablePeriod::where('id', '!=', $timetablePeriod->id)
            ->where(function ($query) use ($validated) {
                $query
                    ->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'This time overlaps with another timetable period.',
                ]);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $timetablePeriod->update($validated);

        return redirect()
            ->route('timetables.periods.index')
            ->with('success', 'Timetable period updated successfully.');
    }

    /**
     * Delete a period.
     */
    public function destroy(TimetablePeriod $timetablePeriod)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent deleting a period currently used by a timetable
        |--------------------------------------------------------------------------
        */

        if ($timetablePeriod->timetables()->exists()) {
            return back()->withErrors([
                'period' => 'This period cannot be deleted because it is already being used by a timetable.',
            ]);
        }

        $timetablePeriod->delete();

        return back()->with('success', 'Timetable period deleted successfully.');
    }
}