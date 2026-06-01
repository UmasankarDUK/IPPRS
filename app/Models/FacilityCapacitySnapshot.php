<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class FacilityCapacitySnapshot extends Model
{
    use HasUuid;

    protected $table = 'org.facility_capacity_snapshot';
    protected $primaryKey = 'capacity_snapshot_id';

    protected $fillable = [
        'facility_id',
        'snapshot_date',
        'general_beds',
        'icu_beds',
        'hdu_beds',
        'isolation_beds',
        'oxygen_supported_beds',
        'ventilator_supported_beds',
        'pediatric_beds',
        'maternity_beds',
        'oxygen_cylinders_available',
        'oxygen_concentrators_available',
        'oxygen_plants_available',
        'remarks'
    ];

    protected $casts = [
        'snapshot_date' => 'date',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }
}
