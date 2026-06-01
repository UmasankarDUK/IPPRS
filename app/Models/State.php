<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasUuid;

    protected $table = 'geo.master_state';
    protected $primaryKey = 'state_id';

    protected $fillable = [
        'state_code',
        'state_name_en',
        'state_name_ml',
        'country_name_en',
        'is_active'
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'state_id', 'state_id');
    }
}
