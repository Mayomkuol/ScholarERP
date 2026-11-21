@php
    $payment = $payment ?? $feePayment ?? null;
@endphp

<div class="row g-3">

    {{-- Student --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Student
        </label>

        <select
            name="student_id"
            id="student_id"
            class="form-select"
            required
        >

            <option value="">
                -- Select Student --
            </option>

            @foreach($students as $student)

                <option
                    value="{{ $student->id }}"
                    {{ old(
                        'student_id',
                        $payment->student_id ?? ''
                    ) == $student->id ? 'selected' : '' }}
                >
                    {{ $student->first_name }}
                    {{ $student->last_name }}
                    @if($student->admission_no)
                        — {{ $student->admission_no }}
                    @endif
                </option>

            @endforeach

        </select>

    </div>


    {{-- Student Fee --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Student Fee
        </label>

        <select
            name="student_fee_id"
            id="student_fee_id"
            class="form-select"
            required
        >

            <option value="">
                -- Select Student Fee --
            </option>

            @foreach($studentFees as $studentFee)

                @php
                    $balance = max(
                        0,
                        (float) $studentFee->net_fee -
                        (float) $studentFee->paid_amount
                    );

                    $walletBalance =
                        isset($wallets[$studentFee->student_id])
                            ? (float) $wallets[$studentFee->student_id]->balance
                            : 0;

                    $walletApplied = min(
                        $walletBalance,
                        $balance
                    );

                    $cashRequired = max(
                        0,
                        $balance - $walletApplied
                    );
                @endphp

                <option
                    value="{{ $studentFee->id }}"
                    data-student="{{ $studentFee->student_id }}"
                    data-year="{{ $studentFee->academic_year_id }}"
                    data-term="{{ $studentFee->term_id }}"
                    data-net-fee="{{ $studentFee->net_fee }}"
                    data-paid="{{ $studentFee->paid_amount }}"
                    data-balance="{{ $balance }}"
                    data-wallet="{{ $walletBalance }}"
                    data-wallet-applied="{{ $walletApplied }}"
                    data-cash-required="{{ $cashRequired }}"
                    {{ old(
                        'student_fee_id',
                        $payment->student_fee_id ?? ''
                    ) == $studentFee->id ? 'selected' : '' }}
                >

                    {{ $studentFee->academicYear->name ?? 'Academic Year' }}
                    —
                    {{ $studentFee->term->name ?? 'Term' }}
                    —
                    KES {{ number_format($balance, 2) }}

                    @if($walletApplied > 0)
                        — Wallet covers KES
                        {{ number_format($walletApplied, 2) }}
                    @endif

                </option>

            @endforeach

        </select>

        <small class="text-muted">
            The student's existing wallet credit is automatically applied first.
        </small>

    </div>


    {{-- Academic Year --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Academic Year
        </label>

        <select
            name="academic_year_id"
            id="academic_year_id"
            class="form-select"
            required
        >

            <option value="">
                -- Select Academic Year --
            </option>

            @foreach($academicYears as $academicYear)

                <option
                    value="{{ $academicYear->id }}"
                    {{ old(
                        'academic_year_id',
                        $payment->academic_year_id ?? ''
                    ) == $academicYear->id ? 'selected' : '' }}
                >
                    {{ $academicYear->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Term --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Term
        </label>

        <select
            name="term_id"
            id="term_id"
            class="form-select"
            required
        >

            <option value="">
                -- Select Term --
            </option>

            @foreach($terms as $term)

                <option
                    value="{{ $term->id }}"
                    {{ old(
                        'term_id',
                        $payment->term_id ?? ''
                    ) == $term->id ? 'selected' : '' }}
                >
                    {{ $term->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Wallet Information --}}
    <div class="col-12">

        <div
            id="walletAlert"
            class="alert alert-info d-none"
        >

            <div class="d-flex align-items-start">

                <i class="bi bi-wallet2 fs-4 me-3"></i>

                <div>

                    <strong>
                        Student Wallet Credit
                    </strong>

                    <div id="walletMessage">
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Amount Received --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            New Cash Received
        </label>

        <div class="input-group">

            <span class="input-group-text">
                KES
            </span>

            <input
                type="number"
                name="amount_paid"
                id="amount_paid"
                class="form-control"
                min="0"
                step="0.01"
                value="{{ old(
                    'amount_paid',
                    $payment->amount_received ?? ''
                ) }}"
                required
            >

        </div>

        <small class="text-muted">
            Enter only NEW cash physically received from the student.
            Existing wallet credit is applied automatically.
        </small>

    </div>


    {{-- Payment Date --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Payment Date
        </label>

        <input
            type="date"
            name="payment_date"
            class="form-control"
            value="{{ old(
                'payment_date',
                isset($payment) && $payment->payment_date
                    ? $payment->payment_date->format('Y-m-d')
                    : now()->format('Y-m-d')
            ) }}"
            required
        >

    </div>


    {{-- Payment Method --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Payment Method
        </label>

        <select
            name="payment_method"
            id="payment_method"
            class="form-select"
            required
        >

            <option value="">
                -- Select Payment Method --
            </option>

            @foreach([
                'Cash',
                'M-Pesa',
                'Bank',
                'Cheque',
               'Student wallet',
                'Other'
            ] as $method)

                <option
                    value="{{ $method }}"
                    {{ old(
                        'payment_method',
                        $payment->payment_method ?? ''
                    ) == $method ? 'selected' : '' }}
                >
                    {{ $method }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Reference --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Reference Number
        </label>

        <input
            type="text"
            name="reference_no"
            class="form-control"
            value="{{ old(
                'reference_no',
                $payment->reference_no ?? ''
            ) }}"
            placeholder="e.g. M-Pesa transaction number"
        >

    </div>


    {{-- Remarks --}}
    <div class="col-12">

        <label class="form-label fw-semibold">
            Remarks
        </label>

        <textarea
            name="remarks"
            class="form-control"
            rows="3"
            placeholder="Optional payment remarks..."
        >{{ old(
            'remarks',
            $payment->remarks ?? ''
        ) }}</textarea>

    </div>

</div>


<hr class="my-4">


{{-- PAYMENT PREVIEW --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-light">

        <h5 class="mb-0">
            <i class="bi bi-calculator me-2"></i>
            Payment Calculation
        </h5>

    </div>


    <div class="card-body">

        <div class="row g-3">

            {{-- Outstanding --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        Outstanding Fee Before Payment
                    </small>

                    <strong
                        id="previewOutstanding"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>


            {{-- Wallet Before --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        Student Wallet Before Payment
                    </small>

                    <strong
                        id="previewWalletBefore"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>


            {{-- Wallet Applied --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        Existing Wallet Credit Applied
                    </small>

                    <strong
                        id="previewWalletApplied"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>


            {{-- Cash Required --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        Cash Required After Wallet
                    </small>

                    <strong
                        id="previewCashRequired"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>


            {{-- New Cash --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        New Cash Received
                    </small>

                    <strong
                        id="previewCashReceived"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>


            {{-- Cash Applied --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        New Cash Applied to Fee
                    </small>

                    <strong
                        id="previewCashApplied"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>


            {{-- New Wallet Credit --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        New Wallet Credit
                    </small>

                    <strong
                        id="previewNewCredit"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>


            {{-- Final Fee --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        Final Fee Balance
                    </small>

                    <strong
                        id="previewFinalBalance"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>


            {{-- Final Wallet --}}
            <div class="col-md-4">

                <div class="border rounded p-3 h-100">

                    <small class="text-muted d-block">
                        Remaining Student Wallet
                    </small>

                    <strong
                        id="previewFinalWallet"
                        class="fs-5"
                    >
                        KES 0.00
                    </strong>

                </div>

            </div>

        </div>


        {{-- Automatic Wallet Notice --}}
        <div
            id="walletCoverageNotice"
            class="alert alert-success mt-4 d-none"
        >

            <i class="bi bi-check-circle-fill me-2"></i>

            <span id="walletCoverageText"></span>

        </div>


        {{-- Cash Required Notice --}}
        <div
            id="cashRequiredNotice"
            class="alert alert-warning mt-4 d-none"
        >

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            <span id="cashRequiredText"></span>

        </div>


        {{-- New Credit Notice --}}
        <div
            id="newCreditNotice"
            class="alert alert-info mt-4 d-none"
        >

            <i class="bi bi-wallet2 me-2"></i>

            <span id="newCreditText"></span>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const studentSelect =
        document.getElementById('student_id');

    const feeSelect =
        document.getElementById('student_fee_id');

    const yearSelect =
        document.getElementById('academic_year_id');

    const termSelect =
        document.getElementById('term_id');

    const amountInput =
        document.getElementById('amount_paid');

    const paymentMethod =
        document.getElementById('payment_method');


    function money(value) {

        value = parseFloat(value || 0);

        return 'KES ' + value.toLocaleString(
            'en-KE',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }


    function getSelectedFee() {

        return feeSelect.options[
            feeSelect.selectedIndex
        ];
    }


    function calculate() {

        const option = getSelectedFee();


        if (
            !option ||
            !option.value
        ) {

            resetPreview();

            return;
        }


        const studentId =
            option.dataset.student || '';

        const yearId =
            option.dataset.year || '';

        const termId =
            option.dataset.term || '';


        /*
         * Automatically synchronize student,
         * academic year and term.
         */
        if (studentId) {
            studentSelect.value = studentId;
        }

        if (yearId) {
            yearSelect.value = yearId;
        }

        if (termId) {
            termSelect.value = termId;
        }


        const outstanding =
            parseFloat(
                option.dataset.balance || 0
            );


        const walletBefore =
            parseFloat(
                option.dataset.wallet || 0
            );


        /*
         * Existing wallet is ALWAYS applied first.
         */
        const walletApplied =
            Math.min(
                walletBefore,
                outstanding
            );


        const cashRequired =
            Math.max(
                0,
                outstanding -
                walletApplied
            );


        /*
         * The form should automatically ask for
         * ONLY the cash still required.
         *
         * Example:
         *
         * Fee       = 28,000
         * Wallet    = 47,000
         * Cash      = 0
         */
        amountInput.value =
            cashRequired > 0
                ? cashRequired.toFixed(2)
                : '0.00';


        /*
         * Calculate using the amount now displayed.
         */
        const cashReceived =
            parseFloat(
                amountInput.value || 0
            );


        const cashApplied =
            Math.min(
                cashReceived,
                cashRequired
            );


        const newCredit =
            Math.max(
                0,
                cashReceived -
                cashApplied
            );


        const finalBalance =
            Math.max(
                0,
                outstanding -
                walletApplied -
                cashApplied
            );


        const finalWallet =
            Math.max(
                0,
                walletBefore -
                walletApplied +
                newCredit
            );


        /*
         * Update preview.
         */
        document.getElementById(
            'previewOutstanding'
        ).textContent =
            money(outstanding);


        document.getElementById(
            'previewWalletBefore'
        ).textContent =
            money(walletBefore);


        document.getElementById(
            'previewWalletApplied'
        ).textContent =
            money(walletApplied);


        document.getElementById(
            'previewCashRequired'
        ).textContent =
            money(cashRequired);


        document.getElementById(
            'previewCashReceived'
        ).textContent =
            money(cashReceived);


        document.getElementById(
            'previewCashApplied'
        ).textContent =
            money(cashApplied);


        document.getElementById(
            'previewNewCredit'
        ).textContent =
            money(newCredit);


        document.getElementById(
            'previewFinalBalance'
        ).textContent =
            money(finalBalance);


        document.getElementById(
            'previewFinalWallet'
        ).textContent =
            money(finalWallet);


        /*
         * Wallet alert.
         */
        const walletAlert =
            document.getElementById(
                'walletAlert'
            );

        const walletMessage =
            document.getElementById(
                'walletMessage'
            );


        if (walletBefore > 0) {

            walletAlert.classList.remove(
                'd-none'
            );


            walletMessage.innerHTML =
                'Available wallet credit: <strong>'
                + money(walletBefore)
                + '</strong>. '
                + 'The system will automatically apply '
                + '<strong>'
                + money(walletApplied)
                + '</strong> '
                + 'to this fee.';
        }
        else {

            walletAlert.classList.add(
                'd-none'
            );
        }


        /*
         * Full wallet coverage.
         */
        const walletCoverageNotice =
            document.getElementById(
                'walletCoverageNotice'
            );


        const walletCoverageText =
            document.getElementById(
                'walletCoverageText'
            );


        if (
            walletApplied >= outstanding &&
            outstanding > 0
        ) {

            walletCoverageNotice.classList.remove(
                'd-none'
            );


            walletCoverageText.innerHTML =
                'The existing wallet credit of '
                + '<strong>'
                + money(walletBefore)
                + '</strong> '
                + 'fully covers this fee. '
                + 'No new cash is required. '
                + 'The remaining wallet balance will be '
                + '<strong>'
                + money(finalWallet)
                + '</strong>.';


            /*
             * Wallet-only payment does not need a
             * physical cash payment method.
             */
            if (paymentMethod) {
                paymentMethod.value =
                    'Other';
            }

        }
        else {

            walletCoverageNotice.classList.add(
                'd-none'
            );
        }


        /*
         * Cash requirement.
         */
        const cashNotice =
            document.getElementById(
                'cashRequiredNotice'
            );


        const cashText =
            document.getElementById(
                'cashRequiredText'
            );


        if (cashRequired > 0) {

            cashNotice.classList.remove(
                'd-none'
            );


            cashText.innerHTML =
                'After applying wallet credit, '
                + 'the remaining amount required is '
                + '<strong>'
                + money(cashRequired)
                + '</strong>.';

        }
        else {

            cashNotice.classList.add(
                'd-none'
            );
        }


        /*
         * New wallet credit.
         */
        const newCreditNotice =
            document.getElementById(
                'newCreditNotice'
            );


        const newCreditText =
            document.getElementById(
                'newCreditText'
            );


        if (newCredit > 0) {

            newCreditNotice.classList.remove(
                'd-none'
            );


            newCreditText.innerHTML =
                'This payment exceeds the remaining fee by '
                + '<strong>'
                + money(newCredit)
                + '</strong>. '
                + 'The excess will be added to the student wallet.';
        }
        else {

            newCreditNotice.classList.add(
                'd-none'
            );
        }
    }


    function resetPreview() {

        const fields = [
            'previewOutstanding',
            'previewWalletBefore',
            'previewWalletApplied',
            'previewCashRequired',
            'previewCashReceived',
            'previewCashApplied',
            'previewNewCredit',
            'previewFinalBalance',
            'previewFinalWallet'
        ];


        fields.forEach(function (id) {

            const element =
                document.getElementById(id);

            if (element) {
                element.textContent =
                    'KES 0.00';
            }
        });


        document.getElementById(
            'walletAlert'
        ).classList.add('d-none');


        document.getElementById(
            'walletCoverageNotice'
        ).classList.add('d-none');


        document.getElementById(
            'cashRequiredNotice'
        ).classList.add('d-none');


        document.getElementById(
            'newCreditNotice'
        ).classList.add('d-none');
    }


    /*
     * When student changes, only show their fees.
     */
    studentSelect.addEventListener(
        'change',
        function () {

            const studentId =
                this.value;


            Array.from(
                feeSelect.options
            ).forEach(function (option, index) {

                if (index === 0) {
                    option.hidden = false;
                    return;
                }


                const optionStudent =
                    option.dataset.student;


                option.hidden =
                    studentId &&
                    optionStudent !== studentId;
            });


            feeSelect.value = '';

            amountInput.value = '';

            resetPreview();
        }
    );


    /*
     * Selecting a fee recalculates everything.
     */
    feeSelect.addEventListener(
        'change',
        calculate
    );


    /*
     * If the user manually changes the cash amount,
     * recalculate only the cash/new-credit section.
     */
    amountInput.addEventListener(
        'input',
        function () {

            const option =
                getSelectedFee();


            if (
                !option ||
                !option.value
            ) {
                return;
            }


            const outstanding =
                parseFloat(
                    option.dataset.balance || 0
                );


            const walletBefore =
                parseFloat(
                    option.dataset.wallet || 0
                );


            const walletApplied =
                Math.min(
                    walletBefore,
                    outstanding
                );


            const cashRequired =
                Math.max(
                    0,
                    outstanding -
                    walletApplied
                );


            const cashReceived =
                parseFloat(
                    amountInput.value || 0
                );


            const cashApplied =
                Math.min(
                    cashReceived,
                    cashRequired
                );


            const newCredit =
                Math.max(
                    0,
                    cashReceived -
                    cashApplied
                );


            const finalBalance =
                Math.max(
                    0,
                    outstanding -
                    walletApplied -
                    cashApplied
                );


            const finalWallet =
                Math.max(
                    0,
                    walletBefore -
                    walletApplied +
                    newCredit
                );


            document.getElementById(
                'previewCashReceived'
            ).textContent =
                money(cashReceived);


            document.getElementById(
                'previewCashApplied'
            ).textContent =
                money(cashApplied);


            document.getElementById(
                'previewNewCredit'
            ).textContent =
                money(newCredit);


            document.getElementById(
                'previewFinalBalance'
            ).textContent =
                money(finalBalance);


            document.getElementById(
                'previewFinalWallet'
            ).textContent =
                money(finalWallet);


            const newCreditNotice =
                document.getElementById(
                    'newCreditNotice'
                );


            const newCreditText =
                document.getElementById(
                    'newCreditText'
                );


            if (newCredit > 0) {

                newCreditNotice.classList.remove(
                    'd-none'
                );


                newCreditText.innerHTML =
                    'This payment exceeds the remaining fee by '
                    + '<strong>'
                    + money(newCredit)
                    + '</strong>. '
                    + 'The excess will be added to the student wallet.';

            }
            else {

                newCreditNotice.classList.add(
                    'd-none'
                );
            }
        }
    );


    /*
     * Initial calculation when editing/reloading.
     */
    if (feeSelect.value) {
        calculate();
    }

});
</script>