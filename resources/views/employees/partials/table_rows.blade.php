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
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this employee?')" type="submit">Delete</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center text-muted">No employees found.</td>
</tr>
@endforelse
