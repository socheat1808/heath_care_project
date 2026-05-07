<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\DoctorApprovalController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Models\AdminDoctor;
use App\Models\Appointments;

// Route::get('/', function () {
//     return view('user.home');
// })->name('home');

// Route::prefix('admin')->group(function () {
//     Route::resource('patients', PatientsController::class);
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', [HomeController::class, 'redirect'])
    ->middleware(['auth',])
    ->name('dashboard');


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/doctors/{id}', [HomeController::class, 'doctorProfile'])->name('doctor.profile');

// Route::get('/admin.layout', function () {
//     return view('admin.layout');
// })->name('admin.layout');




// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/layout', function () {
    return view('admin.layout');
})->name('admin.layout');



Route::middleware('auth', 'admin')
    ->prefix('admin')
    ->group(function () {
        Route::get('/admin.layout', function () {
            return view('admin.layout');
        })->name('admin.layout');

        Route::get('patients',               [PatientsController::class, 'index'])->name('admin.patients.index');
        Route::get('patients/create',        [PatientsController::class, 'create'])->name('admin.patients.create');

        Route::post('patients',              [PatientsController::class, 'store'])->name('admin.patients.store');
        Route::get('patients/{patient}',     [PatientsController::class, 'show'])->name('admin.patients.show');
        Route::get('patients/{patient}/edit', [PatientsController::class, 'edit'])->name('admin.patients.edit');
        Route::put('patients/{patient}',     [PatientsController::class, 'update'])->name('admin.patients.update');
        Route::delete('patients/{patient}',  [PatientsController::class, 'destroy'])->name('admin.patients.destroy');


        Route::get('/doctors',              [DoctorController::class, 'index'])->name('admin.doctors.index');
        Route::get('/doctors/create', [DoctorController::class, 'create'])->name('admin.doctors.create');
        Route::post('/doctors',       [DoctorController::class, 'store'])->name('admin.doctors.store');
        Route::get('/doctors/{id}/edit',      [DoctorController::class, 'edit'])->name('admin.doctors.edit');
        Route::patch('/doctors/{id}',           [DoctorController::class, 'update'])->name('admin.doctors.update');
        Route::delete('/doctors/{id}',        [DoctorController::class, 'destroy'])->name('admin.doctors.destroy');

        // Doctor Approvals
        Route::get('/doctor-approvals',                       [DoctorApprovalController::class, 'index'])->name('admin.doctors.doctor-approvals');
        Route::post('/doctor-approvals/{user}/approve',       [DoctorApprovalController::class, 'approveDoctor'])->name('admin.doctors.doctor-approvals.approve');
        Route::post('/doctor-approvals/{user}/reject',        [DoctorApprovalController::class, 'rejectDoctor'])->name('admin.doctors.doctor-approvals.reject');

        Route::get('/appointments',                         [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::get('/appointments/create',                   [AdminAppointmentController::class, 'create'])->name('admin.appointments.create');
        Route::patch('/appointments/{appointment}/approve',  [AdminAppointmentController::class, 'approve'])->name('admin.appointments.approve');
        Route::patch('/appointments/{appointment}/reject',   [AdminAppointmentController::class, 'reject'])->name('admin.appointments.reject');
        Route::patch('/appointments/{appointment}/complete', [AdminAppointmentController::class, 'complete'])->name('admin.appointments.complete');
        Route::delete('/appointments/{appointment}',         [AdminAppointmentController::class, 'destroy'])->name('admin.appointments.destroy');
        Route::post('/appointments/store',            [AdminAppointmentController::class, 'store'])->name('admin.appointments.store');
    });

// routes/web.php — wrap all doctor routes
Route::middleware(['auth', 'doctor.approved'])->group(function () {
    Route::get('/doctor-dashboard', [DoctorController::class, 'dashboard'])->name('doctor-dashboard.layout');
    Route::get('/dashboard/index', [DoctorController::class, 'dashboard'])->name('doctor-dashboard.index');
    Route::get('/profile', [DoctorController::class, 'profile'])->name('doctor-dashboard.profile');
    Route::patch('/profile', [DoctorController::class, 'updateProfile'])->name('doctor-dashboard.update-profile');




    Route::get('/doctors/doctor-index', [DoctorController::class, 'index'])->name('admin.doctors.doctor-index');

    Route::get('/doctors/appointments', [DoctorController::class, 'appointments'])->name('admin.doctors.appointments');
    // etc.
});




require __DIR__ . '/auth.php';
