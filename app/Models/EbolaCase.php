<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbolaCase extends Model
{
    protected $fillable = [
        'patient_name',
        'age',
        'gender',
        'health_institution_id',
        'status',
        'quarantine_type',
        'test_status',
        'outcome',
        'date_of_reporting'
    ];

    /**
     * Relationship with HealthInstitution (MCH facility).
     */
    public function healthInstitution()
    {
        return $this->belongsTo(HealthInstitution::class, 'health_institution_id');
    }
}
