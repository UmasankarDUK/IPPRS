<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasUuid;

    protected $table = 'geo.master_block';
    protected $primaryKey = 'block_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'district_id',
        'block_code',
        'block_name_en',
        'block_name_ml',
        'block_type',
        'geom_boundary',
        'geom_spatial',
        'is_active',
        'code',
        'population',
        'area_sq_km',
        'block_int_id',
        'distric_int_id'
    ];

    public function getNameAttribute()
    {
        return $this->block_name_en;
    }

    public function getIdAttribute()
    {
        return $this->block_int_id;
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'distric_int_id', 'district_code');
    }

    public function localbodies()
    {
        return $this->belongsToMany(
            Localbody::class,
            'geo.tbl_localbody_block_mapping',
            'block_id',
            'localbody_id',
            'block_int_id',
            'localbody_id'
        );
    }

    public function planSections()
    {
        return $this->morphMany(PlanSection::class, 'planable')->orderBy('section_order');
    }
}
