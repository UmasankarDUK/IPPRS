<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSection extends Model
{
    protected $fillable = ['planable_type', 'planable_id', 'title', 'content', 'section_order'];

    public function planable()
    {
        return $this->morphTo();
    }

    public function getPlanableAttribute()
    {
        if ($this->relationLoaded('planable_resolved')) {
            return $this->getRelation('planable_resolved');
        }

        $resolved = null;
        if ($this->planable_type === 'App\\Models\\District') {
            $resolved = District::where('district_code', $this->planable_id)->first();
        } elseif ($this->planable_type === 'App\\Models\\Block') {
            $resolved = Block::where('block_int_id', $this->planable_id)->first();
        } elseif ($this->planable_type === 'App\\Models\\Localbody') {
            $resolved = Localbody::where('localbody_id', $this->planable_id)->first();
        } elseif ($this->planable_type === 'App\\Models\\HealthInstitution' || $this->planable_type === 'App\\Models\\Institution') {
            $resolved = HealthInstitution::where('id', $this->planable_id)->first();
        }

        if (!$resolved) {
            try {
                $resolved = $this->morphTo()->getResults();
            } catch (\Throwable $e) {
                $resolved = null;
            }
        }

        $this->setRelation('planable_resolved', $resolved);
        return $resolved;
    }
}
