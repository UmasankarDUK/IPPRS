<?php

namespace App\Http\Controllers;

use App\Models\EbolaDailyReport;
use App\Models\HealthInstitution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EbolaReportController extends Controller
{
    /**
     * Show the institution-based daily entry form.
     * Lists all MCHs; loads existing data if date already has entries.
     */
    public function create(Request $request)
    {
        $date = $request->input('report_date', today()->toDateString());

        $institutions = HealthInstitution::orderBy('name')->get();

        // Load any existing rows for this date, keyed by institution_id
        $existing = EbolaDailyReport::where('date_of_reporting', $date)
            ->get()
            ->keyBy('health_institution_id');

        return view('ebola.report.create', compact('date', 'institutions', 'existing'));
    }

    /**
     * Save/update all institution rows for a given date.
     * Uses upsert so re-submitting the same date just overwrites.
     */
    public function store(Request $request)
    {
        $date = $request->input('report_date');

        $request->validate([
            'report_date'  => 'required|date',
            'rows'         => 'required|array',
            'rows.*.health_institution_id' => 'required|exists:health_institutions,id',
        ]);

        DB::transaction(function () use ($request, $date) {
            foreach ($request->input('rows', []) as $row) {
                $institutionId = $row['health_institution_id'];

                // Skip completely blank rows
                $numericFields = array_diff_key($row, ['health_institution_id' => true]);
                $allZero = collect($numericFields)->every(fn($v) => (int)$v === 0);
                if ($allZero) continue;

                EbolaDailyReport::updateOrCreate(
                    [
                        'date_of_reporting'    => $date,
                        'health_institution_id' => $institutionId,
                    ],
                    [
                        'total_cases_reported'    => (int)($row['total_cases_reported'] ?? 0),
                        'home_quarantine'          => (int)($row['home_quarantine'] ?? 0),
                        'inst_quarantine'          => (int)($row['inst_quarantine'] ?? 0),
                        'isolation_no_o2'          => (int)($row['isolation_no_o2'] ?? 0),
                        'isolation_with_o2'        => (int)($row['isolation_with_o2'] ?? 0),
                        'icu_no_o2'                => (int)($row['icu_no_o2'] ?? 0),
                        'icu_with_o2'              => (int)($row['icu_with_o2'] ?? 0),
                        'icu_ventilator'           => (int)($row['icu_ventilator'] ?? 0),
                        'deaths_probable'          => (int)($row['deaths_probable'] ?? 0),
                        'tests_sent'               => (int)($row['tests_sent'] ?? 0),
                        'lab_confirmed'            => (int)($row['lab_confirmed'] ?? 0),
                        'positives_home'           => (int)($row['positives_home'] ?? 0),
                        'positives_inst'           => (int)($row['positives_inst'] ?? 0),
                        'positives_isolation'      => (int)($row['positives_isolation'] ?? 0),
                        'positives_icu_no_o2'      => (int)($row['positives_icu_no_o2'] ?? 0),
                        'positives_icu_with_o2'    => (int)($row['positives_icu_with_o2'] ?? 0),
                        'positives_icu_ventilator' => (int)($row['positives_icu_ventilator'] ?? 0),
                    ]
                );
            }
        });

        return redirect()->route('ebola.index', ['bulletin_date' => $date])
            ->with('success', "Daily report for {$date} saved successfully.");
    }

    /**
     * Edit a single institution's row for a given date.
     */
    public function edit(string $id)
    {
        $report      = EbolaDailyReport::with('healthInstitution')->findOrFail($id);
        $institutions = HealthInstitution::orderBy('name')->get();

        return view('ebola.report.edit', compact('report', 'institutions'));
    }

    /**
     * Update a single institution row.
     */
    public function update(Request $request, string $id)
    {
        $report = EbolaDailyReport::findOrFail($id);

        $validated = $request->validate([
            'date_of_reporting'       => 'required|date',
            'health_institution_id'   => 'required|exists:health_institutions,id',
            'total_cases_reported'    => 'required|integer|min:0',
            'home_quarantine'         => 'required|integer|min:0',
            'inst_quarantine'         => 'required|integer|min:0',
            'isolation_no_o2'         => 'required|integer|min:0',
            'isolation_with_o2'       => 'required|integer|min:0',
            'icu_no_o2'               => 'required|integer|min:0',
            'icu_with_o2'             => 'required|integer|min:0',
            'icu_ventilator'          => 'required|integer|min:0',
            'deaths_probable'         => 'required|integer|min:0',
            'tests_sent'              => 'required|integer|min:0',
            'lab_confirmed'           => 'required|integer|min:0',
            'positives_home'          => 'required|integer|min:0',
            'positives_inst'          => 'required|integer|min:0',
            'positives_isolation'     => 'required|integer|min:0',
            'positives_icu_no_o2'     => 'required|integer|min:0',
            'positives_icu_with_o2'   => 'required|integer|min:0',
            'positives_icu_ventilator'=> 'required|integer|min:0',
        ]);

        $report->update($validated);

        return redirect()->route('ebola.index', ['bulletin_date' => $report->date_of_reporting])
            ->with('success', "Report for {$report->healthInstitution->name} updated.");
    }

    /**
     * Delete a single institution report row.
     */
    public function destroy(string $id)
    {
        $report = EbolaDailyReport::findOrFail($id);
        $date   = $report->date_of_reporting;
        $report->delete();

        return redirect()->route('ebola.index', ['bulletin_date' => $date])
            ->with('success', 'Report entry deleted.');
    }
}
