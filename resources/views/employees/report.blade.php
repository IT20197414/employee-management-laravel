{{-- resources/views/employees/report.blade.php --}}
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Employees Report</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #999; padding: 6px; }
    th { background: #eee; }
  </style>
</head>
<body>
  <h2 style="margin-bottom:10px;">Employees Report</h2>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Name & Email</th>
        <th>Phone</th>
        <th>Position</th>
        <th>Salary</th>
      </tr>
    </thead>
    <tbody>
    @foreach($employees as $i => $emp)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>
          <strong>{{ $emp->name }}</strong><br>
          <span>{{ $emp->email }}</span>
        </td>
        <td>{{ $emp->phone }}</td>
        <td>{{ $emp->position }}</td>
        <td>{{ $emp->salary ? number_format($emp->salary,2) : '-' }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</body>
</html>
