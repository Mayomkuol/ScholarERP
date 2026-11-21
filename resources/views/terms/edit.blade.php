@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-warning">

<h4>Edit Term</h4>

</div>

<div class="card-body">

<form action="{{ route('terms.update',$term) }}" method="POST">

@csrf
@method('PUT')

@include('terms.form')

<br>

<button class="btn btn-primary">

Update Term

</button>

</form>

</div>

</div>

</div>

@endsection