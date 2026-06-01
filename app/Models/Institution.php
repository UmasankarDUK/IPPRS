<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasUuid;

    protected $table = 'org.institution';
    protected $primaryKey = 'institution_id';

    protected $fillable = [
        'organization_id',
        'facility_type_id',
        'district_id',
        'block_id',
        'lsg_id',
        'ward_id',
        'institution_code',
        'institution_name_en',
        'institution_name_ml',
        'ownership_type',
        'institution_level',
        'latitude',
        'longitude',
        'address_id',
        'is_active'
    ];

    public function localbody()
    {
        return $this->belongsTo(Localbody::class, 'lsg_id', 'lsg_id');
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'institution_id', 'institution_id');
    }

    public function planSections()
    {
        return $this->morphMany(PlanSection::class, 'planable')->orderBy('section_order');
    }
}
