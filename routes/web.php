<?php

// routes/web.php
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // redirect root to employees
    return redirect()->route('employees.index');
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
});

require __DIR__.'/auth.php';

