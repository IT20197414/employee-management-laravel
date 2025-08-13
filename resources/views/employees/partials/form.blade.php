{{-- resources/views/employees/partials/form.blade.php --}}
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" value="{{ old('name', optional($employee)->name) }}" class="form-control" required>
    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" value="{{ old('email', optional($employee)->email) }}" class="form-control" required>
    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" value="{{ old('phone', optional($employee)->phone) }}" class="form-control">
    @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Position</label>
    <input type="text" name="position" value="{{ old('position', optional($employee)->position) }}" class="form-control">
    @error('position')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Salary</label>
    <input type="number" step="0.01" name="salary" value="{{ old('salary', optional($employee)->salary) }}" class="form-control">
    @error('salary')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Photo</label>
    <input type="file" name="photo" class="form-control">
    @error('photo')<div class="text-danger small">{{ $message }}</div>@enderror

    @if(optional($employee)->photo_path)
      <div class="mt-2">
        <img src="{{ asset('storage/'.$employee->photo_path) }}" alt="photo" width="80" height="80" class="rounded object-fit-cover">
      </div>
    @endif
  </div>
</div>
