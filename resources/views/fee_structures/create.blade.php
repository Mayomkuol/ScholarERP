@extends('layouts.app')

@section('title','Create Fee Structure')

@section('content')

<div class="container-fluid">
     <x-school-header
    title="FINANCE"
    subtitle="FEE STRUCTURE"
/>

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">

                Create Fee Structure

            </h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('fee-structures.store') }}"
                method="POST">

                @csrf

                @include('fee_structures._form')

                <div class="text-end">

                    <a
                        href="{{ route('fee-structures.index') }}"
                        class="btn btn-secondary">

                        Cancel

                    </a>

                    <button class="btn btn-primary">

                        Save Fee Structure

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection