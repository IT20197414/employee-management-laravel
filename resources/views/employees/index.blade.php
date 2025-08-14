{{-- resources/views/employees/index.blade.php --}}
@extends('layouts.app')

@section('content')
        <div class="card">
            <div class="card-body">
                {{-- Top bar: search form + buttons --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- Search form --}}
                    <form class="d-flex" method="GET" action="{{ route('employees.index') }}" onsubmit="return false;">
                        <input type="text" id="live-search" name="q" class="form-control me-2"
                               placeholder="Search name, email, phone, position..." value="{{ $q ?? '' }}" autocomplete="off">
                    </form>

                    {{-- Buttons --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('employees.create') }}" class="btn btn-success btn-sm">Add Employee</a>
                        <a href="{{ route('employees.report') }}" class="btn btn-secondary btn-sm">Download Report</a>
                    </div>
                </div>

                {{-- Employee table --}}
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    @if(session('updated_employee_id'))
                        <input type="hidden" id="updated-employee-id" value="{{ session('updated_employee_id') }}">
                    @endif
                    <table class="table align-middle table-striped mb-0">
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
                        <tbody id="employee-table-body">
                            @forelse($employees as $emp)
                                <tr id="employee-row-{{ $emp->id }}">
                                    <td>
                                        <div class="photo-zoom-container">
                                            @if($emp->photo_path)
                                                <img src="{{ asset('storage/' . $emp->photo_path) }}" alt="photo" width="48" height="48"
                                                    class="rounded-circle object-fit-cover employee-photo">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center"
                                                    style="width:48px;height:48px;">
                                                    {{ strtoupper(substr($emp->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $emp->name }}</td>
                                    <td>{{ $emp->email }}</td>
                                    <td>{{ $emp->phone }}</td>
                                    <td>{{ $emp->position }}</td>
                                    <td class="text-end">{{ $emp->salary ? number_format($emp->salary, 2) : '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('employees.edit', $emp) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('employees.destroy', $emp) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this employee?')"
                                                type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                <div id="pagination-links">
                    {{ $employees->links() }}
                </div>

                <div class="mt-3">
                    <a href="{{ route('employees.deleted') }}" class="btn btn-warning">Deleted Employees</a>
                </div>
            </div>
        </div>

        {{-- Live search script --}}
        @push('scripts')
        <script>
        // Function to highlight and scroll to the updated row
        function highlightUpdatedRow() {
        const updatedIdInput = document.getElementById('updated-employee-id');
        if (!updatedIdInput) return;
        const updatedId = updatedIdInput.value;
        if (!updatedId) return;
        const row = document.querySelector(`#employee-row-${updatedId}`);
        if (row) {
            row.scrollIntoView({behavior: "smooth", block: "center"});
            row.classList.add('blink-bg');
            setTimeout(() => { row.classList.remove('blink-bg'); }, 1500);
        }
    }

        document.addEventListener('DOMContentLoaded', function () {
            // Live search logic
            const searchInput = document.getElementById('live-search');
            let timer = null;

            searchInput.addEventListener('keyup', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    const query = searchInput.value;
                    fetch(`{{ route('employees.ajaxSearch') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('employee-table-body').innerHTML = data.tableRows;
                            document.getElementById('pagination-links').innerHTML = data.pagination;
                            highlightUpdatedRow(); // Call after AJAX update
                        });
                }, 300); // debounce
            });

            // Highlight row on initial page load
            highlightUpdatedRow();
        });
        </script>
        @endpush
@endsection
