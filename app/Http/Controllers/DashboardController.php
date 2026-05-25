<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Block;
use App\Models\Localbody;
use App\Models\HealthInstitution;
use App\Models\InfrastructureConversion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the pandemic command center.
     */
    public function index(Request $request)
    {
        // Gather actual capacity from database
        $totalBeds = HealthInstitution::sum('capacity_beds');
        $totalIcu = HealthInstitution::sum('capacity_icu');
        $totalOxygenBeds = HealthInstitution::sum('capacity_oxygen_beds');
        $totalOxygenStorage = HealthInstitution::sum('oxygen_storage_liters');

        // Gather planned conversions
        $conversions = InfrastructureConversion::with('localbody')->get();
        $totalPotentialBeds = $conversions->sum('potential_beds');

        // Gather localbody stats for the GIS map
        $localbodies = Localbody::withCount('healthInstitutions')
            ->with('infrastructureConversions')
            ->get()
            ->map(function ($lb) {
                return [
                    'id' => $lb->id,
                    'name' => $lb->name,
                    'population' => $lb->population,
                    'vulnerable' => $lb->vulnerable_population,
                    'capacity_beds' => $lb->healthInstitutions->sum('capacity_beds'),
                    'potential_beds' => $lb->infrastructureConversions->sum('potential_beds'),
                ];
            });

        // Baseline active cases (e.g. 500 cases in the district during normal peak)
        $baselineActiveCases = 800;

        return view('dashboard', compact(
            'totalBeds', 
            'totalIcu', 
            'totalOxygenBeds', 
            'totalOxygenStorage', 
            'conversions',
            'totalPotentialBeds',
            'localbodies',
            'baselineActiveCases'
        ));
    }
}
