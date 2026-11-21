@extends('layouts.app')

@section('title','Edit Fee Category')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-warning">

            <h4 class="mb-0">

                Edit Fee Category

            </h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('fee-categories.update',$category) }}"
                method="POST">

                @csrf
                @method('PUT')

                @include('fee_categories._form')

            </form>

        </div>

    </div>

</div>

@endsection