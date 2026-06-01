<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasUuid;

    protected $table = 'inventory.asset';
    protected $primaryKey = 'asset_id';

    protected $fillable = [
        'facility_id',
        'asset_name',
        'asset_type',
        'model_number',
        'serial_number',
        'status',
        'critical_threshold',
        'is_active'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    public function transactions()
    {
        return $this->hasMany(AssetTransaction::class, 'asset_id', 'asset_id');
    }

    public function maintenances()
    {
        return $this->hasMany(AssetMaintenance::class, 'asset_id', 'asset_id');
    }
}
