@extends('admin.layout')
@section('title', 'Edit Patient')

@section('content')

<div class="page-content active">

    <!-- Header -->
    <div class="page-header">
        <div class="page-header-left">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('admin.patients.index') }}" class="back-btn">←</a>
                <div>
                    <h1>Edit Patient</h1>
                    <p>Update patient profile and medical details.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Error -->
    @if($errors->any())
    <div class="alert-error">
        <strong>Please fix the following:</strong>
        <ul>
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <form action="{{ route('admin.patients.update', $patient->PatientID) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Personal Info -->
            <div class="form-section">
                <h3>Personal Information</h3>

                <div class="form-grid">

                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name"
                            value="{{ old('first_name', $patient->first_name) }}" required>
                    </div>

                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name"
                            value="{{ old('last_name', $patient->last_name) }}" required>
                    </div>

                    <div>
                        <label>Email</label>
                        <input type="email" name="email"
                            value="{{ old('email', $patient->email) }}" required>
                    </div>

                    <div>
                        <label>Phone</label>
                        <input type="text" name="phone"
                            value="{{ old('phone', $patient->phone) }}">
                    </div>

                    <div>
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth"
                            value="{{ old('date_of_birth', $patient->date_of_birth) }}">
                    </div>

                    <div>
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select</option>
                            <option value="male" {{ old('gender', $patient->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Medical Info -->
            <div class="form-section">
                <h3>Medical Information</h3>

                <div class="form-grid">

                    <div>
                        <label>Department</label>
                        <select name="department">
                            @foreach(['General','Cardiology','Dental','Neurology','Orthopaedics','Dermatology','Pediatrics','Psychiatry','Ophthalmology'] as $dept)
                            <option value="{{ $dept }}"
                                {{ old('department', $patient->department) === $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Status</label>
                        <select name="status">
                            <option value="active" {{ old('status', $patient->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $patient->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="full">
                        <label>Medical Notes</label>
                        <textarea name="notes" rows="4">{{ old('notes', $patient->notes) }}</textarea>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="form-footer">
                <a href="{{ route('admin.patients.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Update Patient</button>
            </div>

        </form>
    </div>

</div>

<style>
    /* Error */
    .alert-error {
        background: rgba(220, 38, 38, .08);
        color: #DC2626;
        border: 1px solid rgba(220, 38, 38, .2);
        border-radius: 10px;
        padding: .9rem 1.2rem;
        margin-bottom: 1.2rem;
        font-size: .85rem;
    }

    /* Card */
    .form-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.5rem;
        border: 1px solid var(--border);
    }

    /* Sections */
    .form-section {
        margin-bottom: 1.8rem;
    }

    .form-section h3 {
        font-size: .9rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-grid .full {
        grid-column: 1 / -1;
    }

    /* Inputs */
    input,
    select,
    textarea {
        width: 100%;
        padding: .6rem .8rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        font-size: .85rem;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: var(--green);
    }

    /* Footer */
    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: .7rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }

    /* Buttons */
    .btn-outline {
        padding: .6rem 1.4rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        text-decoration: none;
    }

    .btn-primary {
        padding: .6rem 1.4rem;
        border-radius: 8px;
        background: var(--green);
        color: #fff;
        border: none;
    }
</style>

@endsection