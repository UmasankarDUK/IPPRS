<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ContactDirectory extends Model
{
    use HasUuid;

    protected $table = 'org.contact_directory';
    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'person_id',
        'district_id',
        'block_id',
        'lsg_id',
        'ward_id',
        'contact_role',
        'phone_number',
        'alternative_phone',
        'email',
        'is_active'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }

    public function block()
    {
        return $this->belongsTo(Block::class, 'block_id', 'block_id');
    }

    public function localbody()
    {
        return $this->belongsTo(Localbody::class, 'lsg_id', 'lsg_id');
    }
}
