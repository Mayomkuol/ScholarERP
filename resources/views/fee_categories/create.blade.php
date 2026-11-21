@extends('layouts.app')

@section('title','New Fee Category')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">

                Create Fee Category

            </h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('fee-categories.store') }}"
                method="POST">

                @csrf

                @include('fee_categories._form')

            </form>

        </div>

    </div>

</div>

@endsection