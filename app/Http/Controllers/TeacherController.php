<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display teachers.
     */
    public function index()
    {
        $teachers = Teacher::with([
            'user',
            'department'
        ])
        ->latest()
        ->paginate(15);

        return view('teachers.index', compact('teachers'));
    }


    /**
     * Registration form.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | Existing users who have Teacher role
        | and are not yet linked to a teacher profile.
        |--------------------------------------------------------------------------
        */

        $teacherUsers = User::role('Teacher')
            ->whereDoesntHave('teacher')
            ->orderBy('name')
            ->get();

        return view('teachers.create', compact(
            'departments',
            'teacherUsers'
        ));
    }


    /**
     * Save teacher.
     */
    public function store(Request $request)
    {
        $request->validate([

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT
            |--------------------------------------------------------------------------
            */

            'account_mode' => [
                'required',
                Rule::in(['new', 'existing']),
            ],

            'user_id' => [
                'nullable',
                'required_if:account_mode,existing',
                'exists:users,id',
            ],

            'password' => [
                'nullable',
                'required_if:account_mode,new',
                'string',
                'min:8',
                'confirmed',
            ],

            /*
            |--------------------------------------------------------------------------
            | PERSONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            'first_name' => 'required|string|max:100',

            'middle_name' => 'nullable|string|max:100',

            'last_name' => 'required|string|max:100',

            'gender' => 'required|string|max:20',

            'date_of_birth' => 'nullable|date',

            'national_id' => 'nullable|string|max:50',

            'phone' => 'nullable|string|max:30',

            'email' => 'required|email|unique:teachers,email',

            'address' => 'nullable|string',

            /*
            |--------------------------------------------------------------------------
            | EMPLOYMENT
            |--------------------------------------------------------------------------
            */

            'department_id' => 'nullable|exists:departments,id',

            'designation' => 'required|string|max:100',

            'employment_type' => 'required|string|max:50',

            'joining_date' => 'required|date',

            'qualification' => 'nullable|string|max:255',

            'specialization' => 'nullable|string|max:255',

            'salary' => 'nullable|numeric|min:0',

            'status' => 'required|boolean',

            /*
            |--------------------------------------------------------------------------
            | PHOTO
            |--------------------------------------------------------------------------
            */

            'photo' => 'nullable|image|max:2048',

        ]);


        $photo = null;

        try {

            DB::beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | 1. DETERMINE USER ACCOUNT
            |--------------------------------------------------------------------------
            */

            if ($request->account_mode === 'existing') {

                $user = User::findOrFail($request->user_id);

                /*
                |--------------------------------------------------------------------------
                | Make sure selected user has Teacher role
                |--------------------------------------------------------------------------
                */

                if (!$user->hasRole('Teacher')) {

                    throw new \Exception(
                        'The selected user does not have the Teacher role.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Prevent duplicate teacher profile
                |--------------------------------------------------------------------------
                */

                if ($user->teacher()->exists()) {

                    throw new \Exception(
                        'The selected user is already linked to a teacher profile.'
                    );
                }

            } else {

                /*
                |--------------------------------------------------------------------------
                | CREATE NEW USER ACCOUNT
                |--------------------------------------------------------------------------
                */

                if (User::where('email', $request->email)->exists()) {

                    throw new \Exception(
                        'A user account with this email already exists.'
                    );
                }


                $user = User::create([

                    'name' => trim(
                        $request->first_name . ' ' .
                        $request->last_name
                    ),

                    'email' => $request->email,

                    'password' => Hash::make(
                        $request->password
                    ),

                    'status' => $request->status,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Automatically assign Teacher role
                |--------------------------------------------------------------------------
                */

                $user->assignRole('Teacher');
            }


            /*
            |--------------------------------------------------------------------------
            | 2. GENERATE EMPLOYEE NUMBER
            |--------------------------------------------------------------------------
            */

            $lastTeacher = Teacher::latest('id')->first();

            if ($lastTeacher) {

                $number = intval(
                    substr($lastTeacher->employee_no, 3)
                ) + 1;

            } else {

                $number = 1;
            }


            $employeeNo =
                'EMP' .
                str_pad(
                    $number,
                    6,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | 3. UPLOAD PHOTO
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('photo')) {

                $photo = $request
                    ->file('photo')
                    ->store('teachers', 'public');
            }


            /*
            |--------------------------------------------------------------------------
            | 4. CREATE TEACHER PROFILE
            |--------------------------------------------------------------------------
            */

            Teacher::create([

                'user_id' => $user->id,

                'employee_no' => $employeeNo,

                'first_name' => $request->first_name,

                'middle_name' => $request->middle_name,

                'last_name' => $request->last_name,

                'gender' => $request->gender,

                'date_of_birth' => $request->date_of_birth,

                'national_id' => $request->national_id,

                'phone' => $request->phone,

                'email' => $request->email,

                'address' => $request->address,

                'photo' => $photo,

                'department_id' => $request->department_id,

                'designation' => $request->designation,

                'employment_type' => $request->employment_type,

                'joining_date' => $request->joining_date,

                'qualification' => $request->qualification,

                'specialization' => $request->specialization,

                'salary' => $request->salary,

                'status' => $request->status,

            ]);


            DB::commit();


            return redirect()
                ->route('teachers.index')
                ->with(
                    'success',
                    'Teacher registered successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Remove uploaded photo if database operation failed
            |--------------------------------------------------------------------------
            */

            if ($photo) {

                Storage::disk('public')
                    ->delete($photo);
            }


            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage(),
                ]);
        }
    }


    /**
     * Display teacher profile.
     */
   /**
 * Display teacher profile.
 */
public function show(Teacher $teacher)
{
    $teacher->load([
        'user',
        'department',

        // Teaching allocations
        'allocations.subject',
        'allocations.schoolClass',
        'allocations.stream',
        'allocations.academicYear',
        'allocations.term',

        // Class teacher assignments
        'classTeacherAssignments.schoolClass',
        'classTeacherAssignments.stream',
        'classTeacherAssignments.academicYear',
        'classTeacherAssignments.term',
    ]);

    return view(
        'teachers.show',
        compact('teacher')
    );
}


    /**
     * Edit teacher.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load([
            'user',
            'department'
        ]);

        $departments = Department::orderBy('name')->get();

        return view(
            'teachers.edit',
            compact(
                'teacher',
                'departments'
            )
        );
    }


    /**
 * Update teacher.
 */
public function update(Request $request, Teacher $teacher)
{
    $request->validate([

        /*
        |--------------------------------------------------------------------------
        | SYSTEM ACCOUNT
        |--------------------------------------------------------------------------
        */

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')
                ->ignore($teacher->user_id),
        ],

        'password' => [
            'nullable',
            'string',
            'min:8',
            'confirmed',
        ],

        /*
        |--------------------------------------------------------------------------
        | PERSONAL INFORMATION
        |--------------------------------------------------------------------------
        */

        'first_name' => [
            'required',
            'string',
            'max:100',
        ],

        'middle_name' => [
            'nullable',
            'string',
            'max:100',
        ],

        'last_name' => [
            'required',
            'string',
            'max:100',
        ],

        'gender' => [
            'required',
            'string',
            'max:20',
        ],

        'date_of_birth' => [
            'nullable',
            'date',
        ],

        'national_id' => [
            'nullable',
            'string',
            'max:50',
        ],

        'phone' => [
            'nullable',
            'string',
            'max:30',
        ],

        'address' => [
            'nullable',
            'string',
        ],

        /*
        |--------------------------------------------------------------------------
        | TEACHER EMAIL
        |--------------------------------------------------------------------------
        |
        | Teacher email follows the system account email.
        |
        */

        /*
        |--------------------------------------------------------------------------
        | EMPLOYMENT
        |--------------------------------------------------------------------------
        */

        'department_id' => [
            'nullable',
            'exists:departments,id',
        ],

        'designation' => [
            'required',
            'string',
            'max:100',
        ],

        'employment_type' => [
            'required',
            'string',
            'max:50',
        ],

        'joining_date' => [
            'required',
            'date',
        ],

        'qualification' => [
            'nullable',
            'string',
            'max:255',
        ],

        'specialization' => [
            'nullable',
            'string',
            'max:255',
        ],

        'salary' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'status' => [
            'required',
            'boolean',
        ],

        /*
        |--------------------------------------------------------------------------
        | PHOTO
        |--------------------------------------------------------------------------
        */

        'photo' => [
            'nullable',
            'image',
            'max:2048',
        ],
    ]);


    $newPhoto = null;

    /*
    |--------------------------------------------------------------------------
    | Keep reference to the old photo
    |--------------------------------------------------------------------------
    */

    $oldPhoto = $teacher->photo;


    try {

        DB::beginTransaction();


        /*
        |--------------------------------------------------------------------------
        | 1. UPDATE USER ACCOUNT
        |--------------------------------------------------------------------------
        */

        $user = $teacher->user;

        if ($user) {

            $user->name = $request->name;

            $user->email = $request->email;

            $user->status = $request->status;

            /*
            | Update password only when supplied.
            */

            if ($request->filled('password')) {

                $user->password = Hash::make(
                    $request->password
                );
            }

            $user->save();
        }


        /*
        |--------------------------------------------------------------------------
        | 2. UPLOAD NEW PHOTO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photo')) {

            $newPhoto = $request
                ->file('photo')
                ->store('teachers', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | 3. UPDATE TEACHER
        |--------------------------------------------------------------------------
        */

        $teacher->update([

            'first_name' => $request->first_name,

            'middle_name' => $request->middle_name,

            'last_name' => $request->last_name,

            'gender' => $request->gender,

            'date_of_birth' => $request->date_of_birth,

            'national_id' => $request->national_id,

            'phone' => $request->phone,

            /*
            | Teacher email follows User email.
            */

            'email' => $request->email,

            'address' => $request->address,

            'department_id' => $request->department_id,

            'designation' => $request->designation,

            'employment_type' => $request->employment_type,

            'joining_date' => $request->joining_date,

            'qualification' => $request->qualification,

            'specialization' => $request->specialization,

            'salary' => $request->salary,

            'status' => $request->status,

            'photo' => $newPhoto ?? $teacher->photo,
        ]);


        /*
        |--------------------------------------------------------------------------
        | 4. DELETE OLD PHOTO
        |--------------------------------------------------------------------------
        */

        if ($newPhoto && $oldPhoto) {

            Storage::disk('public')->delete(
                $oldPhoto
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 5. COMMIT TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::commit();


        /*
        |--------------------------------------------------------------------------
        | 6. REDIRECT TO PROFILE
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('teachers.show', $teacher)
            ->with(
                'success',
                'Teacher information updated successfully.'
            );

    } catch (\Throwable $e) {

        /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */

        DB::rollBack();


        /*
        |--------------------------------------------------------------------------
        | DELETE NEW PHOTO IF DATABASE FAILED
        |--------------------------------------------------------------------------
        */

        if ($newPhoto) {

            Storage::disk('public')->delete(
                $newPhoto
            );
        }


        return back()
            ->withInput()
            ->withErrors([
                'error' => $e->getMessage(),
            ]);
    }
}

    /*
 * Delete teacher.
 */
public function destroy(Teacher $teacher)
{
    try {

        DB::beginTransaction();

        /*
        |--------------------------------------------------------------------------
        | 1. Check for teaching allocations
        |--------------------------------------------------------------------------
        */

        if ($teacher->allocations()->exists()) {

            return back()->withErrors([
                'error' =>
                    'This teacher cannot be deleted because they have teaching allocations. ' .
                    'Please remove or reassign their teaching allocations first.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Get linked user
        |--------------------------------------------------------------------------
        */

        $user = $teacher->user;

        /*
        |--------------------------------------------------------------------------
        | 3. Delete teacher photo
        |--------------------------------------------------------------------------
        */

        if ($teacher->photo) {

            Storage::disk('public')->delete(
                $teacher->photo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Delete teacher profile
        |--------------------------------------------------------------------------
        */

        $teacher->delete();

        /*
        |--------------------------------------------------------------------------
        | 5. Delete linked user account
        |--------------------------------------------------------------------------
        */

        if ($user) {
            $user->delete();
        }

        DB::commit();

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'Teacher and linked system account deleted successfully.'
            );

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()->withErrors([
            'error' => $e->getMessage(),
        ]);
    }
}
}