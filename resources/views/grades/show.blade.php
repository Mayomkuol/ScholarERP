@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header">

<h4>Grade Details</h4>

</div>

<div class="card-body">

<table class="table">

<tr>

<th>Grade</th>

<td>{{ $grade->grade }}</td>

</tr>

<tr>

<th>Minimum</th>

<td>{{ $grade->min_mark }}</td>

</tr>

<tr>

<th>Maximum</th>

<td>{{ $grade->max_mark }}</td>

</tr>

<tr>

<th>Points</th>

<td>{{ $grade->points }}</td>

</tr>

<tr>

<th>Remarks</th>

<td>{{ $grade->remarks }}</td>

</tr>

<tr>

<th>Colour</th>

<td>

<span class="badge"
      style="background:{{ $grade->color }}">

{{ $grade->color }}

</span>

</td>

</tr>

</table>

<a href="{{ route('grades.index') }}"
   class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

@endsection