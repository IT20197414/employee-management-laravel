<?php

// routes/web.php
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     // redirect root to employees
//     return redirect()->route('employees.index');
// });


Route::get('/', function () {
    return redirect()->route('register');
});

// Breeze adds auth routes. Keep dashboard but redirect it too.
Route::get('/dashboard', function () {
    return redirect()->route('employees.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // PDF report route
    Route::get('/employees/report/pdf', [EmployeeController::class, 'report'])
        ->name('employees.report');

    // resource routes (index, create, store, edit, update, destroy)
    Route::resource('employees', EmployeeController::class)->except(['show']);

    Route::get('/employees/search/ajax', [EmployeeController::class, 'ajaxSearch'])
    ->name('employees.ajaxSearch');


    //Soft delete routes
    Route::get('/employees/deleted', [EmployeeController::class, 'deletedEmployees'])->name('employees.deleted');
    Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::delete('/employees/{id}/force-delete', [EmployeeController::class, 'forceDelete'])->name('employees.forceDelete');






});

require __DIR__.'/auth.php';

