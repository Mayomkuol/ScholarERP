@extends('layouts.app')

@section('content')

<div class="card shadow-sm">

    <div class="card-header">

        <h4>School Profile</h4>

    </div>

    <div class="card-body">

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <form method="POST" action="{{ route('school.update') }}">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>School Name</label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name',$school->name) }}"
                        class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>School Code</label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code',$school->code) }}"
                        class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email',$school->email) }}"
                        class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Phone</label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone',$school->phone) }}"
                        class="form-control">

                </div>

                <div class="col-md-12 mb-3">

                    <label>Address</label>

                    <textarea
                        name="address"
                        class="form-control">{{ old('address',$school->address) }}</textarea>

                </div>

            </div>

            <button class="btn btn-primary">

                Save School Profile

            </button>

        </form>

    </div>

</div>

@endsection