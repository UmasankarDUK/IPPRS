<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PppDistrict extends Model
{
    use HasUuid;

    protected $table = 'geo.master_district';
    protected $primaryKey = 'district_id';

    protected $fillable = [
        'state_id',
        'district_code',
        'district_name_en',
        'district_name_ml',
        'district_headquarter',
        'geom_boundary',
        'geom_spatial',
        'is_active'
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function blocks()
    {
        return $this->hasMany(PppBlock::class, 'district_id', 'district_id');
    }
}
