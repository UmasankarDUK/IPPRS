<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localbody extends Model
{
    protected $table = 'geo.master_local_body';
    protected $primaryKey = 'localbody_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'localbody_id',
        'localbody_code',
        'dist_id',
        'localbody_name_en',
        'localbody_name_mal',
        'localbody_type_id',
        'is_active',
        'block_id',
        'type',
        'code',
        'population',
        'vulnerable_population'
    ];

    public function getNameAttribute()
    {
        return $this->localbody_name_en;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['localbody_name_en'] = $value;
    }

    public function getIdAttribute()
    {
        return $this->localbody_id;
    }

    public function blocks()
    {
        return $this->belongsToMany(
            Block::class, 
            'geo.tbl_localbody_block_mapping', 
            'localbody_id', 
            'block_id', 
            'localbody_id', 
            'block_int_id'
        );
    }

    public function getBlockAttribute()
    {
        return $this->blocks->first();
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'dist_id', 'district_code');
    }

    public function healthInstitutions()
    {
        return $this->hasMany(HealthInstitution::class, 'localbody_id', 'localbody_id');
    }

    public function infrastructureConversions()
    {
        return $this->hasMany(InfrastructureConversion::class, 'localbody_id', 'localbody_id');
    }

    public function planSections()
    {
        return $this->morphMany(PlanSection::class, 'planable')->orderBy('section_order');
    }
}
