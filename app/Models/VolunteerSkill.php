<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class VolunteerSkill extends Model
{
    use HasUuid;

    protected $table = 'prep.volunteer_skill';
    protected $primaryKey = 'volunteer_skill_id';

    protected $fillable = [
        'volunteer_id',
        'skill_name',
        'certification_no'
    ];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id', 'volunteer_id');
    }
}
