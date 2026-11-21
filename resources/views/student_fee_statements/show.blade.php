@extends('layouts.app')

@section('title', 'Student Fee Statement')

@section('content')

<div class="container-fluid">

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Student Fee Statement
            </h4>

            <div>

                <button
                    onclick="window.print()"
                    class="btn btn-light">

                    <i class="bi bi-printer"></i>
                    Print

                </button>

                <a
                    href="{{ route('fee-statements.index') }}"
                    class="btn btn-secondary">

                    Back

                </a>

            </div>

        </div>

        <div class="card-body">

           {{-- =========================================================
     SCHOOL OFFICIAL HEADER
========================================================= --}}

@php

    $school = $school ?? \App\Models\School::first();

    $logoUrl = null;

    if ($school && !empty($school->logo)) {

        if (
            \Illuminate\Support\Str::startsWith(
                $school->logo,
                ['http://', 'https://']
            )
        ) {

            $logoUrl = $school->logo;

        } else {

            $logoUrl = asset(
                'storage/' . ltrim($school->logo, '/')
            );

        }

    }

@endphp


<div class="school-header">

    {{-- SCHOOL LOGO --}}

    <div class="school-logo-section">

        @if($logoUrl)

            <img
                src="{{ $logoUrl }}"
                alt="{{ $school->name }} Logo"
                class="school-logo"
            >

        @else

            <div class="school-logo-placeholder">
                SCHOOL LOGO
            </div>

        @endif

    </div>


    {{-- SCHOOL INFORMATION --}}

    <div class="school-information">

        <div class="school-name">

            {{ $school->name ?? 'SCHOOL NAME' }}

        </div>


        {{-- MOTTO --}}

        @if(!empty($school?->motto))

            <div class="school-motto">

                "{{ $school->motto }}"

            </div>

        @endif


        {{-- ADDRESS --}}

        @if(!empty($school?->address))

            <div class="school-address">

                {{ $school->address }}

                @if(!empty($school?->country))

                    , {{ $school->country }}

                @endif

            </div>

        @endif


        {{-- CONTACT DETAILS --}}

        <div class="school-contact">

            @if(!empty($school?->phone))

                <strong>Tel:</strong>
                {{ $school->phone }}

            @endif


            @if(
                !empty($school?->phone) &&
                !empty($school?->email)
            )

                &nbsp; | &nbsp;

            @endif


            @if(!empty($school?->email))

                <strong>Email:</strong>
                {{ $school->email }}

            @endif


            @if(!empty($school?->website))

                &nbsp; | &nbsp;

                <strong>Web:</strong>
                {{ $school->website }}

            @endif

        </div>


        {{-- SCHOOL CODE / REGISTRATION NUMBER --}}

        <div class="school-registration">

            @if(!empty($school?->code))

                <strong>School Code:</strong>
                {{ $school->code }}

            @endif


            @if(
                !empty($school?->code) &&
                !empty($school?->registration_no)
            )

                &nbsp; | &nbsp;

            @endif


            @if(!empty($school?->registration_no))

                <strong>Reg. No:</strong>
                {{ $school->registration_no }}

            @endif

        </div>

    </div>


    {{-- DOCUMENT TYPE --}}

    <div class="document-label">

        OFFICIAL<br>
        FEE<br>
        STATEMENT

    </div>

</div>


{{-- DOCUMENT TITLE --}}

<div class="statement-title">

    <h2>
        STUDENT FEE STATEMENT
    </h2>

    <div class="statement-subtitle">

        Generated:
        {{ now()->format('d M Y, H:i') }}

    </div>

</div>
            <!-- Student Information -->

            <h5 class="text-primary mb-3">

                Student Information

            </h5>

            <table class="table table-bordered">

                <tr>

                    <th width="30%">
                        Admission Number
                    </th>

                    <td>

                        {{ $student->admission_no }}

                    </td>

                </tr>

                <tr>

                    <th>
                        Student Name
                    </th>

                    <td>

                        {{ $student->first_name }}
                        {{ $student->middle_name }}
                        {{ $student->last_name }}

                    </td>

                </tr>

            </table>

            @foreach($student->studentFees as $fee)

                @php

                    $paid = $fee->paid_amount;

                    $balance = $fee->balance;

                @endphp

                <div class="card mt-4">

                    <div class="card-header bg-light">

                        <strong>

                            {{ $fee->academicYear->name }}

                        </strong>

                        -

                        <strong>

                            {{ $fee->term->name }}

                        </strong>

                    </div>

                    <div class="card-body">

                        <!-- Fee Summary -->

                        <table class="table table-bordered">

                            <tr>

                                <th width="30%">

                                    Total Fee

                                </th>

                                <td>

                                    {{ number_format($fee->total_fee,2) }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Discount

                                </th>

                                <td>

                                    {{ number_format($fee->discount,2) }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Net Fee

                                </th>

                                <td>

                                    {{ number_format($fee->net_fee,2) }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Total Paid

                                </th>

                                <td class="text-success fw-bold">

                                    {{ number_format($paid,2) }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Balance

                                </th>

                                <td class="text-danger fw-bold">

                                    {{ number_format($balance,2) }}

                                </td>

                            </tr>

                        </table>

                        <!-- Payment History -->

                        <h5 class="mt-4">

                            Payment History

                        </h5>

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered">

                                <thead class="table-dark">

                                    <tr>

                                        <th>#</th>

                                        <th>Date</th>

                                        <th>Receipt No</th>

                                        <th>Method</th>

                                        <th>Amount</th>

                                        <th>Reference</th>

                                        <th>Received By</th>

                                    </tr>

                                </thead>

                                <tbody>

                                @forelse($fee->payments as $payment)

                                    <tr>

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>

                                        <td>

                                            {{ $payment->payment_date->format('d M Y') }}

                                        </td>

                                        <td>

                                            {{ $payment->receipt_no }}

                                        </td>

                                        <td>

                                            {{ $payment->payment_method }}

                                        </td>

                                        <td>

                                            {{ number_format($payment->amount_paid,2) }}

                                        </td>

                                        <td>

                                            {{ $payment->reference_no ?: '-' }}

                                        </td>

                                        <td>

                                            {{ optional($payment->receiver)->name ?? '-' }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center text-muted">

                                            No payments recorded.

                                        </td>

                                    </tr>

                                @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            @endforeach

            <div class="mt-5 text-center">

                <p class="mb-5">

                    ____________________________

                </p>

                <strong>

                    Bursar / Finance Office

                </strong>

            </div>

        </div>

    </div>

</div>

@endsection

<style>

.school-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;

    border-bottom: 2px solid #000;

    padding: 10px 0 12px;

    margin-bottom: 10px;
}


/* =========================================================
   LOGO
========================================================= */

.school-logo-section {
    width: 18%;

    display: flex;
    justify-content: center;
    align-items: center;
}

.school-logo {
    width: 85px;
    height: 85px;

    object-fit: contain;
}

.school-logo-placeholder {
    width: 75px;
    height: 75px;

    border: 1px solid #999;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 8px;
    color: #777;

    text-align: center;
}


/* =========================================================
   SCHOOL INFORMATION
========================================================= */

.school-information {
    width: 64%;

    text-align: center;

    line-height: 1.4;
}

.school-name {
    font-size: 21px;

    font-weight: 800;

    text-transform: uppercase;

    line-height: 1.2;

    margin-bottom: 3px;
}

.school-motto {
    font-size: 11px;

    font-style: italic;

    font-weight: 600;

    margin-bottom: 4px;
}

.school-address {
    font-size: 9px;

    margin-bottom: 2px;
}

.school-contact {
    font-size: 8.5px;

    line-height: 1.5;
}

.school-registration {
    font-size: 8px;

    margin-top: 3px;

    color: #333;
}


/* =========================================================
   DOCUMENT LABEL
========================================================= */

.document-label {
    width: 18%;

    text-align: center;

    font-size: 8px;

    font-weight: bold;

    line-height: 1.5;

    border: 1px solid #555;

    padding: 6px;
}


/* =========================================================
   STATEMENT TITLE
========================================================= */

.statement-title {
    text-align: center;

    margin: 10px 0 15px;
}

.statement-title h2 {
    margin: 0;

    font-size: 15px;

    font-weight: 800;

    text-decoration: underline;
}

.statement-subtitle {
    font-size: 8px;

    color: #555;

    margin-top: 3px;
}


/* =========================================================
   PRINT
========================================================= */

@media print {

    .school-header {
        padding-top: 0;
        padding-bottom: 7px;

        margin-bottom: 7px;
    }

    .school-logo {
        width: 70px;
        height: 70px;
    }

    .school-name {
        font-size: 17px;
    }

    .school-motto {
        font-size: 9px;
    }

    .school-address {
        font-size: 7px;
    }

    .school-contact {
        font-size: 7px;
    }

    .school-registration {
        font-size: 6.5px;
    }

    .document-label {
        font-size: 7px;
    }

    .statement-title {
        margin: 6px 0 9px;
    }

    .statement-title h2 {
        font-size: 11px;
    }

    .statement-subtitle {
        font-size: 6.5px;
    }

}

</style>