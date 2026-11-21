@extends('layouts.app')

@section('title', 'Edit Fee Payment')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-warning d-flex justify-content-between align-items-center">

            <h4 class="mb-0 text-dark">
                Edit Fee Payment
            </h4>

            <a href="{{ route('fee-payments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>

        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('fee-payments.update', $payment->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Student
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $payment->student->first_name }} {{ $payment->student->middle_name }} {{ $payment->student->last_name }}"
                               readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Receipt Number
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $payment->receipt_no }}"
                               readonly>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Payment Date
                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            class="form-control"
                            value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            class="form-select"
                            required>

                            <option value="Cash"
                                {{ old('payment_method', $payment->payment_method) == 'Cash' ? 'selected' : '' }}>
                                Cash
                            </option>

                            <option value="Bank"
                                {{ old('payment_method', $payment->payment_method) == 'Bank' ? 'selected' : '' }}>
                                Bank
                            </option>

                            <option value="Cheque"
                                {{ old('payment_method', $payment->payment_method) == 'Cheque' ? 'selected' : '' }}>
                                Cheque
                            </option>

                            <option value="Mobile Money"
                                {{ old('payment_method', $payment->payment_method) == 'Mobile Money' ? 'selected' : '' }}>
                                Mobile Money
                            </option>

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input
                            type="text"
                            name="reference_no"
                            class="form-control"
                            value="{{ old('reference_no', $payment->reference_no) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Amount Paid
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ number_format($payment->amount_paid,2) }}"
                            readonly>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        class="form-control"
                        rows="4">{{ old('remarks', $payment->remarks) }}</textarea>

                </div>

                <div class="text-end">

                    <button type="submit" class="btn btn-warning">

                        <i class="bi bi-save"></i>

                        Update Payment

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection