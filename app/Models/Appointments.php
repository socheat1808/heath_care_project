<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'patient_id',       // ✅ lowercase
        'doctor_id',        // ✅ lowercase
        'appointment_date',
        'appointment_time',
        'status',
        'reason',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');       // ✅
    }

    public function doctor()
    {
        return $this->belongsTo(AdminDoctor::class, 'doctor_id', 'DoctorID'); // ✅
    }
}
