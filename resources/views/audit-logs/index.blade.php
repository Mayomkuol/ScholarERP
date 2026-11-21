@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

{{-- =========================================================
     PAGE HEADER
========================================================== --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>
        <h4 class="mb-1 fw-bold">
            <i class="fas fa-history text-primary me-2"></i>
            Audit Logs
        </h4>

        <p class="text-muted mb-0">
            Monitor important activities performed across ScholarERP.
        </p>
    </div>

    <div>
        <span class="badge bg-dark px-3 py-2 rounded-pill">
            <i class="fas fa-shield-alt me-1"></i>
            Super Admin
        </span>
    </div>

</div>


{{-- =========================================================
     STATISTICS
========================================================== --}}
<div class="row g-3 mb-4">

    {{-- Total Activities --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="text-muted small mb-1">
                            Total Activities
                        </div>

                        <h3 class="mb-0 fw-bold">
                            {{ number_format($logs->total()) }}
                        </h3>
                    </div>

                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                        <i class="fas fa-history fa-lg"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Modules --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="text-muted small mb-1">
                            Modules
                        </div>

                        <h3 class="mb-0 fw-bold">
                            {{ $modules->count() }}
                        </h3>
                    </div>

                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                        <i class="fas fa-cubes fa-lg"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="text-muted small mb-1">
                            Action Types
                        </div>

                        <h3 class="mb-0 fw-bold">
                            {{ $actions->count() }}
                        </h3>
                    </div>

                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                        <i class="fas fa-tasks fa-lg"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Current User --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="overflow-hidden">

                        <div class="text-muted small mb-1">
                            Current User
                        </div>

                        <h6 class="mb-0 fw-bold text-truncate">
                            {{ auth()->user()->name }}
                        </h6>

                    </div>

                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 flex-shrink-0">
                        <i class="fas fa-user-shield fa-lg"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     FILTERS
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h6 class="mb-1 fw-bold">
                    <i class="fas fa-filter text-primary me-2"></i>
                    Filter Activity
                </h6>

                <small class="text-muted">
                    Search and filter audit records.
                </small>
            </div>

            @if(
                request()->filled('search') ||
                request()->filled('module') ||
                request()->filled('action') ||
                request()->filled('user_id') ||
                request()->filled('date_from') ||
                request()->filled('date_to')
            )

                <a href="{{ route('audit-logs.index') }}"
                   class="btn btn-sm btn-outline-secondary">

                    <i class="fas fa-times me-1"></i>
                    Clear Filters

                </a>

            @endif

        </div>

    </div>


    <div class="card-body">

        <form method="GET"
              action="{{ route('audit-logs.index') }}">

            <div class="row g-3">


                {{-- Search --}}
                <div class="col-xl-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search activity..."
                            value="{{ request('search') }}"
                        >

                    </div>

                </div>


                {{-- Module --}}
                <div class="col-xl-2 col-md-6">

                    <label class="form-label fw-semibold">
                        Module
                    </label>

                    <select name="module"
                            class="form-select">

                        <option value="">
                            All Modules
                        </option>

                        @foreach($modules as $module)

                            <option
                                value="{{ $module }}"
                                @selected(request('module') === $module)
                            >
                                {{ ucwords(str_replace('_', ' ', $module)) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Action --}}
                <div class="col-xl-2 col-md-6">

                    <label class="form-label fw-semibold">
                        Action
                    </label>

                    <select name="action"
                            class="form-select">

                        <option value="">
                            All Actions
                        </option>

                        @foreach($actions as $action)

                            <option
                                value="{{ $action }}"
                                @selected(request('action') === $action)
                            >
                                {{ ucwords(str_replace('_', ' ', $action)) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- User --}}
                <div class="col-xl-2 col-md-6">

                    <label class="form-label fw-semibold">
                        User
                    </label>

                    <select name="user_id"
                            class="form-select">

                        <option value="">
                            All Users
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    (string) request('user_id') ===
                                    (string) $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- From --}}
                <div class="col-xl-1 col-md-6">

                    <label class="form-label fw-semibold">
                        From
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        class="form-control"
                        value="{{ request('date_from') }}"
                    >

                </div>


                {{-- To --}}
                <div class="col-xl-1 col-md-6">

                    <label class="form-label fw-semibold">
                        To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        class="form-control"
                        value="{{ request('date_to') }}"
                    >

                </div>


                {{-- Filter Button --}}
                <div class="col-xl-1 col-md-6 d-flex align-items-end">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                        title="Apply Filters"
                    >
                        <i class="fas fa-filter"></i>
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     ACTIVITY HISTORY
========================================================== --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

            <div>

                <h6 class="mb-1 fw-bold">
                    <i class="fas fa-list text-primary me-2"></i>
                    Activity History
                </h6>

                <small class="text-muted">
                    Showing {{ $logs->count() }}
                    of {{ number_format($logs->total()) }}
                    recorded activities
                </small>

            </div>

            <span class="badge bg-light text-dark border">
                <i class="fas fa-database me-1"></i>
                Audit Trail
            </span>

        </div>

    </div>


    <div class="card-body p-0">

        @if($logs->count())


            {{-- =================================================
                 TABLE
            ================================================== --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                User
                            </th>

                            <th>
                                Action
                            </th>

                            <th>
                                Module
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Date & Time
                            </th>

                            <th class="text-end px-4">
                                View
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($logs as $log)

                            <tr>


                                {{-- =================================
                                     USER
                                ================================== --}}
                                <td class="px-4">

                                    <div class="d-flex align-items-center">

                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                             style="width:38px;height:38px;">

                                            <i class="fas fa-user"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $log->user?->name ?? 'System' }}

                                            </div>

                                            @if($log->user?->email)

                                                <small class="text-muted">

                                                    {{ $log->user->email }}

                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- =================================
                                     ACTION
                                ================================== --}}
                                <td>

                                    @php

                                        $action = strtolower(
                                            (string) $log->action
                                        );

                                        $badge = match($action) {

                                            'created' =>
                                                'success',

                                            'updated' =>
                                                'primary',

                                            'deleted' =>
                                                'danger',

                                            'login' =>
                                                'info',

                                            'logout' =>
                                                'secondary',

                                            'backup_created' =>
                                                'success',

                                            'backup_downloaded' =>
                                                'dark',

                                            'database_restored' =>
                                                'warning',

                                            'restored' =>
                                                'warning',

                                            'role_assigned' =>
                                                'primary',

                                            'role_removed' =>
                                                'danger',

                                            'permission_granted' =>
                                                'success',

                                            'permission_revoked' =>
                                                'danger',

                                            default =>
                                                'dark',
                                        };

                                        $actionLabel = ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $action
                                            )
                                        );

                                    @endphp


                                    <span class="badge bg-{{ $badge }} px-2 py-1">

                                        {{ $actionLabel }}

                                    </span>

                                </td>


                                {{-- =================================
                                     MODULE
                                ================================== --}}
                                <td>

                                    @if($log->module)

                                        <span class="badge bg-light text-dark border">

                                            {{ ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $log->module
                                                )
                                            ) }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     DESCRIPTION
                                ================================== --}}
                                <td>

                                    <div
                                        class="text-truncate"
                                        style="max-width:360px;"
                                        title="{{ $log->description }}"
                                    >

                                        {{ $log->description ?: 'No description provided.' }}

                                    </div>

                                </td>


                                {{-- =================================
                                     IP ADDRESS
                                ================================== --}}
                                <td>

                                    @if($log->ip_address)

                                        <code class="small">
                                            {{ $log->ip_address }}
                                        </code>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     DATE / TIME
                                ================================== --}}
                                <td>

                                    @if($log->created_at)

                                        <div class="fw-semibold">

                                            {{ $log->created_at->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $log->created_at->format('h:i A') }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     VIEW
                                ================================== --}}
                                <td class="text-end px-4">

                                    <a
                                        href="{{ route('audit-logs.show', $log) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Audit Details"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}
            @if($logs->hasPages())

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-top">

                    <small class="text-muted">

                        Showing
                        <strong>{{ $logs->firstItem() }}</strong>
                        to
                        <strong>{{ $logs->lastItem() }}</strong>
                        of
                        <strong>{{ $logs->total() }}</strong>
                        activities

                    </small>

                    <div>
                        {{ $logs->links() }}
                    </div>

                </div>

            @endif


        @else


            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}
            <div class="text-center py-5 px-3">

                <div class="mb-3">

                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width:80px;height:80px;">

                        <i class="fas fa-history fa-2x text-muted"></i>

                    </div>

                </div>

                <h6 class="fw-bold">
                    No Audit Logs Found
                </h6>

                <p class="text-muted mb-3">

                    No activities match your current filters.

                </p>


                @if(
                    request()->filled('search') ||
                    request()->filled('module') ||
                    request()->filled('action') ||
                    request()->filled('user_id') ||
                    request()->filled('date_from') ||
                    request()->filled('date_to')
                )

                    <a
                        href="{{ route('audit-logs.index') }}"
                        class="btn btn-outline-primary btn-sm"
                    >

                        <i class="fas fa-times me-1"></i>
                        Clear Filters

                    </a>

                @endif

            </div>

        @endif

    </div>

</div>

</div>

@endsection
