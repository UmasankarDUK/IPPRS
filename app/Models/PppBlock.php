<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PppBlock extends Model
{
    use HasUuid;

    protected $table = 'geo.master_block';
    protected $primaryKey = 'block_id';

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
        'area_sq_km'
    ];

    public function district()
    {
        return $this->belongsTo(PppDistrict::class, 'district_id', 'district_id');
    }

    public function localbodies()
    {
        return $this->hasMany(PppLocalbody::class, 'block_id', 'block_id');
    }
}
