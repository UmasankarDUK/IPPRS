<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasUuid;

    protected $table = 'prep.volunteer';
    protected $primaryKey = 'volunteer_id';

    protected $fillable = [
        'person_id',
        'district_id',
        'block_id',
        'lsg_id',
        'ward_id',
        'availability_status',
        'is_active'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }

    public function block()
    {
        return $this->belongsTo(Block::class, 'block_id', 'block_id');
    }

    public function localbody()
    {
        return $this->belongsTo(Localbody::class, 'lsg_id', 'lsg_id');
    }

    public function skills()
    {
        return $this->hasMany(VolunteerSkill::class, 'volunteer_id', 'volunteer_id');
    }

    public function assignments()
    {
        return $this->hasMany(VolunteerAssignment::class, 'volunteer_id', 'volunteer_id');
    }
}
