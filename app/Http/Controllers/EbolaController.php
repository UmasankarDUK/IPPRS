<?php

namespace App\Http\Controllers;

use App\Models\HealthInstitution;
use App\Models\EbolaCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EbolaController extends Controller
{
    /**
     * Display the Ebola Command Center & Trend Visualizations.
     */
    public function index(Request $request)
    {
        // 1. Determine the latest reporting date to compile today's stats
        $latestDate = EbolaCase::max('date_of_reporting');

        // Initializing variables
        $dailyCases = 0;
        $dailyHomeQuarantine = 0;
        $dailyInstQuarantine = 0;
        $dailyIsolationNoO2 = 0;
        $dailyIsolationWithO2 = 0;
        $dailyIcuNoO2 = 0;
        $dailyIcuWithO2 = 0;
        $dailyIcuVentilator = 0;
        $dailyProbableAdmissions = 0;
        $dailyTestsSent = 0;
        $dailyConfirmed = 0;
        $dailyPositivesAdmissions = 0;

        if ($latestDate) {
            $latestCases = EbolaCase::where('date_of_reporting', $latestDate)->get();
            
            $dailyCases = $latestCases->count();
            $dailyHomeQuarantine = $latestCases->where('status', 'Suspect')->where('quarantine_type', 'Home Quarantine')->count();
            $dailyInstQuarantine = $latestCases->where('status', 'Probable')->where('quarantine_type', 'Institutional Quarantine')->count();
            $dailyIsolationNoO2 = $latestCases->where('status', 'Probable')->where('quarantine_type', 'Isolation (No O2)')->count();
            $dailyIsolationWithO2 = $latestCases->where('status', 'Probable')->where('quarantine_type', 'Isolation (With O2)')->count();
            $dailyIcuNoO2 = $latestCases->where('status', 'Probable')->where('quarantine_type', 'ICU (No O2)')->count();
            $dailyIcuWithO2 = $latestCases->where('status', 'Probable')->where('quarantine_type', 'ICU (With O2)')->count();
            $dailyIcuVentilator = $latestCases->where('status', 'Probable')->where('quarantine_type', 'ICU (Ventilator)')->count();
            
            $dailyProbableAdmissions = $dailyIsolationNoO2 + $dailyIsolationWithO2 + $dailyIcuNoO2 + $dailyIcuWithO2 + $dailyIcuVentilator;
            $dailyTestsSent = $latestCases->where('test_status', 'Sent for Test')->count();
            $dailyConfirmed = $latestCases->where('status', 'Confirmed')->count();
            
            $dailyPositivesAdmissions = $latestCases->where('test_status', 'Positive')
                ->whereIn('quarantine_type', ['Isolation (No O2)', 'Isolation (With O2)', 'ICU (No O2)', 'ICU (With O2)', 'ICU (Ventilator)'])
                ->count();
        }

        // 2. Calculate Cumulative Totals
        $allCases = EbolaCase::all();
        
        $cumulativeCases = $allCases->count();
        $cumulativeHomeQuarantine = $allCases->where('status', 'Suspect')->where('quarantine_type', 'Home Quarantine')->count();
        $cumulativeInstQuarantine = $allCases->where('status', 'Probable')->where('quarantine_type', 'Institutional Quarantine')->count();
        $cumulativeIsolationNoO2 = $allCases->where('status', 'Probable')->where('quarantine_type', 'Isolation (No O2)')->count();
        $cumulativeIsolationWithO2 = $allCases->where('status', 'Probable')->where('quarantine_type', 'Isolation (With O2)')->count();
        $cumulativeIcuNoO2 = $allCases->where('status', 'Probable')->where('quarantine_type', 'ICU (No O2)')->count();
        $cumulativeIcuWithO2 = $allCases->where('status', 'Probable')->where('quarantine_type', 'ICU (With O2)')->count();
        $cumulativeIcuVentilator = $allCases->where('status', 'Probable')->where('quarantine_type', 'ICU (Ventilator)')->count();
        
        $cumulativeProbableAdmissions = $cumulativeIsolationNoO2 + $cumulativeIsolationWithO2 + $cumulativeIcuNoO2 + $cumulativeIcuWithO2 + $cumulativeIcuVentilator;
        $cumulativeTestsSent = $allCases->where('test_status', 'Sent for Test')->count();
        $cumulativeConfirmed = $allCases->where('status', 'Confirmed')->count();

        $cumulativePositivesHome = $allCases->where('test_status', 'Positive')->where('quarantine_type', 'Home Quarantine')->count();
        $cumulativePositivesInst = $allCases->where('test_status', 'Positive')->where('quarantine_type', 'Institutional Quarantine')->count();
        
        $cumulativePositivesIsolation = $allCases->where('test_status', 'Positive')
            ->whereIn('quarantine_type', ['Isolation (No O2)', 'Isolation (With O2)'])
            ->count();
            
        $cumulativePositivesIcuNoO2 = $allCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (No O2)')->count();
        $cumulativePositivesIcuWithO2 = $allCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (With O2)')->count();
        $cumulativePositivesIcuVentilator = $allCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (Ventilator)')->count();
        
        $cumulativePositivesAdmissions = $cumulativePositivesIsolation + $cumulativePositivesIcuNoO2 + $cumulativePositivesIcuWithO2 + $cumulativePositivesIcuVentilator;

        // 3. Dynamic Chart Data (grouped by date)
        $chartData = EbolaCase::select(
                'date_of_reporting',
                DB::raw('COUNT(*) as total_cases'),
                DB::raw("SUM(CASE WHEN test_status = 'Sent for Test' THEN 1 ELSE 0 END) as tests_sent"),
                DB::raw("SUM(CASE WHEN status = 'Confirmed' THEN 1 ELSE 0 END) as confirmed"),
                DB::raw("SUM(CASE WHEN status = 'Probable' AND quarantine_type IN ('Isolation (No O2)', 'Isolation (With O2)', 'ICU (No O2)', 'ICU (With O2)', 'ICU (Ventilator)') THEN 1 ELSE 0 END) as total_admissions")
            )
            ->groupBy('date_of_reporting')
            ->orderBy('date_of_reporting', 'asc')
            ->get();

        // 4. Excel Daily Bulletin Grid compiler
        $bulletinDate = $request->input('bulletin_date', $latestDate ?? date('Y-m-d'));
        $mchs = HealthInstitution::where('type', 'Medical College Hospital')->get();
        
        $bulletinRows = [];
        foreach ($mchs as $mch) {
            $mchCases = EbolaCase::where('date_of_reporting', $bulletinDate)
                ->where('health_institution_id', $mch->id)
                ->get();
                
            $bulletinRows[] = (object)[
                'institution' => $mch,
                'cases_reported' => $mchCases->count(),
                'home_quarantine' => $mchCases->where('status', 'Suspect')->where('quarantine_type', 'Home Quarantine')->count(),
                'inst_quarantine' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'Institutional Quarantine')->count(),
                'isolation_no_o2' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'Isolation (No O2)')->count(),
                'isolation_with_o2' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'Isolation (With O2)')->count(),
                'icu_no_o2' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'ICU (No O2)')->count(),
                'icu_with_o2' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'ICU (With O2)')->count(),
                'icu_ventilator' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'ICU (Ventilator)')->count(),
                'tests_sent' => $mchCases->where('test_status', 'Sent for Test')->count(),
                'confirmed' => $mchCases->where('status', 'Confirmed')->count(),
                'positives_home' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'Home Quarantine')->count(),
                'positives_inst' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'Institutional Quarantine')->count(),
                'positives_isolation' => $mchCases->where('test_status', 'Positive')->whereIn('quarantine_type', ['Isolation (No O2)', 'Isolation (With O2)'])->count(),
                'positives_icu_no_o2' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (No O2)')->count(),
                'positives_icu_with_o2' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (With O2)')->count(),
                'positives_icu_ventilator' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (Ventilator)')->count(),
            ];
        }

        $bulletinTotals = (object)[
            'cases_reported' => collect($bulletinRows)->sum('cases_reported'),
            'home_quarantine' => collect($bulletinRows)->sum('home_quarantine'),
            'inst_quarantine' => collect($bulletinRows)->sum('inst_quarantine'),
            'isolation_no_o2' => collect($bulletinRows)->sum('isolation_no_o2'),
            'isolation_with_o2' => collect($bulletinRows)->sum('isolation_with_o2'),
            'icu_no_o2' => collect($bulletinRows)->sum('icu_no_o2'),
            'icu_with_o2' => collect($bulletinRows)->sum('icu_with_o2'),
            'icu_ventilator' => collect($bulletinRows)->sum('icu_ventilator'),
            'tests_sent' => collect($bulletinRows)->sum('tests_sent'),
            'confirmed' => collect($bulletinRows)->sum('confirmed'),
            'positives_home' => collect($bulletinRows)->sum('positives_home'),
            'positives_inst' => collect($bulletinRows)->sum('positives_inst'),
            'positives_isolation' => collect($bulletinRows)->sum('positives_isolation'),
            'positives_icu_no_o2' => collect($bulletinRows)->sum('positives_icu_no_o2'),
            'positives_icu_with_o2' => collect($bulletinRows)->sum('positives_icu_with_o2'),
            'positives_icu_ventilator' => collect($bulletinRows)->sum('positives_icu_ventilator'),
        ];

        // 5. Case Log search & filter
        $query = EbolaCase::with('healthInstitution')->orderBy('date_of_reporting', 'desc')->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('quarantine_type', 'like', "%{$search}%")
                  ->orWhere('test_status', 'like', "%{$search}%")
                  ->orWhere('outcome', 'like', "%{$search}%")
                  ->orWhereHas('healthInstitution', function($instQ) use ($search) {
                      $instQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('quarantine_type')) {
            $query->where('quarantine_type', $request->input('quarantine_type'));
        }

        $cases = $query->paginate(15)->withQueryString();

        return view('ebola.index', compact(
            'latestDate',
            'dailyCases',
            'dailyHomeQuarantine',
            'dailyInstQuarantine',
            'dailyIsolationNoO2',
            'dailyIsolationWithO2',
            'dailyIcuNoO2',
            'dailyIcuWithO2',
            'dailyIcuVentilator',
            'dailyProbableAdmissions',
            'dailyTestsSent',
            'dailyConfirmed',
            'dailyPositivesAdmissions',
            'cumulativeCases',
            'cumulativeHomeQuarantine',
            'cumulativeInstQuarantine',
            'cumulativeProbableAdmissions',
            'cumulativeIsolationNoO2',
            'cumulativeIsolationWithO2',
            'cumulativeIcuNoO2',
            'cumulativeIcuWithO2',
            'cumulativeIcuVentilator',
            'cumulativeTestsSent',
            'cumulativeConfirmed',
            'cumulativePositivesHome',
            'cumulativePositivesInst',
            'cumulativePositivesAdmissions',
            'chartData',
            'cases',
            'bulletinDate',
            'bulletinRows',
            'bulletinTotals'
        ));
    }

    /**
     * Renders a form to register an individual patient case.
     */
    public function create(Request $request)
    {
        $mchs = HealthInstitution::where('type', 'Medical College Hospital')->get();
        return view('ebola.create', compact('mchs'));
    }

    /**
     * Store a new individual patient case.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:120',
            'gender' => 'required|string|in:Male,Female,Other',
            'health_institution_id' => 'required|exists:health_institutions,id',
            'status' => 'required|string|in:Suspect,Probable,Confirmed',
            'quarantine_type' => 'required|string|in:Home Quarantine,Institutional Quarantine,Isolation (No O2),Isolation (With O2),ICU (No O2),ICU (With O2),ICU (Ventilator)',
            'test_status' => 'required|string|in:Not Tested,Sent for Test,Positive,Negative',
            'outcome' => 'required|string|in:Active,Recovered,Deceased',
            'date_of_reporting' => 'required|date',
        ]);

        EbolaCase::create($validated);

        return redirect()->route('ebola.index')
            ->with('success', "Individual case file for {$validated['patient_name']} has been logged successfully.");
    }

    /**
     * Renders the edit patient profile & status form.
     */
    public function edit($id)
    {
        $case = EbolaCase::findOrFail($id);
        $mchs = HealthInstitution::where('type', 'Medical College Hospital')->get();
        return view('ebola.edit', compact('case', 'mchs'));
    }

    /**
     * Update an individual patient case file.
     */
    public function update(Request $request, $id)
    {
        $case = EbolaCase::findOrFail($id);

        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:120',
            'gender' => 'required|string|in:Male,Female,Other',
            'health_institution_id' => 'required|exists:health_institutions,id',
            'status' => 'required|string|in:Suspect,Probable,Confirmed',
            'quarantine_type' => 'required|string|in:Home Quarantine,Institutional Quarantine,Isolation (No O2),Isolation (With O2),ICU (No O2),ICU (With O2),ICU (Ventilator)',
            'test_status' => 'required|string|in:Not Tested,Sent for Test,Positive,Negative',
            'outcome' => 'required|string|in:Active,Recovered,Deceased',
            'date_of_reporting' => 'required|date',
        ]);

        $case->update($validated);

        return redirect()->route('ebola.index')
            ->with('success', "Case file for {$validated['patient_name']} updated successfully.");
    }

    /**
     * Delete a patient case record.
     */
    public function destroy($id)
    {
        $case = EbolaCase::findOrFail($id);
        $name = $case->patient_name;
        $case->delete();

        return redirect()->route('ebola.index')
            ->with('success', "Case file for {$name} deleted successfully.");
    }
    /**
     * Download CSV for daily or cumulative bulletin.
     */
    public function downloadBulletinCsv(Request $request)
    {
        $date = $request->query('bulletin_date', now()->format('Y-m-d'));
        $type = $request->query('type', 'daily');

        $rows = $type === 'cumulative' ? $this->getCumulativeRows($date) : $this->getBulletinRows($date);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=ebola_{$type}_{$date}.csv",
        ];

        $callback = function() use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'MCH / Hospital','Cases Reported','Home Quarantine','Inst. Quarantine',
                'Iso (no O2)','Iso (O2)','ICU (no O2)','ICU (O2)','ICU (Vent)',
                'Tests Sent','Confirmed','Positives Home','Positives Inst','Positives Isolation',
                'Positives ICU (no O2)','Positives ICU (O2)','Positives ICU (Vent)'
            ]);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->institution_name ?? $row->institution->name ?? '',
                    $row->cases_reported,
                    $row->home_quarantine,
                    $row->inst_quarantine,
                    $row->isolation_no_o2,
                    $row->isolation_with_o2,
                    $row->icu_no_o2,
                    $row->icu_with_o2,
                    $row->icu_ventilator,
                    $row->tests_sent,
                    $row->confirmed,
                    $row->positives_home,
                    $row->positives_inst,
                    $row->positives_isolation,
                    $row->positives_icu_no_o2,
                    $row->positives_icu_with_o2,
                    $row->positives_icu_ventilator,
                ]);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Get daily bulletin rows for a given date.
     */
    private function getBulletinRows(string $date)
    {
        $mchs = HealthInstitution::where('type', 'Medical College Hospital')->get();
        $rows = [];

        foreach ($mchs as $mch) {
            $mchCases = EbolaCase::where('date_of_reporting', $date)
                ->where('health_institution_id', $mch->id)
                ->get();

            $rows[] = (object)[
                'institution_name' => $mch->name,
                'cases_reported' => $mchCases->count(),
                'home_quarantine' => $mchCases->where('status', 'Suspect')->where('quarantine_type', 'Home Quarantine')->count(),
                'inst_quarantine' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'Institutional Quarantine')->count(),
                'isolation_no_o2' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'Isolation (No O2)')->count(),
                'isolation_with_o2' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'Isolation (With O2)')->count(),
                'icu_no_o2' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'ICU (No O2)')->count(),
                'icu_with_o2' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'ICU (With O2)')->count(),
                'icu_ventilator' => $mchCases->where('status', 'Probable')->where('quarantine_type', 'ICU (Ventilator)')->count(),
                'tests_sent' => $mchCases->where('test_status', 'Sent for Test')->count(),
                'confirmed' => $mchCases->where('status', 'Confirmed')->count(),
                'positives_home' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'Home Quarantine')->count(),
                'positives_inst' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'Institutional Quarantine')->count(),
                'positives_isolation' => $mchCases->where('test_status', 'Positive')->whereIn('quarantine_type', ['Isolation (No O2)', 'Isolation (With O2)'])->count(),
                'positives_icu_no_o2' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (No O2)')->count(),
                'positives_icu_with_o2' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (With O2)')->count(),
                'positives_icu_ventilator' => $mchCases->where('test_status', 'Positive')->where('quarantine_type', 'ICU (Ventilator)')->count(),
            ];
        }

        return $rows;
    }

    /**
     * Get cumulative rows up to a given date.
     */
    private function getCumulativeRows(string $date)
    {
        return DB::table('ebola_cases')
            ->join('health_institutions', 'ebola_cases.health_institution_id', '=', 'health_institutions.id')
            ->whereDate('ebola_cases.date_of_reporting', '<=', $date)
            ->groupBy('health_institutions.id')
            ->select(
                'health_institutions.name as institution_name',
                DB::raw('SUM(cases_reported) as cases_reported'),
                DB::raw('SUM(home_quarantine) as home_quarantine'),
                DB::raw('SUM(inst_quarantine) as inst_quarantine'),
                DB::raw('SUM(isolation_no_o2) as isolation_no_o2'),
                DB::raw('SUM(isolation_with_o2) as isolation_with_o2'),
                DB::raw('SUM(icu_no_o2) as icu_no_o2'),
                DB::raw('SUM(icu_with_o2) as icu_with_o2'),
                DB::raw('SUM(icu_ventilator) as icu_ventilator'),
                DB::raw('SUM(tests_sent) as tests_sent'),
                DB::raw('SUM(confirmed) as confirmed'),
                DB::raw('SUM(positives_home) as positives_home'),
                DB::raw('SUM(positives_inst) as positives_inst'),
                DB::raw('SUM(positives_isolation) as positives_isolation'),
                DB::raw('SUM(positives_icu_no_o2) as positives_icu_no_o2'),
                DB::raw('SUM(positives_icu_with_o2) as positives_icu_with_o2'),
                DB::raw('SUM(positives_icu_ventilator) as positives_icu_ventilator')
            )
            ->get();
    }
}

