@extends('layouts.app')

@section('title', 'Official School Fee Receipt')

@section('content')

@php
    /*
     * =========================================================
     * RECEIPT FINANCIAL CALCULATIONS
     * =========================================================
     *
     * IMPORTANT:
     * No hard-coded student IDs, fee IDs, payment IDs,
     * academic year IDs or term IDs are used here.
     *
     * Everything comes from the current $feePayment and
     * its Eloquent relationships.
     */

    $studentFee = $feePayment->studentFee;

    /*
     * Net fee for this particular student fee.
     */
    $netFee = (float) (
        $studentFee?->net_fee ?? 0
    );

    /*
     * NEW CASH PHYSICALLY RECEIVED.
     *
     * Wallet credit is NOT cash received.
     */
    $cashReceived = (float) (
        $feePayment->amount_received ?? 0
    );

    /*
     * NEW CASH ACTUALLY ALLOCATED TO THE FEE.
     */
    $cashAllocated = (float) (
        $feePayment->amount_allocated
        ?? $feePayment->amount_paid
        ?? 0
    );

    /*
     * EXISTING WALLET CREDIT APPLIED.
     *
     * This is stored directly on the payment record.
     */
    $walletApplied = (float) (
        $feePayment->wallet_amount_applied ?? 0
    );

    /*
     * TOTAL APPLIED TO THE FEE.
     *
     * Cash allocated + existing wallet credit applied.
     */
    $totalApplied = $cashAllocated + $walletApplied;

    /*
     * NEW WALLET CREDIT CREATED BY THIS PAYMENT.
     *
     * This only applies when NEW CASH exceeds the
     * outstanding fee.
     */
    $newWalletCredit = (float) (
        $feePayment->overpayment_amount ?? 0
    );

    /*
     * CURRENT WALLET BALANCE AFTER THIS TRANSACTION.
     */
    $remainingWallet = (float) (
        $wallet?->balance ?? 0
    );

    /*
     * WALLET BALANCE BEFORE THIS TRANSACTION.
     *
     * Existing wallet consumed by this payment is added back.
     *
     * If new wallet credit was created, subtract it because
     * that credit did not exist before this transaction.
     */
    $walletBefore = max(
        0,
        $remainingWallet
        + $walletApplied
        - $newWalletCredit
    );

    /*
     * CURRENT OUTSTANDING BALANCE FOR THIS STUDENT FEE.
     */
    $outstanding = max(
        0,
        $netFee -
        (float) (
            $studentFee?->paid_amount ?? 0
        )
    );

@endphp


<style>

    /* =========================================================
       SCREEN DESIGN
       ========================================================= */

    .receipt-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 20px;
    }

    .receipt-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .receipt-toolbar-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .receipt-paper {
        max-width: 850px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #d9dee7;
        border-radius: 10px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .receipt-top-border {
        height: 7px;
        background: #0d6efd;
    }

    .receipt-header {
        padding: 25px 35px 18px;
        text-align: center;
        border-bottom: 1px solid #dfe3e8;
    }

    .school-logo {
        width: 82px;
        height: 82px;
        object-fit: contain;
        margin-bottom: 7px;
    }

    .school-name {
        font-size: 25px;
        font-weight: 800;
        color: #111827;
        letter-spacing: 0.3px;
        margin-bottom: 3px;
    }

    .school-motto {
        font-size: 12px;
        font-style: italic;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .school-address {
        font-size: 11px;
        color: #4b5563;
        margin-bottom: 2px;
    }

    .school-contact {
        font-size: 11px;
        color: #4b5563;
    }

    .receipt-heading {
        margin-top: 15px;
    }

    .receipt-heading h2 {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
        color: #111827;
        letter-spacing: 0.5px;
    }

    .receipt-heading p {
        font-size: 10px;
        color: #6b7280;
        margin: 3px 0 0;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .receipt-meta {
        padding: 15px 35px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .meta-label {
        font-size: 10px;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 12px;
        font-weight: 700;
        color: #111827;
    }

    .receipt-body {
        padding: 25px 35px;
    }

    .section {
        margin-bottom: 18px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 800;
        color: #0d6efd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        padding-bottom: 6px;
        border-bottom: 1px solid #dbe5f1;
    }

    .section-title i {
        font-size: 14px;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table th,
    .info-table td {
        border: 1px solid #dfe3e8;
        padding: 8px 10px;
        font-size: 11px;
        vertical-align: middle;
    }

    .info-table th {
        width: 38%;
        background: #f8fafc;
        color: #374151;
        font-weight: 700;
    }

    .info-table td {
        color: #111827;
    }

    .amount-received {
        font-size: 16px;
        font-weight: 800;
        color: #198754;
    }

    .amount-applied {
        font-weight: 700;
        color: #111827;
    }

    .amount-credit {
        font-size: 14px;
        font-weight: 800;
        color: #0d6efd;
    }

    .amount-wallet {
        font-size: 14px;
        font-weight: 800;
        color: #198754;
    }

    .credit-notice {
        margin-top: 10px;
        padding: 10px 12px;
        border: 1px solid #b9d7ff;
        background: #f0f7ff;
        border-radius: 6px;
        color: #084298;
        font-size: 11px;
    }

    .credit-notice strong {
        font-weight: 800;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-top: 8px;
    }

    .summary-box {
        border: 1px solid #dfe3e8;
        border-radius: 6px;
        padding: 11px;
        background: #ffffff;
    }

    .summary-label {
        font-size: 9px;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .summary-value {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
    }

    .summary-box.credit {
        background: #f0f7ff;
        border-color: #b9d7ff;
    }

    .summary-box.credit .summary-value {
        color: #0d6efd;
    }

    .summary-box.success {
        background: #f0fff7;
        border-color: #b7e4c7;
    }

    .summary-box.success .summary-value {
        color: #198754;
    }

    .remarks-box {
        border: 1px solid #dfe3e8;
        background: #fafafa;
        border-radius: 5px;
        padding: 10px;
        font-size: 11px;
        color: #374151;
    }

    .reversed-box {
        margin-bottom: 18px;
        padding: 10px 12px;
        border: 1px solid #f5c2c7;
        background: #f8d7da;
        color: #842029;
        border-radius: 5px;
        font-size: 11px;
    }

    .signature-area {
        margin-top: 30px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
    }

    .signature-box {
        text-align: center;
    }

    .signature-line {
        border-top: 1px solid #333;
        padding-top: 6px;
        font-size: 10px;
        color: #374151;
    }

    .receipt-footer {
        text-align: center;
        padding: 15px 35px 22px;
        border-top: 1px solid #e5e7eb;
        color: #6b7280;
        font-size: 10px;
    }

    .receipt-footer strong {
        color: #374151;
    }


    /* =========================================================
       PRINT DESIGN
       ========================================================= */

    @media print {

        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html,
        body {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            font-family: Arial, Helvetica, sans-serif !important;
            color: #000000 !important;
            font-size: 10px !important;
        }

        .no-print,
        nav,
        aside,
        .navbar,
        .sidebar,
        .breadcrumb,
        .alert,
        header,
        footer {
            display: none !important;
        }

        .container,
        .container-fluid,
        .row,
        .col,
        .col-md-12,
        .col-lg-12,
        main,
        .content,
        .main-content {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .receipt-page {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .receipt-paper {
            width: 100% !important;
            max-width: 190mm !important;
            margin: 0 auto !important;
            padding: 0 !important;
            border: 1px solid #333 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
        }

        .receipt-top-border {
            height: 4px !important;
        }

        .receipt-header {
            padding: 10px 18px 8px !important;
        }

        .school-logo {
            width: 60px !important;
            height: 60px !important;
            margin-bottom: 3px !important;
        }

        .school-name {
            font-size: 19px !important;
            margin-bottom: 1px !important;
        }

        .school-motto {
            font-size: 9px !important;
            margin-bottom: 1px !important;
        }

        .school-address,
        .school-contact {
            font-size: 8.5px !important;
            margin-bottom: 0 !important;
        }

        .receipt-heading {
            margin-top: 7px !important;
        }

        .receipt-heading h2 {
            font-size: 14px !important;
        }

        .receipt-heading p {
            font-size: 8px !important;
            margin-top: 1px !important;
        }

        .receipt-meta {
            padding: 7px 18px !important;
        }

        .meta-label {
            font-size: 8px !important;
        }

        .meta-value {
            font-size: 9px !important;
        }

        .receipt-body {
            padding: 12px 18px !important;
        }

        .section {
            margin-bottom: 9px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .section-title {
            font-size: 9px !important;
            margin-bottom: 4px !important;
            padding-bottom: 3px !important;
        }

        .section-title i {
            font-size: 10px !important;
        }

        .info-table {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .info-table th,
        .info-table td {
            padding: 4px 6px !important;
            font-size: 8.5px !important;
            line-height: 1.15 !important;
        }

        .info-table th {
            width: 38% !important;
        }

        .amount-received {
            font-size: 11px !important;
        }

        .amount-applied {
            font-size: 9px !important;
        }

        .amount-credit,
        .amount-wallet {
            font-size: 10px !important;
        }

        .credit-notice {
            margin-top: 4px !important;
            padding: 5px 7px !important;
            font-size: 8px !important;
        }

        .summary-grid {
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 6px !important;
            margin-top: 4px !important;
        }

        .summary-box {
            padding: 6px !important;
            border-radius: 3px !important;
        }

        .summary-label {
            font-size: 7px !important;
            margin-bottom: 2px !important;
        }

        .summary-value {
            font-size: 10px !important;
        }

        .remarks-box {
            padding: 5px 7px !important;
            font-size: 8.5px !important;
        }

        .reversed-box {
            padding: 5px 7px !important;
            margin-bottom: 8px !important;
            font-size: 8.5px !important;
        }

        .signature-area {
            margin-top: 14px !important;
            gap: 35px !important;
            page-break-inside: avoid !important;
        }

        .signature-line {
            padding-top: 3px !important;
            font-size: 8px !important;
        }

        .receipt-footer {
            padding: 7px 18px 10px !important;
            font-size: 7.5px !important;
        }

        * {
            page-break-before: auto !important;
            page-break-after: auto !important;
        }
    }

</style>


<div class="receipt-page">

    {{-- =====================================================
         SCREEN TOOLBAR
         ====================================================== --}}
    <div class="receipt-toolbar no-print">

        <h1 class="receipt-toolbar-title">

            <i class="bi bi-receipt"></i>

            Official School Fee Receipt

        </h1>

        <div class="d-flex gap-2">

            <button
                type="button"
                class="btn btn-outline-primary btn-sm"
                onclick="window.print()"
            >

                <i class="bi bi-printer"></i>

                Print

            </button>


            <a
                href="{{ route('fee-payments.index') }}"
                class="btn btn-outline-secondary btn-sm"
            >

                <i class="bi bi-arrow-left"></i>

                Back

            </a>


            @if(!$feePayment->is_reversed)

                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#reversePaymentModal"
                >

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Reverse Payment

                </button>

            @endif

        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
         ====================================================== --}}
    @if(session('success'))

        <div class="alert alert-success no-print">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
         VALIDATION / ERROR MESSAGES
         ====================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger no-print">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         RECEIPT
         ====================================================== --}}
    <div class="receipt-paper">

        <div class="receipt-top-border"></div>


        {{-- =================================================
             SCHOOL HEADER
             ================================================== --}}
        <div class="receipt-header">

            @if($school?->logo)

                <img
                    src="{{ asset('storage/' . $school->logo) }}"
                    alt="School Logo"
                    class="school-logo"
                >

            @endif


            <div class="school-name">

                {{ $school?->name ?? 'SCHOOL NAME' }}

            </div>


            @if($school?->motto)

                <div class="school-motto">

                    "{{ $school->motto }}"

                </div>

            @endif


            @if($school?->address)

                <div class="school-address">

                    {{ $school->address }}

                </div>

            @endif


            <div class="school-contact">

                @if($school?->phone)

                    <strong>Tel:</strong>
                    {{ $school->phone }}

                @endif


                @if($school?->phone && $school?->email)

                    &nbsp; | &nbsp;

                @endif


                @if($school?->email)

                    <strong>Email:</strong>
                    {{ $school->email }}

                @endif

            </div>


            <div class="receipt-heading">

                <h2>
                    FEE PAYMENT RECEIPT
                </h2>

                <p>
                    Official School Receipt
                </p>

            </div>

        </div>


        {{-- =================================================
             RECEIPT META
             ================================================== --}}
        <div class="receipt-meta">

            <div class="row">

                <div class="col-6">

                    <div class="meta-label">
                        Receipt Number
                    </div>

                    <div class="meta-value">
                        {{ $feePayment->receipt_no }}
                    </div>

                </div>


                <div class="col-6 text-end">

                    <div class="meta-label">
                        Payment Date
                    </div>

                    <div class="meta-value">

                        {{ $feePayment->payment_date?->format('d F Y') }}

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             RECEIPT BODY
             ================================================== --}}
        <div class="receipt-body">


            {{-- =============================================
                 REVERSED PAYMENT
                 ============================================== --}}
            @if($feePayment->is_reversed)

                <div class="reversed-box">

                    <strong>

                        <i class="bi bi-exclamation-triangle"></i>

                        PAYMENT REVERSED

                    </strong>


                    @if($feePayment->reversal_reason)

                        <div class="mt-1">

                            Reason:
                            {{ $feePayment->reversal_reason }}

                        </div>

                    @endif


                    @if($feePayment->reversed_at)

                        <div>

                            Reversed:
                            {{ $feePayment->reversed_at->format('d F Y H:i') }}

                        </div>

                    @endif

                </div>

            @endif


            {{-- =============================================
                 STUDENT INFORMATION
                 ============================================== --}}
            <div class="section">

                <div class="section-title">

                    <i class="bi bi-person-circle"></i>

                    Student Information

                </div>


                <table class="info-table">

                    <tr>

                        <th>
                            Student
                        </th>

                        <td>

                            {{ $feePayment->student?->first_name }}
                            {{ $feePayment->student?->middle_name }}
                            {{ $feePayment->student?->last_name }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Admission Number
                        </th>

                        <td>

                            {{ $feePayment->student?->admission_no ?? '-' }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Academic Year
                        </th>

                        <td>

                            {{ $feePayment->academicYear?->name ?? '-' }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Term
                        </th>

                        <td>

                            {{ $feePayment->term?->name ?? '-' }}

                        </td>

                    </tr>

                </table>

            </div>


            {{-- =============================================
                 PAYMENT DETAILS
                 ============================================== --}}
            <div class="section">

                <div class="section-title">

                    <i class="bi bi-cash-stack"></i>

                    Payment Details

                </div>


                <table class="info-table">


                    {{-- New cash received --}}
                    <tr>

                        <th>
                            New Cash Received
                        </th>

                        <td>

                            <span class="amount-received">

                                KES
                                {{ number_format(
                                    $cashReceived,
                                    2
                                ) }}

                            </span>

                        </td>

                    </tr>


                    {{-- Existing wallet before payment --}}
                    @if($walletBefore > 0 || $walletApplied > 0)

                        <tr>

                            <th>
                                Existing Wallet Credit Before Payment
                            </th>

                            <td>

                                <span class="amount-credit">

                                    KES
                                    {{ number_format(
                                        $walletBefore,
                                        2
                                    ) }}

                                </span>

                            </td>

                        </tr>

                    @endif


                    {{-- Existing wallet applied --}}
                    @if($walletApplied > 0)

                        <tr>

                            <th>
                                Existing Wallet Credit Applied
                            </th>

                            <td>

                                <span class="amount-credit">

                                    KES
                                    {{ number_format(
                                        $walletApplied,
                                        2
                                    ) }}

                                </span>

                            </td>

                        </tr>

                    @endif


                    {{-- New cash applied --}}
                    @if($cashAllocated > 0)

                        <tr>

                            <th>
                                New Cash Applied
                            </th>

                            <td>

                                <span class="amount-applied">

                                    KES
                                    {{ number_format(
                                        $cashAllocated,
                                        2
                                    ) }}

                                </span>

                            </td>

                        </tr>

                    @endif


                    {{-- Total applied --}}
                    <tr>

                        <th>

                            Total Applied to
                            {{ $feePayment->term?->name ?? 'Term' }}

                        </th>

                        <td>

                            <strong>

                                KES
                                {{ number_format(
                                    $totalApplied,
                                    2
                                ) }}

                            </strong>

                        </td>

                    </tr>


                    {{-- New wallet credit --}}
                    @if($newWalletCredit > 0)

                        <tr>

                            <th>
                                New Student Wallet Credit
                            </th>

                            <td>

                                <span class="amount-credit">

                                    KES
                                    {{ number_format(
                                        $newWalletCredit,
                                        2
                                    ) }}

                                </span>

                            </td>

                        </tr>

                    @endif


                    {{-- Remaining wallet --}}
                    <tr>

                        <th>
                            Remaining Student Wallet Credit
                        </th>

                        <td>

                            <span class="amount-wallet">

                                KES
                                {{ number_format(
                                    $remainingWallet,
                                    2
                                ) }}

                            </span>

                        </td>

                    </tr>


                    {{-- Payment method --}}
                    <tr>

                        <th>
                            Payment Method
                        </th>

                        <td>

                            {{ $feePayment->payment_method ?? '-' }}

                        </td>

                    </tr>


                    {{-- Reference --}}
                    @if($feePayment->reference_no)

                        <tr>

                            <th>
                                Reference Number
                            </th>

                            <td>

                                {{ $feePayment->reference_no }}

                            </td>

                        </tr>

                    @endif


                    {{-- Received by --}}
                    <tr>

                        <th>
                            Received By
                        </th>

                        <td>

                            {{ $feePayment->receiver?->name ?? 'System' }}

                        </td>

                    </tr>

                </table>


                {{-- =========================================
                     EXISTING WALLET APPLICATION NOTICE
                     ========================================== --}}
                @if($walletApplied > 0)

                    <div class="credit-notice">

                        <i class="bi bi-wallet2"></i>

                        <strong>
                            Existing Student Wallet Credit Applied:
                        </strong>

                        KES
                        {{ number_format(
                            $walletApplied,
                            2
                        ) }}

                        was automatically applied to

                        {{ $feePayment->term?->name ?? 'this term' }}.

                        The wallet credit was

                        <strong>
                            KES
                            {{ number_format(
                                $walletBefore,
                                2
                            ) }}
                        </strong>

                        before this transaction and is now

                        <strong>
                            KES
                            {{ number_format(
                                $remainingWallet,
                                2
                            ) }}
                        </strong>.

                    </div>

                @endif


                {{-- =========================================
                     NEW WALLET CREDIT NOTICE
                     ========================================== --}}
                @if($newWalletCredit > 0)

                    <div class="credit-notice">

                        <i class="bi bi-wallet2"></i>

                        <strong>
                            New Student Wallet Credit:
                        </strong>

                        KES
                        {{ number_format(
                            $newWalletCredit,
                            2
                        ) }}

                        from this cash payment has been added
                        to the student's wallet.

                    </div>

                @endif

            </div>


            {{-- =============================================
                 FEE SUMMARY
                 ============================================== --}}
            <div class="section">

                <div class="section-title">

                    <i class="bi bi-wallet2"></i>

                    Fee Summary

                </div>


                <div class="summary-grid">


                    {{-- Net fee --}}
                    <div class="summary-box">

                        <div class="summary-label">
                            Net Fee
                        </div>

                        <div class="summary-value">

                            KES
                            {{ number_format(
                                $netFee,
                                2
                            ) }}

                        </div>

                    </div>


                    {{-- Fee paid to date --}}
                    <div class="summary-box success">

                        <div class="summary-label">
                            Fee Paid to Date
                        </div>

                        <div class="summary-value">

                            KES
                            {{ number_format(
                                $studentFee?->paid_amount ?? 0,
                                2
                            ) }}

                        </div>

                    </div>


                    {{-- Outstanding --}}
                    <div class="summary-box">

                        <div class="summary-label">
                            Outstanding
                        </div>

                        <div class="summary-value">

                            KES
                            {{ number_format(
                                $outstanding,
                                2
                            ) }}

                        </div>

                    </div>


                    {{-- Wallet --}}
                    <div class="summary-box credit">

                        <div class="summary-label">
                            Wallet Credit Remaining
                        </div>

                        <div class="summary-value">

                            KES
                            {{ number_format(
                                $remainingWallet,
                                2
                            ) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- =============================================
                 REMARKS
                 ============================================== --}}
            @if($feePayment->remarks)

                <div class="section">

                    <div class="section-title">

                        <i class="bi bi-chat-left-text"></i>

                        Remarks

                    </div>


                    <div class="remarks-box">

                        {{ $feePayment->remarks }}

                    </div>

                </div>

            @endif


            {{-- =============================================
                 SIGNATURES
                 ============================================== --}}
            <div class="signature-area">

                <div class="signature-box">

                    <div class="signature-line">
                        Parent / Guardian Signature
                    </div>

                </div>


                <div class="signature-box">

                    <div class="signature-line">
                        Authorized School Officer
                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             RECEIPT FOOTER
             ================================================== --}}
        <div class="receipt-footer">

            <div>
                <strong>
                    Thank you for your payment.
                </strong>
            </div>

            <div>
                This is an official computer-generated school fee receipt.
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     REVERSE PAYMENT MODAL
     ========================================================== --}}
@if(!$feePayment->is_reversed)

    <div
        class="modal fade no-print"
        id="reversePaymentModal"
        tabindex="-1"
        aria-labelledby="reversePaymentModalLabel"
        aria-hidden="true"
    >

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="reversePaymentModalLabel"
                    >
                        Reverse Payment
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>

                <form
                    method="POST"
                    action="{{ route(
                        'fee-payments.reverse',
                        $feePayment
                    ) }}"
                >

                    @csrf

                    <div class="modal-body">

                        <div class="alert alert-warning">

                            <i class="bi bi-exclamation-triangle"></i>

                            You are about to reverse receipt

                            <strong>
                                {{ $feePayment->receipt_no }}
                            </strong>.

                            This action affects the student's fee
                            balance and wallet.

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Reversal Reason
                            </label>

                            <textarea
                                name="reversal_reason"
                                class="form-control"
                                rows="4"
                                required
                                placeholder="Enter the reason for reversing this payment..."
                            ></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="btn btn-danger"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reverse Payment
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

@endsection
