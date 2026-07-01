<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'patient');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $patients = $query->latest()->paginate(15)->withQueryString();
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'role'              => 'patient',
            'status'            => 'approved',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient created successfully.');
    }

    public function edit(User $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, User $patient)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $patient->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $patient->update($data);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(User $patient)
    {
        $patient->delete();
        return back()->with('success', 'Patient deleted successfully.');
    }
    public function show(User $patient)
    {
        $appointments = \App\Models\Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->orderByDesc('appointment_date')
            ->get();

        $totalAppointments     = $appointments->count();
        $pendingAppointments   = $appointments->where('status', 'pending')->count();
        $completedAppointments = $appointments->where('status', 'completed')->count();
        $cancelledAppointments = $appointments->where('status', 'cancelled')->count();

        return view('admin.patients.show', compact(
            'patient',
            'appointments',
            'totalAppointments',
            'pendingAppointments',
            'completedAppointments',
            'cancelledAppointments'
        ));
    }
}
