@extends('admin.layout')
@section('title', 'Edit Patient')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Edit Patient</h1>
        <p>Update patient information and reset password if needed.</p>
    </div>
    <div style="display:flex;gap:.65rem">
        <a href="{{ route('admin.patients.show', $patient->id) }}"
            style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
                   border:1px solid var(--border);border-radius:10px;color:var(--text-muted);
                   text-decoration:none;font-size:.85rem;font-weight:600;background:var(--bg)">
            View History
        </a>
        <a href="{{ route('admin.patients.index') }}"
            style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
                   border:1px solid var(--border);border-radius:10px;color:var(--text-muted);
                   text-decoration:none;font-size:.85rem;font-weight:600;background:var(--bg)">
            ← Back
        </a>
    </div>
</div>

{{-- Errors --}}
@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:.85rem 1.1rem;
            margin-bottom:1.25rem;font-size:.875rem;color:#b91c1c">
    <div style="font-weight:700;margin-bottom:.35rem">Please fix the following:</div>
    <ul style="padding-left:1.25rem;margin:0">
        @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Success --}}
@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:.85rem 1.1rem;
            margin-bottom:1.25rem;font-size:.875rem;color:#15803d;display:flex;align-items:center;gap:.65rem">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.25rem;align-items:start">

    {{-- ── Main Form ── --}}
    <div class="chart-card" style="margin-bottom:0">
        <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
            @csrf @method('PUT')

            {{-- Personal Information --}}
            <div style="margin-bottom:1.75rem">
                <div style="font-size:.8rem;font-weight:700;text-transform:uppercase;
                            letter-spacing:.08em;color:var(--text-muted);margin-bottom:1rem;
                            padding-bottom:.5rem;border-bottom:1px solid var(--border)">
                    Personal Information
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">

                    {{-- Name --}}
                    <div style="grid-column:1/-1">
                        <label style="display:block;font-size:.83rem;font-weight:600;
                                      color:var(--text-muted);margin-bottom:.45rem">
                            Full Name <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="name"
                            value="{{ old('name', $patient->name) }}"
                            required
                            style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);
                                   border-radius:10px;background:var(--bg);color:var(--text);
                                   font-size:.875rem;outline:none;box-sizing:border-box"
                            onfocus="this.style.borderColor='var(--green)'"
                            onblur="this.style.borderColor='var(--border)'">
                        @error('name')
                        <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label style="display:block;font-size:.83rem;font-weight:600;
                                      color:var(--text-muted);margin-bottom:.45rem">
                            Email <span style="color:#ef4444">*</span>
                        </label>
                        <input type="email" name="email"
                            value="{{ old('email', $patient->email) }}"
                            required
                            style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);
                                   border-radius:10px;background:var(--bg);color:var(--text);
                                   font-size:.875rem;outline:none;box-sizing:border-box"
                            onfocus="this.style.borderColor='var(--green)'"
                            onblur="this.style.borderColor='var(--border)'">
                        @error('email')
                        <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label style="display:block;font-size:.83rem;font-weight:600;
                                      color:var(--text-muted);margin-bottom:.45rem">
                            Phone
                        </label>
                        <input type="text" name="phone"
                            value="{{ old('phone', $patient->phone) }}"
                            placeholder="+855 xx xxx xxx"
                            style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);
                                   border-radius:10px;background:var(--bg);color:var(--text);
                                   font-size:.875rem;outline:none;box-sizing:border-box"
                            onfocus="this.style.borderColor='var(--green)'"
                            onblur="this.style.borderColor='var(--border)'">
                    </div>

                </div>
            </div>

            {{-- Reset Password --}}
            <div style="margin-bottom:1.75rem">
                <div style="font-size:.8rem;font-weight:700;text-transform:uppercase;
                            letter-spacing:.08em;color:var(--text-muted);margin-bottom:1rem;
                            padding-bottom:.5rem;border-bottom:1px solid var(--border)">
                    Reset Password
                    <span style="font-size:.72rem;font-weight:400;text-transform:none;
                                 letter-spacing:0;color:var(--text-muted);margin-left:.5rem">
                        (leave blank to keep current)
                    </span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">

                    <div>
                        <label style="display:block;font-size:.83rem;font-weight:600;
                                      color:var(--text-muted);margin-bottom:.45rem">
                            New Password
                        </label>
                        <input type="password" name="password"
                            placeholder="Min 8 characters"
                            style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);
                                   border-radius:10px;background:var(--bg);color:var(--text);
                                   font-size:.875rem;outline:none;box-sizing:border-box"
                            onfocus="this.style.borderColor='var(--green)'"
                            onblur="this.style.borderColor='var(--border)'">
                        @error('password')
                        <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block;font-size:.83rem;font-weight:600;
                                      color:var(--text-muted);margin-bottom:.45rem">
                            Confirm New Password
                        </label>
                        <input type="password" name="password_confirmation"
                            placeholder="Repeat new password"
                            style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);
                                   border-radius:10px;background:var(--bg);color:var(--text);
                                   font-size:.875rem;outline:none;box-sizing:border-box"
                            onfocus="this.style.borderColor='var(--green)'"
                            onblur="this.style.borderColor='var(--border)'">
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div style="display:flex;justify-content:flex-end;gap:.75rem;
                        padding-top:1.25rem;border-top:1px solid var(--border)">
                <a href="{{ route('admin.patients.index') }}"
                    style="padding:.6rem 1.4rem;border:1px solid var(--border);border-radius:10px;
                           color:var(--text-muted);text-decoration:none;font-size:.875rem;
                           font-weight:600;background:var(--bg)">
                    Cancel
                </a>
                <button type="submit" class="btn-primary" style="padding:.6rem 1.6rem;font-size:.875rem">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Patient
                </button>
            </div>

        </form>
    </div>

    {{-- ── Patient Info Sidebar ── --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

        {{-- Profile card --}}
        <div class="chart-card" style="margin-bottom:0;text-align:center">
            <div style="width:64px;height:64px;border-radius:50%;background:var(--green);color:#fff;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.3rem;font-weight:700;margin:0 auto .75rem">
                {{ strtoupper(substr($patient->name, 0, 2)) }}
            </div>
            <div style="font-size:.95rem;font-weight:700;color:var(--text)">{{ $patient->name }}</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:.75rem">{{ $patient->email }}</div>
            <span class="badge {{ $patient->status === 'approved' ? 'badge-green' : 'badge-red' }}">
                {{ $patient->status === 'approved' ? 'Active' : ucfirst($patient->status ?? 'active') }}
            </span>
        </div>

        {{-- Quick stats --}}
        <div class="chart-card" style="margin-bottom:0">
            <div class="card-title" style="margin-bottom:.75rem">Quick Stats</div>
            @php
            $total = \App\Models\Appointment::where('patient_id', $patient->id)->count();
            $completed = \App\Models\Appointment::where('patient_id', $patient->id)->where('status','completed')->count();
            $pending = \App\Models\Appointment::where('patient_id', $patient->id)->where('status','pending')->count();
            @endphp
            <div style="display:flex;flex-direction:column;gap:.5rem">
                <div style="display:flex;justify-content:space-between;font-size:.83rem;
                            padding:.4rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Total Appointments</span>
                    <span style="font-weight:700;color:var(--text)">{{ $total }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.83rem;
                            padding:.4rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Completed</span>
                    <span style="font-weight:700;color:var(--green)">{{ $completed }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.83rem;padding:.4rem 0">
                    <span style="color:var(--text-muted)">Pending</span>
                    <span style="font-weight:700;color:var(--amber)">{{ $pending }}</span>
                </div>
            </div>
        </div>

        {{-- Joined --}}
        <div class="chart-card" style="margin-bottom:0">
            <div style="font-size:.83rem;color:var(--text-muted);margin-bottom:.25rem">Member since</div>
            <div style="font-size:.875rem;font-weight:600;color:var(--text)">
                {{ $patient->created_at->format('d M Y') }}
            </div>
        </div>

    </div>
</div>

@endsection