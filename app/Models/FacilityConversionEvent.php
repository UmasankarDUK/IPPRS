<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class FacilityConversionEvent extends Model
{
    use HasUuid;

    protected $table = 'prep.facility_conversion_event';
    protected $primaryKey = 'facility_conversion_event_id';

    protected $fillable = [
        'facility_id',
        'conversion_type',
        'start_datetime',
        'end_datetime',
        'conversion_status',
        'capacity_created',
        'remarks'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }
}
