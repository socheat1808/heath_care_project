<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/doctors', [DoctorController::class, 'index'])
        ->name('doctors.index');

    Route::post('/doctor-approvals/{user}/approve', [DoctorController::class, 'approveDoctor'])
        ->name('doctor-approvals.approve');

    Route::post('/doctor-approvals/{user}/reject', [DoctorController::class, 'rejectDoctor'])
        ->name('doctor-approvals.reject');
});
