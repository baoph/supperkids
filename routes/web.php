<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::resource('classes', SchoolClassController::class);

    // Student Excel import/export (must be declared before the resource route).
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    Route::get('students/template', [StudentController::class, 'template'])->name('students.template');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::resource('students', StudentController::class);

    Route::resource('teachers', TeacherController::class);

    // Payment Excel import/export (must be declared before the resource route).
    Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('payments/template', [PaymentController::class, 'template'])->name('payments.template');
    Route::post('payments/import', [PaymentController::class, 'import'])->name('payments.import');
    Route::resource('payments', PaymentController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
