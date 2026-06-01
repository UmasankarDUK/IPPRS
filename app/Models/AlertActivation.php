<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class AlertActivation extends Model
{
    use HasUuid;

    protected $table = 'prep.alert_activation';
    protected $primaryKey = 'activation_id';

    protected $fillable = [
        'geo_level_type',
        'geo_level_id',
        'previous_level',
        'current_level',
        'activated_by_user_id',
        'reason',
        'activated_at'
    ];

    protected $casts = [
        'activated_at' => 'datetime',
    ];
}
