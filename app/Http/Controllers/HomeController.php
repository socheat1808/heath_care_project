<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AdminDoctor;

class HomeController extends Controller

{
    public function index()
    {
        $doctors = AdminDoctor::where('status', 'available')->limit(6)->get();
        return view('user.home', compact('doctors'));
    }
    public function doctors()
    {
        // ✅ Show ALL doctors with search + filter
        $query = AdminDoctor::query();

        if (request()->filled('search')) {
            $query->where('first_name', 'like', '%' . request('search') . '%')
                ->orWhere('last_name',  'like', '%' . request('search') . '%')
                ->orWhere('specialization', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('specialization')) {
            $query->where('specialization', request('specialization'));
        }

        $doctors = $query->where('status', 'available')->paginate(12);
        $specializations = AdminDoctor::select('specialization')->distinct()->pluck('specialization');

        return view('user.doctors', compact('doctors', 'specializations'));
    }
    public function doctorProfile($id)
    {
        $doctor = AdminDoctor::findOrFail($id);
        return view('user.doctor-profile', compact('doctor'));
    }
    public function redirect()
    {
        $user = Auth::user();

        if ($user->role === 'patient') {
            return redirect()->route('home');
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
            return redirect()->route('doctor-dashboard.layout');
        }

        return redirect('/');
    }
}
