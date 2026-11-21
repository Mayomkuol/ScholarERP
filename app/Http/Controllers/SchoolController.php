<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\AcademicYear;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Display School Setup
     */
    public function edit()
    {
        $school = School::firstOrCreate(
            ['id' => 1],
            [
                'name' => '',
                'code' => '',
                'country' => 'Kenya',
                'timezone' => 'Africa/Nairobi',
                'currency' => 'KES',
                'currency_symbol' => 'KSh',
                'pass_mark' => 50,
                'receipt_prefix' => 'REC',
                'invoice_prefix' => 'INV',
                'email_notifications' => true,
                'sms_notifications' => false,
                'maintenance_mode' => false,
                'active' => true,
            ]
        );

        $years = AcademicYear::orderBy('name')->get();

        $terms = Term::orderBy('name')->get();

        return view('school.edit', compact(
            'school',
            'years',
            'terms'
        ));
    }


    /**
     * Update School Setup
     */
    public function update(Request $request)
    {
        $school = School::firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // School Information
            'name' => 'required|string|max:255',

            'code' => 'required|string|max:255',

            'registration_no' => 'nullable|string|max:255',

            'motto' => 'nullable|string|max:255',

            'school_type' => 'nullable|string|max:255',

            'ownership' => 'nullable|string|max:255',


            // Contact
            'email' => 'nullable|email|max:255',

            'phone' => 'nullable|string|max:50',

            'website' => 'nullable|url|max:255',

            'address' => 'nullable|string',

            'country' => 'required|string|max:255',

            'timezone' => 'required|string|max:255',


            // Branding
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'stamp' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,ico|max:2048',

            'login_background' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',


            // Principal
            'principal_name' => 'nullable|string|max:255',

            'principal_email' => 'nullable|email|max:255',

            'principal_phone' => 'nullable|string|max:50',


            // Academic
            'current_academic_year' => 'nullable|string|max:255',

            'current_term' => 'nullable|string|max:255',

            'pass_mark' => 'required|numeric|min:0|max:100',


            // Financial
            'currency' => 'required|string|max:20',

            'currency_symbol' => 'nullable|string|max:10',

            'receipt_prefix' => 'required|string|max:20',

            'invoice_prefix' => 'required|string|max:20',


            // Notifications
            'email_notifications' => 'nullable|boolean',

            'sms_notifications' => 'nullable|boolean',

            'maintenance_mode' => 'nullable|boolean',
        ]);


        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            if ($school->logo) {
                Storage::disk('public')->delete($school->logo);
            }

            $validated['logo'] =
                $request->file('logo')->store(
                    'schools/logos',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | STAMP
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('stamp')) {

            if ($school->stamp) {
                Storage::disk('public')->delete($school->stamp);
            }

            $validated['stamp'] =
                $request->file('stamp')->store(
                    'schools/stamps',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | FAVICON
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('favicon')) {

            if ($school->favicon) {
                Storage::disk('public')->delete($school->favicon);
            }

            $validated['favicon'] =
                $request->file('favicon')->store(
                    'schools/favicons',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN BACKGROUND
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('login_background')) {

            if ($school->login_background) {
                Storage::disk('public')->delete(
                    $school->login_background
                );
            }

            $validated['login_background'] =
                $request->file('login_background')->store(
                    'schools/login-backgrounds',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECKBOXES
        |--------------------------------------------------------------------------
        */

        $validated['email_notifications'] =
            $request->boolean('email_notifications');

        $validated['sms_notifications'] =
            $request->boolean('sms_notifications');

        $validated['maintenance_mode'] =
            $request->boolean('maintenance_mode');


        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        $school->update($validated);


        return redirect()
            ->route('school.edit')
            ->with(
                'success',
                'School information updated successfully.'
            );
    }
}