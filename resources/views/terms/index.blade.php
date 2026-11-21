@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-3">

<h3>Academic Terms</h3>

<a href="{{ route('terms.create') }}"
class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

New Term

</a>

</div>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>#</th>

<th>Academic Year</th>

<th>Term</th>

<th>Code</th>

<th>Dates</th>

<th>Status</th>

<th>Current</th>

<th width="170">Action</th>

</tr>

</thead>

<tbody>

@forelse($terms as $term)

<tr>

<td>{{ $term->id }}</td>

<td>{{ $term->academicYear->name }}</td>

<td>{{ $term->name }}</td>

<td>{{ $term->code }}</td>

<td>

{{ $term->start_date }}

<br>

{{ $term->end_date }}

</td>

<td>

@if($term->status=='Open')

<span class="badge bg-success">

Open

</span>

@else

<span class="badge bg-danger">

Closed

</span>

@endif

</td>

<td>

@if($term->is_current)

<span class="badge bg-primary">

Current

</span>

@endif

</td>

<td>

<a href="{{ route('terms.edit',$term) }}"
class="btn btn-warning btn-sm">

Edit

</a>

<form
action="{{ route('terms.destroy',$term) }}"
method="POST"
class="d-inline">

@csrf

@method('DELETE')

<button
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this term?')">

Delete

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="8" class="text-center">

No terms found.

</td>

</tr>

@endforelse

</tbody>

</table>

{{ $terms->links() }}

</div>

</div>

</div>

@endsection