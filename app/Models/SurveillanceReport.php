<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SurveillanceReport extends Model
{
    use HasUuid;

    protected $table = 'surv.surveillance_report';
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'reporting_unit_type',
        'reporting_unit_id',
        'report_frequency_id',
        'report_source_id',
        'report_date',
        'period_start',
        'period_end',
        'submitted_by_user_id',
        'approved_by_user_id',
        'approval_status_id',
        'submission_status_id',
        'remarks'
    ];

    protected $casts = [
        'report_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function cases()
    {
        return $this->hasMany(SurveillanceCase::class, 'report_id', 'report_id');
    }
}
