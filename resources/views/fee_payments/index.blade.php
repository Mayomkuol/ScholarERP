@extends('layouts.app')

@section('title', 'Fee Payments')

@section('content')

<div class="container-fluid">

    <x-school-header
    title="FINANCE"
    subtitle="FEE PAYMENT REGISTER"
/>

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Fee Payments</h3>
            <small class="text-muted">
                Manage student fee payments and reversals.
            </small>
        </div>

        <a href="{{ route('fee-payments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Receive Payment
        </a>

    </div>

    {{-- Summary --}}
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card border-success shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted mb-2">

                        Total Payments

                    </h6>

                    <h2 class="fw-bold">

                        {{ $payments->total() }}

                    </h2>

                </div>

            </div>

        </div>

    </div>

    {{-- Alerts --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif

    {{-- Payments Table --}}
    <div class="card shadow border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>#</th>

                            <th>Receipt No.</th>

                            <th>Student</th>

                            <th>Admission No.</th>

                            <th class="text-end">Amount</th>

                            <th>Payment Date</th>

                            <th>Method</th>

                            <th>Status</th>

                            <th>Received By</th>

                            <th class="text-center" width="230">

                                Actions

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($payments as $payment)

                        <tr>

                            <td>

                                {{ $payments->firstItem() + $loop->index }}

                            </td>

                            <td>

                                <strong>

                                    {{ $payment->receipt_no }}

                                </strong>

                            </td>

                            <td>

                                {{ $payment->student?->first_name }}
                                {{ $payment->student?->middle_name }}
                                {{ $payment->student?->last_name }}

                            </td>

                            <td>

                                {{ $payment->student?->admission_no }}

                            </td>

                            <td class="text-end fw-bold">

                                KES {{ number_format($payment->amount_paid,2) }}

                            </td>

                            <td>

                                {{ $payment->payment_date->format('d M Y') }}

                            </td>

                            <td>

                                {{ $payment->payment_method }}

                            </td>

                            <td>

                                @if(empty($payment->reversed_at))

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger"
                                          title="{{ $payment->reversal_reason }}">

                                        Reversed

                                    </span>

                                    <br>

                                    <small class="text-muted">

                                        {{ \Carbon\Carbon::parse($payment->reversed_at)->format('d M Y') }}

                                    </small>

                                @endif

                            </td>

                            <td>

                                {{ optional($payment->receiver)->name ?? 'System' }}

                            </td>

                            <td class="text-center">

                                {{-- View --}}
                                <a href="{{ route('fee-payments.show', $payment->receipt_no) }}"
   class="btn btn-primary btn-sm"
   title="View Receipt">

    <i class="bi bi-eye"></i>

</a>
                                {{-- Edit --}}
                                @if(empty($payment->reversed_at))

                                    <a href="{{ route('fee-payments.edit',$payment) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Edit Payment">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                @else

                                    <button
                                        class="btn btn-secondary btn-sm"
                                        disabled
                                        title="Reversed payments cannot be edited">

                                        <i class="bi bi-pencil"></i>

                                    </button>

                                @endif

                                {{-- Reverse --}}
                                @if(empty($payment->reversed_at))

                                <form action="{{ route('fee-payments.reverse',$payment) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf

                                    <button
                                        class="btn btn-danger btn-sm"
                                        title="Reverse Payment"
                                        onclick="return confirm('Are you sure you want to reverse this payment?')">

                                        <i class="bi bi-arrow-counterclockwise"></i>

                                    </button>

                                </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10" class="text-center py-5">

                                <i class="bi bi-cash-stack fs-1 text-muted"></i>

                                <br><br>

                                No fee payments found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-4">

                {{ $payments->links() }}

            </div>

        </div>

    </div>

</div>

@endsection