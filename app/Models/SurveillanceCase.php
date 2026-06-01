<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SurveillanceCase extends Model
{
    use HasUuid;

    protected $table = 'surv.surveillance_case';
    protected $primaryKey = 'case_id';

    protected $fillable = [
        'report_id',
        'disease_id',
        'patient_person_id',
        'facility_id',
        'ward_id',
        'onset_date',
        'diagnosis_date',
        'case_classification', // Suspect, Probable, Confirmed
        'outcome_status', // Active, Recovered, Deceased
        'hospitalization_status', // Home Quarantine, Inst Quarantine, etc.
        'icd_code'
    ];

    protected $casts = [
        'onset_date' => 'date',
        'diagnosis_date' => 'date',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    public function report()
    {
        return $this->belongsTo(SurveillanceReport::class, 'report_id', 'report_id');
    }
}
