<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class VolunteerAssignment extends Model
{
    use HasUuid;

    protected $table = 'prep.volunteer_assignment';
    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'volunteer_id',
        'assigned_geo_type',
        'assigned_geo_id',
        'assignment_role',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id', 'volunteer_id');
    }
}
