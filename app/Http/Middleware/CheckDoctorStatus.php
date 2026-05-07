<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDoctorStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'doctor') {

                if ($user->status === 'pending') {
                    return response()->view('doctor.pending');
                }

                if ($user->status === 'rejected') {
                    return response()->view('doctor.rejected');
                }
            }
        }

        return $next($request);
    }
}
