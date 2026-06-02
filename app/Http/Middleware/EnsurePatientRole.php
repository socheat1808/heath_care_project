<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePatientRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();



        // Not logged in → redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Logged in but not a patient → block
        if ($user->role !== 'patient') {
            return match ($user->role) {
                'admin'  => redirect()->route('admin.layout')
                    ->with('error', 'Patients only area.'),
                'doctor' => redirect()->route('doctor-dashboard.layout')
                    ->with('error', 'Patients only area.'),
                default  => redirect('/'),
            };
        }

        return $next($request);
    }
}
