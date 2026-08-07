<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\DoctorPendingApproval;
use App\Mail\WelcomePatient;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Mail\NewDoctorRegistered;


class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone'          => ['nullable', 'string', 'max:20'],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
            'role'           => ['required', 'in:patient,doctor'],
            'specialization' => ['required_if:role,doctor', 'nullable', 'string', 'max:255'],
        ]);

        // ── Create user ──
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->role === 'doctor' ? 'pending' : 'approved',
        ]);

        // ── If doctor → create doctor record too ──
        if ($request->role === 'doctor') {
            $parts = explode(' ', trim($request->name));
            Doctor::create([
                'first_name'          => $parts[0],
                'last_name'           => implode(' ', array_slice($parts, 1)),
                'email'               => $request->email,
                'phone'               => $request->phone,
                'specialization'      => $request->specialization ?? 'General Health',
                'status'              => 'unavailable',
                'schedule_load'       => 0,
                'years_of_experience' => 0, // ← add this
                'consultation_fee'    => 0, // ← add this too
            ]);
        }

        event(new Registered($user));

        // ── Send welcome email ──
        // ── Send welcome email ──
        try {
            if ($request->role === 'patient') {
                Mail::to($user->email)->send(new WelcomePatient($user));
            } else {
                // Send to doctor
                Mail::to($user->email)->send(new DoctorPendingApproval($user));

                // ── Send alert to all admins ──
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(
                        new \App\Mail\NewDoctorRegistered($user, $request->specialization ?? 'General Health')
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error('Registration email failed: ' . $e->getMessage());
        }

        // ── Redirect ──
        // ── Redirect ──
        if ($request->role === 'doctor') {
            Auth::logout();
            return redirect()->route('login')
                ->with('status', '✅ Registration successful! Your account is pending admin approval. You will be notified by email once approved.');
        }

        // Patient → send verification email
        Auth::login($user);
        return redirect()->route('verification.notice'); // ← change this

        // Auth::login($user);
        // return redirect()->route('patient.dashboard');
    }
}
