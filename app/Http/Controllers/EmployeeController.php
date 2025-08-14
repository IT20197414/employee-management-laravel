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
        if ($employee->photo_path) {
            Storage::disk('public')->delete($employee->photo_path);
        }
        $validated['photo_path'] = $request->file('photo')->store('employees', 'public');
    }

    $employee->update($validated);

    // Instead of flash success, store updated ID
    return redirect()->route('employees.index')->with('updated_employee_id', $employee->id);
}






    // PDF report of all employees
    public function report(Request $request)
    {
        $employees = Employee::orderBy('name')->get();

        $pdf = Pdf::loadView('employees.report', compact('employees'))->setPaper('a4', 'portrait');

        return $pdf->download('employees-report.pdf');
    }


    // EmployeeController.php
public function ajaxSearch(Request $request)
{
    $q = $request->input('q');

    $employees = Employee::query()
        ->when($q, function($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('position', 'like', "%{$q}%");
        })
        ->orderBy('name')
        ->paginate(10)
        ->appends($request->only('q'));

    return response()->json([
        'tableRows' => view('employees.partials.table_rows', compact('employees'))->render(),
        'pagination' => $employees->withQueryString()->links()->render(),
    ]);
}

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete(); // Soft delete only
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function forceDelete($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);

        // Delete photo file if exists
        if ($employee->photo && Storage::exists('public/photos/' . $employee->photo)) {
            Storage::delete('public/photos/' . $employee->photo);
        }

        $employee->forceDelete();
        return redirect()->route('employees.deleted')->with('success', 'Employee permanently deleted.');
    }


    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();
        return redirect()->route('employees.deleted')->with('success', 'Employee restored successfully.');
    }

    public function deletedEmployees()
    {
        $employees = Employee::onlyTrashed()->paginate(10);
        return view('employees.deleted', compact('employees'));
    }





}
