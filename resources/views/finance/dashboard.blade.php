@extends('layouts.app')

@section('title', 'Finance Dashboard')

@section('content')

<div class="container-fluid">

    <x-school-header
        title="FINANCE"
        subtitle="DASHBOARD"
    />

    {{-- ============================================================
        COLLECTION SUMMARY
    ============================================================= --}}

    <div class="row g-4">

        {{-- Today's Collection --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 border-start border-success border-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                Today's Collection
                            </h6>

                            <h3 class="text-success mb-0">
                                KES {{ number_format($todayCollection, 2) }}
                            </h3>
                        </div>

                        <div class="text-success fs-2">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- This Month --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                This Month
                            </h6>

                            <h3 class="text-primary mb-0">
                                KES {{ number_format($monthCollection, 2) }}
                            </h3>
                        </div>

                        <div class="text-primary fs-2">
                            <i class="bi bi-calendar-month"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- This Year --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 border-start border-info border-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                This Year
                            </h6>

                            <h3 class="text-info mb-0">
                                KES {{ number_format($yearCollection, 2) }}
                            </h3>
                        </div>

                        <div class="text-info fs-2">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Outstanding --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 border-start border-danger border-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                Outstanding Fees
                            </h6>

                            <h3 class="text-danger mb-0">
                                KES {{ number_format($outstanding, 2) }}
                            </h3>
                        </div>

                        <div class="text-danger fs-2">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FEE SUMMARY
    ============================================================= --}}

    <div class="row g-4 mt-1">

        {{-- Expected Fees --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Expected Fees
                    </h6>

                    <h3 class="mb-0">
                        KES {{ number_format($expectedFees, 2) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Total Paid --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Fees Paid
                    </h6>

                    <h3 class="text-success mb-0">
                        KES {{ number_format($totalPaid, 2) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Fully Paid --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Fully Paid Students
                    </h6>

                    <h2 class="text-success mb-0">
                        {{ number_format($fullyPaid) }}
                    </h2>

                </div>

            </div>

        </div>


        {{-- Students Owing --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Students Owing
                    </h6>

                    <h2 class="text-danger mb-0">
                        {{ number_format($owing) }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        STUDENT SUMMARY
    ============================================================= --}}

    <div class="row mt-4">

        <div class="col-md-12">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            Student Fee Summary
                        </h5>

                        <span class="badge bg-primary">
                            {{ number_format($students) }} Students
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-md-4">

                            <div class="py-3">

                                <div class="text-muted mb-2">
                                    Total Students
                                </div>

                                <h2 class="mb-0">
                                    {{ number_format($students) }}
                                </h2>

                            </div>

                        </div>


                        <div class="col-md-4 border-start border-end">

                            <div class="py-3">

                                <div class="text-muted mb-2">
                                    Fully Paid
                                </div>

                                <h2 class="text-success mb-0">
                                    {{ number_format($fullyPaid) }}
                                </h2>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="py-3">

                                <div class="text-muted mb-2">
                                    Owing
                                </div>

                                <h2 class="text-danger mb-0">
                                    {{ number_format($owing) }}
                                </h2>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        RECENT PAYMENTS
    ============================================================= --}}

    <div class="card mt-4 shadow-sm border-0">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Recent Payments
                </h5>

                <span class="badge bg-light text-primary">
                    Latest 10
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                Date
                            </th>

                            <th>
                                Receipt
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Amount Received
                            </th>

                            <th>
                                Amount Allocated
                            </th>

                            <th>
                                Wallet Credit
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($recentPayments as $payment)

                            <tr>

                                <td class="ps-3">

                                    {{ $payment->payment_date
                                        ? $payment->payment_date->format('d M Y')
                                        : '—'
                                    }}

                                </td>


                                <td>

                                    <span class="fw-semibold">
                                        {{ $payment->receipt_no }}
                                    </span>

                                </td>


                                <td>

                                    @if($payment->student)

                                        <div class="fw-semibold">

                                            {{ $payment->student->first_name }}
                                            {{ $payment->student->last_name }}

                                        </div>

                                        @if(!empty($payment->student->admission_no))

                                            <small class="text-muted">
                                                {{ $payment->student->admission_no }}
                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            Student unavailable
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <span class="fw-bold text-success">

                                        KES
                                        {{ number_format(
                                            $payment->amount_received ?? $payment->amount_paid ?? 0,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                <td>

                                    KES
                                    {{ number_format(
                                        $payment->amount_allocated ?? 0,
                                        2
                                    ) }}

                                </td>


                                <td>

                                    @if(($payment->overpayment_amount ?? 0) > 0)

                                        <span class="badge bg-info">

                                            KES
                                            {{ number_format(
                                                $payment->overpayment_amount,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-receipt fs-1 d-block mb-2"></i>

                                        No payments recorded yet.

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