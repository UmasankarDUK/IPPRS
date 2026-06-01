<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SurgeFacility extends Model
{
    use HasUuid;

    protected $table = 'org.surge_facility';
    protected $primaryKey = 'surge_facility_id';

    protected $fillable = [
        'facility_id',
        'surge_type', // QUARANTINE, ISOLATION, RELIEF_CAMP, FIELD_HOSPITAL, SHELTER
        'max_capacity',
        'current_capacity',
        'is_active'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }
}
