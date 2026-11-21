<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\Student;
use App\Models\StudentFee;
use Carbon\Carbon;

class FinanceDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Date Ranges
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = Carbon::now()->endOfMonth();

        $yearStart = Carbon::now()->startOfYear();
        $yearEnd   = Carbon::now()->endOfYear();


        /*
        |--------------------------------------------------------------------------
        | Collections
        |--------------------------------------------------------------------------
        |
        | amount_received = actual cash received from the payer.
        | This is the correct figure for cash collection reporting.
        |
        */

        $todayCollection = FeePayment::whereDate(
            'payment_date',
            $today
        )->sum('amount_received');

        $monthCollection = FeePayment::whereBetween(
            'payment_date',
            [$monthStart, $monthEnd]
        )->sum('amount_received');

        $yearCollection = FeePayment::whereBetween(
            'payment_date',
            [$yearStart, $yearEnd]
        )->sum('amount_received');


        /*
        |--------------------------------------------------------------------------
        | Fee Summary
        |--------------------------------------------------------------------------
        */

        $expectedFees = StudentFee::sum('net_fee');

        /*
        | Amount actually allocated toward student fees.
        |
        | This excludes money that was moved into the student's wallet
        | as an overpayment.
        */

        $totalPaid = FeePayment::sum('amount_allocated');

        /*
        |--------------------------------------------------------------------------
        | Outstanding Fees
        |--------------------------------------------------------------------------
        */

        $outstanding = max(
            0,
            $expectedFees - $totalPaid
        );


        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        */

        $students = Student::count();


        /*
        |--------------------------------------------------------------------------
        | Paid / Owing Students
        |--------------------------------------------------------------------------
        |
        | Use database queries instead of loading every StudentFee
        | record into PHP.
        |
        */

        $fullyPaid = StudentFee::whereColumn(
            'paid_amount',
            '>=',
            'net_fee'
        )->count();

        $owing = StudentFee::whereColumn(
            'paid_amount',
            '<',
            'net_fee'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Payments
        |--------------------------------------------------------------------------
        */

        $recentPayments = FeePayment::with([
                'student'
            ])
            ->latest('payment_date')
            ->latest('id')
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view('finance.dashboard', compact(
            'todayCollection',
            'monthCollection',
            'yearCollection',
            'expectedFees',
            'totalPaid',
            'outstanding',
            'students',
            'fullyPaid',
            'owing',
            'recentPayments'
        ));
    }
}