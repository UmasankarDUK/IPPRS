<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    use HasUuid;

    protected $table = 'inventory.asset_maintenance';
    protected $primaryKey = 'maintenance_id';

    protected $fillable = [
        'asset_id',
        'maintenance_type',
        'scheduled_date',
        'performed_date',
        'technician_name',
        'maintenance_status',
        'cost',
        'remarks'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'performed_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'asset_id');
    }
}
