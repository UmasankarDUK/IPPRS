<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbolaDailyReport extends Model
{
    protected $table = 'ebola_daily_reports';

    protected $fillable = [
        'date_of_reporting',
        'health_institution_id',
        'total_cases_reported',
        'home_quarantine',
        'inst_quarantine',
        'isolation_no_o2',
        'isolation_with_o2',
        'icu_no_o2',
        'icu_with_o2',
        'icu_ventilator',
        'deaths_probable',
        'tests_sent',
        'lab_confirmed',
        'positives_home',
        'positives_inst',
        'positives_isolation',
        'positives_icu_no_o2',
        'positives_icu_with_o2',
        'positives_icu_ventilator',
    ];

    protected $casts = [
        'date_of_reporting' => 'date',
    ];

    // Computed: Total admissions (Col L = G+H+I+J+K)
    public function getTotalAdmissionsAttribute(): int
    {
        return $this->isolation_no_o2
            + $this->isolation_with_o2
            + $this->icu_no_o2
            + $this->icu_with_o2
            + $this->icu_ventilator;
    }

    // Computed: Total positives (Col V = P+Q+R+S+T+U)
    public function getTotalPositivesAttribute(): int
    {
        return $this->positives_home
            + $this->positives_inst
            + $this->positives_isolation
            + $this->positives_icu_no_o2
            + $this->positives_icu_with_o2
            + $this->positives_icu_ventilator;
    }

    public function healthInstitution(): BelongsTo
    {
        return $this->belongsTo(HealthInstitution::class);
    }
}
