<?php

// app/Http/Controllers/EmployeeController.php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends Controller
{
    // List + search + pagination
    public function index(Request $request)
    {
        $q = $request->input('q');

        $employees = Employee::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('position', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->appends($request->only('q'));

        return view('employees.index', compact('employees', 'q'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:employees,email'],
            'phone'    => ['nullable','string','max:50'],
            'position' => ['nullable','string','max:255'],
            'salary'   => ['nullable','numeric','min:0'],
            'photo'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:employees,email,'.$employee->id],
            'phone'    => ['nullable','string','max:50'],
            'position' => ['nullable','string','max:255'],
            'salary'   => ['nullable','numeric','min:0'],
            'photo'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            // delete old if any
            if ($employee->photo_path) {
                Storage::disk('public')->delete($employee->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo_path) {
            Storage::disk('public')->delete($employee->photo_path);
        }
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }

    // PDF report of all employees
    public function report(Request $request)
    {
        $employees = Employee::orderBy('name')->get();

        $pdf = Pdf::loadView('employees.report', compact('employees'))->setPaper('a4', 'portrait');

        return $pdf->download('employees-report.pdf');
    }
}
