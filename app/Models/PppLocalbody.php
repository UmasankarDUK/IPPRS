<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PppLocalbody extends Model
{
    use HasUuid;

    protected $table = 'geo.master_lsg';
    protected $primaryKey = 'lsg_id';

    protected $fillable = [
        'block_id',
        'lsg_code',
        'lsg_name_en',
        'lsg_name_ml',
        'lsg_type',
        'area_sq_km',
        'population_latest',
        'geom_boundary',
        'geom_spatial',
        'is_active'
    ];

    public function block()
    {
        return $this->belongsTo(PppBlock::class, 'block_id', 'block_id');
    }

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'lsg_id', 'lsg_id');
    }
}
