<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Block;
use App\Models\Localbody;
use App\Models\HealthInstitution;
use App\Models\PlanSection;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a directory of all plans.
     */
    public function index()
    {
        $districts = District::where('state_id', 12)->withCount('blocks')->get();
        $blocks = Block::with('district')->withCount('localbodies')->get();
        $localbodies = Localbody::with('block')->withCount('healthInstitutions')->get();
        $institutions = HealthInstitution::with('localbody')->get();

        return view('plans.index', compact('districts', 'blocks', 'localbodies', 'institutions'));
    }

    /**
     * Resolve the planable entity by type and ID.
     */
    protected function getPlanable(string $type, $id)
    {
        switch (strtolower($type)) {
            case 'district':
                return District::findOrFail($id);
            case 'block':
                return Block::findOrFail($id);
            case 'localbody':
                return Localbody::findOrFail($id);
            case 'institution':
                return HealthInstitution::findOrFail($id);
            default:
                abort(404, 'Invalid plan category');
        }
    }

    /**
     * Show the detailed plan in GitBook style.
     */
    public function show(string $type, $id, $sectionId = null)
    {
        $entity = $this->getPlanable($type, $id);
        
        $modules = [
            'overview' => 'Nutshell Overview',
            'demographics' => 'Geographic & Demographics',
            'subdivisions' => 'Administrative Subdivisions',
            'healthcare' => 'Healthcare Infrastructure',
            'alternative' => 'Alternative Infrastructure'
        ];

        // Default to overview if no sectionId or invalid sectionId
        if (!$sectionId || !array_key_exists($sectionId, $modules)) {
            $sectionId = 'overview';
        }
        $activeModule = $sectionId;
        
        // Data Collections
        $overviewStats = [];
        $subdivisionsList = collect();
        $healthcareList = collect();
        $alternativeList = collect();
        
        if ($type === 'district') {
            // Nutshell Stats
            $overviewStats['total_blocks'] = \App\Models\Block::where('district_id', $id)->count();
            $overviewStats['total_localbodies'] = \App\Models\Localbody::whereHas('block', function($q) use ($id) {
                $q->where('district_id', $id);
            })->count();
            $overviewStats['total_institutions'] = \App\Models\HealthInstitution::whereHas('localbody.block', function($q) use ($id) {
                $q->where('district_id', $id);
            })->count();
            
            $institutions = \App\Models\HealthInstitution::whereHas('localbody.block', function($q) use ($id) {
                $q->where('district_id', $id);
            })->get();
            $overviewStats['total_beds'] = $institutions->sum('capacity_beds');
            $overviewStats['total_icu'] = $institutions->sum('capacity_icu');
            $overviewStats['total_oxygen_beds'] = $institutions->sum('capacity_oxygen_beds');
            $overviewStats['total_oxygen_storage'] = $institutions->sum('oxygen_storage_liters');

            // Module Data
            if ($activeModule === 'subdivisions') {
                $subdivisionsList = \App\Models\Block::where('district_id', $id)
                    ->with('localbodies')
                    ->withSum('localbodies as total_population', 'population')
                    ->get();
            }
            if ($activeModule === 'healthcare') {
                $healthcareList = \App\Models\HealthInstitution::whereHas('localbody.block', function($q) use ($id) {
                    $q->where('district_id', $id);
                })->with('localbody')->get();
            }
            if ($activeModule === 'alternative') {
                $alternativeList = \App\Models\InfrastructureConversion::whereHas('localbody.block', function($q) use ($id) {
                    $q->where('district_id', $id);
                })->with('localbody')->get();
            }
        } elseif ($type === 'block') {
            $overviewStats['total_localbodies'] = \App\Models\Localbody::where('block_id', $id)->count();
            $overviewStats['total_institutions'] = \App\Models\HealthInstitution::whereHas('localbody', function($q) use ($id) {
                $q->where('block_id', $id);
            })->count();
            
            $institutions = \App\Models\HealthInstitution::whereHas('localbody', function($q) use ($id) {
                $q->where('block_id', $id);
            })->get();
            
            $overviewStats['total_beds'] = $institutions->sum('capacity_beds');
            $overviewStats['total_icu'] = $institutions->sum('capacity_icu');
            $overviewStats['total_oxygen_beds'] = $institutions->sum('capacity_oxygen_beds');
            $overviewStats['total_oxygen_storage'] = $institutions->sum('oxygen_storage_liters');
            
            if ($activeModule === 'subdivisions') {
                $subdivisionsList = \App\Models\Localbody::where('block_id', $id)->get();
            }
            if ($activeModule === 'healthcare') {
                $healthcareList = \App\Models\HealthInstitution::whereHas('localbody', function($q) use ($id) {
                    $q->where('block_id', $id);
                })->with('localbody')->get();
            }
            if ($activeModule === 'alternative') {
                $alternativeList = \App\Models\InfrastructureConversion::whereHas('localbody', function($q) use ($id) {
                    $q->where('block_id', $id);
                })->with('localbody')->get();
            }
        } elseif ($type === 'localbody') {
            $overviewStats['total_institutions'] = \App\Models\HealthInstitution::where('localbody_id', $id)->count();
            $institutions = \App\Models\HealthInstitution::where('localbody_id', $id)->get();
            
            $overviewStats['total_beds'] = $institutions->sum('capacity_beds');
            $overviewStats['total_icu'] = $institutions->sum('capacity_icu');
            $overviewStats['total_oxygen_beds'] = $institutions->sum('capacity_oxygen_beds');
            $overviewStats['total_oxygen_storage'] = $institutions->sum('oxygen_storage_liters');
            
            if ($activeModule === 'healthcare') {
                $healthcareList = \App\Models\HealthInstitution::where('localbody_id', $id)->get();
            }
            if ($activeModule === 'alternative') {
                $alternativeList = \App\Models\InfrastructureConversion::where('localbody_id', $id)->get();
            }
        } elseif ($type === 'institution') {
            $overviewStats['total_beds'] = $entity->capacity_beds;
            $overviewStats['total_icu'] = $entity->capacity_icu;
            $overviewStats['total_oxygen_beds'] = $entity->capacity_oxygen_beds;
            $overviewStats['total_oxygen_storage'] = $entity->oxygen_storage_liters;
        }

        return view('plans.show', compact('entity', 'type', 'modules', 'activeModule', 'overviewStats', 'subdivisionsList', 'healthcareList', 'alternativeList'));
    }

    /**
     * Form to create a new section.
     */
    public function createSection(string $type, $id)
    {
        $entity = $this->getPlanable($type, $id);
        return view('plans.create_section', compact('entity', 'type'));
    }

    /**
     * Store a newly created section.
     */
    public function storeSection(Request $request, string $type, $id)
    {
        $entity = $this->getPlanable($type, $id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $maxOrder = $entity->planSections()->max('section_order') ?? 0;

        $section = $entity->planSections()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'section_order' => $maxOrder + 1,
        ]);

        return redirect()->route('plans.show', ['type' => $type, 'id' => $id, 'sectionId' => $section->id])
                         ->with('success', 'Plan chapter added successfully!');
    }

    /**
     * Form to edit an existing section.
     */
    public function editSection($sectionId)
    {
        $section = PlanSection::findOrFail($sectionId);
        
        // Determine type and entity
        $entity = $section->planable;
        $type = strtolower(class_basename($entity));
        if ($type === 'healthinstitution') {
            $type = 'institution';
        }

        return view('plans.edit_section', compact('section', 'entity', 'type'));
    }

    /**
     * Update an existing section.
     */
    public function updateSection(Request $request, $sectionId)
    {
        $section = PlanSection::findOrFail($sectionId);
        $entity = $section->planable;
        $type = strtolower(class_basename($entity));
        if ($type === 'healthinstitution') {
            $type = 'institution';
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $section->update($validated);

        return redirect()->route('plans.show', ['type' => $type, 'id' => $entity->id, 'sectionId' => $section->id])
                         ->with('success', 'Plan chapter updated successfully!');
    }

    /**
     * Delete an existing section.
     */
    public function destroySection($sectionId)
    {
        $section = PlanSection::findOrFail($sectionId);
        $entity = $section->planable;
        $type = strtolower(class_basename($entity));
        if ($type === 'healthinstitution') {
            $type = 'institution';
        }
        
        $section->delete();

        // Reorder remaining sections
        $sections = $entity->planSections()->orderBy('section_order')->get();
        foreach ($sections as $index => $s) {
            $s->update(['section_order' => $index]);
        }

        return redirect()->route('plans.show', ['type' => $type, 'id' => $entity->id])
                         ->with('success', 'Plan chapter deleted successfully!');
    }
}
