<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

// Dashboard
use App\Http\Controllers\DashboardController;

// School Setup
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StreamController;

// Subjects
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectAllocationController;

// Students
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentDocumentController;
use App\Http\Controllers\StudentPromotionController;
use App\Http\Controllers\StudentDisciplineController;
use App\Http\Controllers\AttendanceController;

// Staff
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherAllocationController;
use App\Http\Controllers\TimetableController;

// Examinations
use App\Http\Controllers\ExamTypeController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamMarkController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MarksheetController;

// Reports
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ClassMarkSheetController;
use App\Http\Controllers\StudentReportCardController;

// Finance
use App\Http\Controllers\FeeCategoryController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\StudentFeeStatementController;
use App\Http\Controllers\FinanceDashboardController;

// Administration
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
// System Backup & Restore
use App\Http\Controllers\BackupController;

use App\Http\Controllers\ClassTeacherAssignmentController;

use App\Http\Controllers\ClassTeacherDashboardController;

use App\Http\Controllers\StreamReportController;


use App\Http\Controllers\TimetablePeriodController;

use App\Http\Controllers\TimetableBlockController;



use App\Http\Controllers\AuditLogController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| IMPORTANT:
| auth.php must remain OUTSIDE the authenticated middleware group.
|
*/

require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Authenticated Application
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
/*
|--------------------------------------------------------------------------
| System Backup & Restore
|--------------------------------------------------------------------------
*/

Route::prefix('backups')
    ->name('backups.')
    ->middleware('role:Super Admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Backup Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [BackupController::class, 'index']
        )
            ->name('index');


        /*
        |--------------------------------------------------------------------------
        | Create Backup
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/create',
            [BackupController::class, 'create']
        )
            ->name('create');


        /*
        |--------------------------------------------------------------------------
        | Download Backup
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{backup}/download',
            [BackupController::class, 'download']
        )
            ->name('download');


        /*
        |--------------------------------------------------------------------------
        | Restore Backup
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{backup}/restore',
            [BackupController::class, 'restore']
        )
            ->name('restore');


        /*
        |--------------------------------------------------------------------------
        | Delete Backup
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{backup}',
            [BackupController::class, 'destroy']
        )
            ->name('destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )
        ->name('dashboard')
        ->middleware('permission:dashboard.view');


    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    Route::prefix('users')
        ->name('users.')
        ->group(function () {

            Route::get(
                '/',
                [UserController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:users.view');

            Route::get(
                '/create',
                [UserController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:users.create');

            Route::post(
                '/',
                [UserController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:users.create');

            Route::get(
                '/{user}',
                [UserController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:users.view');

            Route::get(
                '/{user}/edit',
                [UserController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:users.edit');

            Route::put(
                '/{user}',
                [UserController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:users.edit');

            Route::delete(
                '/{user}',
                [UserController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:users.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    Route::prefix('roles')
        ->name('roles.')
        ->group(function () {

            Route::get(
                '/',
                [RoleController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:roles.view');

            Route::get(
                '/create',
                [RoleController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:roles.create');

            Route::post(
                '/',
                [RoleController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:roles.create');

            Route::get(
                '/{role}',
                [RoleController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:roles.view');

            Route::get(
                '/{role}/edit',
                [RoleController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:roles.edit');

            Route::put(
                '/{role}',
                [RoleController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:roles.edit');

            Route::delete(
                '/{role}',
                [RoleController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:roles.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | School Setup
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/school/setup',
        [SchoolController::class, 'edit']
    )
        ->name('school.edit')
        ->middleware('permission:schools.view');

    Route::put(
        '/school/setup',
        [SchoolController::class, 'update']
    )
        ->name('school.update')
        ->middleware('permission:schools.edit');


    /*
    |--------------------------------------------------------------------------
    | Academic Years
    |--------------------------------------------------------------------------
    */

    Route::prefix('academic-years')
        ->name('academic-years.')
        ->group(function () {

            Route::get(
                '/',
                [AcademicYearController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:academic_years.view');

            Route::get(
                '/create',
                [AcademicYearController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:academic_years.create');

            Route::post(
                '/',
                [AcademicYearController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:academic_years.create');

            Route::get(
                '/{academic_year}',
                [AcademicYearController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:academic_years.view');

            Route::get(
                '/{academic_year}/edit',
                [AcademicYearController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:academic_years.edit');

            Route::put(
                '/{academic_year}',
                [AcademicYearController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:academic_years.edit');

            Route::delete(
                '/{academic_year}',
                [AcademicYearController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:academic_years.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Terms
    |--------------------------------------------------------------------------
    */

    Route::prefix('terms')
        ->name('terms.')
        ->group(function () {

            Route::get(
                '/',
                [TermController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:terms.view');

            Route::get(
                '/create',
                [TermController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:terms.create');

            Route::post(
                '/',
                [TermController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:terms.create');

            Route::get(
                '/{term}',
                [TermController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:terms.view');

            Route::get(
                '/{term}/edit',
                [TermController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:terms.edit');

            Route::put(
                '/{term}',
                [TermController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:terms.edit');

            Route::delete(
                '/{term}',
                [TermController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:terms.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */

    Route::prefix('classes')
        ->name('classes.')
        ->group(function () {

            Route::get(
                '/',
                [SchoolClassController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:classes.view');

            Route::get(
                '/create',
                [SchoolClassController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:classes.create');

            Route::post(
                '/',
                [SchoolClassController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:classes.create');

            Route::get(
                '/{class}',
                [SchoolClassController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:classes.view');

            Route::get(
                '/{class}/edit',
                [SchoolClassController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:classes.edit');

            Route::put(
                '/{class}',
                [SchoolClassController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:classes.edit');

            Route::delete(
                '/{class}',
                [SchoolClassController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:classes.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Streams
    |--------------------------------------------------------------------------
    */

    Route::prefix('streams')
        ->name('streams.')
        ->group(function () {

            Route::get(
                '/',
                [StreamController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:streams.view');

            Route::get(
                '/create',
                [StreamController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:streams.create');

            Route::post(
                '/',
                [StreamController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:streams.create');

            Route::get(
                '/{stream}',
                [StreamController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:streams.view');

            Route::get(
                '/{stream}/edit',
                [StreamController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:streams.edit');

            Route::put(
                '/{stream}',
                [StreamController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:streams.edit');

            Route::delete(
                '/{stream}',
                [StreamController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:streams.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    */

    Route::prefix('subjects')
        ->name('subjects.')
        ->group(function () {

            Route::get(
                '/',
                [SubjectController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:subjects.view');

            Route::get(
                '/create',
                [SubjectController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:subjects.create');

            Route::post(
                '/',
                [SubjectController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:subjects.create');

            Route::get(
                '/{subject}',
                [SubjectController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:subjects.view');

            Route::get(
                '/{subject}/edit',
                [SubjectController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:subjects.edit');

            Route::put(
                '/{subject}',
                [SubjectController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:subjects.edit');

            Route::delete(
                '/{subject}',
                [SubjectController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:subjects.delete');

        });


    /*
|--------------------------------------------------------------------------
| Subject Allocations
|--------------------------------------------------------------------------
*/

Route::prefix('subject-allocations')
    ->name('subject-allocations.')
    ->group(function () {

        Route::get(
            '/',
            [SubjectAllocationController::class, 'index']
        )
            ->name('index')
            ->middleware('permission:subject_allocations.view');

        Route::get(
            '/create',
            [SubjectAllocationController::class, 'create']
        )
            ->name('create')
            ->middleware('permission:subject_allocations.create');

        /*
        |--------------------------------------------------------------------------
        | AJAX / Lookup Routes
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/streams/{schoolClass}',
            [SubjectAllocationController::class, 'streams']
        )
            ->name('streams')
            ->middleware('permission:subject_allocations.view');

        Route::get(
            '/subjects/{schoolClass}/{stream}',
            [SubjectAllocationController::class, 'subjects']
        )
            ->name('subjects')
            ->middleware('permission:subject_allocations.view');

        /*
        |--------------------------------------------------------------------------
        | Store
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [SubjectAllocationController::class, 'store']
        )
            ->name('store')
            ->middleware('permission:subject_allocations.create');

        /*
        |--------------------------------------------------------------------------
        | Show
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{subject_allocation}',
            [SubjectAllocationController::class, 'show']
        )
            ->name('show')
            ->middleware('permission:subject_allocations.view');

        /*
        |--------------------------------------------------------------------------
        | Edit
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{subject_allocation}/edit',
            [SubjectAllocationController::class, 'edit']
        )
            ->name('edit')
            ->middleware('permission:subject_allocations.edit');

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{subject_allocation}',
            [SubjectAllocationController::class, 'update']
        )
            ->name('update')
            ->middleware('permission:subject_allocations.edit');

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{subject_allocation}',
            [SubjectAllocationController::class, 'destroy']
        )
            ->name('destroy')
            ->middleware('permission:subject_allocations.delete');

    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Allocations
    |--------------------------------------------------------------------------
    */

    Route::prefix('teacher-allocations')
        ->name('teacher-allocations.')
        ->group(function () {

            Route::get(
                '/',
                [TeacherAllocationController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:teacher_allocations.view');

            Route::get(
                '/create',
                [TeacherAllocationController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:teacher_allocations.create');

            Route::post(
                '/',
                [TeacherAllocationController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:teacher_allocations.create');

            Route::get(
                '/{teacher_allocation}',
                [TeacherAllocationController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:teacher_allocations.view');

            Route::get(
                '/{teacher_allocation}/edit',
                [TeacherAllocationController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:teacher_allocations.edit');

            Route::put(
                '/{teacher_allocation}',
                [TeacherAllocationController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:teacher_allocations.edit');

            Route::delete(
                '/{teacher_allocation}',
                [TeacherAllocationController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:teacher_allocations.delete');

        });



/*
|--------------------------------------------------------------------------
| Timetables
|--------------------------------------------------------------------------
*/

Route::prefix('timetables')
    ->name('timetables.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Timetable Period Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/periods',
            [TimetablePeriodController::class, 'index']
        )
            ->name('periods.index')
            ->middleware('permission:timetables.view');

        Route::get(
            '/periods/create',
            [TimetablePeriodController::class, 'create']
        )
            ->name('periods.create')
            ->middleware('permission:timetables.create');

        Route::post(
            '/periods',
            [TimetablePeriodController::class, 'store']
        )
            ->name('periods.store')
            ->middleware('permission:timetables.create');

        Route::get(
            '/periods/{timetablePeriod}',
            [TimetablePeriodController::class, 'show']
        )
            ->name('periods.show')
            ->middleware('permission:timetables.view');

        Route::get(
            '/periods/{timetablePeriod}/edit',
            [TimetablePeriodController::class, 'edit']
        )
            ->name('periods.edit')
            ->middleware('permission:timetables.edit');

        Route::put(
            '/periods/{timetablePeriod}',
            [TimetablePeriodController::class, 'update']
        )
            ->name('periods.update')
            ->middleware('permission:timetables.edit');

        Route::delete(
            '/periods/{timetablePeriod}',
            [TimetablePeriodController::class, 'destroy']
        )
            ->name('periods.destroy')
            ->middleware('permission:timetables.delete');


        /*
        |--------------------------------------------------------------------------
        | Timetable AJAX Data Routes
        |--------------------------------------------------------------------------
        |
        | These MUST come before /{timetable}
        |
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/data/streams',
            [TimetableController::class, 'getStreams']
        )
            ->name('data.streams')
            ->middleware('permission:timetables.create');

        Route::get(
            '/data/subjects',
            [TimetableController::class, 'getSubjects']
        )
            ->name('data.subjects')
            ->middleware('permission:timetables.create');

        Route::get(
            '/data/teachers',
            [TimetableController::class, 'getTeachers']
        )
            ->name('data.teachers')
            ->middleware('permission:timetables.create');


        /*
        |--------------------------------------------------------------------------
        | Timetable Blocks
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/blocks',
            [TimetableBlockController::class, 'index']
        )
            ->name('blocks.index')
            ->middleware('permission:timetables.view');

        Route::get(
            '/blocks/create',
            [TimetableBlockController::class, 'create']
        )
            ->name('blocks.create')
            ->middleware('permission:timetables.create');

        Route::post(
            '/blocks',
            [TimetableBlockController::class, 'store']
        )
            ->name('blocks.store')
            ->middleware('permission:timetables.create');

        Route::get(
            '/blocks/{timetableBlock}',
            [TimetableBlockController::class, 'show']
        )
            ->name('blocks.show')
            ->middleware('permission:timetables.view');

        Route::get(
            '/blocks/{timetableBlock}/edit',
            [TimetableBlockController::class, 'edit']
        )
            ->name('blocks.edit')
            ->middleware('permission:timetables.edit');

        Route::put(
            '/blocks/{timetableBlock}',
            [TimetableBlockController::class, 'update']
        )
            ->name('blocks.update')
            ->middleware('permission:timetables.edit');

        Route::delete(
            '/blocks/{timetableBlock}',
            [TimetableBlockController::class, 'destroy']
        )
            ->name('blocks.destroy')
            ->middleware('permission:timetables.delete');


        /*
        |--------------------------------------------------------------------------
        | Main Timetable
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [TimetableController::class, 'index']
        )
            ->name('index')
            ->middleware('permission:timetables.view');

        Route::get(
            '/create',
            [TimetableController::class, 'create']
        )
            ->name('create')
            ->middleware('permission:timetables.create');

        Route::post(
            '/',
            [TimetableController::class, 'store']
        )
            ->name('store')
            ->middleware('permission:timetables.create');


        /*
        |--------------------------------------------------------------------------
        | Dynamic Timetable Routes
        |--------------------------------------------------------------------------
        |
        | These MUST remain LAST.
        |
        |--------------------------------------------------------------------------
        */

Route::post(
    '/blocks/{timetableBlock}/lessons',
    [TimetableBlockController::class, 'storeLesson']
)
    ->name('blocks.lessons.store')
    ->middleware('permission:timetables.create');

Route::delete(
    '/blocks/{timetableBlock}/lessons/{timetable}',
    [TimetableBlockController::class, 'destroyLesson']
)
    ->name('blocks.lessons.destroy')
    ->middleware('permission:timetables.delete');

        Route::get(
            '/{timetable}',
            [TimetableController::class, 'show']
        )
            ->name('show')
            ->middleware('permission:timetables.view');

        Route::get(
            '/{timetable}/edit',
            [TimetableController::class, 'edit']
        )
            ->name('edit')
            ->middleware('permission:timetables.edit');

        Route::put(
            '/{timetable}',
            [TimetableController::class, 'update']
        )
            ->name('update')
            ->middleware('permission:timetables.edit');

        Route::delete(
            '/{timetable}',
            [TimetableController::class, 'destroy']
        )
            ->name('destroy')
            ->middleware('permission:timetables.delete');

    });


    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    Route::prefix('students')
        ->name('students.')
        ->group(function () {

            Route::get(
                '/',
                [StudentController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:students.view');

            Route::get(
                '/create',
                [StudentController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:students.create');

            Route::post(
                '/',
                [StudentController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:students.create');

            Route::get(
                '/{student}',
                [StudentController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:students.view');

            Route::get(
                '/{student}/edit',
                [StudentController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:students.edit');

            Route::put(
                '/{student}',
                [StudentController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:students.edit');

            Route::delete(
                '/{student}',
                [StudentController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:students.delete');

        });
/*
|--------------------------------------------------------------------------
| Student Documents
|--------------------------------------------------------------------------
*/

Route::scopeBindings()
    ->prefix('students/{student}/documents')
    ->name('students.documents.')
    ->group(function () {

        Route::get('/', [
            StudentDocumentController::class,
            'index'
        ])
            ->name('index')
            ->middleware('permission:student_documents.view');

        Route::get('/create', [
            StudentDocumentController::class,
            'create'
        ])
            ->name('create')
            ->middleware('permission:student_documents.create');

        Route::post('/', [
            StudentDocumentController::class,
            'store'
        ])
            ->name('store')
            ->middleware('permission:student_documents.create');
    });


/*
|--------------------------------------------------------------------------
| Delete Student Document
|--------------------------------------------------------------------------
*/

Route::delete(
    '/student-documents/{studentDocument}',
    [
        StudentDocumentController::class,
        'destroy'
    ]
)
    ->name('student-documents.destroy')
    ->middleware('permission:student_documents.delete');


    /*
    |--------------------------------------------------------------------------
    | Student Promotions
    |--------------------------------------------------------------------------
    */

    Route::prefix('student-promotions')
        ->name('student-promotions.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Promotion Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [StudentPromotionController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:promotions.view');


            /*
            |--------------------------------------------------------------------------
            | Generate Examination Results
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/generate-results',
                [StudentPromotionController::class, 'generateResults']
            )
                ->name('generate-results')
                ->middleware('permission:promotions.view');


            /*
            |--------------------------------------------------------------------------
            | Create Promotion
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/create',
                [StudentPromotionController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:promotions.create');


            /*
            |--------------------------------------------------------------------------
            | Store Promotion
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/',
                [StudentPromotionController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:promotions.create');


            /*
            |--------------------------------------------------------------------------
            | Promotion History
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/history',
                [StudentPromotionController::class, 'history']
            )
                ->name('history')
                ->middleware('permission:promotions.view');


            /*
            |--------------------------------------------------------------------------
            | View Promotion
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/{student_promotion}',
                [StudentPromotionController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:promotions.view');


            /*
            |--------------------------------------------------------------------------
            | Edit Promotion
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/{student_promotion}/edit',
                [StudentPromotionController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:promotions.edit');


            /*
            |--------------------------------------------------------------------------
            | Update Promotion
            |--------------------------------------------------------------------------
            */

            Route::put(
                '/{student_promotion}',
                [StudentPromotionController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:promotions.edit');


            /*
            |--------------------------------------------------------------------------
            | Delete Promotion
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/{student_promotion}',
                [StudentPromotionController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:promotions.delete');

        });


/*
|--------------------------------------------------------------------------
| Student Discipline
|--------------------------------------------------------------------------
*/

Route::scopeBindings()
    ->prefix('students/{student}/discipline')
    ->name('students.discipline.')
    ->group(function () {

        Route::get('/', [
            StudentDisciplineController::class,
            'index'
        ])
            ->name('index')
            ->middleware('permission:discipline.view');

        Route::get('/create', [
            StudentDisciplineController::class,
            'create'
        ])
            ->name('create')
            ->middleware('permission:discipline.create');

        Route::post('/', [
            StudentDisciplineController::class,
            'store'
        ])
            ->name('store')
            ->middleware('permission:discipline.create');

        Route::get('/{discipline}', [
            StudentDisciplineController::class,
            'show'
        ])
            ->name('show')
            ->middleware('permission:discipline.view');

        Route::get('/{discipline}/edit', [
            StudentDisciplineController::class,
            'edit'
        ])
            ->name('edit')
            ->middleware('permission:discipline.edit');

        Route::put('/{discipline}', [
            StudentDisciplineController::class,
            'update'
        ])
            ->name('update')
            ->middleware('permission:discipline.edit');

        Route::delete('/{discipline}', [
            StudentDisciplineController::class,
            'destroy'
        ])
            ->name('destroy')
            ->middleware('permission:discipline.delete');
    });



    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    Route::prefix('attendance')
        ->name('attendance.')
        ->group(function () {

            Route::get(
                '/',
                [AttendanceController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:attendance.view');

            Route::get(
                '/create',
                [AttendanceController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:attendance.create');
                Route::get(
    '/report',
    [AttendanceController::class, 'report']
)
    ->name('report')
    ->middleware('permission:attendance.view');

            Route::post(
                '/',
                [AttendanceController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:attendance.create');

            Route::get(
                '/export',
                [AttendanceController::class, 'export']
            )
                ->name('export')
                ->middleware('permission:attendance.view');

            Route::get(
                '/print',
                [AttendanceController::class, 'print']
            )
                ->name('print')
                ->middleware('permission:attendance.view');

            Route::get(
                '/student/{student}',
                [AttendanceController::class, 'student']
            )
                ->name('student')
                ->middleware('permission:attendance.view');

            Route::get(
                '/register',
                [AttendanceController::class, 'register']
            )
                ->name('register')
                ->middleware('permission:attendance.create');

            Route::get(
                '/{attendance}',
                [AttendanceController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:attendance.view');

            Route::get(
                '/{attendance}/edit',
                [AttendanceController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:attendance.edit');

            Route::put(
                '/{attendance}',
                [AttendanceController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:attendance.edit');

            Route::delete(
                '/{attendance}',
                [AttendanceController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:attendance.edit');

        });


    /*
    |--------------------------------------------------------------------------
    | Departments
    |--------------------------------------------------------------------------
    */

    Route::prefix('departments')
        ->name('departments.')
        ->group(function () {

            Route::get(
                '/',
                [DepartmentController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:departments.view');

            Route::get(
                '/create',
                [DepartmentController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:departments.create');

            Route::post(
                '/',
                [DepartmentController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:departments.create');

            Route::get(
                '/{department}',
                [DepartmentController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:departments.view');

            Route::get(
                '/{department}/edit',
                [DepartmentController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:departments.edit');

            Route::put(
                '/{department}',
                [DepartmentController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:departments.edit');

            Route::delete(
                '/{department}',
                [DepartmentController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:departments.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Teachers
    |--------------------------------------------------------------------------
    */

    Route::prefix('teachers')
        ->name('teachers.')
        ->group(function () {

            Route::get(
                '/',
                [TeacherController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:teachers.view');

            Route::get(
                '/create',
                [TeacherController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:teachers.create');

            Route::post(
                '/',
                [TeacherController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:teachers.create');

            Route::get(
                '/{teacher}',
                [TeacherController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:teachers.view');

            Route::get(
                '/{teacher}/edit',
                [TeacherController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:teachers.edit');

            Route::put(
                '/{teacher}',
                [TeacherController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:teachers.edit');

            Route::delete(
                '/{teacher}',
                [TeacherController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:teachers.delete');

        });


    /*
|--------------------------------------------------------------------------
| Examinations
|--------------------------------------------------------------------------
*/

Route::prefix('exams')
    ->name('exams.')
    ->group(function () {

        // View exams
        Route::get(
            '/',
            [ExamController::class, 'index']
        )
            ->name('index')
            ->middleware('permission:exams.view');

        // Create exam
        Route::get(
            '/create',
            [ExamController::class, 'create']
        )
            ->name('create')
            ->middleware('permission:exams.create');

        Route::post(
            '/',
            [ExamController::class, 'store']
        )
            ->name('store')
            ->middleware('permission:exams.create');

        // View single exam
        Route::get(
            '/{exam}',
            [ExamController::class, 'show']
        )
            ->name('show')
            ->middleware('permission:exams.view');

        // Edit exam
        Route::get(
            '/{exam}/edit',
            [ExamController::class, 'edit']
        )
            ->name('edit')
            ->middleware('permission:exams.edit');

        Route::put(
            '/{exam}',
            [ExamController::class, 'update']
        )
            ->name('update')
            ->middleware('permission:exams.edit');

        // Delete exam
        Route::delete(
            '/{exam}',
            [ExamController::class, 'destroy']
        )
            ->name('destroy')
            ->middleware('permission:exams.delete');

    });


/*
|--------------------------------------------------------------------------
| Exam Types
|--------------------------------------------------------------------------
*/

Route::prefix('exam-types')
    ->name('exam-types.')
    ->group(function () {

        // View exam types
        Route::get(
            '/',
            [ExamTypeController::class, 'index']
        )
            ->name('index')
            ->middleware('permission:exam_types.view');

        // Create exam type
        Route::get(
            '/create',
            [ExamTypeController::class, 'create']
        )
            ->name('create')
            ->middleware('permission:exam_types.create');

        Route::post(
            '/',
            [ExamTypeController::class, 'store']
        )
            ->name('store')
            ->middleware('permission:exam_types.create');

        // View single exam type
        Route::get(
            '/{examType}',
            [ExamTypeController::class, 'show']
        )
            ->name('show')
            ->middleware('permission:exam_types.view');

        // Edit exam type
        Route::get(
            '/{examType}/edit',
            [ExamTypeController::class, 'edit']
        )
            ->name('edit')
            ->middleware('permission:exam_types.edit');

        Route::put(
            '/{examType}',
            [ExamTypeController::class, 'update']
        )
            ->name('update')
            ->middleware('permission:exam_types.edit');

        // Delete exam type
        Route::delete(
            '/{examType}',
            [ExamTypeController::class, 'destroy']
        )
            ->name('destroy')
            ->middleware('permission:exam_types.delete');

    });


/*
|--------------------------------------------------------------------------
| Exam Types
|--------------------------------------------------------------------------
*/
Route::prefix('exam-types')->name('exam-types.')->group(function () {

    // View exam types
    Route::get('/', [ExamTypeController::class, 'index'])
        ->middleware('permission:exam_types.view')
        ->name('index');

    // Create exam type
    Route::get('/create', [ExamTypeController::class, 'create'])
        ->middleware('permission:exam_types.create')
        ->name('create');

    Route::post('/', [ExamTypeController::class, 'store'])
        ->middleware('permission:exam_types.create')
        ->name('store');

    // Edit exam type
    Route::get('/{examType}/edit', [ExamTypeController::class, 'edit'])
        ->middleware('permission:exam_types.edit')
        ->name('edit');

    Route::put('/{examType}', [ExamTypeController::class, 'update'])
        ->middleware('permission:exam_types.edit')
        ->name('update');

    // Delete exam type
    Route::delete('/{examType}', [ExamTypeController::class, 'destroy'])
        ->middleware('permission:exam_types.delete')
        ->name('destroy');
});


/*
    |--------------------------------------------------------------------------
    | Exam Marks
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/exam-marks',
        [ExamMarkController::class, 'index']
    )
        ->name('exam-marks.index')
        ->middleware('permission:marks.view');

    Route::get(
        '/exam-marks/create',
        [ExamMarkController::class, 'create']
    )
        ->name('exam-marks.create')
        ->middleware('permission:marks.create');

    Route::post(
        '/exam-marks/load',
        [ExamMarkController::class, 'loadStudents']
    )
        ->name('exam-marks.load')
        ->middleware('permission:marks.create');

    Route::post(
        '/exam-marks',
        [ExamMarkController::class, 'store']
    )
        ->name('exam-marks.store')
        ->middleware('permission:marks.create');

    /*
    |--------------------------------------------------------------------------
    | Grades
    |--------------------------------------------------------------------------
    */

    Route::prefix('grades')
        ->name('grades.')
        ->group(function () {

            Route::get(
                '/',
                [GradeController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:grades.view');

            Route::get(
                '/create',
                [GradeController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:grades.create');

            Route::post(
                '/',
                [GradeController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:grades.create');

            Route::get(
                '/{grade}',
                [GradeController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:grades.view');

            Route::get(
                '/{grade}/edit',
                [GradeController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:grades.edit');

            Route::put(
                '/{grade}',
                [GradeController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:grades.edit');

            Route::delete(
                '/{grade}',
                [GradeController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:grades.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Class Mark Sheet
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/class-mark-sheet',
        [ClassMarkSheetController::class, 'index']
    )
        ->name('class-mark-sheet.index')
        ->middleware('permission:class_mark_sheet.view');

    Route::post(
        '/class-mark-sheet/load',
        [ClassMarkSheetController::class, 'load']
    )
        ->name('class-mark-sheet.load')
        ->middleware('permission:class_mark_sheet.view');

    Route::get(
        '/class-mark-sheet/print',
        [ClassMarkSheetController::class, 'print']
    )
        ->name('class-mark-sheet.print')
        ->middleware('permission:class_mark_sheet.view');


    /*
    |--------------------------------------------------------------------------
    | Report Cards
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/report-cards',
        [ReportCardController::class, 'index']
    )
        ->name('report-cards.index')
        ->middleware('permission:student_report_cards.view');

    Route::get(
        '/report-cards/{student}/{exam}',
        [ReportCardController::class, 'show']
    )
        ->name('report-cards.show')
        ->middleware('permission:student_report_cards.view');

    Route::get(
        '/report-cards/{exam}/{student}/print',
        [ReportCardController::class, 'print']
    )
        ->name('report-cards.print')
        ->middleware('permission:student_report_cards.view');


    /*
    |--------------------------------------------------------------------------
    | Student Report Cards
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/student-report-card',
        [StudentReportCardController::class, 'index']
    )
        ->name('student-report-card.index')
        ->middleware('permission:student_report_cards.view');

    Route::post(
        '/student-report-card/load',
        [StudentReportCardController::class, 'load']
    )
        ->name('student-report-card.load')
        ->middleware('permission:student_report_cards.view');

    Route::get(
        '/student-report-card/students/{schoolClass}',
        [StudentReportCardController::class, 'studentsByClass']
    )
        ->name('student-report-card.students-by-class')
        ->middleware('permission:student_report_cards.view');


    /*
    |--------------------------------------------------------------------------
    | Finance Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/finance/dashboard',
        [FinanceDashboardController::class, 'index']
    )
        ->name('finance.dashboard')
        ->middleware('permission:finance.view');


    /*
    |--------------------------------------------------------------------------
    | Fee Categories
    |--------------------------------------------------------------------------
    */

    Route::prefix('fee-categories')
        ->name('fee-categories.')
        ->group(function () {

            Route::get(
                '/',
                [FeeCategoryController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:fee_categories.view');

            Route::get(
                '/create',
                [FeeCategoryController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:fee_categories.create');

            Route::post(
                '/',
                [FeeCategoryController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:fee_categories.create');

            Route::get(
                '/{fee_category}',
                [FeeCategoryController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:fee_categories.view');

            Route::get(
                '/{fee_category}/edit',
                [FeeCategoryController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:fee_categories.edit');

            Route::put(
                '/{fee_category}',
                [FeeCategoryController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:fee_categories.edit');

            Route::delete(
                '/{fee_category}',
                [FeeCategoryController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:fee_categories.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Fee Structures
    |--------------------------------------------------------------------------
    */

    Route::prefix('fee-structures')
        ->name('fee-structures.')
        ->group(function () {

            Route::get(
                '/',
                [FeeStructureController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:fee_structures.view');

            Route::get(
                '/create',
                [FeeStructureController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:fee_structures.create');

            Route::post(
                '/',
                [FeeStructureController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:fee_structures.create');

            Route::get(
                '/{fee_structure}',
                [FeeStructureController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:fee_structures.view');

            Route::get(
                '/{fee_structure}/edit',
                [FeeStructureController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:fee_structures.edit');

            Route::put(
                '/{fee_structure}',
                [FeeStructureController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:fee_structures.edit');

            Route::delete(
                '/{fee_structure}',
                [FeeStructureController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:fee_structures.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Student Fees
    |--------------------------------------------------------------------------
    */

    Route::prefix('student-fees')
        ->name('student-fees.')
        ->group(function () {

            Route::get(
                '/',
                [StudentFeeController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:student_fees.view');

            Route::get(
                '/create',
                [StudentFeeController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:student_fees.create');

            Route::post(
                '/',
                [StudentFeeController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:student_fees.create');

            Route::get(
                '/{student_fee}',
                [StudentFeeController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:student_fees.view');

            Route::get(
                '/{student_fee}/edit',
                [StudentFeeController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:student_fees.edit');

            Route::put(
                '/{student_fee}',
                [StudentFeeController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:student_fees.edit');

            Route::delete(
                '/{student_fee}',
                [StudentFeeController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:student_fees.delete');

        });


    /*
    |--------------------------------------------------------------------------
    | Fee Payments
    |--------------------------------------------------------------------------
    */

    Route::prefix('fee-payments')
        ->name('fee-payments.')
        ->group(function () {

            Route::get(
                '/',
                [FeePaymentController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:fee_payments.view');

            Route::get(
                '/create',
                [FeePaymentController::class, 'create']
            )
                ->name('create')
                ->middleware('permission:fee_payments.create');

            Route::post(
                '/',
                [FeePaymentController::class, 'store']
            )
                ->name('store')
                ->middleware('permission:fee_payments.create');

            Route::get(
                '/receipt/{feePayment:receipt_no}',
                [FeePaymentController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:fee_payments.view');

            Route::get(
                '/{feePayment}/edit',
                [FeePaymentController::class, 'edit']
            )
                ->name('edit')
                ->middleware('permission:fee_payments.edit');

            Route::put(
                '/{feePayment}',
                [FeePaymentController::class, 'update']
            )
                ->name('update')
                ->middleware('permission:fee_payments.edit');

            Route::delete(
                '/{feePayment}',
                [FeePaymentController::class, 'destroy']
            )
                ->name('destroy')
                ->middleware('permission:fee_payments.edit');

            Route::post(
                '/{feePayment}/reverse',
                [FeePaymentController::class, 'reverse']
            )
                ->name('reverse')
                ->middleware('permission:fee_payments.reverse');

               Route::get(
    '/apply-credit/{studentFee}',
    [FeePaymentController::class, 'applyCreditForm']
)
    ->name('apply-credit.form')
    ->middleware('permission:fee_payments.create');

Route::post(
    '/apply-credit/{studentFee}',
    [FeePaymentController::class, 'applyCredit']
)
    ->name('apply-credit')
    ->middleware('permission:fee_payments.create');
    });


    /*
    |--------------------------------------------------------------------------
    | Fee Statements
    |--------------------------------------------------------------------------
    */

    Route::prefix('fee-statements')
        ->name('fee-statements.')
        ->group(function () {

            Route::get(
                '/',
                [StudentFeeStatementController::class, 'index']
            )
                ->name('index')
                ->middleware('permission:fee_statements.view');

            Route::get(
                '/student/{student}',
                [StudentFeeStatementController::class, 'show']
            )
                ->name('show')
                ->middleware('permission:fee_statements.view');

        });/*
|--------------------------------------------------------------------------
| Class Teacher Assignments
|--------------------------------------------------------------------------
|
| Administrative class-teacher assignment management.
| Individual routes enforce the required teachers.* permissions.
|
*/

Route::prefix('class-teacher-assignments')
    ->name('class-teacher-assignments.')
    ->group(function () {

        Route::get(
            '/streams/{schoolClassId}',
            [ClassTeacherAssignmentController::class, 'streams']
        )
            ->name('streams')
            ->middleware('permission:teachers.view');

        Route::get(
            '/',
            [ClassTeacherAssignmentController::class, 'index']
        )
            ->name('index')
            ->middleware('permission:teachers.view');

        Route::get(
            '/create',
            [ClassTeacherAssignmentController::class, 'create']
        )
            ->name('create')
            ->middleware('permission:teachers.create');

        Route::post(
            '/',
            [ClassTeacherAssignmentController::class, 'store']
        )
            ->name('store')
            ->middleware('permission:teachers.create');

        Route::get(
            '/{class_teacher_assignment}/edit',
            [ClassTeacherAssignmentController::class, 'edit']
        )
            ->name('edit')
            ->middleware('permission:teachers.edit');

        Route::put(
            '/{class_teacher_assignment}',
            [ClassTeacherAssignmentController::class, 'update']
        )
            ->name('update')
            ->middleware('permission:teachers.edit');

        Route::delete(
            '/{class_teacher_assignment}',
            [ClassTeacherAssignmentController::class, 'destroy']
        )
            ->name('destroy')
            ->middleware('permission:teachers.delete');

    });


/*
|--------------------------------------------------------------------------
| Class Teacher Dashboard
|--------------------------------------------------------------------------
|
| Accessible to authenticated users.
| The controller determines the logged-in teacher's assignment.
|
| IMPORTANT:
| Do NOT add permission:teachers.view here.
|
*/

Route::get(
    '/class-teacher-dashboard',
    [ClassTeacherDashboardController::class, 'index']
)
    ->name('class-teacher-dashboard.index');


/*
|--------------------------------------------------------------------------
| Stream Reports
|--------------------------------------------------------------------------
*/

Route::middleware('permission:student_report_cards.view')->group(function () {

    Route::get(
        '/stream-reports',
        [StreamReportController::class, 'index']
    )
        ->name('stream-reports.index');

    Route::get(
        '/stream-reports/classes/{schoolClass}/exams',
        [StreamReportController::class, 'examsByClass']
    )
        ->name('stream-reports.exams-by-class');

    Route::post(
        '/stream-reports/load',
        [StreamReportController::class, 'load']
    )
        ->name('stream-reports.load');

});

/*
|--------------------------------------------------------------------------
| Audit Logs
|--------------------------------------------------------------------------
|
| Only Super Admin users can access audit logs.
|
*/

Route::prefix('audit-logs')
    ->name('audit-logs.')
    ->middleware('role:Super Admin')
    ->group(function () {

        Route::get(
            '/',
            [AuditLogController::class, 'index']
        )
            ->name('index');

        Route::get(
            '/{auditLog}',
            [AuditLogController::class, 'show']
        )
            ->name('show');

    });

    Route::get(
    '/student-documents/{studentDocument}/download',
    [StudentDocumentController::class, 'download']
)->name('students.documents.download');


/*
|--------------------------------------------------------------------------
| End Authenticated Routes
|--------------------------------------------------------------------------
*/

});