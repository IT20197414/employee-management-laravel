{{-- resources/views/employees/partials/form.blade.php --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $employee->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
            <input type="text" name="position" id="position"
                   class="form-control @error('position') is-invalid @enderror"
                   value="{{ old('position', $employee->position ?? '') }}" required>
            @error('position')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
            <input type="text" name="salary" id="salary"
                   class="form-control @error('salary') is-invalid @enderror"
                   value="{{ old('salary', $employee->salary ?? '') }}" required>
            @error('salary')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $employee->email ?? '') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $employee->phone ?? '') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" name="photo" id="photo"
                   class="form-control @error('photo') is-invalid @enderror">
            @error('photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if(!empty($employee) && $employee->photo_path)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="photo" width="64" height="64"
                         class="rounded-circle object-fit-cover">
                </div>
            @endif
        </div>
    </div>
</div>

<script>
(function() {
    const salaryInput = document.getElementById('salary');
    if (salaryInput) {
        salaryInput.addEventListener('input', function () {
            let value = salaryInput.value.replace(/,/g, '').replace(/[^\d]/g, '');
            if (value) {
                salaryInput.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            } else {
                salaryInput.value = '';
            }
        });

        // On form submit, remove commas so backend gets raw number
        salaryInput.form.addEventListener('submit', function () {
            salaryInput.value = salaryInput.value.replace(/,/g, '');
        });
    }
})();
</script>
