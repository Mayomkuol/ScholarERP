@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-clock-history me-2"></i>
                Timetable Periods
            </h3>

            <p class="text-muted mb-0">
                Manage the school's daily timetable periods, breaks and activities.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('timetables.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-calendar3 me-1"></i>
                Timetable

            </a>

            <a href="{{ route('timetables.periods.create') }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>
                Add Period

            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Unable to complete the operation.
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Total Periods
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $periods->count() }}
                            </h3>
                        </div>

                        <div class="text-primary fs-2">
                            <i class="bi bi-clock"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Active
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $periods->where('is_active', true)->count() }}
                            </h3>

                        </div>

                        <div class="text-success fs-2">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Lessons
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $periods->where('type', 'lesson')->count() }}
                            </h3>

                        </div>

                        <div class="text-info fs-2">
                            <i class="bi bi-book"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Breaks / Activities
                            </small>

                            <h3 class="fw-bold mb-0">

                                {{
                                    $periods->whereIn('type', [
                                        'break',
                                        'lunch',
                                        'assembly',
                                        'activity'
                                    ])->count()
                                }}

                            </h3>

                        </div>

                        <div class="text-warning fs-2">
                            <i class="bi bi-cup-hot"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Period Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0 fw-bold">
                        School Daily Schedule
                    </h5>

                    <small class="text-muted">
                        These periods will be used when creating the weekly timetable.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="text-center" style="width: 80px;">
                                #
                            </th>

                            <th>
                                Period
                            </th>

                            <th>
                                Time
                            </th>

                            <th>
                                Type
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th class="text-end" style="width: 180px;">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($periods as $period)

                            <tr>

                                <td class="text-center fw-bold text-muted">

                                    {{ $period->period_number ?? '—' }}

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ $period->name }}

                                    </div>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }}

                                        -

                                        {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}

                                    </span>

                                </td>


                                <td>

                                    @php

                                        $typeClass = match($period->type) {

                                            'lesson' => 'bg-primary',

                                            'break' => 'bg-warning text-dark',

                                            'lunch' => 'bg-success',

                                            'assembly' => 'bg-info text-dark',

                                            'activity' => 'bg-secondary',

                                            default => 'bg-dark',

                                        };

                                    @endphp

                                    <span class="badge {{ $typeClass }}">

                                        {{ ucfirst($period->type) }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    @if($period->is_active)

                                        <span class="badge bg-success-subtle text-success">

                                            <i class="bi bi-check-circle me-1"></i>
                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-secondary-subtle text-secondary">

                                            <i class="bi bi-dash-circle me-1"></i>
                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                <td class="text-end">

                                    <a href="{{ route('timetables.periods.edit', $period) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    <form action="{{ route('timetables.periods.destroy', $period) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this period?')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-clock-history fs-1 d-block mb-3"></i>

                                        <h5>No timetable periods configured</h5>

                                        <p class="mb-3">
                                            Create your school's daily timetable periods to get started.
                                        </p>

                                        <a href="{{ route('timetables.periods.create') }}"
                                           class="btn btn-primary">

                                            <i class="bi bi-plus-circle me-1"></i>
                                            Add First Period

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection