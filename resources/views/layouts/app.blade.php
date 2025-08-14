{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <style>
.blink-bg {
    animation: blink-bg-fade 1.5s;
    background-color: #d1e7dd !important;
}
@keyframes blink-bg-fade {
    0%   { background-color: #d1e7dd; }
    100% { background-color: inherit; }
}

.alert.fade {
    opacity: 0;
    transition: opacity 0.5s;
}

/* //zoom the image */
.photo-zoom-container {
    width: 48px;
    height: 48px;
    position: relative;
    display: inline-block;
    vertical-align: middle;
}
.employee-photo {
    transition: transform 0.3s cubic-bezier(.4,2,.6,1), box-shadow 0.3s;
    cursor: pointer;
    z-index: 1;
    position: relative;
    width: 48px;
    height: 48px;
}
.photo-zoom-container:hover .employee-photo {
    transform: scale(3) translateX(30px);
    z-index: 9999;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    position: absolute;
    left: 0;
    top: 0;
}
td { overflow: visible !important; }

</style>

  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="{{ route('employees.index') }}">{{ config('app.name') }}</a>
    <div class="d-flex">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
      </form>
    </div>
  </div>
</nav>
<div class="container">
@if(session('success'))
    <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif
  @yield('content')
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.add('fade');
            setTimeout(() => successAlert.remove(), 500);
        }, 3000);
    }
});
</script>
@endpush
@stack('scripts')  {{-- This makes @push('scripts') work --}}
</body>
</html>
