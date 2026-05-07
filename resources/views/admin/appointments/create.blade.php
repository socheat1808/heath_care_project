@extends('admin.layout')
@section('title', 'Add Appointment')

@section('content')
<div class="page-content active" id="page-appointments-create">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1>Add Appointment</h1>
            <p>Schedule a new appointment for a patient with a doctor.</p>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Appointments
            </a>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:.9rem;">
        <strong>Please fix the following errors:</strong>
        <ul style="margin:6px 0 0 18px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.appointments.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

            {{-- ── LEFT COLUMN ── --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Patient & Doctor --}}
                <div class="table-card" style="padding:24px;">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid var(--border);">
                        Patient &amp; Doctor
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                        {{-- Patient --}}
                        <div class="fg">
                            <label class="flabel">Patient <span style="color:#ef4444">*</span></label>
                            <select name="PatientID" class="finput {{ $errors->has('PatientID') ? 'finput-err' : '' }}" required>
                                <option value="">— Select Patient —</option>
                                @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('PatientID') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} ({{ $patient->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('PatientID')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        {{-- Doctor --}}
                        <div class="fg">
                            <label class="flabel">Doctor <span style="color:#ef4444">*</span></label>
                            <select name="DoctorID" class="finput {{ $errors->has('DoctorID') ? 'finput-err' : '' }}" required onchange="updatePreview(this)">
                                <option value="">— Select Doctor —</option>
                                @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->DoctorID }}"
                                    data-spec="{{ $doctor->specialization }}"
                                    data-fee="{{ $doctor->consultation_fee ?? '—' }}"
                                    {{ old('DoctorID') == $doctor->DoctorID ? 'selected' : '' }}>
                                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} — {{ $doctor->specialization }}
                                </option>
                                @endforeach
                            </select>
                            @error('DoctorID')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- Schedule --}}
                <div class="table-card" style="padding:24px;">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid var(--border);">
                        Schedule
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                        {{-- Date --}}
                        <div class="fg">
                            <label class="flabel">Appointment Date <span style="color:#ef4444">*</span></label>
                            <input type="date" name="appointment_date"
                                class="finput {{ $errors->has('appointment_date') ? 'finput-err' : '' }}"
                                value="{{ old('appointment_date', now()->addDay()->format('Y-m-d')) }}"
                                min="{{ now()->format('Y-m-d') }}" required>
                            @error('appointment_date')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        {{-- Time --}}
                        <div class="fg">
                            <label class="flabel">Appointment Time <span style="color:#ef4444">*</span></label>
                            <select name="appointment_time" class="finput {{ $errors->has('appointment_time') ? 'finput-err' : '' }}" required>
                                <option value="">— Select Time —</option>
                                @php
                                $t = \Carbon\Carbon::createFromTime(8,0);
                                $e = \Carbon\Carbon::createFromTime(17,0);
                                @endphp
                                @while($t <= $e)
                                    <option value="{{ $t->format('H:i') }}" {{ old('appointment_time') === $t->format('H:i') ? 'selected' : '' }}>
                                    {{ $t->format('h:i A') }}
                                    </option>
                                    @php $t->addMinutes(30) @endphp
                                    @endwhile
                            </select>
                            @error('appointment_time')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- Details --}}
                <div class="table-card" style="padding:24px;">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid var(--border);">
                        Details
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

                        {{-- Reason --}}
                        <div class="fg">
                            <label class="flabel">Reason for Visit</label>
                            <input type="text" name="reason" class="finput"
                                value="{{ old('reason') }}" placeholder="e.g. Regular check-up, Follow-up…">
                        </div>

                        {{-- Status --}}
                        <div class="fg">
                            <label class="flabel">Status <span style="color:#ef4444">*</span></label>
                            <select name="status" class="finput" required>
                                @foreach(['pending'=>'Pending','approved'=>'Confirmed','completed'=>'Completed','cancelled'=>'Cancelled'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('status','approved') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- Notes --}}
                    <div class="fg">
                        <label class="flabel">Notes</label>
                        <textarea name="notes" class="finput" rows="3"
                            placeholder="Any additional notes or instructions…"
                            style="resize:vertical;">{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>{{-- /left --}}

            {{-- ── RIGHT COLUMN ── --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Actions --}}
                <div class="table-card" style="padding:20px;">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border);">
                        Actions
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Appointment
                        </button>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;text-align:center;">
                            Cancel
                        </a>
                    </div>
                </div>

                {{-- Doctor Preview --}}
                <div class="table-card" style="padding:20px;" id="doctorPreview">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border);">
                        Doctor Info
                    </div>
                    <div id="previewEmpty" style="text-align:center;padding:20px 0;color:var(--text-muted);font-size:.85rem;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" width="32" height="32" style="margin:0 auto 8px;display:block;opacity:.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Select a doctor to see details
                    </div>
                    <div id="previewContent" style="display:none;">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                            <div class="avatar av-green" style="width:42px;height:42px;font-size:.9rem;flex-shrink:0;" id="prev-avatar">—</div>
                            <div>
                                <div style="font-weight:600;font-size:.95rem;" id="prev-name">—</div>
                                <span class="badge badge-green" id="prev-spec">—</span>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:8px;font-size:.85rem;">
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-muted);">Consultation Fee</span>
                                <strong id="prev-fee">—</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Tips --}}
                <div style="background:#e8f5f1;border:1px solid #b2d8cf;border-radius:12px;padding:16px 18px;">
                    <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#1a7a5e;margin-bottom:10px;">Tips</div>
                    <ul style="font-size:.82rem;color:#1a5c47;line-height:1.7;padding-left:16px;margin:0;">
                        <li>Appointments default to <strong>Confirmed</strong> status.</li>
                        <li>Time slots are available from <strong>8:00 AM – 5:00 PM</strong>.</li>
                        <li>Check doctor availability before booking.</li>
                    </ul>
                </div>

            </div>{{-- /right --}}

        </div>
    </form>

</div>

<style>
    .fg {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .flabel {
        font-size: .8rem;
        font-weight: 600;
        color: var(--text-secondary, #374151);
    }

    .finput {
        border: 1px solid var(--border, #e5e9f0);
        border-radius: 8px;
        padding: 9px 12px;
        font-size: .88rem;
        font-family: inherit;
        color: var(--text, #1a1f2e);
        background: #fff;
        outline: none;
        width: 100%;
        transition: border-color .2s, box-shadow .2s;
    }

    .finput:focus {
        border-color: #1a7a5e;
        box-shadow: 0 0 0 3px rgba(26, 122, 94, .08);
    }

    .finput-err {
        border-color: #ef4444 !important;
    }

    .ferr {
        color: #ef4444;
        font-size: .75rem;
        margin-top: 2px;
    }

    textarea.finput {
        resize: vertical;
    }

    @media(max-width:900px) {
        div[style*="grid-template-columns:1fr 340px"] {
            grid-template-columns: 1fr !important;
        }

        div[style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    function updatePreview(select) {
        const opt = select.options[select.selectedIndex];
        const empty = document.getElementById('previewEmpty');
        const content = document.getElementById('previewContent');

        if (!select.value) {
            empty.style.display = 'block';
            content.style.display = 'none';
            return;
        }

        const fullName = opt.text.split('—')[0].trim();
        const spec = opt.dataset.spec || '—';
        const fee = opt.dataset.fee || '—';
        const initials = fullName.replace('Dr. ', '').split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();

        document.getElementById('prev-avatar').textContent = initials;
        document.getElementById('prev-name').textContent = fullName;
        document.getElementById('prev-spec').textContent = spec;
        document.getElementById('prev-fee').textContent = fee !== '—' ? '$' + fee : '—';

        empty.style.display = 'none';
        content.style.display = 'block';
    }
</script>
@endsection