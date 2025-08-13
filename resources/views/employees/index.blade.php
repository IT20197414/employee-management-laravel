{{-- resources/views/employees/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <form class="d-flex" method="GET" action="{{ route('employees.index') }}">
        <input type="text" name="q" class="form-control me-2" placeholder="Search name, email, phone, position..."
               value="{{ $q }}">
        <button class="btn btn-primary" type="submit">Search</button>
      </form>
      <div class="d-flex gap-2">
        <a href="{{ route('employees.create') }}" class="btn btn-success">Add Employee</a>
        <a href="{{ route('employees.report') }}" class="btn btn-secondary">Download Report</a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle table-striped">
        <thead>
          <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Position</th>
            <th class="text-end">Salary</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($employees as $emp)
          <tr>
            <td>
              @if($emp->photo_path)
                <img src="{{ asset('storage/'.$emp->photo_path) }}" alt="photo" width="48" height="48" class="rounded-circle object-fit-cover">
              @else
                <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width:48px;height:48px;">
                  {{ strtoupper(substr($emp->name,0,1)) }}
                </div>
              @endif
            </td>
            <td>{{ $emp->name }}</td>
            <td>{{ $emp->email }}</td>
            <td>{{ $emp->phone }}</td>
            <td>{{ $emp->position }}</td>
            <td class="text-end">{{ $emp->salary ? number_format($emp->salary,2) : '-' }}</td>
            <td class="text-end">
              <a href="{{ route('employees.edit', $emp) }}" class="btn btn-sm btn-primary">Edit</a>
              <form action="{{ route('employees.destroy', $emp) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this employee?')"
                        type="submit">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted">No employees found.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{ $employees->links() }}
  </div>
</div>
@endsection
