<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasUuid;

    protected $table = 'geo.master_district';
    protected $primaryKey = 'district_id';
    public $incrementing = false;
    protected $keyType = 'string';



    protected $fillable = [
        'state_id',
        'district_code',
        'district_name_en',
        'district_name_ml',
        'district_headquarter',
        'geom_boundary',
        'geom_spatial',
        'is_active',
        'state',
        'code',
        'population',
        'area_sq_km'
    ];

    public function getNameAttribute()
    {
        return $this->district_name_en;
    }

    public function getIdAttribute()
    {
        return $this->district_code;
    }

    public function blocks()
    {
        return $this->hasMany(Block::class, 'distric_int_id', 'district_code');
    }

    public function planSections()
    {
        return $this->morphMany(PlanSection::class, 'planable', 'planable_type', 'planable_id', 'district_code')->orderBy('section_order');
    }
}
