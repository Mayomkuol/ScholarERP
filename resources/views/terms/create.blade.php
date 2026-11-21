@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Create New Term</h4>

</div>

<div class="card-body">

<form action="{{ route('terms.store') }}" method="POST">

@csrf

@include('terms.form')

<br>

<button class="btn btn-success">

Save Term

</button>

<a href="{{ route('terms.index') }}"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

@endsection