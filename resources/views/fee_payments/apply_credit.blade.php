@extends('layouts.app')

@section('title', 'Apply Student Fee Credit')

@section('content')

<div class="container-fluid">

```
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">
            <i class="bi bi-wallet2 text-primary"></i>
            Apply Student Fee Credit
        </h4>

        <p class="text-muted mb-0">
            Apply available student credit toward an outstanding fee.
        </p>
    </div>

    <a
        href="{{ route('fee-payments.index') }}"
        class="btn btn-secondary"
    >
        <i class="bi bi-arrow-left"></i>
        Back to Payments
    </a>

</div>


{{-- Success Message --}}
@if(session('success'))

    <div class="alert alert-success">

        <i class="bi bi-check-circle-fill"></i>

        {{ session('success') }}

    </div>

@endif


{{-- Validation Errors --}}
@if($errors->any())

    <div class="alert alert-danger">

        <strong>
            Please correct the following:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<div class="row">

    {{-- ================================
         STUDENT INFORMATION
    ================================= --}}
    <div class="col-lg-5">

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    <i class="bi bi-person"></i>
                    Student Information
                </h5>

            </div>

            <div class="card-body">

                <table class="table table-bordered mb-0">

                    <tr>
                        <th width="40%">
                            Student
                        </th>

                        <td>
                            {{ $student->first_name }}
                            {{ $student->middle_name }}
                            {{ $student->last_name }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            Admission No.
                        </th>

                        <td>
                            {{ $student->admission_no }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            Available Credit
                        </th>

                        <td>

                            <span class="fw-bold fs-5 text-primary">

                                KES
                                {{ number_format(
                                    $wallet->balance,
                                    2
                                ) }}

                            </span>

                        </td>
                    </tr>

                </table>

            </div>

        </div>


        {{-- ================================
             SELECTED FEE
        ================================= --}}
        <div class="card shadow-sm border-0">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">
                    <i class="bi bi-receipt"></i>
                    Selected Fee
                </h5>

            </div>

            <div class="card-body">

                <table class="table table-bordered mb-0">

                    <tr>

                        <th width="40%">
                            Academic Year
                        </th>

                        <td>
                            {{ $studentFee->academicYear->name }}
                        </td>

                    </tr>

                    <tr>

                        <th>
                            Term
                        </th>

                        <td>
                            {{ $studentFee->term->name }}
                        </td>

                    </tr>

                    <tr>

                        <th>
                            Net Fee
                        </th>

                        <td>
                            KES
                            {{ number_format(
                                $studentFee->net_fee,
                                2
                            ) }}
                        </td>

                    </tr>

                    <tr>

                        <th>
                            Already Paid
                        </th>

                        <td>
                            KES
                            {{ number_format(
                                $studentFee->paid_amount,
                                2
                            ) }}
                        </td>

                    </tr>

                    <tr>

                        <th>
                            Outstanding
                        </th>

                        <td class="fw-bold text-danger">

                            KES
                            {{ number_format(
                                $studentFee->balance,
                                2
                            ) }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>


    {{-- ================================
         APPLY CREDIT
    ================================= --}}
    <div class="col-lg-7">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    <i class="bi bi-wallet2"></i>
                    Apply Available Credit
                </h5>

            </div>

            <div class="card-body">

                @if((float) $wallet->balance <= 0)

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle"></i>

                        This student currently has no available fee credit.

                    </div>

                @elseif((float) $studentFee->balance <= 0)

                    <div class="alert alert-success">

                        <i class="bi bi-check-circle"></i>

                        This fee is already fully paid.

                    </div>

                @else

                    <div class="alert alert-info">

                        <strong>
                            Available Student Credit:
                        </strong>

                        KES
                        {{ number_format(
                            $wallet->balance,
                            2
                        ) }}

                        <br>

                        <strong>
                            Outstanding Fee:
                        </strong>

                        KES
                        {{ number_format(
                            $studentFee->balance,
                            2
                        ) }}

                    </div>


                    <form
                        action="{{ route(
                            'fee-payments.apply-credit',
                            $studentFee
                        ) }}"
                        method="POST"
                    >

                        @csrf


                        <div class="mb-4">

                            <label
                                for="amount"
                                class="form-label fw-bold"
                            >
                                Credit Amount to Apply
                            </label>

                            <div class="input-group input-group-lg">

                                <span class="input-group-text">
                                    KES
                                </span>

                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    class="form-control"
                                    min="0.01"
                                    max="{{ min(
                                        (float) $wallet->balance,
                                        (float) $studentFee->balance
                                    ) }}"
                                    step="0.01"
                                    value="{{ old(
                                        'amount',
                                        min(
                                            (float) $wallet->balance,
                                            (float) $studentFee->balance
                                        )
                                    ) }}"
                                    required
                                >

                            </div>

                            <small class="text-muted">

                                Maximum applicable credit:

                                <strong>
                                    KES
                                    {{ number_format(
                                        min(
                                            (float) $wallet->balance,
                                            (float) $studentFee->balance
                                        ),
                                        2
                                    ) }}
                                </strong>

                            </small>

                        </div>


                        {{-- Transaction Preview --}}
                        <div class="card bg-light border mb-4">

                            <div class="card-body">

                                <h6 class="fw-bold">
                                    Transaction Preview
                                </h6>

                                <div class="d-flex justify-content-between">

                                    <span>
                                        Current Outstanding
                                    </span>

                                    <strong>
                                        KES
                                        {{ number_format(
                                            $studentFee->balance,
                                            2
                                        ) }}
                                    </strong>

                                </div>

                                <div class="d-flex justify-content-between">

                                    <span>
                                        Credit to Apply
                                    </span>

                                    <strong class="text-primary">
                                        KES
                                        <span id="preview-credit">
                                            {{ number_format(
                                                min(
                                                    (float) $wallet->balance,
                                                    (float) $studentFee->balance
                                                ),
                                                2
                                            ) }}
                                        </span>
                                    </strong>

                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">

                                    <span class="fw-bold">
                                        New Outstanding Balance
                                    </span>

                                    <strong class="text-danger">

                                        KES
                                        <span id="preview-balance">
                                            {{ number_format(
                                                max(
                                                    0,
                                                    (float) $studentFee->balance
                                                    -
                                                    min(
                                                        (float) $wallet->balance,
                                                        (float) $studentFee->balance
                                                    )
                                                ),
                                                2
                                            ) }}
                                        </span>

                                    </strong>

                                </div>

                            </div>

                        </div>


                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'fee-payments.index'
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Apply this student credit to the selected fee?'
                                )"
                            >

                                <i class="bi bi-wallet2"></i>

                                Apply Student Credit

                            </button>

                        </div>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>
```

</div>

{{-- ================================
LIVE PREVIEW
================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const amountInput = document.getElementById('amount');

    const previewCredit =
        document.getElementById('preview-credit');

    const previewBalance =
        document.getElementById('preview-balance');

    if (!amountInput) {
        return;
    }

    const outstanding =
        {{ (float) $studentFee->balance }};

    const walletBalance =
        {{ (float) $wallet->balance }};

    function updatePreview() {

        let amount =
            parseFloat(amountInput.value) || 0;

        amount =
            Math.min(
                amount,
                walletBalance,
                outstanding
            );

        const newBalance =
            Math.max(
                0,
                outstanding - amount
            );

        previewCredit.textContent =
            amount.toLocaleString(
                'en-US',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

        previewBalance.textContent =
            newBalance.toLocaleString(
                'en-US',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
    }

    amountInput.addEventListener(
        'input',
        updatePreview
    );

    updatePreview();

});

</script>

@endsection
