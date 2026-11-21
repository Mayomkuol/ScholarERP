<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $students = Student::with([
            'schoolClass',
            'stream',
            'academicYear',
            'term',
        ])
        ->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('admission_no', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(15)
        ->withQueryString();

        return view('students.index', compact('students'));
    }


    /**
     * Show the student admission form.
     */
    public function create()
    {
        // Generate next admission number.
        $nextId = (Student::max('id') ?? 0) + 1;

        $admissionNo =
            'SCH' .
            date('Y') .
            str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $classes = SchoolClass::orderBy('name')->get();

        /*
         * Load active streams together with their classes.
         *
         * Example:
         * 7 Blue
         * 7 Red
         * 7 Green
         * 8 Blue
         * 8 Red
         * 8 Green
         */
        $streams = Stream::with('schoolClass')
            ->where('status', 'Active')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        $years = AcademicYear::orderByDesc('id')->get();

        $terms = Term::orderBy('id')->get();

        return view('students.create', compact(
            'admissionNo',
            'classes',
            'streams',
            'years',
            'terms'
        ));
    }


    /**
     * Store a newly admitted student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            // Admission
            'admission_date' => 'nullable|date',

            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',

            'gender' => 'required|in:Male,Female',
            'dob' => 'required|date',

            'birth_certificate' => 'nullable|string|max:255',

            'blood_group' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',

            // Contact
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',

            // Academic
            'school_class_id' => 'required|exists:school_classes,id',
            'stream_id' => 'nullable|exists:streams,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'required|exists:terms,id',

            // Parent
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email|max:255',

            // Father
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',

            // Mother
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',

            // Guardian
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_relationship' => 'nullable|string|max:100',

            // Medical
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',

            // Previous School
            'previous_school' => 'nullable|string|max:255',

            // Photo
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        /*
         * Make sure the selected stream actually belongs
         * to the selected class.
         */
        if (!empty($validated['stream_id'])) {

            $streamBelongsToClass = Stream::where('id', $validated['stream_id'])
                ->where('school_class_id', $validated['school_class_id'])
                ->exists();

            if (!$streamBelongsToClass) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'stream_id' =>
                            'The selected stream does not belong to the selected class.'
                    ]);
            }
        }


        // Generate admission number.
        $nextId = (Student::max('id') ?? 0) + 1;

        $validated['admission_no'] =
            'SCH' .
            date('Y') .
            str_pad($nextId, 4, '0', STR_PAD_LEFT);


        // Default admission date.
        $validated['admission_date'] =
            $validated['admission_date'] ?? now()->format('Y-m-d');


        // Student status.
        $validated['status'] = 'Active';


        // Upload student photo.
        if ($request->hasFile('photo')) {

            $validated['photo'] = $request
                ->file('photo')
                ->store('students', 'public');
        }


        // Create student.
        Student::create($validated);


        return redirect()
            ->route('students.index')
            ->with('success', 'Student admitted successfully.');
    }


    /**
     * Display a student's profile.
     */
    public function show(Student $student)
{
    $student->load([
    'schoolClass',
    'stream',
    'academicYear',
    'term',
    'documents',
    'promotions',
    'attendances',
    'guardians',
    'medicalRecord',
    'disciplines',
    'transfers',
]);

    return view('students.show', compact('student'));
}


    /**
     * Show the student edit form.
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::orderBy('name')->get();

        $streams = Stream::with('schoolClass')
            ->where('status', 'Active')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        $years = AcademicYear::orderByDesc('id')->get();

        $terms = Term::orderBy('id')->get();

        return view('students.edit', compact(
            'student',
            'classes',
            'streams',
            'years',
            'terms'
        ));
    }


    /**
     * Update an existing student.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([

            // Admission
            'admission_date' => 'nullable|date',

            // Personal
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',

            'gender' => 'required|in:Male,Female',
            'dob' => 'required|date',

            'birth_certificate' => 'nullable|string|max:255',

            'blood_group' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',

            // Contact
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',

            // Academic
            'school_class_id' => 'required|exists:school_classes,id',
            'stream_id' => 'nullable|exists:streams,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'required|exists:terms,id',

            // Parent
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email|max:255',

            // Father
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',

            // Mother
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',

            // Guardian
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_relationship' => 'nullable|string|max:100',

            // Medical
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',

            // Previous School
            'previous_school' => 'nullable|string|max:255',

            // Photo
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Status
            'status' => 'required|string',
        ]);


        /*
         * Make sure the selected stream belongs
         * to the selected class.
         */
        if (!empty($validated['stream_id'])) {

            $streamBelongsToClass = Stream::where('id', $validated['stream_id'])
                ->where('school_class_id', $validated['school_class_id'])
                ->exists();

            if (!$streamBelongsToClass) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'stream_id' =>
                            'The selected stream does not belong to the selected class.'
                    ]);
            }
        }


        // Upload new photo if provided.
        if ($request->hasFile('photo')) {

            // Delete old photo.
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }

            // Store new photo.
            $validated['photo'] = $request
                ->file('photo')
                ->store('students', 'public');
        }


        // Update student.
        $student->update($validated);


        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }


    /**
     * Delete a student.
     */
    public function destroy(Student $student)
    {
        // Delete student photo.
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        // Delete student.
        $student->delete();


        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }


    public function createDiscipline(Student $student)
{
    return view('students.discipline.create', compact('student'));
}


public function storeDiscipline(Request $request, Student $student)
{
    $validated = $request->validate([
        'category' => ['required', 'string', 'max:255'],
        'incident' => ['required', 'string'],
        'incident_date' => ['required', 'date'],
        'action_taken' => ['nullable', 'string'],
        'reported_by' => ['nullable', 'string', 'max:255'],
        'status' => ['required', 'in:Pending,Resolved'],
    ]);

    $student->disciplineRecords()->create($validated);

    return redirect()
        ->route('students.show', $student)
        ->with('success', 'Discipline record added successfully.');
}
}