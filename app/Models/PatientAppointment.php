<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_date', 'status', 'note', 'prescription',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(patient::class);
    } 

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id')
                    ->whereHas('roles', function($query) {
                        $query->where('name', 'Doctor');
                    });
    }

}
