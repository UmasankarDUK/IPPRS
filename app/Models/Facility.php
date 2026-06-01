<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasUuid;

    protected $table = 'org.facility';
    protected $primaryKey = 'facility_id';

    protected $fillable = [
        'institution_id',
        'facility_code',
        'facility_name_en',
        'facility_name_ml',
        'facility_type_id',
        'facility_level',
        'ownership_type',
        'district_id',
        'block_id',
        'lsg_id',
        'ward_id',
        'latitude',
        'longitude',
        'address_id',
        'operational_status',
        'opened_on',
        'closed_on',
        'is_critical'
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id', 'institution_id');
    }

    public function capacities()
    {
        return $this->hasMany(FacilityCapacitySnapshot::class, 'facility_id', 'facility_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'facility_id', 'facility_id');
    }

    public function surgeFacilities()
    {
        return $this->hasMany(SurgeFacility::class, 'facility_id', 'facility_id');
    }
}
