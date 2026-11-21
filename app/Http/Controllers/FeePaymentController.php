<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\FeePayment;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\StudentWallet;
use App\Models\StudentWalletTransaction;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FeePaymentController extends Controller
{
    /**
     * Display all fee payments.
     */
    public function index()
    {
        $payments = FeePayment::with([
            'student',
            'studentFee',
            'academicYear',
            'term',
            'receiver',
        ])
            ->latest()
            ->paginate(20);

        $school = School::first();

        return view('fee_payments.index', compact(
            'payments',
            'school'
        ));
    }


    /**
     * Show payment form.
     *
     * Only outstanding student fees are shown.
     */
    public function create()
    {
        $students = Student::orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        /*
         * Only show fees that still have an outstanding balance.
         */
        $studentFees = StudentFee::with([
            'student',
            'academicYear',
            'term',
        ])
            ->whereRaw(
                'net_fee > COALESCE(paid_amount, 0)'
            )
            ->orderBy('academic_year_id')
            ->orderBy('term_id')
            ->get();

        $wallets = StudentWallet::whereIn(
            'student_id',
            $students->pluck('id')
        )
            ->get()
            ->keyBy('student_id');

        $academicYears = AcademicYear::orderByDesc('id')
            ->get();

        $terms = Term::orderBy('id')
            ->get();

        return view('fee_payments.create', compact(
            'students',
            'studentFees',
            'wallets',
            'academicYears',
            'terms'
        ));
    }


    /**
     * Store a new fee payment.
     *
     * Financial rules:
     *
     * 1. Existing wallet credit is applied first.
     * 2. NEW physical cash is applied second.
     * 3. Excess NEW physical cash becomes wallet credit.
     *
     * IMPORTANT:
     * academic_year_id and term_id are taken from the selected
     * StudentFee and are NOT trusted from the browser.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
            ],

            'student_fee_id' => [
                'required',
                'exists:student_fees,id',
            ],

            /*
             * This means NEW physical cash only.
             */
            'amount_paid' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:100',
            ],

            'reference_no' => [
                'nullable',
                'string',
                'max:150',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $paymentId = null;

        DB::transaction(function () use (
            $validated,
            &$paymentId
        ) {

            /*
             * ---------------------------------------------------------
             * 1. LOCK THE STUDENT FEE
             * ---------------------------------------------------------
             */
            $studentFee = StudentFee::with([
                'student',
                'academicYear',
                'term',
            ])
                ->lockForUpdate()
                ->findOrFail(
                    $validated['student_fee_id']
                );


            /*
             * ---------------------------------------------------------
             * 2. VERIFY STUDENT
             * ---------------------------------------------------------
             */
            if (
                (int) $studentFee->student_id !==
                (int) $validated['student_id']
            ) {
                abort(
                    422,
                    'The selected fee does not belong to this student.'
                );
            }


            /*
             * ---------------------------------------------------------
             * 3. GET YEAR + TERM DIRECTLY FROM STUDENT FEE
             * ---------------------------------------------------------
             *
             * Never trust separate year/term values from the browser.
             */
            $academicYearId =
                $studentFee->academic_year_id;

            $termId =
                $studentFee->term_id;


            /*
             * ---------------------------------------------------------
             * 4. CALCULATE OUTSTANDING FEE
             * ---------------------------------------------------------
             */
            $netFee = round(
                (float) $studentFee->net_fee,
                2
            );

            $paidAmount = round(
                (float) $studentFee->paid_amount,
                2
            );

            $feeBalanceBefore = round(
                max(
                    0,
                    $netFee - $paidAmount
                ),
                2
            );


            /*
             * A fully paid fee must never receive another payment.
             */
            if ($feeBalanceBefore <= 0) {
                abort(
                    422,
                    'This student fee is already fully paid.'
                );
            }


            /*
             * ---------------------------------------------------------
             * 5. NEW CASH RECEIVED
             * ---------------------------------------------------------
             *
             * This is actual physical/new money received.
             */
            $amountReceived = round(
                (float) $validated['amount_paid'],
                2
            );


            /*
             * ---------------------------------------------------------
             * 6. GET / CREATE STUDENT WALLET
             * ---------------------------------------------------------
             */
            $wallet = StudentWallet::firstOrCreate(
                [
                    'student_id' =>
                        $studentFee->student_id,
                ],
                [
                    'balance' => 0,
                ]
            );


            /*
             * Lock wallet.
             */
            $wallet = StudentWallet::lockForUpdate()
                ->findOrFail($wallet->id);


            $walletBefore = round(
                max(
                    0,
                    (float) $wallet->balance
                ),
                2
            );


            /*
             * ---------------------------------------------------------
             * 7. APPLY EXISTING WALLET CREDIT FIRST
             * ---------------------------------------------------------
             */
            $walletApplied = round(
                min(
                    $walletBefore,
                    $feeBalanceBefore
                ),
                2
            );


            if ($walletApplied > 0) {

                /*
                 * Apply wallet to fee.
                 */
                $studentFee->paid_amount = round(
                    (float) $studentFee->paid_amount
                    + $walletApplied,
                    2
                );


                /*
                 * Remove wallet credit.
                 */
                $wallet->balance = round(
                    (float) $wallet->balance
                    - $walletApplied,
                    2
                );


                $wallet->save();


                /*
                 * Create wallet debit.
                 *
                 * Payment ID is temporarily null because the
                 * FeePayment record has not yet been created.
                 */
                $walletTransactionData = [
                    'student_wallet_id' =>
                        $wallet->id,

                    'amount' =>
                        $walletApplied,

                    'type' =>
                        'Debit',

                    'description' =>
                        'Student wallet credit automatically applied to '
                        . $studentFee->academicYear->name
                        . ' - '
                        . $studentFee->term->name
                        . '.',

                    'fee_payment_id' =>
                        null,

                    'created_by' =>
                        auth()->id(),
                ];


                if (
                    Schema::hasColumn(
                        'student_wallet_transactions',
                        'student_fee_id'
                    )
                ) {
                    $walletTransactionData['student_fee_id'] =
                        $studentFee->id;
                }


                StudentWalletTransaction::create(
                    $walletTransactionData
                );
            }


            /*
             * ---------------------------------------------------------
             * 8. CALCULATE FEE REMAINING AFTER WALLET
             * ---------------------------------------------------------
             */
            $remainingFeeAfterWallet = round(
                max(
                    0,
                    $netFee
                    - (float) $studentFee->paid_amount
                ),
                2
            );


            /*
             * ---------------------------------------------------------
             * 9. APPLY NEW CASH
             * ---------------------------------------------------------
             */
            $cashAllocated = round(
                min(
                    $amountReceived,
                    $remainingFeeAfterWallet
                ),
                2
            );


            /*
             * Excess NEW cash becomes NEW wallet credit.
             */
            $newOverpayment = round(
                max(
                    0,
                    $amountReceived
                    - $cashAllocated
                ),
                2
            );


            /*
             * Apply cash to fee.
             */
            if ($cashAllocated > 0) {

                $studentFee->paid_amount = round(
                    (float) $studentFee->paid_amount
                    + $cashAllocated,
                    2
                );
            }


            /*
             * Update fee status.
             */
            $this->updateFeeStatus($studentFee);

            $studentFee->save();


            /*
             * ---------------------------------------------------------
             * 10. CREATE PAYMENT RECORD
             * ---------------------------------------------------------
             *
             * A payment record is created when either:
             *
             * - new cash was received
             * - wallet credit was applied
             */
            $shouldCreatePayment =
                $amountReceived > 0 ||
                $walletApplied > 0;


            if ($shouldCreatePayment) {

                $receiptNo =
                    $this->generateReceiptNo();


                /*
                 * Wallet-only transaction gets its own payment method.
                 */
                $paymentMethod =
                    $validated['payment_method'];


                if (
                    $amountReceived <= 0 &&
                    $walletApplied > 0
                ) {
                    $paymentMethod =
                        'Wallet Credit';
                }


                $payment = FeePayment::create([

                    'student_id' =>
                        $studentFee->student_id,

                    'student_fee_id' =>
                        $studentFee->id,

                    /*
                     * IMPORTANT:
                     *
                     * These come directly from StudentFee.
                     */
                    'academic_year_id' =>
                        $academicYearId,

                    'term_id' =>
                        $termId,

                    /*
                     * NEW CASH APPLIED.
                     */
                    'amount_paid' =>
                        $cashAllocated,

                    /*
                     * ACTUAL NEW CASH RECEIVED.
                     */
                    'amount_received' =>
                        $amountReceived,

                    /*
                     * CASH ALLOCATED TO FEE.
                     */
                    'amount_allocated' =>
                        $cashAllocated,

                    /*
                     * EXISTING WALLET CREDIT USED.
                     */
                    'wallet_amount_applied' =>
                        $walletApplied,

                    /*
                     * NEW WALLET CREDIT CREATED.
                     */
                    'overpayment_amount' =>
                        $newOverpayment,

                    'payment_date' =>
                        $validated['payment_date'],

                    'payment_method' =>
                        $paymentMethod,

                    'reference_no' =>
                        $validated['reference_no'],

                    'receipt_no' =>
                        $receiptNo,

                    'remarks' =>
                        $validated['remarks'],

                    'received_by' =>
                        auth()->id(),

                    'is_reversed' =>
                        false,
                ]);


                $paymentId =
                    $payment->id;


                /*
                 * -----------------------------------------------------
                 * 11. CREATE NEW WALLET CREDIT
                 * -----------------------------------------------------
                 */
                if ($newOverpayment > 0) {

                    $wallet->balance = round(
                        (float) $wallet->balance
                        + $newOverpayment,
                        2
                    );

                    $wallet->save();


                    StudentWalletTransaction::create([

                        'student_wallet_id' =>
                            $wallet->id,

                        /*
                         * Link credit to the student fee where
                         * possible.
                         */
                        'student_fee_id' =>
                            Schema::hasColumn(
                                'student_wallet_transactions',
                                'student_fee_id'
                            )
                                ? $studentFee->id
                                : null,

                        'amount' =>
                            $newOverpayment,

                        'type' =>
                            'Credit',

                        'description' =>
                            'Excess new fee payment carried forward '
                            . 'as student fee credit.',

                        'fee_payment_id' =>
                            $payment->id,

                        'created_by' =>
                            auth()->id(),
                    ]);
                }


                /*
                 * -----------------------------------------------------
                 * 12. LINK WALLET DEBIT TO PAYMENT
                 * -----------------------------------------------------
                 */
                if ($walletApplied > 0) {

                    $debitQuery =
                        StudentWalletTransaction::where(
                            'student_wallet_id',
                            $wallet->id
                        )
                        ->where(
                            'type',
                            'Debit'
                        )
                        ->where(
                            'amount',
                            $walletApplied
                        )
                        ->where(
                            'fee_payment_id',
                            null
                        )
                        ->latest('id');


                    if (
                        Schema::hasColumn(
                            'student_wallet_transactions',
                            'student_fee_id'
                        )
                    ) {
                        $debitQuery->where(
                            'student_fee_id',
                            $studentFee->id
                        );
                    }


                    $debitTransaction =
                        $debitQuery->first();


                    if ($debitTransaction) {

                        $debitTransaction->fee_payment_id =
                            $payment->id;

                        $debitTransaction->save();
                    }
                }
            }
        });


        /*
         * -------------------------------------------------------------
         * REDIRECT
         * -------------------------------------------------------------
         */
        if ($paymentId) {

            $payment =
                FeePayment::findOrFail(
                    $paymentId
                );


            return redirect()
                ->route(
                    'fee-payments.show',
                    $payment->receipt_no
                )
                ->with(
                    'success',
                    'Fee payment processed successfully.'
                );
        }


        return redirect()
            ->route('fee-payments.index')
            ->with(
                'success',
                'No payment was recorded because no cash or wallet credit was applied.'
            );
    }


    /**
     * Display receipt.
     */
    public function show(string $receiptNo)
    {
        $feePayment = FeePayment::where(
            'receipt_no',
            $receiptNo
        )
            ->with([
                'student',
                'studentFee',
                'academicYear',
                'term',
                'receiver',
                'reverser',
            ])
            ->firstOrFail();


        $school = School::first();


        $wallet = StudentWallet::firstOrCreate(
            [
                'student_id' =>
                    $feePayment->student_id,
            ],
            [
                'balance' => 0,
            ]
        );


        $cashReceived = round(
            (float) (
                $feePayment->amount_received
                ?? $feePayment->amount_paid
                ?? 0
            ),
            2
        );


        $cashApplied = round(
            (float) (
                $feePayment->amount_allocated
                ?? $feePayment->amount_paid
                ?? 0
            ),
            2
        );


        $walletApplied = round(
            (float) (
                $feePayment->wallet_amount_applied
                ?? 0
            ),
            2
        );


        $newCredit = round(
            (float) (
                $feePayment->overpayment_amount
                ?? 0
            ),
            2
        );


        $walletAfter = round(
            max(
                0,
                (float) $wallet->balance
            ),
            2
        );


        $walletBefore = round(
            max(
                0,
                $walletAfter
                - $newCredit
                + $walletApplied
            ),
            2
        );


        $totalApplied = round(
            $cashApplied
            + $walletApplied,
            2
        );


        return view(
            'fee_payments.show',
            compact(
                'feePayment',
                'school',
                'wallet',
                'cashReceived',
                'cashApplied',
                'walletApplied',
                'newCredit',
                'walletBefore',
                'walletAfter',
                'totalApplied'
            )
        );
    }


    /**
     * Edit payment details.
     */
    public function edit(FeePayment $feePayment)
    {
        $students = Student::orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $studentFees = StudentFee::with([
            'student',
            'academicYear',
            'term',
        ])
            ->whereRaw(
                'net_fee > COALESCE(paid_amount, 0)'
            )
            ->orderBy('academic_year_id')
            ->orderBy('term_id')
            ->get();

        $academicYears =
            AcademicYear::orderByDesc('id')->get();

        $terms =
            Term::orderBy('id')->get();

        return view(
            'fee_payments.edit',
            compact(
                'feePayment',
                'students',
                'studentFees',
                'academicYears',
                'terms'
            )
        );
    }


    /**
     * Update payment details only.
     */
    public function update(
        Request $request,
        FeePayment $feePayment
    ) {
        if ($feePayment->is_reversed) {
            return back()->with(
                'error',
                'A reversed payment cannot be edited.'
            );
        }


        $validated = $request->validate([
            'payment_date' =>
                'required|date',

            'payment_method' =>
                'required|string|max:100',

            'reference_no' =>
                'nullable|string|max:150',

            'remarks' =>
                'nullable|string',
        ]);


        $feePayment->update(
            $validated
        );


        return redirect()
            ->route(
                'fee-payments.show',
                $feePayment->receipt_no
            )
            ->with(
                'success',
                'Payment details updated successfully.'
            );
    }


    /**
     * Reverse a payment.
     */
    public function reverse(
        Request $request,
        FeePayment $feePayment
    ) {
        $validated = $request->validate([
            'reversal_reason' =>
                'required|string|max:1000',
        ]);


        if ($feePayment->is_reversed) {
            return back()->with(
                'error',
                'This payment has already been reversed.'
            );
        }


        DB::transaction(function () use (
            $feePayment,
            $validated
        ) {

            $payment =
                FeePayment::lockForUpdate()
                ->findOrFail(
                    $feePayment->id
                );


            $studentFee =
                StudentFee::lockForUpdate()
                ->findOrFail(
                    $payment->student_fee_id
                );


            $wallet =
                StudentWallet::lockForUpdate()
                ->where(
                    'student_id',
                    $payment->student_id
                )
                ->first();


            /*
             * Reverse cash allocated.
             */
            $cashAllocated = round(
                (float) (
                    $payment->amount_allocated
                    ?? $payment->amount_paid
                    ?? 0
                ),
                2
            );


            if ($cashAllocated > 0) {

                $studentFee->paid_amount =
                    max(
                        0,
                        round(
                            (float) $studentFee->paid_amount
                            - $cashAllocated,
                            2
                        )
                    );
            }


            /*
             * Restore wallet credit used.
             */
            $walletApplied = round(
                (float) (
                    $payment->wallet_amount_applied
                    ?? 0
                ),
                2
            );


            if (
                $walletApplied > 0 &&
                $wallet
            ) {

                $wallet->balance =
                    round(
                        (float) $wallet->balance
                        + $walletApplied,
                        2
                    );


                $wallet->save();


                $transactionData = [

                    'student_wallet_id' =>
                        $wallet->id,

                    'amount' =>
                        $walletApplied,

                    'type' =>
                        'Credit',

                    'description' =>
                        'Wallet credit restored after reversal of '
                        . 'receipt '
                        . $payment->receipt_no
                        . '.',

                    'fee_payment_id' =>
                        $payment->id,

                    'created_by' =>
                        auth()->id(),
                ];


                if (
                    Schema::hasColumn(
                        'student_wallet_transactions',
                        'student_fee_id'
                    )
                ) {
                    $transactionData['student_fee_id'] =
                        $studentFee->id;
                }


                StudentWalletTransaction::create(
                    $transactionData
                );
            }


            /*
             * Remove NEW wallet credit created by this payment.
             */
            $newCredit = round(
                (float) (
                    $payment->overpayment_amount
                    ?? 0
                ),
                2
            );


            if (
                $newCredit > 0 &&
                $wallet
            ) {

                $removeAmount = min(
                    $newCredit,
                    max(
                        0,
                        (float) $wallet->balance
                    )
                );


                if ($removeAmount > 0) {

                    $wallet->balance =
                        round(
                            (float) $wallet->balance
                            - $removeAmount,
                            2
                        );


                    $wallet->save();


                    $transactionData = [

                        'student_wallet_id' =>
                            $wallet->id,

                        'amount' =>
                            $removeAmount,

                        'type' =>
                            'Debit',

                        'description' =>
                            'Removal of new wallet credit after '
                            . 'reversal of receipt '
                            . $payment->receipt_no
                            . '.',

                        'fee_payment_id' =>
                            $payment->id,

                        'created_by' =>
                            auth()->id(),
                    ];


                    if (
                        Schema::hasColumn(
                            'student_wallet_transactions',
                            'student_fee_id'
                        )
                    ) {
                        $transactionData['student_fee_id'] =
                            $studentFee->id;
                    }


                    StudentWalletTransaction::create(
                        $transactionData
                    );
                }
            }


            $this->updateFeeStatus(
                $studentFee
            );

            $studentFee->save();


            $payment->is_reversed =
                true;


            if (
                Schema::hasColumn(
                    'fee_payments',
                    'reversal_reason'
                )
            ) {
                $payment->reversal_reason =
                    $validated['reversal_reason'];
            }


            if (
                Schema::hasColumn(
                    'fee_payments',
                    'reversed_by'
                )
            ) {
                $payment->reversed_by =
                    auth()->id();
            }


            if (
                Schema::hasColumn(
                    'fee_payments',
                    'reversed_at'
                )
            ) {
                $payment->reversed_at =
                    now();
            }


            $payment->save();
        });


        return redirect()
            ->route(
                'fee-payments.show',
                $feePayment->receipt_no
            )
            ->with(
                'success',
                'Payment reversed successfully.'
            );
    }


    /**
     * Show manual wallet-credit application form.
     */
    public function applyCreditForm(
        StudentFee $studentFee
    ) {
        $studentFee->load([
            'student',
            'academicYear',
            'term',
        ]);


        $wallet = StudentWallet::firstOrCreate(
            [
                'student_id' =>
                    $studentFee->student_id,
            ],
            [
                'balance' => 0,
            ]
        );


        return view(
            'fee_payments.apply_credit',
            compact(
                'studentFee',
                'wallet'
            )
        );
    }


    /**
     * Manually apply wallet credit.
     */
    public function applyCredit(
        Request $request,
        StudentFee $studentFee
    ) {
        $validated = $request->validate([
            'amount' =>
                'required|numeric|min:0.01',
        ]);


        DB::transaction(function () use (
            $validated,
            $studentFee
        ) {

            $studentFee =
                StudentFee::with([
                    'academicYear',
                    'term',
                ])
                ->lockForUpdate()
                ->findOrFail(
                    $studentFee->id
                );


            $wallet =
                StudentWallet::lockForUpdate()
                ->where(
                    'student_id',
                    $studentFee->student_id
                )
                ->first();


            if (!$wallet) {
                abort(
                    422,
                    'The student does not have a wallet.'
                );
            }


            $feeBalance = max(
                0,
                (float) $studentFee->net_fee
                - (float) $studentFee->paid_amount
            );


            $walletBalance = max(
                0,
                (float) $wallet->balance
            );


            $amount = min(
                (float) $validated['amount'],
                $feeBalance,
                $walletBalance
            );


            if ($amount <= 0) {
                abort(
                    422,
                    'There is no amount available to apply.'
                );
            }


            $studentFee->paid_amount =
                round(
                    (float) $studentFee->paid_amount
                    + $amount,
                    2
                );


            $this->updateFeeStatus(
                $studentFee
            );

            $studentFee->save();


            $wallet->balance =
                round(
                    (float) $wallet->balance
                    - $amount,
                    2
                );


            $wallet->save();


            $transactionData = [

                'student_wallet_id' =>
                    $wallet->id,

                'amount' =>
                    $amount,

                'type' =>
                    'Debit',

                'description' =>
                    'Student wallet credit manually applied to '
                    . $studentFee->academicYear->name
                    . ' - '
                    . $studentFee->term->name
                    . '.',

                'fee_payment_id' =>
                    null,

                'created_by' =>
                    auth()->id(),
            ];


            if (
                Schema::hasColumn(
                    'student_wallet_transactions',
                    'student_fee_id'
                )
            ) {
                $transactionData['student_fee_id'] =
                    $studentFee->id;
            }


            StudentWalletTransaction::create(
                $transactionData
            );
        });


        return redirect()
            ->route('fee-payments.index')
            ->with(
                'success',
                'Student wallet credit applied successfully.'
            );
    }


    /**
     * Update StudentFee status.
     */
    protected function updateFeeStatus(
        StudentFee $studentFee
    ): void {

        $netFee = round(
            (float) $studentFee->net_fee,
            2
        );


        $paidAmount = round(
            (float) $studentFee->paid_amount,
            2
        );


        $balance = max(
            0,
            $netFee - $paidAmount
        );


        $studentFee->paid_amount =
            $paidAmount;


        if (
            Schema::hasColumn(
                'student_fees',
                'balance'
            )
        ) {
            $studentFee->balance =
                $balance;
        }


        if (
            Schema::hasColumn(
                'student_fees',
                'status'
            )
        ) {

            if ($balance <= 0) {

                $studentFee->status =
                    'Paid';

            } elseif ($paidAmount > 0) {

                $studentFee->status =
                    'Partial';

            } else {

                $studentFee->status =
                    'Unpaid';
            }
        }
    }


    /**
     * Generate unique receipt number.
     */
    protected function generateReceiptNo(): string
    {
        $date =
            now()->format('Ymd');


        $lastPayment =
            FeePayment::where(
                'receipt_no',
                'like',
                'RCP-' . $date . '-%'
            )
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();


        if (!$lastPayment) {

            $number = 1;

        } else {

            $lastReceipt =
                $lastPayment->receipt_no;


            $lastNumber =
                (int) substr(
                    $lastReceipt,
                    -5
                );


            $number =
                $lastNumber + 1;
        }


        return 'RCP-'
            . $date
            . '-'
            . str_pad(
                $number,
                5,
                '0',
                STR_PAD_LEFT
            );
    }
}

