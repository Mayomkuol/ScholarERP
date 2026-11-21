@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="scholar-dashboard">

    {{-- ================================================================
        DASHBOARD HEADER
    ================================================================= --}}

    <div class="dashboard-header">

        <div>
            <div class="dashboard-eyebrow">
                <i class="bi bi-grid-1x2-fill"></i>
                ScholarERP Administration
            </div>

            <h1>Dashboard</h1>

            <p>
                Welcome back.
                Here's an overview of your school's academic operations.
            </p>
        </div>

        <div class="dashboard-date">
            <div class="date-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div>
                <span>Today</span>
                <strong>{{ $currentDate }}</strong>
            </div>
        </div>

    </div>


    {{-- ================================================================
        ACADEMIC SESSION BAR
    ================================================================= --}}

    <div class="session-card">

        <div class="session-left">

            <div class="session-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>
                <span class="session-label">
                    Current Academic Session
                </span>

                <h3>
                    {{ $academicYear ?: 'Academic Year Not Set' }}
                </h3>
            </div>

        </div>

        <div class="session-term">

            <span>Current Term</span>

            <strong>
                {{ $currentTerm ?: 'Term Not Set' }}
            </strong>

        </div>

        @if($school)
            <div class="school-session">

                <span>School</span>

                <strong>
                    {{ $school->name ?? 'ScholarERP School' }}
                </strong>

            </div>
        @endif

    </div>


    {{-- ================================================================
        MAIN STATISTICS
    ================================================================= --}}

    <div class="stats-grid">

        {{-- Students --}}
        <div class="stat-card">

            <div class="stat-top">

                <div class="stat-icon students">
                    <i class="bi bi-people-fill"></i>
                </div>

                <span class="stat-label">
                    Students
                </span>

            </div>

            <div class="stat-value">
                {{ number_format($students) }}
            </div>

            <div class="stat-footer">
                <span>
                    <i class="bi bi-person-check-fill"></i>
                    Enrolled learners
                </span>

                @if($unassignedStudents > 0)
                    <span class="stat-warning">
                        {{ number_format($unassignedStudents) }} unassigned
                    </span>
                @else
                    <span class="stat-success">
                        All assigned
                    </span>
                @endif
            </div>

        </div>


        {{-- Teachers --}}
        <div class="stat-card">

            <div class="stat-top">

                <div class="stat-icon teachers">
                    <i class="bi bi-person-workspace"></i>
                </div>

                <span class="stat-label">
                    Teachers
                </span>

            </div>

            <div class="stat-value">
                {{ number_format($teachers) }}
            </div>

            <div class="stat-footer">

                <span>
                    <i class="bi bi-person-badge-fill"></i>
                    Teaching staff
                </span>

                <span class="stat-success">
                    Active workforce
                </span>

            </div>

        </div>


        {{-- Classes --}}
        <div class="stat-card">

            <div class="stat-top">

                <div class="stat-icon classes">
                    <i class="bi bi-building-fill"></i>
                </div>

                <span class="stat-label">
                    Classes
                </span>

            </div>

            <div class="stat-value">
                {{ number_format($classes) }}
            </div>

            <div class="stat-footer">

                <span>
                    <i class="bi bi-diagram-3-fill"></i>
                    Academic classes
                </span>

                <span>
                    {{ $classes > 0 ? 'Configured' : 'Not configured' }}
                </span>

            </div>

        </div>


        {{-- Subjects --}}
        <div class="stat-card">

            <div class="stat-top">

                <div class="stat-icon subjects">
                    <i class="bi bi-book-fill"></i>
                </div>

                <span class="stat-label">
                    Subjects
                </span>

            </div>

            <div class="stat-value">
                {{ number_format($subjects) }}
            </div>

            <div class="stat-footer">

                <span>
                    <i class="bi bi-journal-bookmark-fill"></i>
                    Academic subjects
                </span>

                <span>
                    Curriculum
                </span>

            </div>

        </div>

    </div>


    {{-- ================================================================
        DASHBOARD CONTENT
    ================================================================= --}}

    <div class="dashboard-grid">


        {{-- ============================================================
            STUDENT DISTRIBUTION
        ============================================================= --}}

        <div class="dashboard-card chart-card">

            <div class="card-header">

                <div>

                    <span class="card-eyebrow">
                        Enrollment
                    </span>

                    <h2>
                        Students by Class
                    </h2>

                    <p>
                        Student distribution across academic classes.
                    </p>

                </div>

                <div class="header-icon">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>

            </div>


            @if(count($studentClassLabels) > 0)

                <div class="chart-wrapper">

                    <canvas id="studentClassChart"></canvas>

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>

                    <h3>No enrollment data</h3>

                    <p>
                        Student class distribution will appear here
                        once students are assigned to classes.
                    </p>

                </div>

            @endif

        </div>


        {{-- ============================================================
            SCHOOL OVERVIEW
        ============================================================= --}}

        <div class="dashboard-card overview-card">

            <div class="card-header">

                <div>

                    <span class="card-eyebrow">
                        Overview
                    </span>

                    <h2>
                        School Structure
                    </h2>

                    <p>
                        Current structure of your school.
                    </p>

                </div>

                <div class="header-icon">
                    <i class="bi bi-pie-chart-fill"></i>
                </div>

            </div>


            <div class="overview-content">

                <div class="overview-chart">

                    <canvas id="schoolOverviewChart"></canvas>

                    <div class="chart-center">

                        <strong>
                            {{ number_format($students + $teachers + $classes) }}
                        </strong>

                        <span>
                            Records
                        </span>

                    </div>

                </div>


                <div class="overview-list">

                    <div class="overview-item">

                        <div class="overview-item-left">

                            <span class="overview-dot student-dot"></span>

                            <span>Students</span>

                        </div>

                        <strong>
                            {{ number_format($students) }}
                        </strong>

                    </div>


                    <div class="overview-item">

                        <div class="overview-item-left">

                            <span class="overview-dot teacher-dot"></span>

                            <span>Teachers</span>

                        </div>

                        <strong>
                            {{ number_format($teachers) }}
                        </strong>

                    </div>


                    <div class="overview-item">

                        <div class="overview-item-left">

                            <span class="overview-dot class-dot"></span>

                            <span>Classes</span>

                        </div>

                        <strong>
                            {{ number_format($classes) }}
                        </strong>

                    </div>


                    <div class="overview-item">

                        <div class="overview-item-left">

                            <span class="overview-dot subject-dot"></span>

                            <span>Subjects</span>

                        </div>

                        <strong>
                            {{ number_format($subjects) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        QUICK ACTIONS
    ================================================================= --}}

    <div class="dashboard-card quick-actions-card">

        <div class="card-header">

            <div>

                <span class="card-eyebrow">
                    Shortcuts
                </span>

                <h2>
                    Quick Actions
                </h2>

                <p>
                    Access the most frequently used ScholarERP modules.
                </p>

            </div>

            <div class="header-icon">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>

        </div>


        <div class="quick-actions">

            @if(Route::has('students.index'))

                <a href="{{ route('students.index') }}" class="quick-action">

                    <span class="quick-action-icon students">
                        <i class="bi bi-people-fill"></i>
                    </span>

                    <span>
                        <strong>Students</strong>
                        <small>Manage students</small>
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            @elseif(Route::has('students'))

                <a href="{{ route('students') }}" class="quick-action">

                    <span class="quick-action-icon students">
                        <i class="bi bi-people-fill"></i>
                    </span>

                    <span>
                        <strong>Students</strong>
                        <small>Manage students</small>
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            @endif


            @if(Route::has('teachers.index'))

                <a href="{{ route('teachers.index') }}" class="quick-action">

                    <span class="quick-action-icon teachers">
                        <i class="bi bi-person-workspace"></i>
                    </span>

                    <span>
                        <strong>Teachers</strong>
                        <small>Manage teaching staff</small>
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            @endif


            @if(Route::has('classes.index'))

                <a href="{{ route('classes.index') }}" class="quick-action">

                    <span class="quick-action-icon classes">
                        <i class="bi bi-building-fill"></i>
                    </span>

                    <span>
                        <strong>Classes</strong>
                        <small>Manage school classes</small>
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            @endif


            @if(Route::has('examinations.index'))

                <a href="{{ route('examinations.index') }}" class="quick-action">

                    <span class="quick-action-icon examinations">
                        <i class="bi bi-journal-check"></i>
                    </span>

                    <span>
                        <strong>Examinations</strong>
                        <small>Manage examinations</small>
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            @endif


            @if(Route::has('attendance.index'))

                <a href="{{ route('attendance.index') }}" class="quick-action">

                    <span class="quick-action-icon attendance">
                        <i class="bi bi-calendar-check-fill"></i>
                    </span>

                    <span>
                        <strong>Attendance</strong>
                        <small>Track attendance</small>
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            @endif


            @if(Route::has('timetables.index'))

                <a href="{{ route('timetables.index') }}" class="quick-action">

                    <span class="quick-action-icon timetable">
                        <i class="bi bi-calendar3"></i>
                    </span>

                    <span>
                        <strong>Timetables</strong>
                        <small>Manage timetables</small>
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            @endif

        </div>

    </div>


    {{-- ================================================================
        MANAGEMENT STATUS
    ================================================================= --}}

    <div class="dashboard-card management-card">

        <div class="card-header">

            <div>

                <span class="card-eyebrow">
                    Management
                </span>

                <h2>
                    School Management Status
                </h2>

                <p>
                    A quick look at your current setup.
                </p>

            </div>

            <div class="header-icon">
                <i class="bi bi-check2-circle"></i>
            </div>

        </div>


        <div class="management-grid">

            <div class="management-item">

                <div class="management-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div>

                    <strong>
                        Students
                    </strong>

                    <span>
                        {{ number_format($assignedStudents) }}
                        assigned to classes
                    </span>

                </div>

                <span class="status-badge {{ $students > 0 ? 'success' : 'warning' }}">
                    {{ $students > 0 ? 'Active' : 'Setup required' }}
                </span>

            </div>


            <div class="management-item">

                <div class="management-icon">
                    <i class="bi bi-building-fill"></i>
                </div>

                <div>

                    <strong>
                        Classes
                    </strong>

                    <span>
                        {{ number_format($classes) }}
                        classes configured
                    </span>

                </div>

                <span class="status-badge {{ $classes > 0 ? 'success' : 'warning' }}">
                    {{ $classes > 0 ? 'Ready' : 'Setup required' }}
                </span>

            </div>


            <div class="management-item">

                <div class="management-icon">
                    <i class="bi bi-book-fill"></i>
                </div>

                <div>

                    <strong>
                        Subjects
                    </strong>

                    <span>
                        {{ number_format($subjects) }}
                        subjects available
                    </span>

                </div>

                <span class="status-badge {{ $subjects > 0 ? 'success' : 'warning' }}">
                    {{ $subjects > 0 ? 'Ready' : 'Setup required' }}
                </span>

            </div>


            <div class="management-item">

                <div class="management-icon">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>

                <div>

                    <strong>
                        Academic Session
                    </strong>

                    <span>
                        {{ $academicYear ?: 'Not configured' }}
                    </span>

                </div>

                <span class="status-badge {{ $academicYear ? 'success' : 'warning' }}">
                    {{ $academicYear ? 'Active' : 'Setup required' }}
                </span>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================
    CHART.JS
================================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Student Distribution Chart
    |--------------------------------------------------------------------------
    */

    const studentClassCanvas =
        document.getElementById('studentClassChart');

    if (studentClassCanvas) {

        new Chart(studentClassCanvas, {

            type: 'bar',

            data: {

                labels: @json($studentClassLabels),

                datasets: [{

                    label: 'Students',

                    data: @json($studentClassData),

                    borderWidth: 0,

                    borderRadius: 8,

                    maxBarThickness: 48

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ' Students: ' + context.raw;

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0

                        },

                        grid: {

                            drawBorder: false

                        }

                    },

                    x: {

                        grid: {

                            display: false

                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | School Overview Doughnut
    |--------------------------------------------------------------------------
    */

    const overviewCanvas =
        document.getElementById('schoolOverviewChart');

    if (overviewCanvas) {

        new Chart(overviewCanvas, {

            type: 'doughnut',

            data: {

                labels: [
                    'Students',
                    'Teachers',
                    'Classes',
                    'Subjects'
                ],

                datasets: [{

                    data: [
                        {{ $students }},
                        {{ $teachers }},
                        {{ $classes }},
                        {{ $subjects }}
                    ],

                    borderWidth: 0,

                    hoverOffset: 8

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: true,

                cutout: '72%',

                plugins: {

                    legend: {
                        display: false
                    }

                }

            }

        });

    }

});

</script>


<style>

/* ================================================================
   SCHOLARERP DASHBOARD
================================================================ */

.scholar-dashboard {
    padding: 28px;
    max-width: 1600px;
    margin: 0 auto;
}


/* ================================================================
   HEADER
================================================================ */

.dashboard-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 22px;
}

.dashboard-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    opacity: .65;
    margin-bottom: 7px;
}

.dashboard-header h1 {
    margin: 0;
    font-size: 30px;
    font-weight: 800;
    letter-spacing: -.03em;
}

.dashboard-header p {
    margin: 7px 0 0;
    font-size: 14px;
    opacity: .65;
}

.dashboard-date {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 12px 16px;
    border: 1px solid rgba(0,0,0,.08);
    border-radius: 13px;
    background: rgba(255,255,255,.7);
}

.date-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(13,110,253,.09);
    color: #0d6efd;
}

.dashboard-date span,
.school-session span,
.session-term span {
    display: block;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .05em;
    opacity: .55;
    font-weight: 700;
}

.dashboard-date strong {
    display: block;
    margin-top: 2px;
    font-size: 13px;
}


/* ================================================================
   SESSION CARD
================================================================ */

.session-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 20px 22px;
    margin-bottom: 22px;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.07);
    background: linear-gradient(
        135deg,
        rgba(13,110,253,.08),
        rgba(255,255,255,.95)
    );
}

.session-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.session-icon {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0d6efd;
    color: #fff;
    font-size: 20px;
}

.session-label {
    display: block;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .06em;
    opacity: .55;
    font-weight: 700;
}

.session-left h3 {
    margin: 3px 0 0;
    font-size: 18px;
    font-weight: 800;
}

.session-term,
.school-session {
    padding-left: 24px;
    border-left: 1px solid rgba(0,0,0,.09);
}

.session-term strong,
.school-session strong {
    display: block;
    margin-top: 4px;
    font-size: 14px;
}


/* ================================================================
   STATISTICS
================================================================ */

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 17px;
    margin-bottom: 22px;
}

.stat-card {
    padding: 20px;
    border: 1px solid rgba(0,0,0,.07);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 5px 20px rgba(0,0,0,.035);
    transition: transform .2s ease, box-shadow .2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(0,0,0,.07);
}

.stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.stat-icon.students,
.quick-action-icon.students {
    background: rgba(13,110,253,.1);
    color: #0d6efd;
}

.stat-icon.teachers,
.quick-action-icon.teachers {
    background: rgba(111,66,193,.1);
    color: #6f42c1;
}

.stat-icon.classes,
.quick-action-icon.classes {
    background: rgba(25,135,84,.1);
    color: #198754;
}

.stat-icon.subjects,
.quick-action-icon.subjects {
    background: rgba(255,193,7,.13);
    color: #997404;
}

.stat-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    opacity: .55;
}

.stat-value {
    margin-top: 17px;
    font-size: 30px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -.03em;
}

.stat-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid rgba(0,0,0,.06);
    font-size: 11px;
    opacity: .62;
}

.stat-footer span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.stat-success {
    color: #198754;
}

.stat-warning {
    color: #b58105;
}


/* ================================================================
   DASHBOARD CARDS
================================================================ */

.dashboard-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(320px, .9fr);
    gap: 18px;
    margin-bottom: 18px;
}

.dashboard-card {
    border: 1px solid rgba(0,0,0,.07);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 5px 20px rgba(0,0,0,.035);
    overflow: hidden;
}

.card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    padding: 20px 21px 12px;
}

.card-eyebrow {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-weight: 800;
    opacity: .5;
}

.card-header h2 {
    margin: 3px 0 0;
    font-size: 17px;
    font-weight: 800;
}

.card-header p {
    margin: 5px 0 0;
    font-size: 12px;
    opacity: .55;
}

.header-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(13,110,253,.08);
    color: #0d6efd;
}


/* ================================================================
   BAR CHART
================================================================ */

.chart-wrapper {
    height: 315px;
    padding: 10px 20px 22px;
}


/* ================================================================
   OVERVIEW
================================================================ */

.overview-content {
    display: grid;
    grid-template-columns: 180px 1fr;
    align-items: center;
    gap: 18px;
    padding: 8px 21px 25px;
}

.overview-chart {
    position: relative;
}

.overview-chart canvas {
    width: 180px !important;
    height: 180px !important;
}

.chart-center {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    pointer-events: none;
}

.chart-center strong {
    font-size: 21px;
    font-weight: 800;
}

.chart-center span {
    margin-top: 2px;
    font-size: 10px;
    opacity: .55;
}

.overview-list {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.overview-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 7px;
    border-bottom: 1px solid rgba(0,0,0,.055);
    font-size: 13px;
}

.overview-item:last-child {
    border-bottom: 0;
}

.overview-item-left {
    display: flex;
    align-items: center;
    gap: 9px;
}

.overview-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
}

.student-dot {
    background: #0d6efd;
}

.teacher-dot {
    background: #6f42c1;
}

.class-dot {
    background: #198754;
}

.subject-dot {
    background: #ffc107;
}

.overview-item strong {
    font-weight: 800;
}


/* ================================================================
   EMPTY STATE
================================================================ */

.empty-state {
    text-align: center;
    padding: 60px 25px;
}

.empty-icon {
    width: 54px;
    height: 54px;
    margin: 0 auto 13px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(13,110,253,.08);
    color: #0d6efd;
    font-size: 22px;
}

.empty-state h3 {
    margin: 0;
    font-size: 15px;
}

.empty-state p {
    max-width: 340px;
    margin: 7px auto 0;
    font-size: 12px;
    line-height: 1.6;
    opacity: .55;
}


/* ================================================================
   QUICK ACTIONS
================================================================ */

.quick-actions-card {
    margin-bottom: 18px;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    padding: 10px 21px 22px;
}

.quick-action {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 13px;
    border: 1px solid rgba(0,0,0,.07);
    border-radius: 12px;
    color: inherit;
    text-decoration: none;
    transition: all .18s ease;
}

.quick-action:hover {
    border-color: rgba(13,110,253,.25);
    background: rgba(13,110,253,.025);
    transform: translateY(-1px);
}

.quick-action-icon {
    width: 39px;
    height: 39px;
    flex: 0 0 39px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-action span:nth-child(2) {
    min-width: 0;
    flex: 1;
}

.quick-action strong {
    display: block;
    font-size: 12px;
}

.quick-action small {
    display: block;
    margin-top: 2px;
    font-size: 10px;
    opacity: .55;
}

.quick-action > i {
    font-size: 13px;
    opacity: .35;
}


/* ================================================================
   MANAGEMENT
================================================================ */

.management-card {
    margin-bottom: 20px;
}

.management-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1px;
    background: rgba(0,0,0,.06);
    margin-top: 7px;
}

.management-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: #fff;
}

.management-icon {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(13,110,253,.07);
    color: #0d6efd;
}

.management-item > div:nth-child(2) {
    flex: 1;
    min-width: 0;
}

.management-item strong {
    display: block;
    font-size: 12px;
}

.management-item span {
    display: block;
    margin-top: 3px;
    font-size: 10px;
    opacity: .55;
}

.status-badge {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 9px !important;
    font-weight: 800;
    white-space: nowrap;
}

.status-badge.success {
    background: rgba(25,135,84,.1);
    color: #198754;
}

.status-badge.warning {
    background: rgba(255,193,7,.13);
    color: #997404;
}


/* ================================================================
   RESPONSIVE
================================================================ */

@media (max-width: 1200px) {

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .quick-actions {
        grid-template-columns: repeat(2, 1fr);
    }

}


@media (max-width: 900px) {

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .session-card {
        flex-wrap: wrap;
    }

    .session-term,
    .school-session {
        border-left: 0;
        padding-left: 0;
    }

}


@media (max-width: 650px) {

    .scholar-dashboard {
        padding: 18px;
    }

    .dashboard-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .dashboard-date {
        width: 100%;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .quick-actions {
        grid-template-columns: 1fr;
    }

    .management-grid {
        grid-template-columns: 1fr;
    }

    .overview-content {
        grid-template-columns: 1fr;
    }

    .overview-chart {
        width: 180px;
        margin: 0 auto;
    }

}

</style>

@endsection
