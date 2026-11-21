@extends('layouts.app')

@section('title','Student Fee Statements')

@section('content')

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>

Student Fee Statements

</h4>

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>Admission No</th>

<th>Name</th>

<th>Action</th>

</tr>

</thead>

<tbody>

@foreach($students as $student)

<tr>

<td>

{{ $student->admission_no }}

</td>

<td>

{{ $student->first_name }}

{{ $student->last_name }}

</td>

<td>

<a
href="{{ route('fee-statements.show',$student) }}"
class="btn btn-success">

View Statement

</a>

</td>

</tr>

@endforeach

</tbody>

</table>

{{ $students->links() }}

</div>

</div>

</div>

@endsection