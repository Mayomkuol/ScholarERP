@extends('layouts.app')

@section('title','Receive Fee Payment')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-success text-white">

            <h4 class="mb-0">

                Receive School Fees

            </h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('fee-payments.store') }}"
                method="POST">

                @csrf

                @include('fee_payments._form')

                <div class="text-end">

                    <button class="btn btn-success">

                        <i class="bi bi-cash-stack"></i>

                        Save Payment

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection