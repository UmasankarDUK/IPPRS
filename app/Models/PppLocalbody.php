<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PppLocalbody extends Model
{
    protected $table = 'geo.master_local_body';
    protected $primaryKey = 'localbody_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'localbody_id',
        'localbody_code',
        'dist_id',
        'localbody_name_en',
        'localbody_name_mal',
        'localbody_type_id',
        'is_active',
        'block_id',
        'type',
        'code',
        'population',
        'vulnerable_population'
    ];

    public function block()
    {
        return $this->belongsTo(PppBlock::class, 'block_id', 'block_id');
    }

    public function planSections()
    {
        return $this->morphMany(PlanSection::class, 'planable')->orderBy('section_order');
    }
}
