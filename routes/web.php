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
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('payments', PaymentController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
