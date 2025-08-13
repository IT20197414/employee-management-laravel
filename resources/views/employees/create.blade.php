{{-- resources/views/employees/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="mb-3">Add Employee</h5>
    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
      @csrf
      @include('employees.partials.form', ['employee' => null])
      <div class="mt-3">
        <button class="btn btn-success">Save</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
