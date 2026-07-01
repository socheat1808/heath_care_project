<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorApprovalController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\ProfileController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Doctor\LeaveController;
use App\Http\Controllers\Admin\LeaveController as AdminLeaveController;
use app\models\User;


// ════════════════════════════════════════════════════════════
// PUBLIC routes (no login required)
// ════════════════════════════════════════════════════════════
Route::get('/',             [HomeController::class, 'index'])->name('home');
Route::get('/redirect',     [HomeController::class, 'redirect'])->name('home.redirect');
Route::get('/doctors',      [HomeController::class, 'doctors'])->name('doctors');
Route::get('/doctors/{id}', [HomeController::class, 'doctorProfile'])->name('doctor.profile');

// Doctor profile (public — any role can view)
Route::get('/doctor/{doctorId}/profile', [AppointmentController::class, 'doctorProfile'])
    ->name('patient.appointments.doctor-profile');

// ── Redirect after login (role based) ───────────────────────
Route::get('/dashboard', [HomeController::class, 'redirect'])
    ->middleware(['auth'])
    ->name('dashboard');

// ════════════════════════════════════════════════════════════
// ADMIN routes
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminAppointmentController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/layout',    fn() => view('admin.layout'))->name('admin.layout');

        // Patients
        Route::get('patients',                [PatientController::class, 'index'])->name('admin.patients.index');
        Route::get('patients/create',         [PatientController::class, 'create'])->name('admin.patients.create');
        Route::post('patients',               [PatientController::class, 'store'])->name('admin.patients.store');
        Route::get('patients/{patient}',      [PatientController::class, 'show'])->name('admin.patients.show');
        Route::get('patients/{patient}/edit', [PatientController::class, 'edit'])->name('admin.patients.edit');
        Route::put('patients/{patient}',      [PatientController::class, 'update'])->name('admin.patients.update');
        Route::delete('patients/{patient}',   [PatientController::class, 'destroy'])->name('admin.patients.destroy');

        // Doctors
        Route::get('/doctors',           [DashboardController::class, 'index'])->name('admin.doctors.index');
        Route::get('/doctors/create',    [DashboardController::class, 'create'])->name('admin.doctors.create');
        Route::post('/doctors',          [DashboardController::class, 'store'])->name('admin.doctors.store');
        Route::get('/doctors/{id}/edit', [DashboardController::class, 'edit'])->name('admin.doctors.edit');
        Route::patch('/doctors/{id}',    [DashboardController::class, 'update'])->name('admin.doctors.update');
        Route::delete('/doctors/{id}',   [DashboardController::class, 'destroy'])->name('admin.doctors.destroy');

        // Doctor Approvals
        Route::get('/doctor-approvals',                 [DoctorApprovalController::class, 'index'])->name('admin.doctors.doctor-approvals');
        Route::post('/doctor-approvals/{user}/approve', [DoctorApprovalController::class, 'approveDoctor'])->name('admin.doctors.doctor-approvals.approve');
        Route::post('/doctor-approvals/{user}/reject',  [DoctorApprovalController::class, 'rejectDoctor'])->name('admin.doctors.doctor-approvals.reject');

        Route::get('/leave-requests',                          [AdminLeaveController::class, 'index'])->name('admin.leave.index');
        Route::patch('/leave-requests/{leaveRequest}/approve', [AdminLeaveController::class, 'approve'])->name('admin.leave.approve');
        Route::patch('/leave-requests/{leaveRequest}/reject',  [AdminLeaveController::class, 'reject'])->name('admin.leave.reject');

        // Appointments
        Route::get('/appointments',                          [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::get('/appointments/create',                   [AdminAppointmentController::class, 'create'])->name('admin.appointments.create');
        Route::post('/appointments/store',                   [AdminAppointmentController::class, 'store'])->name('admin.appointments.store');
        Route::patch('/appointments/{appointment}/approve',  [AdminAppointmentController::class, 'approve'])->name('admin.appointments.approve');
        Route::patch('/appointments/{appointment}/reject',   [AdminAppointmentController::class, 'reject'])->name('admin.appointments.reject');
        Route::patch('/appointments/{appointment}/complete', [AdminAppointmentController::class, 'complete'])->name('admin.appointments.complete');
        Route::delete('/appointments/{appointment}',         [AdminAppointmentController::class, 'destroy'])->name('admin.appointments.destroy');
    });

// ════════════════════════════════════════════════════════════
// DOCTOR routes
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'doctor.approved'])->group(function () {

    // Dashboard
    Route::get('/doctor-dashboard', [DashboardController::class, 'dashboard'])->name('doctor.layout'); // ← not view()

    // Appointments
    Route::get('/doctor-dashboard/appointments',        [DashboardController::class, 'appointments'])->name('doctor.appointments.index');
    Route::get('/doctor-dashboard/appointments/create', [DashboardController::class, 'createAppointment'])->name('doctor.appointments.create');
    Route::post('/doctor-dashboard/appointments/store', [DashboardController::class, 'storeAppointment'])->name('doctor.appointments.store');
    Route::patch('/appointments/{appointment}/complete', [DashboardController::class, 'completeAppointment'])->name('doctor.appointments.complete');
    Route::patch('/appointments/{appointment}/notes',   [DashboardController::class, 'addNotes'])->name('doctor.appointments.notes');

    // My Patients
    Route::get('/doctor-dashboard/my-patients', [DashboardController::class, 'myPatients'])
        ->name('doctor.patients.index');

    // Schedule
    Route::get('/doctor-dashboard/schedule', [DashboardController::class, 'schedule'])->name('doctor.schedule.index');
    Route::put('/doctor-dashboard/schedule', [DashboardController::class, 'updateSchedule'])->name('doctor.schedule.update');

    // Profile
    Route::get('/doctor-dashboard/profile',   [DashboardController::class, 'profile'])->name('doctor.profile.edit');
    Route::patch('/doctor-dashboard/profile', [DashboardController::class, 'updateProfile'])->name('doctor.profile.update');

    Route::patch('/appointments/{appointment}/approve', [DashboardController::class, 'approveAppointment'])->name('doctor.appointments.approve');
    Route::patch('/appointments/{appointment}/reject',  [DashboardController::class, 'rejectAppointment'])->name('doctor.appointments.reject');
    Route::patch('/appointments/{appointment}/cancel',  [DashboardController::class, 'cancelAppointment'])->name('doctor.appointments.cancel');

    Route::get('/doctor-dashboard/patients/{patientId}/notes', [DashboardController::class, 'patientNotes'])
        ->name('doctor.patients.notes');

    Route::get('/doctor-dashboard/leave',          [LeaveController::class, 'index'])->name('doctor.leave.index');
    Route::post('/doctor-dashboard/leave',         [LeaveController::class, 'store'])->name('doctor.leave.store');
    Route::delete('/doctor-dashboard/leave/{leaveRequest}', [LeaveController::class, 'cancel'])->name('doctor.leave.cancel');
});

// ════════════════════════════════════════════════════════════
// PATIENT routes
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'patient'])->group(function () {

    // Dashboard
    Route::get('/patient-dashboard', [AppointmentController::class, 'dashboard'])
        ->name('patient.dashboard');

    // My appointments
    Route::get('/patient/appointments', [AppointmentController::class, 'index'])
        ->name('patient.appointments.index');

    // Find doctors
    Route::get('/find-doctors', [HomeController::class, 'findDoctors'])
        ->name('patient.appointments.find-doctors');

    // Book appointment form
    Route::get('/doctor/{doctorId}/book', [AppointmentController::class, 'create'])
        ->name('patient.appointments.book');

    // Store appointment
    Route::post('/patient/appointments', [AppointmentController::class, 'store'])
        ->name('patient.appointments.store');

    // Cancel appointment
    Route::patch('/patient/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('patient.appointments.cancel');
});

// ════════════════════════════════════════════════════════════
// ALL AUTHENTICATED USERS (any role)
// ════════════════════════════════════════════════════════════
Route::middleware(['auth'])->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
