{{-- resources/views/employees/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="mb-3">Edit Employee</h5>
    <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      @include('employees.partials.form', ['employee' => $employee])
      <div class="mt-3">
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
