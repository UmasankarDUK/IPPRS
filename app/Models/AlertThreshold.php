<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class AlertThreshold extends Model
{
    use HasUuid;

    protected $table = 'prep.alert_threshold';
    protected $primaryKey = 'threshold_id';

    protected $fillable = [
        'geo_level_type',
        'geo_level_id',
        'alert_level',
        'alert_stage',
        'trigger_metric',
        'trigger_value',
        'action_matrix',
        'is_active'
    ];

    protected $casts = [
        'trigger_value' => 'float',
    ];
}
