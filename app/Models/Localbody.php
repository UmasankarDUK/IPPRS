<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localbody extends Model
{
    protected $fillable = ['block_id', 'name', 'type', 'code', 'population', 'vulnerable_population'];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function healthInstitutions()
    {
        return $this->hasMany(HealthInstitution::class, 'localbody_id');
    }

    public function infrastructureConversions()
    {
        return $this->hasMany(InfrastructureConversion::class);
    }

    public function planSections()
    {
        return $this->morphMany(PlanSection::class, 'planable')->orderBy('section_order');
    }
}
