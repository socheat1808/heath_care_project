@extends('doctor-dashboard.layout') {{-- change to your doctor layout --}}
@section('title', 'My Profile')
@section('content')

<div class="page-content active">

    <div class="page-header">
        <div class="page-header-left">
            <h1>My Profile</h1>
            <p>View and update your personal and professional details.</p>
        </div>
    </div>

    @if(session('success'))
    <div style="display:flex;align-items:center;gap:.6rem;padding:.75rem 1.25rem;border-radius:10px;background:rgba(26,138,110,.1);color:#1A8A6E;border:1px solid rgba(26,138,110,.2);font-size:.875rem;font-weight:500;margin-bottom:1.5rem">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div style="background:rgba(220,38,38,.08);color:#DC2626;border:1px solid rgba(220,38,38,.2);border-radius:10px;padding:.85rem 1.25rem;margin-bottom:1.5rem;font-size:.85rem">
        <strong>Please fix the following:</strong>
        <ul style="margin:.4rem 0 0;padding-left:1.25rem">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Profile Header Card --}}
    <div class="doctor-card">

        <!-- Cover -->
        <div class="doctor-cover"></div>

        <!-- Content -->
        <div class="doctor-header">

            <!-- Avatar -->
            <div class="doctor-avatar">
                @if($doctor && $doctor->photo)
                <img src="{{ asset('storage/' . $doctor->photo) }}" alt="Doctor Photo">
                @else
                <span>
                    {{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1) . substr($doctor->last_name ?? 'R', 0, 1)) }}
                </span>
                @endif
            </div>

            <!-- Info -->
            <div class="doctor-info">
                <h2>
                    Dr. {{ $doctor->first_name ?? $user->name }} {{ $doctor->last_name ?? '' }}
                </h2>

                <p class="doctor-meta">
                    {{ $doctor->specialization ?? 'Doctor' }}
                    @if($doctor && $doctor->years_of_experience)
                    · {{ $doctor->years_of_experience }} years experience
                    @endif
                </p>
            </div>

            <!-- Status -->
            <div class="doctor-status">
                @if($doctor && $doctor->status === 'available')
                <span class="badge green">● Available</span>
                @elseif($doctor && $doctor->status === 'onleave')
                <span class="badge amber">● On Leave</span>
                @else
                <span class="badge red">● Unavailable</span>
                @endif
            </div>

        </div>
    </div>

    {{-- Edit Form --}}
    <form action="{{ route('doctor-dashboard.update-profile') }}" method="POST">
        @csrf
        @method('PATCH')

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">

            {{-- ── Personal Info ── --}}
            <div style="background:#fff;border-radius:16px;border:1px solid #E5E7EB;padding:1.75rem;box-shadow:0 2px 8px rgba(0,0,0,.04)">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid #F3F4F6">
                    Personal Information
                </div>
                <div style="display:flex;flex-direction:column;gap:1rem">

                    <div class="pf-fi">
                        <label class="pf-label">First Name</label>
                        <input type="text" name="first_name" class="pf-input"
                            value="{{ old('first_name', $doctor->first_name ?? '') }}" required>
                    </div>

                    <div class="pf-fi">
                        <label class="pf-label">Last Name</label>
                        <input type="text" name="last_name" class="pf-input"
                            value="{{ old('last_name', $doctor->last_name ?? '') }}" required>
                    </div>

                    <div class="pf-fi">
                        <label class="pf-label">Email</label>
                        <input type="email" class="pf-input"
                            value="{{ $user->email }}" disabled
                            style="background:#F9FAFB;color:#6B7280;cursor:not-allowed">
                        <span style="font-size:.75rem;color:#9CA3AF">Email cannot be changed.</span>
                    </div>

                    <div class="pf-fi">
                        <label class="pf-label">Phone</label>
                        <input type="text" name="phone" class="pf-input"
                            value="{{ old('phone', $doctor->phone ?? '') }}"
                            placeholder="+855 xxx xxx xxx">
                    </div>

                </div>
            </div>

            {{-- ── Professional Info ── --}}
            <div style="background:#fff;border-radius:16px;border:1px solid #E5E7EB;padding:1.75rem;box-shadow:0 2px 8px rgba(0,0,0,.04)">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid #F3F4F6">
                    Professional Information
                </div>
                <div style="display:flex;flex-direction:column;gap:1rem">

                    <div class="pf-fi">
                        <label class="pf-label">Specialisation</label>
                        <select name="specialization" class="pf-input" required>
                            @foreach(['General Health','Cardiology','Dental','Neurology','Orthopaedics','Dermatology','Pediatrics','Psychiatry','Ophthalmology'] as $spec)
                            <option value="{{ $spec }}"
                                {{ old('specialization', $doctor->specialization ?? '') === $spec ? 'selected' : '' }}>
                                {{ $spec }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pf-fi">
                        <label class="pf-label">Years of Experience</label>
                        <input type="number" name="years_of_experience" class="pf-input"
                            value="{{ old('years_of_experience', $doctor->years_of_experience ?? '') }}"
                            min="0" max="50" placeholder="e.g. 10">
                    </div>

                    <div class="pf-fi">
                        <label class="pf-label">Consultation Fee ($)</label>
                        <input type="number" name="consultation_fee" class="pf-input"
                            value="{{ old('consultation_fee', $doctor->consultation_fee ?? '') }}"
                            min="0" step="0.01" placeholder="e.g. 80">
                    </div>

                </div>
            </div>

            {{-- ── Biography ── --}}
            <div style="background:#fff;border-radius:16px;border:1px solid #E5E7EB;padding:1.75rem;box-shadow:0 2px 8px rgba(0,0,0,.04);grid-column:1/-1">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid #F3F4F6">
                    Biography
                </div>
                <textarea name="biography_note" class="pf-input" rows="4"
                    placeholder="Write a short professional summary about yourself…">{{ old('biography_note', $doctor->biography_note ?? '') }}</textarea>
            </div>

            {{-- ── Change Password ── --}}
            <div style="background:#fff;border-radius:16px;border:1px solid #E5E7EB;padding:1.75rem;box-shadow:0 2px 8px rgba(0,0,0,.04);grid-column:1/-1">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid #F3F4F6">
                    Change Password <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.75rem">(leave blank to keep current)</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="pf-fi">
                        <label class="pf-label">New Password</label>
                        <input type="password" name="password" class="pf-input" placeholder="Min. 8 characters">
                    </div>
                    <div class="pf-fi">
                        <label class="pf-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="pf-input" placeholder="Repeat new password">
                    </div>
                </div>
            </div>

        </div>

        {{-- Save button --}}
        <div style="display:flex;justify-content:flex-end;margin-top:1.5rem">
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 2rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Save Changes
            </button>
        </div>

    </form>

</div>

<style>
    .pf-fi {
        display: flex;
        flex-direction: column;
        gap: .35rem
    }

    .pf-label {
        font-size: .8375rem;
        font-weight: 600;
        color: #111827
    }

    .pf-input {
        width: 100%;
        padding: .6rem .9rem;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        font-size: .875rem;
        font-family: inherit;
        color: #111827;
        background: #fff;
        outline: none;
        transition: border-color .2s, box-shadow .2s
    }

    .pf-input:focus {
        border-color: #1A8A6E;
        box-shadow: 0 0 0 3px rgba(26, 138, 110, .1)
    }

    textarea.pf-input {
        resize: vertical;
        min-height: 100px
    }

    .doctor-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
    }

    .doctor-cover {
        height: 120px;
        background: linear-gradient(135deg, #E8F7F3, #1A8A6E);
    }

    .doctor-header {
        display: flex;
        align-items: flex-end;
        gap: 1.5rem;
        padding: 0 2rem 1.5rem;
        margin-top: -40px;
        flex-wrap: wrap;
    }

    .doctor-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #fff;
        border: 4px solid #fff;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: #1A8A6E;
    }

    .doctor-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .doctor-info h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
    }

    .doctor-meta {
        font-size: .875rem;
        color: #6B7280;
        margin-top: .2rem;
    }

    .doctor-status {
        margin-left: auto;
        padding-bottom: .5rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .9rem;
        border-radius: 999px;
        font-size: .8rem;
        font-weight: 600;
    }

    .badge.green {
        background: rgba(26, 138, 110, .1);
        color: #1A8A6E;
    }

    .badge.amber {
        background: rgba(217, 119, 6, .1);
        color: #D97706;
    }

    .badge.red {
        background: rgba(220, 38, 38, .1);
        color: #DC2626;
    }
</style>

@endsection