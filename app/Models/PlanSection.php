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
}
