<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthInstitution extends Model
{
    protected $table = 'health_institutions';

    protected $fillable = [
        'localbody_id', 
        'name', 
        'type', 
        'capacity_beds', 
        'capacity_icu', 
        'capacity_oxygen_beds', 
        'oxygen_storage_liters', 
        'lat', 
        'lng'
    ];

    public function localbody()
    {
        return $this->belongsTo(Localbody::class, 'localbody_id', 'localbody_id');
    }

    public function planSections()
    {
        return $this->morphMany(PlanSection::class, 'planable')->orderBy('section_order');
    }
}
