@extends('layouts.app')

@section('title','Edit Fee Structure')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-warning">

            <h4 class="mb-0">

                Edit Fee Structure

            </h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('fee-structures.update',$feeStructure) }}"
                method="POST">

                @csrf
                @method('PUT')

                @include('fee_structures._form')

                <div class="text-end">

                    <a
                        href="{{ route('fee-structures.index') }}"
                        class="btn btn-secondary">

                        Cancel

                    </a>

                    <button class="btn btn-warning">

                        Update Fee Structure

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection