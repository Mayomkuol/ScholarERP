@extends('layouts.app')

@section('title', 'Student Fees')

@section('content')

<div class="container-fluid">
     <x-school-header
    title="FINANCE"
    subtitle="Student Fee Assignment"
/>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Student Fee Assignment
            </h3>

            <p class="text-muted mb-0">
                Manage assigned fees for students.
            </p>
        </div>

        <a href="{{ route('student-fees.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Assign Fees
        </a>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif

    {{-- Summary Cards --}}
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Total Students
                    </small>

                    <h3 class="fw-bold mt-2">
                        {{ $studentFees->count() }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Total Assigned Fees
                    </small>

                    <h3 class="fw-bold text-primary mt-2">

                        {{ number_format($studentFees->sum('total_fee'),2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Total Discounts
                    </small>

                    <h3 class="fw-bold text-warning mt-2">

                        {{ number_format($studentFees->sum('discount'),2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Net Fees
                    </small>

                    <h3 class="fw-bold text-success mt-2">

                        {{ number_format($studentFees->sum('net_fee'),2) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>

    {{-- Main Card --}}
    <div class="card shadow">

        <div class="card-header bg-white">

            <div class="row">

                <div class="col-md-4">

                    <input
                        type="text"
                        id="searchTable"
                        class="form-control"
                        placeholder="Search student...">

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0" id="studentFeeTable">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Admission No</th>

                            <th>Student</th>

                            <th>Academic Year</th>

                            <th>Term</th>

                            <th>Total Fee</th>

                            <th>Discount</th>

                            <th>Net Fee</th>

                            <th>Status</th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($studentFees as $fee)

                        <tr>

                            <td>

                                {{ $loop->iteration + ($studentFees->firstItem() - 1) }}

                            </td>

                            <td>

                                {{ $fee->student->admission_no }}

                            </td>

                            <td>

                                {{ $fee->student->first_name }}
                                {{ $fee->student->middle_name }}
                                {{ $fee->student->last_name }}

                            </td>

                            <td>

                                {{ $fee->academicYear->name }}

                            </td>

                            <td>

                                {{ $fee->term->name }}

                            </td>

                            <td>

                                {{ number_format($fee->total_fee,2) }}

                            </td>

                            <td>

                                {{ number_format($fee->discount,2) }}

                            </td>

                            <td>

                                <strong class="text-success">

                                    {{ number_format($fee->net_fee,2) }}

                                </strong>

                            </td>

                            <td>

                                <span class="badge bg-success">

                                    Assigned

                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route('student-fees.edit',$fee) }}"
                                    class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <form
                                    action="{{ route('student-fees.destroy',$fee) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete this fee assignment?')"
                                        class="btn btn-danger btn-sm">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10" class="text-center py-5">

                                <div class="text-muted">

                                    <i class="bi bi-wallet2 fs-1"></i>

                                    <p class="mt-3">

                                        No student fee assignments found.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer bg-white">

            {{ $studentFees->links() }}

        </div>

    </div>

</div>

<script>

document.getElementById('searchTable').addEventListener('keyup', function () {

    let value = this.value.toLowerCase();

    let rows = document.querySelectorAll('#studentFeeTable tbody tr');

    rows.forEach(function(row){

        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';

    });

});

</script>

@endsection