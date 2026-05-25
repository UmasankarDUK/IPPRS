<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['district_id', 'name', 'code', 'population', 'area_sq_km'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function localbodies()
    {
        return $this->hasMany(Localbody::class);
    }

    public function planSections()
    {
        return $this->morphMany(PlanSection::class, 'planable')->orderBy('section_order');
    }
}
