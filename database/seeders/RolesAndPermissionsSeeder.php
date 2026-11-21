<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | RESET PERMISSION CACHE
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | ALL SYSTEM PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            'dashboard.view',


            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */

            'users.view',
            'users.create',
            'users.edit',
            'users.delete',


            /*
            |--------------------------------------------------------------------------
            | Roles
            |--------------------------------------------------------------------------
            */

            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',


            /*
            |--------------------------------------------------------------------------
            | Permissions
            |--------------------------------------------------------------------------
            */

            'permissions.view',
            'permissions.manage',


            /*
            |--------------------------------------------------------------------------
            | School
            |--------------------------------------------------------------------------
            */

            'schools.view',
            'schools.create',
            'schools.edit',
            'schools.delete',


            /*
            |--------------------------------------------------------------------------
            | Academic Years
            |--------------------------------------------------------------------------
            */

            'academic_years.view',
            'academic_years.create',
            'academic_years.edit',
            'academic_years.delete',


            /*
            |--------------------------------------------------------------------------
            | Terms
            |--------------------------------------------------------------------------
            */

            'terms.view',
            'terms.create',
            'terms.edit',
            'terms.delete',


            /*
            |--------------------------------------------------------------------------
            | Classes
            |--------------------------------------------------------------------------
            */

            'classes.view',
            'classes.create',
            'classes.edit',
            'classes.delete',


            /*
            |--------------------------------------------------------------------------
            | Streams
            |--------------------------------------------------------------------------
            */

            'streams.view',
            'streams.create',
            'streams.edit',
            'streams.delete',


            /*
            |--------------------------------------------------------------------------
            | Subjects
            |--------------------------------------------------------------------------
            */

            'subjects.view',
            'subjects.create',
            'subjects.edit',
            'subjects.delete',


            /*
            |--------------------------------------------------------------------------
            | Subject Allocations
            |--------------------------------------------------------------------------
            */

            'subject_allocations.view',
            'subject_allocations.create',
            'subject_allocations.edit',
            'subject_allocations.delete',


            /*
            |--------------------------------------------------------------------------
            | Departments
            |--------------------------------------------------------------------------
            */

            'departments.view',
            'departments.create',
            'departments.edit',
            'departments.delete',


            /*
            |--------------------------------------------------------------------------
            | Teachers
            |--------------------------------------------------------------------------
            */

            'teachers.view',
            'teachers.create',
            'teachers.edit',
            'teachers.delete',


            /*
            |--------------------------------------------------------------------------
            | Teacher Allocations
            |--------------------------------------------------------------------------
            */

            'teacher_allocations.view',
            'teacher_allocations.create',
            'teacher_allocations.edit',
            'teacher_allocations.delete',


            /*
            |--------------------------------------------------------------------------
            | Timetables
            |--------------------------------------------------------------------------
            */

            'timetables.view',
            'timetables.create',
            'timetables.edit',
            'timetables.delete',


            /*
            |--------------------------------------------------------------------------
            | Students
            |--------------------------------------------------------------------------
            */

            'students.view',
            'students.create',
            'students.edit',
            'students.delete',


            /*
            |--------------------------------------------------------------------------
            | Admissions
            |--------------------------------------------------------------------------
            */

            'admissions.view',
            'admissions.create',
            'admissions.edit',
            'admissions.delete',


            /*
            |--------------------------------------------------------------------------
            | Student Documents
            |--------------------------------------------------------------------------
            */

            'student_documents.view',
            'student_documents.create',
            'student_documents.edit',
            'student_documents.delete',


            /*
            |--------------------------------------------------------------------------
            | Student Promotions
            |--------------------------------------------------------------------------
            */

            'promotions.view',
            'promotions.create',
            'promotions.edit',
            'promotions.delete',


            /*
            |--------------------------------------------------------------------------
            | Attendance
            |--------------------------------------------------------------------------
            */

            'attendance.view',
            'attendance.create',
            'attendance.edit',
            'attendance.delete',


            /*
            |--------------------------------------------------------------------------
            | Discipline
            |--------------------------------------------------------------------------
            */

            'discipline.view',
            'discipline.create',
            'discipline.edit',
            'discipline.delete',


            /*
            |--------------------------------------------------------------------------
            | Examination Types
            |--------------------------------------------------------------------------
            */

            'exam_types.view',
            'exam_types.create',
            'exam_types.edit',
            'exam_types.delete',


            /*
            |--------------------------------------------------------------------------
            | Examinations
            |--------------------------------------------------------------------------
            */

            'exams.view',
            'exams.create',
            'exams.edit',
            'exams.delete',


            /*
            |--------------------------------------------------------------------------
            | Marks
            |--------------------------------------------------------------------------
            */

            'marks.view',
            'marks.create',
            'marks.edit',


            /*
            |--------------------------------------------------------------------------
            | Grades
            |--------------------------------------------------------------------------
            */

            'grades.view',
            'grades.create',
            'grades.edit',
            'grades.delete',


            /*
            |--------------------------------------------------------------------------
            | Class Mark Sheet
            |--------------------------------------------------------------------------
            */

            'class_mark_sheet.view',


            /*
            |--------------------------------------------------------------------------
            | Student Report Cards
            |--------------------------------------------------------------------------
            */

            'student_report_cards.view',


            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */

            'reports.view',


            /*
            |--------------------------------------------------------------------------
            | Finance
            |--------------------------------------------------------------------------
            */

            'finance.view',


            /*
            |--------------------------------------------------------------------------
            | Fee Categories
            |--------------------------------------------------------------------------
            */

            'fee_categories.view',
            'fee_categories.create',
            'fee_categories.edit',
            'fee_categories.delete',


            /*
            |--------------------------------------------------------------------------
            | Fee Structures
            |--------------------------------------------------------------------------
            */

            'fee_structures.view',
            'fee_structures.create',
            'fee_structures.edit',
            'fee_structures.delete',


            /*
            |--------------------------------------------------------------------------
            | Student Fees
            |--------------------------------------------------------------------------
            */

            'student_fees.view',
            'student_fees.create',
            'student_fees.edit',
            'student_fees.delete',


            /*
            |--------------------------------------------------------------------------
            | Fee Payments
            |--------------------------------------------------------------------------
            */

            'fee_payments.view',
            'fee_payments.create',
            'fee_payments.edit',
            'fee_payments.delete',
            'fee_payments.reverse',


            /*
            |--------------------------------------------------------------------------
            | Fee Statements
            |--------------------------------------------------------------------------
            */

            'fee_statements.view',
        ];


        /*
        |--------------------------------------------------------------------------
        | CREATE / UPDATE PERMISSIONS
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE ROLES
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $registrar = Role::firstOrCreate([
            'name' => 'Registrar',
            'guard_name' => 'web',
        ]);

        $teacher = Role::firstOrCreate([
            'name' => 'Teacher',
            'guard_name' => 'web',
        ]);

        $accountant = Role::firstOrCreate([
            'name' => 'Accountant',
            'guard_name' => 'web',
        ]);

        $hrAdmin = Role::firstOrCreate([
            'name' => 'HR/Admin',
            'guard_name' => 'web',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        |
        | Super Admin receives every permission in ScholarERP.
        |
        */

        $superAdmin->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );


        /*
        |--------------------------------------------------------------------------
        | REGISTRAR
        |--------------------------------------------------------------------------
        */

        $registrar->syncPermissions([

            'dashboard.view',

            // Students
            'students.view',
            'students.create',
            'students.edit',

            // Admissions
            'admissions.view',
            'admissions.create',
            'admissions.edit',

            // Student Documents
'student_documents.view',
'student_documents.create',
'student_documents.edit',
'student_documents.delete',

            // Promotions
            'promotions.view',
            'promotions.create',
            'promotions.edit',

            // Discipline
            'discipline.view',
            'discipline.create',
            'discipline.edit',

            // Attendance
            'attendance.view',

            // Teachers
            'teachers.view',

            // Departments
            'departments.view',

            // Classes
            'classes.view',
            'classes.create',
            'classes.edit',

            // Streams
            'streams.view',
            'streams.create',
            'streams.edit',

            // Subjects
            'subjects.view',
            'subjects.create',
            'subjects.edit',

            // Academic Years
            'academic_years.view',
            'academic_years.create',
            'academic_years.edit',

            // Terms
            'terms.view',
            'terms.create',
            'terms.edit',

            // Subject Allocations
            'subject_allocations.view',
            'subject_allocations.create',
            'subject_allocations.edit',

            // Teacher Allocations
            'teacher_allocations.view',
            'teacher_allocations.create',
            'teacher_allocations.edit',

            // Timetables
            'timetables.view',

            // Examinations
            'exam_types.view',
            'exams.view',

            // Results
            'marks.view',
            'grades.view',
            'class_mark_sheet.view',
            'student_report_cards.view',

            // Reports
            'reports.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | TEACHER
        |--------------------------------------------------------------------------
        */

        $teacher->syncPermissions([

            'dashboard.view',

            // Students
            'students.view',

            // Classes
            'classes.view',

            // Streams
            'streams.view',

            // Subjects
            'subjects.view',

            // Timetables
            'timetables.view',

            // Attendance
            'attendance.view',
            'attendance.create',
            'attendance.edit',

            // Discipline
            'discipline.view',
            'discipline.create',
            'discipline.edit',

            // Examinations
            'exam_types.view',
            'exams.view',

            // Marks
            'marks.view',
            'marks.create',
            'marks.edit',

            // Grades
            'grades.view',

            // Class Mark Sheet
            'class_mark_sheet.view',

            // Report Cards
            'student_report_cards.view',

            // Reports
            'reports.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ACCOUNTANT
        |--------------------------------------------------------------------------
        */

        $accountant->syncPermissions([

            'dashboard.view',

            // Finance
            'finance.view',

            // Fee Categories
            'fee_categories.view',
            'fee_categories.create',
            'fee_categories.edit',
            'fee_categories.delete',

            // Fee Structures
            'fee_structures.view',
            'fee_structures.create',
            'fee_structures.edit',
            'fee_structures.delete',

            // Student Fees
            'student_fees.view',
            'student_fees.create',
            'student_fees.edit',

            // Fee Payments
            'fee_payments.view',
            'fee_payments.create',
            'fee_payments.edit',
            'fee_payments.reverse',

            // Statements
            'fee_statements.view',

            // Reports
            'reports.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | HR / ADMIN
        |--------------------------------------------------------------------------
        */

        $hrAdmin->syncPermissions([

            'dashboard.view',

            // Users
            'users.view',
            'users.create',
            'users.edit',

           // Departments
'departments.view',
'departments.create',
'departments.edit',
'departments.delete',

            // Teachers
            'teachers.view',
            'teachers.create',
            'teachers.edit',

            // Reports
            'reports.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CLEAR PERMISSION CACHE
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}