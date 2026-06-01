<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PlanRevision extends Model
{
    use HasUuid;

    protected $table = 'prep.plan_revision';
    protected $primaryKey = 'revision_id';

    protected $fillable = [
        'plan_document_id',
        'revision_no',
        'approved_by_person_id',
        'approved_at',
        'revision_reason',
        'is_current'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function planDocument()
    {
        return $this->belongsTo(PlanSection::class, 'plan_document_id', 'plan_document_id'); // maps to document
    }
}
