<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Doctor;
use App\Http\Controllers\Controller;

class HomeController extends Controller

{
    // public function index(Request $request)
    // {
    //     $query = AdminDoctor::with('schedules')
    //         ->orderByRaw("FIELD(status, 'available', 'onleave', 'unavailable')")
    //         ->orderBy('first_name');

    //     // Only show available by default on home page
    //     // but allow filter to override
    //     if (!$request->hasAny(['search', 'specialization', 'status'])) {
    //         $query->where('status', 'available');
    //     }

    //     // Search by name or specialization
    //     if ($request->filled('search')) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('first_name',      'like', '%' . $request->search . '%')
    //                 ->orWhere('last_name',      'like', '%' . $request->search . '%')
    //                 ->orWhere('specialization', 'like', '%' . $request->search . '%');
    //         });
    //     }

    //     // Filter by specialization
    //     if ($request->filled('specialization')) {
    //         $query->where('specialization', $request->specialization);
    //     }

    //     // Filter by status
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     $doctors = $query->paginate(9)->withQueryString();

    //     return view('user.home', compact('doctors'));

    // }
    public function index()
    {
        $doctors = Doctor::where('status', 'available')->limit(3)->get();
        return view('public.home', compact('doctors'));
    }
    public function Patientindex(Request $request)
    {
        $query = Doctor::with('schedules')
            ->orderByRaw("FIELD(status, 'available', 'onleave', 'unavailable')")
            ->orderBy('first_name');

        // Only show available by default on home page
        // but allow filter to override
        if (!$request->hasAny(['search', 'specialization', 'status'])) {
            $query->where('status', 'available');
        }

        // Search by name or specialization
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name',      'like', '%' . $request->search . '%')
                    ->orWhere('last_name',      'like', '%' . $request->search . '%')
                    ->orWhere('specialization', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by specialization
        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $doctors = $query->paginate(9)->withQueryString();
        return view('patient.appointments.find-doctors', compact('doctors'));
    }

    public function doctors()
    {
        // ✅ Show ALL doctors with search + filter
        $query = Doctor::query();

        if (request()->filled('search')) {
            $query->where('first_name', 'like', '%' . request('search') . '%')
                ->orWhere('last_name',  'like', '%' . request('search') . '%')
                ->orWhere('specialization', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('specialization')) {
            $query->where('specialization', request('specialization'));
        }

        $doctors = $query->where('status', 'available')->paginate(12);
        $specializations = Doctor::select('specialization')->distinct()->pluck('specialization');

        return view('public.home', compact('doctors', 'specializations'));
    }
    public function doctorProfile($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('user.doctor-profile', compact('doctor'));
    }
    public function redirect()
    {
        $user = Auth::user();

        if ($user->role === 'patient') {
            return redirect()->route('patient.dashboard'); // ← not view()
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.layout'); // ← not view()
        }

        if ($user->role === 'doctor') {
            // 🔒 Gate by status BEFORE showing the dashboard
            if ($user->status === 'pending') {
                return view('doctor.pending');
            }

            if ($user->status === 'rejected') {
                return view('doctor.rejected');
            }

            // ✅ Only approved doctors reach here
            return redirect()->route('doctor.layout'); // ← not view()
        }

        return redirect('/');
    }
}
