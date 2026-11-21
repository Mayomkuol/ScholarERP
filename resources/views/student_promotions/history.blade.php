@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Promotion History
            </h4>

            <p class="text-muted mb-0">
                View the academic promotion history of students.
            </p>
        </div>

        <div>
            <a href="{{ route('student-promotions.index') }}"
               class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                Promotions
            </a>
        </div>

    </div>


    {{-- Promotion History Card --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-success text-white">

            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>
                Promotion History
            </h5>

        </div>


        <div class="card-body">

            @if($promotions->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Date</th>
                                <th>Admission No.</th>
                                <th>Student</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Remarks</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($promotions as $promotion)

                                <tr>

                                    <td>
                                        {{ \Carbon\Carbon::parse($promotion->promotion_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $promotion->student->admission_no ?? '-' }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $promotion->student->first_name ?? '' }}
                                        {{ $promotion->student->last_name ?? '' }}
                                    </td>

                                    <td>

                                        {{ $promotion->fromClass->name ?? '-' }}

                                        @if($promotion->fromStream)
                                            <span class="text-muted">
                                                ({{ $promotion->fromStream->name }})
                                            </span>
                                        @endif

                                        <br>

                                        <small class="text-muted">
                                            {{ $promotion->fromYear->name ?? '-' }}
                                        </small>

                                    </td>

                                    <td>

                                        {{ $promotion->toClass->name ?? '-' }}

                                        @if($promotion->toStream)
                                            <span class="text-muted">
                                                ({{ $promotion->toStream->name }})
                                            </span>
                                        @endif

                                        <br>

                                        <small class="text-muted">
                                            {{ $promotion->toYear->name ?? '-' }}
                                        </small>

                                    </td>

                                    <td>
                                        {{ $promotion->remarks ?: '—' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">

                    {{ $promotions->links() }}

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-clock-history fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Promotion History
                    </h5>

                    <p class="text-muted mb-0">
                        No student promotions have been recorded yet.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection