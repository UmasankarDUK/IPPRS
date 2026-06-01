<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class AssetTransaction extends Model
{
    use HasUuid;

    protected $table = 'inventory.asset_transaction';
    protected $primaryKey = 'txn_id';

    public $timestamps = false; // Transaction has default txn_datetime, no Eloquent updated_at

    protected $fillable = [
        'asset_id',
        'txn_datetime',
        'txn_type',
        'quantity',
        'from_facility_id',
        'to_facility_id',
        'remarks',
        'created_by_user_id'
    ];

    protected $casts = [
        'txn_datetime' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'asset_id');
    }

    public function fromFacility()
    {
        return $this->belongsTo(Facility::class, 'from_facility_id', 'facility_id');
    }

    public function toFacility()
    {
        return $this->belongsTo(Facility::class, 'to_facility_id', 'facility_id');
    }
}
