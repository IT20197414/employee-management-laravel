@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Deleted Employees</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Salary</th>
                    <th>Deleted Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                    <tr>
                        <td>
                            @if($emp->photo_path && file_exists(public_path('storage/' . $emp->photo_path)))
                                <img src="{{ asset('storage/' . $emp->photo_path) }}" alt="Photo" width="60" height="60">
                            @else
                                <span>No photo</span>
                            @endif
                        </td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->email }}</td>
                        <td>{{ $emp->phone }}</td>
                        <td>{{ $emp->position }}</td>
                        <td>{{ $emp->salary ? number_format($emp->salary, 2) : '-' }}</td>
                        <td>
                            {{ $emp->deleted_at ? $emp->deleted_at->format('Y-m-d') : '-' }}
                        </td>
                        <td class="d-flex gap-1">
                            <form action="{{ route('employees.restore', $emp->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-success btn-sm" type="submit">Restore</button>
                            </form>
                            <form action="{{ route('employees.forceDelete', $emp->id) }}" method="POST"
                                onsubmit="return confirm('If you want to Delete Permanently, click OK.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm w-100" style="max-width:120px;" type="submit">Delete Perm.</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No deleted employees found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div>
            {{ $employees->links() }}
        </div>

        <div class="mt-3">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to Employee List</a>
        </div>
    </div>
@endsection
