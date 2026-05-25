<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfrastructureConversion extends Model
{
    protected $fillable = ['localbody_id', 'name', 'type', 'potential_beds', 'status'];

    public function localbody()
    {
        return $this->belongsTo(Localbody::class);
    }
}
