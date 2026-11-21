{{-- resources/views/layouts/sidebar.blade.php --}}

@php
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Permission Helper
    |--------------------------------------------------------------------------
    */
    $can = function (string $permission): bool {
        return auth()->check() && auth()->user()->can($permission);
    };

    /*
    |--------------------------------------------------------------------------
    | Active Navigation Groups
    |--------------------------------------------------------------------------
    */
    $administrationOpen =
        request()->routeIs('school.*') ||
        request()->routeIs('academic-years.*') ||
        request()->routeIs('terms.*');

    $academicsOpen =
        request()->routeIs('classes.*') ||
        request()->routeIs('streams.*') ||
        request()->routeIs('subjects.*') ||
        request()->routeIs('subject-allocations.*') ||
        request()->routeIs('teacher-allocations.*') ||
        request()->routeIs('timetables.*');

    $peopleOpen =
        request()->routeIs('students.*') ||
        request()->routeIs('admissions.*') ||
        request()->routeIs('attendance.*') ||
        request()->routeIs('promotions.*') ||
        request()->routeIs('discipline.*') ||
        request()->routeIs('teachers.*') ||
        request()->routeIs('class-teacher-assignments.*');

    $examinationsOpen =
        request()->routeIs('exam-types.*') ||
        request()->routeIs('exams.*') ||
        request()->routeIs('exam-marks.*') ||
        request()->routeIs('class-mark-sheet.*') ||
        request()->routeIs('grades.*') ||
        request()->routeIs('student-report-card.*');

    $financeOpen =
        request()->routeIs('finance.*') ||
        request()->routeIs('fee-categories.*') ||
        request()->routeIs('fee-structures.*') ||
        request()->routeIs('student-fees.*') ||
        request()->routeIs('fee-payments.*') ||
        request()->routeIs('fee-statements.*');

    $reportsOpen =
        request()->routeIs('reports.*') ||
        request()->routeIs('student-reports.*');

    $systemOpen =
        request()->routeIs('users.*') ||
        request()->routeIs('roles.*') ||
        request()->routeIs('permissions.*') ||
        request()->routeIs('backups.*') ||
        request()->routeIs('audit-logs.*') ||
        request()->routeIs('settings.*');
@endphp


<style>
    /* ============================================================
       SCHOLAR ERP SIDEBAR
       ============================================================ */

    :root {
        --scholar-sidebar-width: 272px;
        --scholar-sidebar-collapsed-width: 76px;
        --scholar-sidebar-bg: #0f172a;
        --scholar-sidebar-bg-dark: #0b1220;
        --scholar-sidebar-border: rgba(255, 255, 255, 0.07);
        --scholar-sidebar-text: #cbd5e1;
        --scholar-sidebar-muted: #64748b;
        --scholar-sidebar-hover: rgba(255, 255, 255, 0.07);
        --scholar-sidebar-active: rgba(59, 130, 246, 0.16);
        --scholar-sidebar-active-border: #3b82f6;
        --scholar-sidebar-accent: #60a5fa;
    }

    /* ============================================================
       SIDEBAR
       ============================================================ */

    .scholar-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1050;

        width: var(--scholar-sidebar-width);

        display: flex;
        flex-direction: column;

        background:
            linear-gradient(
                180deg,
                var(--scholar-sidebar-bg) 0%,
                var(--scholar-sidebar-bg-dark) 100%
            );

        border-right: 1px solid var(--scholar-sidebar-border);

        box-shadow: 8px 0 30px rgba(15, 23, 42, 0.08);

        transition:
            width 0.25s ease,
            transform 0.25s ease;
    }

    /* ============================================================
       BRAND
       ============================================================ */

    .scholar-sidebar-brand {
        height: 74px;
        min-height: 74px;

        display: flex;
        align-items: center;

        padding: 0 18px;

        border-bottom: 1px solid var(--scholar-sidebar-border);
    }

    .scholar-brand-link {
        display: flex;
        align-items: center;
        gap: 12px;

        min-width: 0;

        color: #ffffff;
        text-decoration: none;
    }

    .scholar-brand-logo {
        width: 42px;
        height: 42px;

        flex: 0 0 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            );

        color: #ffffff;

        box-shadow:
            0 8px 20px rgba(37, 99, 235, 0.25);
    }

    .scholar-brand-logo i {
        font-size: 21px;
    }

    .scholar-brand-text {
        min-width: 0;
    }

    .scholar-brand-name {
        display: block;

        font-size: 17px;
        font-weight: 700;
        line-height: 1.2;

        color: #ffffff;

        white-space: nowrap;
    }

    .scholar-brand-subtitle {
        display: block;

        margin-top: 2px;

        font-size: 10px;
        font-weight: 600;

        letter-spacing: 0.08em;
        text-transform: uppercase;

        color: #64748b;

        white-space: nowrap;
    }

    /* ============================================================
       SIDEBAR BODY
       ============================================================ */

    .scholar-sidebar-body {
        flex: 1;

        min-height: 0;

        overflow-y: auto;
        overflow-x: hidden;

        padding: 14px 10px 16px;
    }

    .scholar-sidebar-body::-webkit-scrollbar {
        width: 5px;
    }

    .scholar-sidebar-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .scholar-sidebar-body::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.20);
        border-radius: 20px;
    }

    .scholar-sidebar-body {
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.20) transparent;
    }

    /* ============================================================
       SEARCH
       ============================================================ */

    .scholar-sidebar-search {
        position: relative;

        margin: 0 4px 18px;
    }

    .scholar-sidebar-search i {
        position: absolute;

        left: 12px;
        top: 50%;

        transform: translateY(-50%);

        color: #64748b;

        font-size: 14px;

        pointer-events: none;
    }

    .scholar-sidebar-search input {
        width: 100%;
        height: 38px;

        padding: 0 38px 0 35px;

        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 10px;

        outline: none;

        background: rgba(255, 255, 255, 0.045);

        color: #e2e8f0;

        font-size: 12px;

        transition: all 0.2s ease;
    }

    .scholar-sidebar-search input::placeholder {
        color: #64748b;
    }

    .scholar-sidebar-search input:focus {
        border-color: rgba(96, 165, 250, 0.45);

        background: rgba(255, 255, 255, 0.065);

        box-shadow:
            0 0 0 3px rgba(59, 130, 246, 0.08);
    }

    .scholar-search-shortcut {
        position: absolute;

        right: 9px;
        top: 50%;

        transform: translateY(-50%);

        padding: 2px 5px;

        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 5px;

        color: #64748b;

        font-size: 9px;
        font-weight: 600;

        background: rgba(255, 255, 255, 0.035);
    }

    /* ============================================================
       NAVIGATION SECTIONS
       ============================================================ */

    .scholar-nav-section {
        margin-bottom: 18px;
    }

    .scholar-nav-section-title {
        padding: 0 12px 7px;

        color: #475569;

        font-size: 9px;
        font-weight: 700;

        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    /* ============================================================
       NAVIGATION LINKS
       ============================================================ */

    .scholar-nav-link {
        position: relative;

        display: flex;
        align-items: center;

        min-height: 42px;

        margin: 2px 0;
        padding: 0 12px;

        gap: 11px;

        border-radius: 9px;

        color: var(--scholar-sidebar-text);

        text-decoration: none;

        font-size: 13px;
        font-weight: 500;

        transition:
            background 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease;
    }

    .scholar-nav-link i {
        width: 20px;
        min-width: 20px;

        text-align: center;

        color: #64748b;

        font-size: 16px;

        transition: color 0.18s ease;
    }

    .scholar-nav-link span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .scholar-nav-link:hover {
        color: #ffffff;

        background: var(--scholar-sidebar-hover);

        transform: translateX(1px);
    }

    .scholar-nav-link:hover i {
        color: #93c5fd;
    }

    .scholar-nav-link.active {
        color: #ffffff;

        background: var(--scholar-sidebar-active);
    }

    .scholar-nav-link.active::before {
        content: "";

        position: absolute;

        left: 0;
        top: 8px;
        bottom: 8px;

        width: 3px;

        border-radius: 0 4px 4px 0;

        background: var(--scholar-sidebar-active-border);
    }

    .scholar-nav-link.active i {
        color: var(--scholar-sidebar-accent);
    }

    /* ============================================================
       NAVIGATION BADGE
       ============================================================ */

    .scholar-nav-badge {
        margin-left: auto;

        padding: 2px 6px;

        border-radius: 20px;

        background: rgba(59, 130, 246, 0.14);

        color: #93c5fd;

        font-size: 9px;
        font-weight: 700;
    }

    /* ============================================================
       MOBILE OVERLAY
       ============================================================ */

    .scholar-sidebar-overlay {
        display: none;

        position: fixed;
        inset: 0;

        z-index: 1040;

        background: rgba(15, 23, 42, 0.55);

        backdrop-filter: blur(2px);
    }

    /* ============================================================
       MAIN CONTENT SUPPORT
       ============================================================ */

    .scholar-main-content {
        margin-left: var(--scholar-sidebar-width);

        transition: margin-left 0.25s ease;
    }

    /* ============================================================
       COLLAPSED SIDEBAR
       ============================================================ */

    body.scholar-sidebar-collapsed .scholar-sidebar {
        width: var(--scholar-sidebar-collapsed-width);
    }

    body.scholar-sidebar-collapsed .scholar-main-content {
        margin-left: var(--scholar-sidebar-collapsed-width);
    }

    body.scholar-sidebar-collapsed .scholar-brand-text,
    body.scholar-sidebar-collapsed .scholar-sidebar-search,
    body.scholar-sidebar-collapsed .scholar-nav-section-title,
    body.scholar-sidebar-collapsed .scholar-nav-link span,
    body.scholar-sidebar-collapsed .scholar-nav-badge,
    body.scholar-sidebar-collapsed .scholar-user-info {
        display: none;
    }

    body.scholar-sidebar-collapsed .scholar-sidebar-brand {
        justify-content: center;
        padding: 0;
    }

    body.scholar-sidebar-collapsed .scholar-brand-link {
        justify-content: center;
    }

    body.scholar-sidebar-collapsed .scholar-nav-link {
        justify-content: center;
        padding: 0;
    }

    body.scholar-sidebar-collapsed .scholar-nav-link i {
        margin: 0;
    }

    body.scholar-sidebar-collapsed .scholar-nav-link.active::before {
        display: none;
    }

    body.scholar-sidebar-collapsed .scholar-user-card {
        justify-content: center;
        padding: 10px 0;
    }

    /* ============================================================
       USER FOOTER
       ============================================================ */

    .scholar-sidebar-footer {
        padding: 12px 10px;

        border-top: 1px solid var(--scholar-sidebar-border);
    }

    .scholar-user-card {
        display: flex;
        align-items: center;

        gap: 10px;

        padding: 8px 9px;

        border-radius: 10px;

        background: rgba(255, 255, 255, 0.035);
    }

    .scholar-user-avatar {
        width: 34px;
        height: 34px;

        min-width: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: linear-gradient(
            135deg,
            #2563eb,
            #1e40af
        );

        color: #ffffff;

        font-size: 12px;
        font-weight: 700;
    }

    .scholar-user-info {
        min-width: 0;
        flex: 1;
    }

    .scholar-user-name {
        display: block;

        max-width: 170px;

        color: #e2e8f0;

        font-size: 11px;
        font-weight: 600;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .scholar-user-role {
        display: block;

        margin-top: 2px;

        color: #64748b;

        font-size: 9px;
        font-weight: 500;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .scholar-logout-form {
        margin: 0;
    }

    .scholar-logout-button {
        width: 30px;
        height: 30px;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 0;

        border: 0;
        border-radius: 7px;

        background: transparent;

        color: #64748b;

        transition: all 0.18s ease;
    }

    .scholar-logout-button:hover {
        background: rgba(239, 68, 68, 0.10);

        color: #f87171;
    }

    /* ============================================================
       MOBILE
       ============================================================ */

    @media (max-width: 991.98px) {

        .scholar-sidebar {
            transform: translateX(-100%);

            width: var(--scholar-sidebar-width) !important;
        }

        body.scholar-sidebar-mobile-open .scholar-sidebar {
            transform: translateX(0);
        }

        body.scholar-sidebar-mobile-open .scholar-sidebar-overlay {
            display: block;
        }

        .scholar-main-content {
            margin-left: 0 !important;
        }

        body.scholar-sidebar-collapsed .scholar-sidebar {
            width: var(--scholar-sidebar-width) !important;
        }

        body.scholar-sidebar-collapsed .scholar-brand-text,
        body.scholar-sidebar-collapsed .scholar-sidebar-search,
        body.scholar-sidebar-collapsed .scholar-nav-section-title,
        body.scholar-sidebar-collapsed .scholar-nav-link span,
        body.scholar-sidebar-collapsed .scholar-nav-badge,
        body.scholar-sidebar-collapsed .scholar-user-info {
            display: block;
        }

        body.scholar-sidebar-collapsed .scholar-sidebar-brand {
            justify-content: flex-start;
            padding: 0 18px;
        }

        body.scholar-sidebar-collapsed .scholar-nav-link {
            justify-content: flex-start;
            padding: 0 12px;
        }

        body.scholar-sidebar-collapsed .scholar-nav-link.active::before {
            display: block;
        }

        body.scholar-sidebar-collapsed .scholar-user-card {
            justify-content: flex-start;
            padding: 8px 9px;
        }
    }
</style>


{{-- ============================================================
     MOBILE OVERLAY
     ============================================================ --}}
<div
    class="scholar-sidebar-overlay"
    id="scholarSidebarOverlay"
    aria-hidden="true">
</div>


{{-- ============================================================
     SIDEBAR
     ============================================================ --}}
<aside
    class="scholar-sidebar"
    id="scholarSidebar"
    aria-label="Main navigation">


    {{-- ========================================================
         BRAND
         ======================================================== --}}
    <div class="scholar-sidebar-brand">

        <a
            href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}"
            class="scholar-brand-link">

            <div class="scholar-brand-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div class="scholar-brand-text">

                <span class="scholar-brand-name">
                    ScholarERP
                </span>

                <span class="scholar-brand-subtitle">
                    School Management
                </span>

            </div>

        </a>

    </div>


    {{-- ========================================================
         SIDEBAR BODY
         ======================================================== --}}
    <div class="scholar-sidebar-body">


        {{-- ====================================================
             SEARCH
             ==================================================== --}}
        <div class="scholar-sidebar-search">

            <i class="bi bi-search"></i>

            <input
                type="text"
                id="scholarSidebarSearch"
                placeholder="Search menu..."
                autocomplete="off">

            <span class="scholar-search-shortcut">
                Ctrl K
            </span>

        </div>


        {{-- ====================================================
             DASHBOARD
             ==================================================== --}}
        @if($can('dashboard.view') && Route::has('dashboard'))

            <div class="scholar-nav-section">

                <a
                    href="{{ route('dashboard') }}"
                    class="scholar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    data-menu-search="dashboard home">

                    <i class="bi bi-grid-1x2-fill"></i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </div>

        @endif


        {{-- ====================================================
             ADMINISTRATION
             ==================================================== --}}
        @if(
            $can('schools.view') ||
            $can('academic_years.view') ||
            $can('terms.view')
        )

            <div class="scholar-nav-section">

                <div class="scholar-nav-section-title">
                    Administration
                </div>


                {{-- School Setup --}}
                @if($can('schools.view') && Route::has('school.edit'))

                    <a
                        href="{{ route('school.edit') }}"
                        class="scholar-nav-link {{ request()->routeIs('school.*') ? 'active' : '' }}"
                        data-menu-search="school setup institution">

                        <i class="bi bi-building"></i>

                        <span>
                            School Setup
                        </span>

                    </a>

                @endif


                {{-- Academic Years --}}
                @if($can('academic_years.view') && Route::has('academic-years.index'))

                    <a
                        href="{{ route('academic-years.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}"
                        data-menu-search="academic years">

                        <i class="bi bi-calendar3"></i>

                        <span>
                            Academic Years
                        </span>

                    </a>

                @endif


                {{-- Terms --}}
                @if($can('terms.view') && Route::has('terms.index'))

                    <a
                        href="{{ route('terms.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('terms.*') ? 'active' : '' }}"
                        data-menu-search="terms semester">

                        <i class="bi bi-calendar-week"></i>

                        <span>
                            Terms
                        </span>

                    </a>

                @endif

            </div>

        @endif


        {{-- ====================================================
             ACADEMICS
             ==================================================== --}}
        @if(
            $can('classes.view') ||
            $can('streams.view') ||
            $can('subjects.view') ||
            $can('subject_allocations.view') ||
            $can('teacher_allocations.view') ||
            $can('timetables.view')
        )

            <div class="scholar-nav-section">

                <div class="scholar-nav-section-title">
                    Academics
                </div>


                {{-- Classes --}}
                @if($can('classes.view') && Route::has('classes.index'))

                    <a
                        href="{{ route('classes.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}"
                        data-menu-search="classes">

                        <i class="bi bi-diagram-3-fill"></i>

                        <span>
                            Classes
                        </span>

                    </a>

                @endif


                {{-- Streams --}}
                @if($can('streams.view') && Route::has('streams.index'))

                    <a
                        href="{{ route('streams.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('streams.*') ? 'active' : '' }}"
                        data-menu-search="streams">

                        <i class="bi bi-layers-fill"></i>

                        <span>
                            Streams
                        </span>

                    </a>

                @endif


                {{-- Subjects --}}
                @if($can('subjects.view') && Route::has('subjects.index'))

                    <a
                        href="{{ route('subjects.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}"
                        data-menu-search="subjects">

                        <i class="bi bi-book-half"></i>

                        <span>
                            Subjects
                        </span>

                    </a>

                @endif


                {{-- Subject Allocations --}}
                @if(
                    $can('subject_allocations.view') &&
                    Route::has('subject-allocations.index')
                )

                    <a
                        href="{{ route('subject-allocations.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('subject-allocations.*') ? 'active' : '' }}"
                        data-menu-search="subject allocations">

                        <i class="bi bi-journal-check"></i>

                        <span>
                            Subject Allocations
                        </span>

                    </a>

                @endif


                {{-- Teacher Allocations --}}
                @if(
                    $can('teacher_allocations.view') &&
                    Route::has('teacher-allocations.index')
                )

                    <a
                        href="{{ route('teacher-allocations.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('teacher-allocations.*') ? 'active' : '' }}"
                        data-menu-search="teacher allocations">

                        <i class="bi bi-person-workspace"></i>

                        <span>
                            Teacher Allocations
                        </span>

                    </a>

                @endif
{{-- ====================================================
     TIMETABLES
     ==================================================== --}}
@if($can('timetables.view'))

    <div class="scholar-nav-section">

        <div class="scholar-nav-section-title">
            Timetables
        </div>

        {{-- Timetable Blocks --}}
        @if(Route::has('timetables.blocks.index'))

            <a
                href="{{ route('timetables.blocks.index') }}"
                class="scholar-nav-link {{ request()->routeIs('timetables.blocks.*') ? 'active' : '' }}"
                data-menu-search="timetables timetable blocks">

                <i class="bi bi-calendar3"></i>

                <span>
                    Timetable Blocks
                </span>

            </a>

        @endif


        {{-- Timetable Periods --}}
        @if(Route::has('timetables.periods.index'))

            <a
                href="{{ route('timetables.periods.index') }}"
                class="scholar-nav-link {{ request()->routeIs('timetables.periods.*') ? 'active' : '' }}"
                data-menu-search="timetables timetable periods">

                <i class="bi bi-clock-history"></i>

                <span>
                    Timetable Periods
                </span>

            </a>

        @endif

    </div>

@endif

        {{-- ====================================================
             PEOPLE
             ==================================================== --}}
        @if(
            $can('students.view') ||
            $can('admissions.view') ||
            $can('attendance.view') ||
            $can('promotions.view') ||
            $can('discipline.view') ||
            $can('teachers.view')
        )

            <div class="scholar-nav-section">

                <div class="scholar-nav-section-title">
                    People
                </div>


                {{-- New Admission --}}
                @if(
                    $can('admissions.view') &&
                    Route::has('students.create')
                )

                    <a
                        href="{{ route('students.create') }}"
                        class="scholar-nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}"
                        data-menu-search="new admission admission student registration">

                        <i class="bi bi-person-plus-fill"></i>

                        <span>
                            New Admission
                        </span>

                    </a>

                @endif


                {{-- Student List --}}
                @if(
                    $can('students.view') &&
                    Route::has('students.index')
                )

                    <a
                        href="{{ route('students.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('students.*') && !request()->routeIs('students.create') ? 'active' : '' }}"
                        data-menu-search="students student list">

                        <i class="bi bi-people-fill"></i>

                        <span>
                            Student List
                        </span>

                    </a>

                @endif


                {{-- Attendance --}}
                @if(
                    $can('attendance.view') &&
                    Route::has('attendance.index')
                )

                    <a
                        href="{{ route('attendance.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}"
                        data-menu-search="attendance students">

                        <i class="bi bi-calendar-check-fill"></i>

                        <span>
                            Attendance
                        </span>

                    </a>

                @endif


                {{-- Promotion --}}
                @if(
                    $can('promotions.view') &&
                    Route::has('student-promotions.index')
                )

                    <a
                        href="{{ route('student-promotions.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}"
                        data-menu-search="promotion student promotion">

                        <i class="bi bi-arrow-up-circle-fill"></i>

                        <span>
                            Promotion
                        </span>

                    </a>

                @endif


                {{-- Discipline --}}
                @if(
                    $can('discipline.view') &&
                    Route::has('discipline.index')
                )

                    <a
                        href="{{ route('discipline.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('discipline.*') ? 'active' : '' }}"
                        data-menu-search="discipline">

                        <i class="bi bi-shield-exclamation"></i>

                        <span>
                            Discipline
                        </span>

                    </a>

                @endif


                {{-- Teachers --}}
                @if(
                    $can('teachers.view') &&
                    Route::has('teachers.index')
                )

                    <a
                        href="{{ route('teachers.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}"
                        data-menu-search="teachers staff">

                        <i class="bi bi-person-badge-fill"></i>

                        <span>
                            Teachers
                        </span>

                    </a>

                @endif


                {{-- Class Teachers --}}
                @if(
                    $can('teachers.view') &&
                    Route::has('class-teacher-assignments.index')
                )

                    <a
                        href="{{ route('class-teacher-assignments.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('class-teacher-assignments.*') ? 'active' : '' }}"
                        data-menu-search="class teachers teacher assignment">

                        <i class="bi bi-person-check-fill"></i>

                        <span>
                            Class Teachers
                        </span>

                    </a>

                @endif

            </div>

        @endif

{{-- ====================================================
     CLASS TEACHER
     ==================================================== --}}
@auth

    @php
        $user = auth()->user();

        $isClassTeacher = false;

        /*
        |--------------------------------------------------------------------------
        | Determine whether the logged-in teacher is currently
        | assigned as a Class Teacher.
        |--------------------------------------------------------------------------
        */

        if ($user->teacher) {
            $isClassTeacher = \App\Models\ClassTeacherAssignment::query()
                ->where('teacher_id', $user->teacher->id)
                ->where('status', 'Active')
                ->whereHas('academicYear', function ($query) {
                    $query->where('is_current', true);
                })
                ->whereHas('term', function ($query) {
                    $query->where('is_current', true);
                })
                ->exists();
        }
    @endphp

    @if(
        $isClassTeacher &&
        (
            Route::has('class-teacher-dashboard.index') ||
            Route::has('stream-reports.index')
        )
    )

        <div class="scholar-nav-section">

            <div class="scholar-nav-section-title">
                Class Teacher
            </div>

            {{-- Class Teacher Dashboard --}}
            @if(Route::has('class-teacher-dashboard.index'))

                <a
                    href="{{ route('class-teacher-dashboard.index') }}"
                    class="scholar-nav-link {{ request()->routeIs('class-teacher-dashboard.*') ? 'active' : '' }}"
                    data-menu-search="class teacher dashboard">

                    <i class="bi bi-speedometer2"></i>

                    <span>
                        Class Teacher Dashboard
                    </span>

                </a>

            @endif

            {{-- Stream Reports --}}
            @if(Route::has('stream-reports.index'))

                <a
                    href="{{ route('stream-reports.index') }}"
                    class="scholar-nav-link {{ request()->routeIs('stream-reports.*') ? 'active' : '' }}"
                    data-menu-search="stream reports">

                    <i class="bi bi-bar-chart"></i>

                    <span>
                        Stream Reports
                    </span>

                </a>

            @endif

        </div>

    @endif
@endif


        {{-- ====================================================
             EXAMINATIONS
             ==================================================== --}}
        @if(
            $can('exam_types.view') ||
            $can('exams.view') ||
            $can('marks.view') ||
            $can('class_mark_sheet.view') ||
            $can('grades.view') ||
            $can('student_report_cards.view')
        )

            <div class="scholar-nav-section">

                <div class="scholar-nav-section-title">
                    Examinations
                </div>


                {{-- Exam Types --}}
                @if(
                    $can('exam_types.view') &&
                    Route::has('exam-types.index')
                )

                    <a
                        href="{{ route('exam-types.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('exam-types.*') ? 'active' : '' }}"
                        data-menu-search="exam types examinations">

                        <i class="bi bi-list-check"></i>

                        <span>
                            Exam Types
                        </span>

                    </a>

                @endif


                {{-- Exams --}}
                @if(
                    $can('exams.view') &&
                    Route::has('exams.index')
                )

                    <a
                        href="{{ route('exams.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}"
                        data-menu-search="exams examinations">

                        <i class="bi bi-file-earmark-text-fill"></i>

                        <span>
                            Exams
                        </span>

                    </a>

                @endif


                {{-- Exam Marks --}}
                @if(
                    $can('marks.view') &&
                    Route::has('exam-marks.index')
                )

                    <a
                        href="{{ route('exam-marks.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('exam-marks.*') ? 'active' : '' }}"
                        data-menu-search="exam marks marks">

                        <i class="bi bi-pencil-square"></i>

                        <span>
                            Exam Marks
                        </span>

                    </a>

                @endif


                {{-- Class Mark Sheet --}}
                @if(
                    $can('class_mark_sheet.view') &&
                    Route::has('class-mark-sheet.index')
                )

                    <a
                        href="{{ route('class-mark-sheet.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('class-mark-sheet.*') ? 'active' : '' }}"
                        data-menu-search="class mark sheet marks">

                        <i class="bi bi-table"></i>

                        <span>
                            Class Mark Sheet
                        </span>

                    </a>

                @endif


                {{-- Grades --}}
                @if(
                    $can('grades.view') &&
                    Route::has('grades.index')
                )

                    <a
                        href="{{ route('grades.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}"
                        data-menu-search="grades grading">

                        <i class="bi bi-award-fill"></i>

                        <span>
                            Grades
                        </span>

                    </a>

                @endif

{{-- Student Report Cards --}}
@if(
    $can('student_report_cards.view') &&
    Route::has('student-report-card.index')
)

    <a
        href="{{ route('student-report-card.index') }}"
        class="scholar-nav-link {{ request()->routeIs('student-report-card.*') ? 'active' : '' }}"
        data-menu-search="student report cards report card">

        <i class="bi bi-file-earmark-person-fill"></i>

        <span>
            Student Report Cards
        </span>

    </a>

@endif


            </div>

        @endif
        @endif

        {{-- ====================================================
             FINANCE
             ==================================================== --}}
        @if(
            $can('finance.view') ||
            $can('fee_categories.view') ||
            $can('fee_structures.view') ||
            $can('student_fees.view') ||
            $can('fee_payments.view') ||
            $can('fee_statements.view')
        )

            <div class="scholar-nav-section">

                <div class="scholar-nav-section-title">
                    Finance
                </div>


                {{-- Finance Dashboard --}}
                @if(
                    $can('finance.view') &&
                    Route::has('finance.dashboard')
                )

                    <a
                        href="{{ route('finance.dashboard') }}"
                        class="scholar-nav-link {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}"
                        data-menu-search="finance dashboard finance">

                        <i class="bi bi-wallet2"></i>

                        <span>
                            Finance Dashboard
                        </span>

                    </a>

                @endif


                {{-- Fee Categories --}}
                @if(
                    $can('fee_categories.view') &&
                    Route::has('fee-categories.index')
                )

                    <a
                        href="{{ route('fee-categories.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('fee-categories.*') ? 'active' : '' }}"
                        data-menu-search="fee categories fees">

                        <i class="bi bi-tags-fill"></i>

                        <span>
                            Fee Categories
                        </span>

                    </a>

                @endif


                {{-- Fee Structures --}}
                @if(
                    $can('fee_structures.view') &&
                    Route::has('fee-structures.index')
                )

                    <a
                        href="{{ route('fee-structures.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('fee-structures.*') ? 'active' : '' }}"
                        data-menu-search="fee structures school fees">

                        <i class="bi bi-diagram-2-fill"></i>

                        <span>
                            Fee Structures
                        </span>

                    </a>

                @endif


                {{-- Student Fees --}}
                @if(
                    $can('student_fees.view') &&
                    Route::has('student-fees.index')
                )

                    <a
                        href="{{ route('student-fees.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('student-fees.*') ? 'active' : '' }}"
                        data-menu-search="student fees fees balances">

                        <i class="bi bi-person-vcard-fill"></i>

                        <span>
                            Student Fees
                        </span>

                    </a>

                @endif


                {{-- Fee Payments --}}
                @if(
                    $can('fee_payments.view') &&
                    Route::has('fee-payments.index')
                )

                    <a
                        href="{{ route('fee-payments.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('fee-payments.*') ? 'active' : '' }}"
                        data-menu-search="fee payments payments receipts">

                        <i class="bi bi-cash-stack"></i>

                        <span>
                            Fee Payments
                        </span>

                    </a>

                @endif


                {{-- Fee Statements --}}
                @if(
                    $can('fee_statements.view') &&
                    Route::has('fee-statements.index')
                )

                    <a
                        href="{{ route('fee-statements.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('fee-statements.*') ? 'active' : '' }}"
                        data-menu-search="fee statements statements">

                        <i class="bi bi-receipt-cutoff"></i>

                        <span>
                            Fee Statements
                        </span>

                    </a>

                @endif

            </div>

        @endif


        {{-- ====================================================
             REPORTS
             ==================================================== --}}
        @if(
            $can('reports.view') &&
            (
                Route::has('reports.index') ||
                Route::has('student-reports.index')
            )
        )

            <div class="scholar-nav-section">

                <div class="scholar-nav-section-title">
                    Reports
                </div>


                {{-- General Reports --}}
                @if(Route::has('reports.index'))

                    <a
                        href="{{ route('reports.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        data-menu-search="reports analytics reports">

                        <i class="bi bi-bar-chart-fill"></i>

                        <span>
                            Reports & Analytics
                        </span>

                    </a>

                @endif



{{-- Student Reports --}}
@if(
    $can('student_report_cards.view') &&
    Route::has('student-report-card.index')
)
    <a
        href="{{ route('student-report-card.index') }}"
        class="scholar-nav-link {{ request()->routeIs('student-report-card.*') ? 'active' : '' }}"
        data-menu-search="student report cards report card">
        <i class="bi bi-file-earmark-person-fill"></i>
        <span>Student Report Cards</span>
    </a>
@endif

@endif


        {{-- ====================================================
             SYSTEM
             ==================================================== --}}
        @if(
            $can('users.view') ||
            $can('roles.view') ||
            $can('permissions.view') ||
            $can('settings.view') ||
            $user?->hasRole('Super Admin')
        )

            <div class="scholar-nav-section">

                <div class="scholar-nav-section-title">
                    System
                </div>


                {{-- Users --}}
                @if(
                    $can('users.view') &&
                    Route::has('users.index')
                )

                    <a
                        href="{{ route('users.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                        data-menu-search="users system users">

                        <i class="bi bi-people"></i>

                        <span>
                            Users
                        </span>

                    </a>

                @endif


                {{-- Roles --}}
                @if(
                    $can('roles.view') &&
                    Route::has('roles.index')
                )

                    <a
                        href="{{ route('roles.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                        data-menu-search="roles permissions">

                        <i class="bi bi-person-gear"></i>

                        <span>
                            Roles
                        </span>

                    </a>

                @endif


                {{-- Permissions --}}
                @if(
                    $can('permissions.view') &&
                    Route::has('permissions.index')
                )

                    <a
                        href="{{ route('permissions.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}"
                        data-menu-search="permissions access control">

                        <i class="bi bi-key-fill"></i>

                        <span>
                            Permissions
                        </span>

                    </a>

                @endif


                {{-- Settings --}}
                @if(
                    $can('settings.view') &&
                    Route::has('settings.index')
                )

                    <a
                        href="{{ route('settings.index') }}"
                        class="scholar-nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                        data-menu-search="settings configuration">

                        <i class="bi bi-gear-fill"></i>

                        <span>
                            Settings
                        </span>

                    </a>

                @endif


                {{-- =================================================
                     SUPER ADMIN ONLY
                     ================================================= --}}

                @if($user?->hasRole('Super Admin'))

                    {{-- Backups --}}
                    @if(Route::has('backups.index'))

                        <a
                            href="{{ route('backups.index') }}"
                            class="scholar-nav-link {{ request()->routeIs('backups.*') ? 'active' : '' }}"
                            data-menu-search="backup backups restore">

                            <i class="bi bi-database-fill-up"></i>

                            <span>
                                Backup & Restore
                            </span>

                        </a>

                    @endif


                    {{-- Audit Logs --}}
                    @if(Route::has('audit-logs.index'))

                        <a
                            href="{{ route('audit-logs.index') }}"
                            class="scholar-nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}"
                            data-menu-search="audit logs activity">

                            <i class="bi bi-shield-check"></i>

                            <span>
                                Audit Logs
                            </span>

                        </a>

                    @endif

                @endif

            </div>

        @endif


    </div>


    {{-- ========================================================
         SIDEBAR FOOTER
         ======================================================== --}}
    <div class="scholar-sidebar-footer">

        <div class="scholar-user-card">

            <div class="scholar-user-avatar">

                @if($user)

                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}

                @else

                    U

                @endif

            </div>


            <div class="scholar-user-info">

                <span class="scholar-user-name">

                    {{ $user?->name ?? 'User' }}

                </span>


                <span class="scholar-user-role">

                    @if($user)

                        {{ $user->getRoleNames()->first() ?? 'User' }}

                    @else

                        User

                    @endif

                </span>

            </div>


            {{-- Logout --}}
            @auth

                <form
                    method="POST"
                    action="{{ Route::has('logout') ? route('logout') : '#' }}"
                    class="scholar-logout-form">

                    @csrf

                    @if(Route::has('logout'))

                        <button
                            type="submit"
                            class="scholar-logout-button"
                            title="Logout"
                            aria-label="Logout">

                            <i class="bi bi-box-arrow-right"></i>

                        </button>

                    @endif

                </form>

            @endauth

        </div>


        <div
            style="
                text-align:center;
                margin-top:8px;
                color:#475569;
                font-size:8px;
                font-weight:600;
                letter-spacing:.05em;
            ">

            Generated by ScholarERP

        </div>

    </div>

</aside>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const body = document.body;

        const sidebar = document.getElementById('scholarSidebar');

        const overlay = document.getElementById('scholarSidebarOverlay');

        const searchInput = document.getElementById('scholarSidebarSearch');


        /* ========================================================
           SIDEBAR COLLAPSE
           ======================================================== */

        window.toggleScholarSidebar = function () {

            if (window.innerWidth <= 991) {

                body.classList.toggle(
                    'scholar-sidebar-mobile-open'
                );

                return;
            }

            body.classList.toggle(
                'scholar-sidebar-collapsed'
            );

            try {

                localStorage.setItem(
                    'scholarSidebarCollapsed',
                    body.classList.contains(
                        'scholar-sidebar-collapsed'
                    )
                );

            } catch (error) {
                // Ignore localStorage errors.
            }
        };


        /* ========================================================
           RESTORE DESKTOP SIDEBAR STATE
           ======================================================== */

        try {

            const collapsed =
                localStorage.getItem(
                    'scholarSidebarCollapsed'
                );

            if (
                collapsed === 'true' &&
                window.innerWidth > 991
            ) {

                body.classList.add(
                    'scholar-sidebar-collapsed'
                );

            }

        } catch (error) {
            // Ignore localStorage errors.
        }


        /* ========================================================
           MOBILE SIDEBAR
           ======================================================== */

        if (overlay) {

            overlay.addEventListener(
                'click',
                function () {

                    body.classList.remove(
                        'scholar-sidebar-mobile-open'
                    );

                }
            );

        }


        /* ========================================================
           CLOSE MOBILE SIDEBAR WHEN LINK IS CLICKED
           ======================================================== */

        if (sidebar) {

            sidebar
                .querySelectorAll('.scholar-nav-link')
                .forEach(function (link) {

                    link.addEventListener(
                        'click',
                        function () {

                            if (window.innerWidth <= 991) {

                                body.classList.remove(
                                    'scholar-sidebar-mobile-open'
                                );

                            }

                        }
                    );

                });

        }


        /* ========================================================
           MENU SEARCH
           ======================================================== */

        if (searchInput) {

            searchInput.addEventListener(
                'input',
                function () {

                    const query =
                        this.value
                            .toLowerCase()
                            .trim();

                    const links =
                        sidebar
                            ? sidebar.querySelectorAll(
                                '.scholar-nav-link'
                            )
                            : [];

                    links.forEach(function (link) {

                        const searchableText =
                            (
                                link.innerText +
                                ' ' +
                                (
                                    link.dataset.menuSearch || ''
                                )
                            )
                            .toLowerCase();

                        if (
                            !query ||
                            searchableText.includes(query)
                        ) {

                            link.style.display = '';

                        } else {

                            link.style.display = 'none';

                        }

                    });


                    /* Hide empty section headings */
                    document
                        .querySelectorAll(
                            '.scholar-nav-section'
                        )
                        .forEach(function (section) {

                            const visibleLinks =
                                Array.from(
                                    section.querySelectorAll(
                                        '.scholar-nav-link'
                                    )
                                ).some(function (link) {

                                    return (
                                        link.style.display !== 'none'
                                    );

                                });

                            const title =
                                section.querySelector(
                                    '.scholar-nav-section-title'
                                );

                            if (title) {

                                title.style.display =
                                    visibleLinks
                                        ? ''
                                        : 'none';

                            }

                        });

                }
            );

        }


        /* ========================================================
           CTRL + K SEARCH
           ======================================================== */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    (event.ctrlKey || event.metaKey) &&
                    event.key.toLowerCase() === 'k'
                ) {

                    event.preventDefault();

                    if (searchInput) {

                        searchInput.focus();

                        searchInput.select();

                    }

                }

            }
        );


        /* ========================================================
           RESPONSIVE CLEANUP
           ======================================================== */

        window.addEventListener(
            'resize',
            function () {

                if (window.innerWidth > 991) {

                    body.classList.remove(
                        'scholar-sidebar-mobile-open'
                    );

                }

            }
        );

    });
</script>
