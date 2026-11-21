@extends('layouts.app')

@section('title', 'Student Attendance History')

@section('content')

<style>
/* ============================================================
   SCHOLARERP — ATTENDANCE
   Clean, professional, high-visibility design
   ============================================================ */

:root {
    /* Brand */
    --att-primary: #2563eb;
    --att-primary-dark: #1d4ed8;
    --att-primary-soft: #eff6ff;
    --att-primary-border: #bfdbfe;

    /* Hierarchy */
    --att-dark: #0f172a;
    --att-dark-2: #1e293b;
    --att-purple: #7c3aed;
    --att-purple-soft: #f5f3ff;
    --att-purple-border: #ddd6fe;

    /* Status */
    --att-present: #15803d;
    --att-present-bg: #dcfce7;
    --att-present-border: #86efac;

    --att-absent: #b91c1c;
    --att-absent-bg: #fee2e2;
    --att-absent-border: #fca5a5;

    --att-late: #b45309;
    --att-late-bg: #fef3c7;
    --att-late-border: #fcd34d;

    --att-excused: #0e7490;
    --att-excused-bg: #cffafe;
    --att-excused-border: #67e8f9;

    --att-sick: #475569;
    --att-sick-bg: #e2e8f0;
    --att-sick-border: #94a3b8;

    /* General */
    --att-text: #334155;
    --att-muted: #64748b;
    --att-light-muted: #94a3b8;
    --att-border: #dbe3ef;
    --att-border-light: #eef2f7;
    --att-bg: #f8fafc;
    --att-white: #ffffff;

    /* Effects */
    --att-radius: 14px;
    --att-shadow: 0 4px 18px rgba(15, 23, 42, .06);
    --att-shadow-hover: 0 9px 26px rgba(15, 23, 42, .10);
}


/* ============================================================
   PAGE
   ============================================================ */

.attendance-page {
    width: 100%;
    min-height: 100%;
    padding: 24px;
    background: var(--att-bg);
}

.attendance-container {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
}


/* ============================================================
   ALERT
   ============================================================ */

.attendance-alert {
    margin-bottom: 20px;
    border: 0;
    border-radius: 12px;
    box-shadow: var(--att-shadow);
}


/* ============================================================
   PAGE HEADER
   ============================================================ */

.attendance-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.attendance-title-area {
    display: flex;
    align-items: center;
    gap: 14px;
}

.attendance-title-icon {
    width: 52px;
    height: 52px;
    flex: 0 0 52px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background: linear-gradient(
        135deg,
        var(--att-primary),
        var(--att-primary-dark)
    );

    color: #fff;
    font-size: 23px;

    box-shadow: 0 7px 18px rgba(37, 99, 235, .20);
}

.attendance-page-title {
    margin: 0;
    color: var(--att-dark);
    font-size: 26px;
    line-height: 1.2;
    font-weight: 750;
    letter-spacing: -.4px;
}

.attendance-page-subtitle {
    margin: 5px 0 0;
    color: var(--att-muted);
    font-size: 13px;
}

.attendance-header-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
}

.attendance-header-btn {
    min-height: 40px;
    padding: 9px 14px;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    border-radius: 9px;

    font-size: 13px;
    font-weight: 650;
    line-height: 1;

    text-decoration: none;

    transition:
        transform .18s ease,
        background-color .18s ease,
        box-shadow .18s ease;
}

.attendance-header-btn:hover {
    transform: translateY(-1px);
}


/* Header buttons */

.attendance-btn-export {
    background: #16a34a;
    color: #fff;
}

.attendance-btn-export:hover {
    background: #15803d;
    color: #fff;
    box-shadow: 0 4px 12px rgba(22, 163, 74, .20);
}

.attendance-btn-print {
    background: var(--att-dark);
    color: #fff;
}

.attendance-btn-print:hover {
    background: #020617;
    color: #fff;
    box-shadow: 0 4px 12px rgba(15, 23, 42, .20);
}

.attendance-btn-create {
    background: var(--att-primary);
    color: #fff;
}

.attendance-btn-create:hover {
    background: var(--att-primary-dark);
    color: #fff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, .22);
}


/* ============================================================
   SUMMARY CARDS
   ============================================================ */

.attendance-summary-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.attendance-summary-card {
    position: relative;
    overflow: hidden;

    padding: 18px;

    background: var(--att-white);
    border: 1px solid var(--att-border);
    border-radius: var(--att-radius);

    box-shadow: var(--att-shadow);

    transition:
        transform .2s ease,
        box-shadow .2s ease;
}

.attendance-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--att-shadow-hover);
}

.attendance-summary-card::after {
    content: "";

    position: absolute;
    right: -25px;
    bottom: -25px;

    width: 85px;
    height: 85px;

    border-radius: 50%;

    background: currentColor;
    opacity: .045;

    pointer-events: none;
}

.attendance-summary-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.attendance-summary-label {
    color: var(--att-muted);
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .6px;
}

.attendance-summary-icon {
    width: 39px;
    height: 39px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    font-size: 17px;
}

.attendance-summary-value {
    margin-top: 12px;

    color: var(--att-dark);
    font-size: 25px;
    line-height: 1;
    font-weight: 750;
}

.attendance-summary-description {
    margin-top: 7px;
    color: var(--att-light-muted);
    font-size: 11px;
}


/* Summary colors */

.summary-present {
    color: var(--att-present);
}

.summary-present .attendance-summary-icon {
    background: var(--att-present-bg);
    color: var(--att-present);
}

.summary-absent {
    color: var(--att-absent);
}

.summary-absent .attendance-summary-icon {
    background: var(--att-absent-bg);
    color: var(--att-absent);
}

.summary-late {
    color: var(--att-late);
}

.summary-late .attendance-summary-icon {
    background: var(--att-late-bg);
    color: var(--att-late);
}

.summary-excused {
    color: var(--att-excused);
}

.summary-excused .attendance-summary-icon {
    background: var(--att-excused-bg);
    color: var(--att-excused);
}

.summary-sick {
    color: var(--att-sick);
}

.summary-sick .attendance-summary-icon {
    background: var(--att-sick-bg);
    color: var(--att-sick);
}


/* ============================================================
   FILTER CARD
   ============================================================ */

.attendance-filter-card {
    margin-bottom: 24px;

    overflow: hidden;

    background: var(--att-white);
    border: 1px solid var(--att-border);
    border-radius: var(--att-radius);

    box-shadow: var(--att-shadow);
}

.attendance-filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    padding: 15px 18px;

    border-bottom: 1px solid var(--att-border);
}

.attendance-filter-title {
    display: flex;
    align-items: center;
    gap: 8px;

    color: var(--att-dark);
    font-size: 14px;
    font-weight: 700;
}

.attendance-filter-title i {
    color: var(--att-primary);
    font-size: 16px;
}

.attendance-filter-body {
    padding: 18px;
}

.attendance-filter-label {
    display: block;
    margin-bottom: 6px;

    color: #475569;
    font-size: 11px;
    font-weight: 700;
}

.attendance-filter-control {
    width: 100%;
    height: 40px;

    padding: 0 11px;

    border: 1px solid var(--att-border);
    border-radius: 8px;

    background: #fff;
    color: var(--att-text);

    font-size: 13px;
    outline: none;

    transition:
        border-color .18s ease,
        box-shadow .18s ease;
}

.attendance-filter-control:hover {
    border-color: #cbd5e1;
}

.attendance-filter-control:focus {
    border-color: var(--att-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .09);
}

.attendance-filter-btn {
    width: 100%;
    height: 40px;

    border: 0;
    border-radius: 8px;

    font-size: 12px;
    font-weight: 700;

    cursor: pointer;

    transition:
        background-color .18s ease,
        transform .18s ease;
}

.attendance-filter-btn:hover {
    transform: translateY(-1px);
}

.attendance-filter-apply {
    background: var(--att-primary);
    color: #fff;
}

.attendance-filter-apply:hover {
    background: var(--att-primary-dark);
}

.attendance-filter-reset {
    background: #e2e8f0;
    color: #475569;
}

.attendance-filter-reset:hover {
    background: #cbd5e1;
    color: var(--att-dark);
}


/* ============================================================
   WEEKLY HEADING
   ============================================================ */

.attendance-week-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    margin-bottom: 16px;
}

.attendance-week-title {
    margin: 0;

    color: var(--att-dark);
    font-size: 18px;
    font-weight: 750;
}

.attendance-week-subtitle {
    margin-top: 4px;

    color: var(--att-muted);
    font-size: 12px;
}

.attendance-record-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 7px 11px;

    background: #fff;
    border: 1px solid var(--att-border);
    border-radius: 8px;

    color: var(--att-muted);
    font-size: 11px;
    font-weight: 650;
}


/* ============================================================
   DAY SECTION
   ============================================================ */

.attendance-day-section {
    margin-bottom: 36px;

    border-radius: 15px;

    /* Creates stronger separation between days */
    filter: drop-shadow(0 3px 9px rgba(15, 23, 42, .035));
}

.attendance-day-section:last-child {
    margin-bottom: 0;
}


/* DAY HEADER */

.attendance-day-header {
    position: relative;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    padding: 15px 18px;

    background: linear-gradient(
        135deg,
        #1d4ed8 0%,
        #2563eb 55%,
        #3b82f6 100%
    );

    border: 1px solid #1d4ed8;
    border-radius: 14px 14px 0 0;

    color: #fff;
    box-shadow: 0 5px 15px rgba(37, 99, 235, .13);
}

.attendance-day-header::before {
    content: "";

    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;

    width: 4px;

    background: #93c5fd;
    border-radius: 14px 0 0 0;
}

.attendance-day-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.attendance-day-icon {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(255, 255, 255, .16);
    border: 1px solid rgba(255, 255, 255, .25);
    border-radius: 10px;

    color: #fff;
    font-size: 18px;
}

.attendance-day-name {
    margin: 0;

    color: #fff;
    font-size: 16px;
    line-height: 1.2;
    font-weight: 750;
}

.attendance-day-date {
    margin-top: 3px;

    color: rgba(255, 255, 255, .78);
    font-size: 11px;
}

.attendance-day-stats {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
    flex-wrap: wrap;
}

.attendance-day-stat {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 6px 9px;

    border-radius: 7px;

    background: rgba(255, 255, 255, .13);
    border: 1px solid rgba(255, 255, 255, .15);

    color: #fff;
    font-size: 10px;
    font-weight: 700;
}

.attendance-day-stat.present {
    background: rgba(220, 252, 231, .16);
    color: #dcfce7;
}

.attendance-day-stat.absent {
    background: rgba(254, 226, 226, .16);
    color: #fee2e2;
}

.attendance-day-stat.late {
    background: rgba(254, 243, 199, .17);
    color: #fef3c7;
}


/* ============================================================
   CLASS SECTION
   ============================================================ */

.attendance-class-section {
    overflow: hidden;

    background: #fff;
    border: 1px solid var(--att-border);
    border-top: 0;
}

.attendance-class-section:last-child {
    border-radius: 0 0 14px 14px;
}

.attendance-class-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    padding: 12px 18px;

    background: linear-gradient(
        90deg,
        #0f172a,
        #1e293b
    );

    border-bottom: 1px solid #334155;
}

.attendance-class-info {
    display: flex;
    align-items: center;
    gap: 9px;
}

.attendance-class-icon {
    width: 31px;
    height: 31px;
    flex: 0 0 31px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #2563eb;
    border: 1px solid #60a5fa;

    color: #fff;
    font-size: 13px;

    box-shadow: 0 3px 8px rgba(37, 99, 235, .25);
}

.attendance-class-name {
    margin: 0;

    color: #fff;
    font-size: 13px;
    font-weight: 750;
}

.attendance-class-total {
    color: #cbd5e1;
    font-size: 10px;
}


/* ============================================================
   STREAM SECTION
   ============================================================ */

.attendance-stream-section {
    border-bottom: 1px solid var(--att-border-light);
}

.attendance-stream-section:last-child {
    border-bottom: 0;
}

.attendance-stream-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;

    padding: 10px 18px 10px 55px;

    background: linear-gradient(
        90deg,
        #f5f3ff,
        #ffffff
    );

    border-bottom: 1px solid var(--att-purple-border);
}

.attendance-stream-name-wrapper {
    display: flex;
    align-items: center;
    gap: 9px;
}

.attendance-stream-dot {
    width: 8px;
    height: 8px;
    flex: 0 0 8px;

    border-radius: 50%;

    background: var(--att-purple);

    box-shadow:
        0 0 0 4px #ede9fe;
}

.attendance-stream-name {
    margin: 0;

    color: #4c1d95;
    font-size: 11px;
    font-weight: 750;
}

.attendance-stream-total {
    padding: 4px 8px;

    background: #ede9fe;
    border-radius: 20px;

    color: var(--att-purple);
    font-size: 10px;
    font-weight: 650;
}


/* ============================================================
   STUDENT TABLE
   ============================================================ */

.attendance-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.attendance-table {
    width: 100%;
    min-width: 760px;
    margin: 0;

    border-collapse: collapse;
}

.attendance-table thead th {
    padding: 9px 18px;

    background: #f8fafc;

    color: #64748b;

    border-top: 1px solid #eef2f7;
    border-bottom: 1px solid #e2e8f0;

    font-size: 9px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .55px;

    white-space: nowrap;
}

.attendance-table tbody td {
    padding: 11px 18px;

    color: var(--att-text);

    border-bottom: 1px solid #eef2f7;

    font-size: 12px;
    vertical-align: middle;
}

.attendance-table tbody tr {
    transition:
        background-color .15s ease,
        box-shadow .15s ease;
}

.attendance-table tbody tr:hover {
    background: #f8fbff;
    box-shadow: inset 3px 0 0 var(--att-primary);
}

.attendance-table tbody tr:last-child td {
    border-bottom: 0;
}


/* ============================================================
   STUDENT
   ============================================================ */

.attendance-student {
    display: flex;
    align-items: center;
    gap: 10px;
}

.attendance-student-avatar {
    width: 35px;
    height: 35px;
    flex: 0 0 35px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: var(--att-primary-soft);
    border: 1px solid var(--att-primary-border);

    color: var(--att-primary);

    font-size: 10px;
    font-weight: 800;
}

.attendance-student-name {
    color: var(--att-dark);
    font-size: 12px;
    font-weight: 700;
}

.attendance-student-label {
    margin-top: 2px;
    color: var(--att-light-muted);
    font-size: 9px;
}

.attendance-admission {
    color: #475569;
    font-size: 11px;
    font-weight: 650;
}


/* ============================================================
   STATUS BADGES
   ============================================================ */

.attendance-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;

    min-width: 78px;
    padding: 6px 10px;

    border: 1px solid transparent;
    border-radius: 7px;

    font-size: 9px;
    font-weight: 800;

    line-height: 1;
    white-space: nowrap;
}

.attendance-status i {
    font-size: 9px;
}


/* PRESENT */

.attendance-status-present {
    background: var(--att-present-bg);
    border-color: var(--att-present-border);
    color: var(--att-present);
}


/* ABSENT */

.attendance-status-absent {
    background: var(--att-absent-bg);
    border-color: var(--att-absent-border);
    color: var(--att-absent);
}


/* LATE */

.attendance-status-late {
    background: var(--att-late-bg);
    border-color: var(--att-late-border);
    color: var(--att-late);
}


/* EXCUSED */

.attendance-status-excused {
    background: var(--att-excused-bg);
    border-color: var(--att-excused-border);
    color: var(--att-excused);
}


/* SICK */

.attendance-status-sick {
    background: var(--att-sick-bg);
    border-color: var(--att-sick-border);
    color: var(--att-sick);
}


/* DEFAULT */

.attendance-status-default {
    background: #e2e8f0;
    border-color: #cbd5e1;
    color: #475569;
}


/* ============================================================
   RECORDED BY
   ============================================================ */

.attendance-recorded-by {
    color: #475569;
    font-size: 11px;
    font-weight: 600;
}

.attendance-recorded-time {
    margin-top: 2px;
    color: var(--att-light-muted);
    font-size: 9px;
}


/* ============================================================
   ACTION BUTTONS
   ============================================================ */

.attendance-actions {
    display: flex;
    align-items: center;
    gap: 5px;
}

.attendance-action-btn {
    width: 31px;
    height: 31px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    background: #fff;

    border: 1px solid var(--att-border);
    border-radius: 8px;

    color: #64748b;

    text-decoration: none;
    font-size: 12px;

    transition:
        background-color .18s ease,
        border-color .18s ease,
        color .18s ease,
        transform .18s ease;
}

.attendance-action-btn:hover {
    transform: translateY(-1px);

    background: var(--att-primary-soft);
    border-color: var(--att-primary-border);
    color: var(--att-primary);
}

.attendance-action-btn.edit:hover {
    background: var(--att-late-bg);
    border-color: var(--att-late-border);
    color: var(--att-late);
}


/* ============================================================
   EMPTY DAY
   ============================================================ */

.attendance-empty-day {
    padding: 35px 20px;

    background: #fff;

    border: 1px solid var(--att-border);
    border-top: 0;
    border-radius: 0 0 14px 14px;

    text-align: center;
}

.attendance-empty-day-icon {
    width: 48px;
    height: 48px;

    margin: 0 auto 10px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: #f1f5f9;
    color: #64748b;

    font-size: 20px;
}

.attendance-empty-day-title {
    margin: 0;

    color: var(--att-dark);
    font-size: 13px;
    font-weight: 700;
}

.attendance-empty-day-text {
    max-width: 420px;
    margin: 4px auto 0;

    color: var(--att-light-muted);
    font-size: 11px;
}


/* ============================================================
   EMPTY PAGE
   ============================================================ */

.attendance-empty-page {
    padding: 65px 20px;

    background: #fff;

    border: 1px solid var(--att-border);
    border-radius: 15px;

    box-shadow: var(--att-shadow);

    text-align: center;
}

.attendance-empty-page-icon {
    width: 65px;
    height: 65px;

    margin: 0 auto 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 16px;

    background: var(--att-primary-soft);
    border: 1px solid var(--att-primary-border);

    color: var(--att-primary);
    font-size: 27px;
}

.attendance-empty-page h3 {
    margin: 0;

    color: var(--att-dark);
    font-size: 18px;
    font-weight: 750;
}

.attendance-empty-page p {
    max-width: 500px;
    margin: 7px auto 0;

    color: var(--att-muted);
    font-size: 12px;
}


/* ============================================================
   PAGINATION
   ============================================================ */

.attendance-pagination {
    margin-top: 20px;
}

.attendance-pagination nav {
    display: flex;
    justify-content: center;
}

.attendance-pagination .pagination {
    margin-bottom: 0;
}

.attendance-pagination .page-link {
    color: var(--att-primary);
    border-color: var(--att-border);
    font-size: 12px;
}

.attendance-pagination .page-link:hover {
    background: var(--att-primary-soft);
    border-color: var(--att-primary-border);
}

.attendance-pagination .page-item.active .page-link {
    background: var(--att-primary);
    border-color: var(--att-primary);
    color: #fff;
}


/* ============================================================
   STATUS LEGEND
   ============================================================ */

.attendance-legend {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: wrap;

    margin-top: 22px;
    padding: 13px 16px;

    background: #fff;

    border: 1px solid var(--att-border);
    border-radius: 11px;

    box-shadow: var(--att-shadow);
}

.attendance-legend-title {
    margin-right: 5px;

    color: #475569;

    font-size: 10px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .5px;
}

.attendance-legend-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 6px 9px;

    border-radius: 7px;

    font-size: 9px;
    font-weight: 750;
}

.attendance-legend-dot {
    width: 7px;
    height: 7px;
    flex: 0 0 7px;

    border-radius: 50%;
}


/* Legend colors */

.legend-present {
    background: var(--att-present-bg);
    color: var(--att-present);
}

.legend-present .attendance-legend-dot {
    background: var(--att-present);
}

.legend-absent {
    background: var(--att-absent-bg);
    color: var(--att-absent);
}

.legend-absent .attendance-legend-dot {
    background: var(--att-absent);
}

.legend-late {
    background: var(--att-late-bg);
    color: var(--att-late);
}

.legend-late .attendance-legend-dot {
    background: var(--att-late);
}

.legend-excused {
    background: var(--att-excused-bg);
    color: var(--att-excused);
}

.legend-excused .attendance-legend-dot {
    background: var(--att-excused);
}

.legend-sick {
    background: var(--att-sick-bg);
    color: var(--att-sick);
}

.legend-sick .attendance-legend-dot {
    background: var(--att-sick);
}


/* ============================================================
   RESPONSIVE — TABLET
   ============================================================ */

@media (max-width: 1250px) {

    .attendance-summary-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

}


/* ============================================================
   RESPONSIVE — SMALL TABLET
   ============================================================ */

@media (max-width: 950px) {

    .attendance-page {
        padding: 18px;
    }

    .attendance-page-header {
        flex-direction: column;
    }

    .attendance-header-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .attendance-header-btn {
        flex: 1;
    }

    .attendance-day-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .attendance-day-stats {
        width: 100%;
        justify-content: flex-start;
    }

    .attendance-stream-header {
        padding-left: 35px;
    }

}


/* ============================================================
   RESPONSIVE — MOBILE
   ============================================================ */

@media (max-width: 700px) {

    .attendance-page {
        padding: 12px;
    }

    .attendance-title-area {
        align-items: flex-start;
    }

    .attendance-title-icon {
        width: 44px;
        height: 44px;
        flex-basis: 44px;
        font-size: 19px;
    }

    .attendance-page-title {
        font-size: 21px;
    }

    .attendance-page-subtitle {
        font-size: 11px;
    }

    .attendance-header-actions {
        display: grid;
        grid-template-columns: 1fr;
        width: 100%;
    }

    .attendance-header-btn {
        width: 100%;
        flex: none;
    }

    .attendance-summary-grid {
        grid-template-columns: 1fr 1fr;
        gap: 9px;
    }

    .attendance-summary-card {
        padding: 14px;
    }

    .attendance-summary-value {
        font-size: 21px;
    }

    .attendance-filter-header {
        padding: 13px 14px;
    }

    .attendance-filter-body {
        padding: 14px;
    }

    .attendance-week-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .attendance-record-count {
        width: 100%;
        justify-content: center;
    }

    .attendance-day-section {
        margin-bottom: 28px;
    }

    .attendance-day-header {
        padding: 13px;
    }

    .attendance-day-info {
        align-items: flex-start;
    }

    .attendance-day-icon {
        width: 35px;
        height: 35px;
        flex-basis: 35px;
        font-size: 15px;
    }

    .attendance-day-name {
        font-size: 14px;
    }

    .attendance-class-header {
        padding: 11px 13px;
    }

    .attendance-stream-header {
        padding: 9px 13px 9px 27px;
    }

    .attendance-table thead th,
    .attendance-table tbody td {
        padding-left: 13px;
        padding-right: 13px;
    }

    .attendance-legend {
        align-items: flex-start;
        flex-direction: column;
    }

}


/* ============================================================
   RESPONSIVE — VERY SMALL MOBILE
   ============================================================ */

@media (max-width: 450px) {

    .attendance-summary-grid {
        grid-template-columns: 1fr;
    }

    .attendance-day-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        width: 100%;
    }

    .attendance-day-stat {
        justify-content: center;
    }

}


/* ============================================================
   PRINT
   ============================================================ */

@media print {

    .attendance-page {
        padding: 0;
        background: #fff;
    }

    .attendance-header-actions,
    .attendance-filter-card,
    .attendance-legend,
    .attendance-actions,
    .attendance-pagination {
        display: none !important;
    }

    .attendance-summary-grid {
        grid-template-columns: repeat(5, 1fr);
    }

    .attendance-summary-card,
    .attendance-day-header,
    .attendance-class-section {
        box-shadow: none !important;
    }

    .attendance-day-section {
        page-break-inside: avoid;
        filter: none;
    }

    .attendance-table {
        min-width: 0;
    }

    .attendance-day-header {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .attendance-class-header {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .attendance-status {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

}
</style>


<div class="attendance-page">

    <div class="attendance-container">


        {{-- ============================================================
             SUCCESS MESSAGE
        ============================================================= --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show attendance-alert"
                role="alert"
            >

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-check-circle-fill"></i>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>

            </div>

        @endif


        {{-- ============================================================
             PAGE HEADER
        ============================================================= --}}

        <div class="attendance-page-header">

            <div class="attendance-title-area">

                <div class="attendance-title-icon">

                    <i class="bi bi-calendar2-check"></i>

                </div>

                <div>

                    <h1 class="attendance-page-title">
                        Student Attendance
                    </h1>

                    <p class="attendance-page-subtitle">
                        Monitor student attendance by day, class and stream.
                    </p>

                </div>

            </div>


            <div class="attendance-header-actions">

                <a
                    href="{{ route('attendance.export') }}"
                    class="attendance-header-btn attendance-btn-export"
                >
                    <i class="bi bi-file-earmark-excel"></i>
                    Export
                </a>

               <a
    href="{{ route('attendance.report') }}"
    class="attendance-header-btn attendance-btn-print"
>
    <i class="bi bi-printer"></i>
    Print
</a>

                <a
                    href="{{ route('attendance.create') }}"
                    class="attendance-header-btn attendance-btn-create"
                >
                    <i class="bi bi-plus-lg"></i>
                    Take Attendance
                </a>

            </div>

        </div>


        {{-- ============================================================
             SUMMARY CARDS
        ============================================================= --}}

        <div class="attendance-summary-grid">

            {{-- Present --}}
            <div class="attendance-summary-card summary-present">

                <div class="attendance-summary-top">

                    <span class="attendance-summary-label">
                        Present
                    </span>

                    <div class="attendance-summary-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                </div>

                <div class="attendance-summary-value">
                    {{ $stats['present'] ?? 0 }}
                </div>

                <div class="attendance-summary-description">
                    Students present
                </div>

            </div>


            {{-- Absent --}}
            <div class="attendance-summary-card summary-absent">

                <div class="attendance-summary-top">

                    <span class="attendance-summary-label">
                        Absent
                    </span>

                    <div class="attendance-summary-icon">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>

                </div>

                <div class="attendance-summary-value">
                    {{ $stats['absent'] ?? 0 }}
                </div>

                <div class="attendance-summary-description">
                    Students absent
                </div>

            </div>


            {{-- Late --}}
            <div class="attendance-summary-card summary-late">

                <div class="attendance-summary-top">

                    <span class="attendance-summary-label">
                        Late
                    </span>

                    <div class="attendance-summary-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>

                </div>

                <div class="attendance-summary-value">
                    {{ $stats['late'] ?? 0 }}
                </div>

                <div class="attendance-summary-description">
                    Late arrivals
                </div>

            </div>


            {{-- Excused --}}
            <div class="attendance-summary-card summary-excused">

                <div class="attendance-summary-top">

                    <span class="attendance-summary-label">
                        Excused
                    </span>

                    <div class="attendance-summary-icon">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>

                </div>

                <div class="attendance-summary-value">
                    {{ $stats['excused'] ?? 0 }}
                </div>

                <div class="attendance-summary-description">
                    Excused absences
                </div>

            </div>


            {{-- Sick --}}
            <div class="attendance-summary-card summary-sick">

                <div class="attendance-summary-top">

                    <span class="attendance-summary-label">
                        Sick
                    </span>

                    <div class="attendance-summary-icon">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>

                </div>

                <div class="attendance-summary-value">
                    {{ $stats['sick'] ?? 0 }}
                </div>

                <div class="attendance-summary-description">
                    Sick students
                </div>

            </div>

        </div>


        {{-- ============================================================
             ATTENDANCE RATE
        ============================================================= --}}

        <div class="attendance-filter-card mb-4">

            <div class="attendance-filter-header">

                <div class="attendance-filter-title">

                    <i class="bi bi-graph-up-arrow"></i>

                    Overall Attendance Rate

                </div>

                <strong class="text-primary">
                    {{ $attendanceRate }}%
                </strong>

            </div>

            <div class="attendance-filter-body">

                <div
                    class="progress"
                    style="height: 12px; border-radius: 20px; background:#e2e8f0;"
                >

                    <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        style="width: {{ min((float) $attendanceRate, 100) }}%; border-radius:20px;"
                        aria-valuenow="{{ $attendanceRate }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>

                </div>

                <div class="d-flex justify-content-between mt-2">

                    <small class="text-muted">
                        Overall student attendance performance
                    </small>

                    <small class="fw-semibold text-success">
                        {{ $attendanceRate }}%
                    </small>

                </div>

            </div>

        </div>


        {{-- ============================================================
             FILTERS
        ============================================================= --}}

        <div class="attendance-filter-card">

            <div class="attendance-filter-header">

                <div class="attendance-filter-title">

                    <i class="bi bi-funnel"></i>

                    Attendance Filters

                </div>

                <small class="text-muted">
                    Filter attendance records
                </small>

            </div>


            <div class="attendance-filter-body">

                <form
                    method="GET"
                    action="{{ url()->current() }}"
                >

                    <div class="row g-3">

                        {{-- Date --}}
                        <div class="col-xl-3 col-md-6">

                            <label class="attendance-filter-label">
                                Date
                            </label>

                            <input
                                type="date"
                                name="date"
                                value="{{ request('date') }}"
                                class="attendance-filter-control"
                            >

                        </div>


                        {{-- Class --}}
                        <div class="col-xl-3 col-md-6">

                            <label class="attendance-filter-label">
                                School Class
                            </label>

                            <select
                                name="class_id"
                                class="attendance-filter-control"
                            >

                                <option value="">
                                    All Classes
                                </option>

                                @foreach($classes as $class)

                                    <option
                                        value="{{ $class->id }}"
                                        @selected((string) request('class_id') === (string) $class->id)
                                    >
                                        {{ $class->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Status --}}
                        <div class="col-xl-3 col-md-6">

                            <label class="attendance-filter-label">
                                Attendance Status
                            </label>

                            <select
                                name="status"
                                class="attendance-filter-control"
                            >

                                <option value="">
                                    All Statuses
                                </option>

                                <option
                                    value="Present"
                                    @selected(request('status') === 'Present')
                                >
                                    Present
                                </option>

                                <option
                                    value="Absent"
                                    @selected(request('status') === 'Absent')
                                >
                                    Absent
                                </option>

                                <option
                                    value="Late"
                                    @selected(request('status') === 'Late')
                                >
                                    Late
                                </option>

                                <option
                                    value="Excused"
                                    @selected(request('status') === 'Excused')
                                >
                                    Excused
                                </option>

                                <option
                                    value="Sick"
                                    @selected(request('status') === 'Sick')
                                >
                                    Sick
                                </option>

                            </select>

                        </div>


                        {{-- Buttons --}}
                        <div class="col-xl-3 col-md-6">

                            <label class="attendance-filter-label">
                                &nbsp;
                            </label>

                            <div class="row g-2">

                                <div class="col-7">

                                    <button
                                        type="submit"
                                        class="attendance-filter-btn attendance-filter-apply"
                                    >
                                        <i class="bi bi-search me-1"></i>
                                        Filter
                                    </button>

                                </div>

                                <div class="col-5">

                                    <a
                                        href="{{ url()->current() }}"
                                        class="attendance-filter-btn attendance-filter-reset d-flex align-items-center justify-content-center text-decoration-none"
                                    >
                                        Reset
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- ============================================================
             WEEKLY ATTENDANCE HEADER
        ============================================================= --}}

        <div class="attendance-week-heading">

            <div>

                <h2 class="attendance-week-title">
                    Weekly Attendance
                </h2>

                <div class="attendance-week-subtitle">
                    Attendance is organized by weekday, class, stream and student.
                </div>

            </div>

            <div class="attendance-record-count">

                <i class="bi bi-clipboard-check"></i>

                {{ $records->total() }}

                Total Record{{ $records->total() == 1 ? '' : 's' }}

            </div>

        </div>


        {{-- ============================================================
             PREPARE CURRENT RECORDS
        ============================================================= --}}

        @php

            /*
             * IMPORTANT:
             * We use your existing paginated $records collection.
             *
             * We do not introduce a new controller variable.
             */

            $attendanceRecords = collect($records->items());

            /*
             * Group records by actual attendance date.
             */

            $recordsByDate = $attendanceRecords
                ->groupBy(function ($record) {

                    return \Carbon\Carbon::parse(
                        $record->attendance_date
                    )->format('Y-m-d');

                })
                ->sortKeys();

            /*
             * Monday-Friday ordering.
             */

            $weekdayOrder = [
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
            ];

        @endphp


        {{-- ============================================================
             ATTENDANCE RECORDS
        ============================================================= --}}

        @if($recordsByDate->count())

            @foreach($recordsByDate as $attendanceDate => $dayRecords)

                @php

                    $carbonDate = \Carbon\Carbon::parse($attendanceDate);

                    $dayNumber = $carbonDate->dayOfWeekIso;

                    /*
                     * The requested weekly layout is Monday-Friday.
                     * Saturday and Sunday are not displayed in the
                     * weekday sections.
                     */

                    if (!array_key_exists($dayNumber, $weekdayOrder)) {
                        continue;
                    }

                    $dayName = $weekdayOrder[$dayNumber];

                    /*
                     * Day statistics.
                     */

                    $dayPresent = $dayRecords
                        ->where('status', 'Present')
                        ->count();

                    $dayAbsent = $dayRecords
                        ->where('status', 'Absent')
                        ->count();

                    $dayLate = $dayRecords
                        ->where('status', 'Late')
                        ->count();

                    /*
                     * Group the day by class.
                     */

                    $classGroups = $dayRecords->groupBy(function ($record) {

                        return optional($record->schoolClass)->id
                            ?? $record->class_id
                            ?? 'unknown';

                    });

                @endphp


                {{-- ====================================================
                     DAY
                ===================================================== --}}

                <section class="attendance-day-section">


                    {{-- Day Header --}}

                    <div class="attendance-day-header">

                        <div class="attendance-day-info">

                            <div class="attendance-day-icon">

                                @switch($dayName)

                                    @case('Monday')
                                        <i class="bi bi-1-circle"></i>
                                        @break

                                    @case('Tuesday')
                                        <i class="bi bi-2-circle"></i>
                                        @break

                                    @case('Wednesday')
                                        <i class="bi bi-3-circle"></i>
                                        @break

                                    @case('Thursday')
                                        <i class="bi bi-4-circle"></i>
                                        @break

                                    @case('Friday')
                                        <i class="bi bi-5-circle"></i>
                                        @break

                                @endswitch

                            </div>


                            <div>

                                <h3 class="attendance-day-name">
                                    {{ $dayName }}
                                </h3>

                                <div class="attendance-day-date">

                                    {{ $carbonDate->format('d F Y') }}

                                </div>

                            </div>

                        </div>


                        <div class="attendance-day-stats">

                            <span class="attendance-day-stat">

                                <i class="bi bi-people"></i>

                                {{ $dayRecords->count() }} Records

                            </span>


                            <span class="attendance-day-stat present">

                                <i class="bi bi-check-circle"></i>

                                {{ $dayPresent }} Present

                            </span>


                            <span class="attendance-day-stat absent">

                                <i class="bi bi-x-circle"></i>

                                {{ $dayAbsent }} Absent

                            </span>


                            <span class="attendance-day-stat late">

                                <i class="bi bi-clock"></i>

                                {{ $dayLate }} Late

                            </span>

                        </div>

                    </div>


                    {{-- ====================================================
                         CLASS GROUPS
                    ===================================================== --}}

                    @foreach($classGroups as $classId => $classRecords)

                        @php

                            $firstClassRecord = $classRecords->first();

                            $schoolClass = optional($firstClassRecord->schoolClass);

                            /*
                             * Group records by stream.
                             */

                            $streamGroups = $classRecords->groupBy(function ($record) {

                                return optional($record->stream)->id
                                    ?? $record->stream_id
                                    ?? 'no-stream';

                            });

                        @endphp


                        <div class="attendance-class-section">


                            {{-- Class Header --}}

                            <div class="attendance-class-header">

                                <div class="attendance-class-info">

                                    <div class="attendance-class-icon">

                                        <i class="bi bi-mortarboard-fill"></i>

                                    </div>

                                    <div>

                                        <h4 class="attendance-class-name">

                                            {{ $schoolClass->name ?? 'Class' }}

                                        </h4>

                                        <div class="attendance-class-total">

                                            {{ $classRecords->count() }}
                                            record{{ $classRecords->count() == 1 ? '' : 's' }}

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- ====================================================
                                 STREAMS
                            ===================================================== --}}

                            @foreach($streamGroups as $streamId => $streamRecords)

                                @php

                                    $firstStreamRecord = $streamRecords->first();

                                    $stream = optional($firstStreamRecord->stream);

                                @endphp


                                <div class="attendance-stream-section">


                                    {{-- Stream Header --}}

                                    <div class="attendance-stream-header">

                                        <div class="attendance-stream-name-wrapper">

                                            <span class="attendance-stream-dot"></span>

                                            <h5 class="attendance-stream-name">

                                                {{ $stream->name ?? 'No Stream' }}

                                            </h5>

                                        </div>

                                        <span class="attendance-stream-total">

                                            {{ $streamRecords->count() }}
                                            student{{ $streamRecords->count() == 1 ? '' : 's' }}

                                        </span>

                                    </div>


                                    {{-- ====================================================
                                         STUDENT TABLE
                                    ===================================================== --}}

                                    <div class="attendance-table-wrapper">

                                        <table class="attendance-table">

                                            <thead>

                                                <tr>

                                                    <th style="width: 35%;">
                                                        Student
                                                    </th>

                                                    <th style="width: 18%;">
                                                        Admission No.
                                                    </th>

                                                    <th style="width: 15%;">
                                                        Status
                                                    </th>

                                                    <th style="width: 20%;">
                                                        Recorded By
                                                    </th>

                                                    <th style="width: 12%;">
                                                        Action
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody>

                                                @foreach($streamRecords as $record)

                                                    @php

                                                        /*
                                                         * Student.
                                                         */

                                                        $student = $record->student;

                                                        /*
                                                         * Full student name.
                                                         */

                                                        $studentName = trim(
                                                            ($student->first_name ?? '') . ' ' .
                                                            ($student->middle_name ?? '') . ' ' .
                                                            ($student->last_name ?? '')
                                                        );

                                                        if (!$studentName) {
                                                            $studentName = 'Unknown Student';
                                                        }

                                                        /*
                                                         * Admission number.
                                                         */

                                                        $admissionNo =
                                                            $student->admission_no
                                                            ?? '—';

                                                        /*
                                                         * Status.
                                                         */

                                                        $status = trim(
                                                            (string) $record->status
                                                        );

                                                        /*
                                                         * Status styling.
                                                         */

                                                        switch (strtolower($status)) {

                                                            case 'present':

                                                                $statusClass =
                                                                    'attendance-status-present';

                                                                $statusIcon =
                                                                    'bi-check-circle-fill';

                                                                break;


                                                            case 'absent':

                                                                $statusClass =
                                                                    'attendance-status-absent';

                                                                $statusIcon =
                                                                    'bi-x-circle-fill';

                                                                break;


                                                            case 'late':

                                                                $statusClass =
                                                                    'attendance-status-late';

                                                                $statusIcon =
                                                                    'bi-clock-fill';

                                                                break;


                                                            case 'excused':

                                                                $statusClass =
                                                                    'attendance-status-excused';

                                                                $statusIcon =
                                                                    'bi-info-circle-fill';

                                                                break;


                                                            case 'sick':

                                                                $statusClass =
                                                                    'attendance-status-sick';

                                                                $statusIcon =
                                                                    'bi-heart-pulse-fill';

                                                                break;


                                                            default:

                                                                $statusClass =
                                                                    'attendance-status-default';

                                                                $statusIcon =
                                                                    'bi-question-circle-fill';

                                                                break;

                                                        }


                                                        /*
                                                         * Student initials.
                                                         */

                                                        $nameParts = preg_split(
                                                            '/\s+/',
                                                            trim($studentName)
                                                        );

                                                        $initials = '';

                                                        if (!empty($nameParts[0])) {

                                                            $initials .= strtoupper(
                                                                substr(
                                                                    $nameParts[0],
                                                                    0,
                                                                    1
                                                                )
                                                            );

                                                        }

                                                        if (count($nameParts) > 1) {

                                                            $lastNamePart =
                                                                end($nameParts);

                                                            $initials .= strtoupper(
                                                                substr(
                                                                    $lastNamePart,
                                                                    0,
                                                                    1
                                                                )
                                                            );

                                                        }

                                                        if (!$initials) {
                                                            $initials = 'ST';
                                                        }

                                                        /*
                                                         * Recorded user.
                                                         */

                                                        $recordedBy =
                                                            optional($record->user)->name
                                                            ?? 'System';

                                                        /*
                                                         * Recorded time.
                                                         */

                                                        $recordedTime =
                                                            $record->created_at
                                                            ? $record->created_at->format('h:i A')
                                                            : '—';

                                                    @endphp


                                                    <tr>


                                                        {{-- Student --}}

                                                        <td>

                                                            <div class="attendance-student">

                                                                <div class="attendance-student-avatar">

                                                                    {{ $initials }}

                                                                </div>

                                                                <div>

                                                                    <div class="attendance-student-name">

                                                                        <a
                                                                            href="{{ route('attendance.student', $student->id) }}"
                                                                            class="text-decoration-none text-dark"
                                                                        >

                                                                            {{ $studentName }}

                                                                        </a>

                                                                    </div>

                                                                    <div class="attendance-student-label">

                                                                        Student

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </td>


                                                        {{-- Admission Number --}}

                                                        <td>

                                                            <span class="attendance-admission">

                                                                {{ $admissionNo }}

                                                            </span>

                                                        </td>


                                                        {{-- Status --}}

                                                        <td>

                                                            <span
                                                                class="attendance-status {{ $statusClass }}"
                                                            >

                                                                <i class="bi {{ $statusIcon }}"></i>

                                                                {{ ucfirst($status) }}

                                                            </span>

                                                        </td>


                                                        {{-- Recorded By --}}

                                                        <td>

                                                            <div class="attendance-recorded-by">

                                                                {{ $recordedBy }}

                                                            </div>

                                                            <div class="attendance-recorded-time">

                                                                {{ $recordedTime }}

                                                            </div>

                                                        </td>


                                                        {{-- Actions --}}

                                                        <td>

                                                            <div class="attendance-actions">

                                                                <a
                                                                    href="{{ route('attendance.show', $record->id) }}"
                                                                    class="attendance-action-btn"
                                                                    title="View Attendance"
                                                                >

                                                                    <i class="bi bi-eye"></i>

                                                                </a>


                                                                <a
                                                                    href="{{ route('attendance.edit', $record->id) }}"
                                                                    class="attendance-action-btn edit"
                                                                    title="Edit Attendance"
                                                                >

                                                                    <i class="bi bi-pencil"></i>

                                                                </a>

                                                            </div>

                                                        </td>


                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endforeach

                </section>

            @endforeach


        @else

            {{-- ========================================================
                 EMPTY STATE
            ========================================================= --}}

            <div class="attendance-empty-page">

                <div class="attendance-empty-page-icon">

                    <i class="bi bi-calendar-x"></i>

                </div>

                <h3>
                    No attendance records found
                </h3>

                <p>
                    There are no attendance records matching the selected
                    date, class or attendance status.
                </p>

                <a
                    href="{{ url()->current() }}"
                    class="attendance-header-btn attendance-btn-create mt-3"
                >

                    <i class="bi bi-arrow-clockwise"></i>

                    Clear Filters

                </a>

            </div>

        @endif


        {{-- ============================================================
             PAGINATION
        ============================================================= --}}

        @if($records->hasPages())

            <div class="attendance-pagination">

                {{ $records->withQueryString()->links() }}

            </div>

        @endif


        {{-- ============================================================
             STATUS LEGEND
        ============================================================= --}}

        <div class="attendance-legend">

            <span class="attendance-legend-title">
                Status
            </span>


            <span class="attendance-legend-item legend-present">

                <span class="attendance-legend-dot"></span>

                Present

            </span>


            <span class="attendance-legend-item legend-absent">

                <span class="attendance-legend-dot"></span>

                Absent

            </span>


            <span class="attendance-legend-item legend-late">

                <span class="attendance-legend-dot"></span>

                Late

            </span>


            <span class="attendance-legend-item legend-excused">

                <span class="attendance-legend-dot"></span>

                Excused

            </span>


            <span class="attendance-legend-item legend-sick">

                <span class="attendance-legend-dot"></span>

                Sick

            </span>

        </div>


    </div>

</div>


<script>

    document.addEventListener('DOMContentLoaded', function () {

        /*
         * Automatically submit the filter form when the user
         * presses Enter inside an input/select.
         */

        const filterForm = document.querySelector(
            '.attendance-filter-card form'
        );

        if (!filterForm) {
            return;
        }

        filterForm.addEventListener('keydown', function (event) {

            if (event.key !== 'Enter') {
                return;
            }

            if (
                event.target.tagName.toLowerCase() === 'button'
            ) {
                return;
            }

            event.preventDefault();

            filterForm.submit();

        });

    });

</script>

@endsection