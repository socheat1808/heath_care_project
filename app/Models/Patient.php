<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $primaryKey = 'PatientID';

    public function getRouteKeyName()
    {
        return 'PatientID';
    }
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'department',
        'status',
        'notes',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
