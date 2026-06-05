<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Block;
use App\Models\Localbody;
use App\Models\HealthInstitution;
use App\Models\PlanSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PlanController extends Controller
{
    /**
     * Display a directory of all plans.
     */
    public function index(Request $request)
    {
        $districts = District::withCount('blocks')->orderBy('district_name_en')->get();
        
        // Block Filter: Filter blocks by District
        $selectedDistrictForBlock = $request->input('block_district_id');
        $blocksQuery = Block::with('district')->withCount('localbodies');
        if ($selectedDistrictForBlock) {
            $blocksQuery->where('distric_int_id', (int) $selectedDistrictForBlock);
        }
        $blocks = $blocksQuery->orderBy('block_name_en')->get();

        // Localbody Filter: Filter by District and Block using tbl_localbody_block_mapping
        $selectedDistrictForLb = $request->input('lb_district_id');
        $selectedBlockForLb = $request->input('lb_block_id');
        
        $localbodiesQuery = Localbody::with('blocks')->withCount('healthInstitutions');
        
        if ($selectedBlockForLb) {
            $localbodiesQuery->whereIn('localbody_id', function($query) use ($selectedBlockForLb) {
                $query->select('localbody_id')
                      ->from('geo.tbl_localbody_block_mapping')
                      ->where('block_id', (int) $selectedBlockForLb);
            });
        } elseif ($selectedDistrictForLb) {
            $localbodiesQuery->where('dist_id', (int) $selectedDistrictForLb);
        }
        $localbodies = $localbodiesQuery->orderBy('localbody_name_en')->get();
        
        // Dynamically get blocks for the localbody block dropdown based on chosen district
        $lbBlocks = collect();
        if ($selectedDistrictForLb) {
            $lbBlocks = Block::where('distric_int_id', (int) $selectedDistrictForLb)->orderBy('block_name_en')->get();
        } else {
            $lbBlocks = Block::orderBy('block_name_en')->get();
        }

        $institutions = HealthInstitution::with('localbody')->orderBy('name')->get();

        return view('plans.index', compact(
            'districts', 
            'blocks', 
            'localbodies', 
            'institutions',
            'selectedDistrictForBlock',
            'selectedDistrictForLb',
            'selectedBlockForLb',
            'lbBlocks'
        ));
    }

    /**
     * Resolve the planable entity by type and ID.
     */
    protected function getPlanable(string $type, $id)
    {
        switch (strtolower($type)) {
            case 'district':
                if (is_numeric($id)) {
                    return District::where('district_code', (int) $id)->firstOrFail();
                }
                return District::findOrFail($id);
            case 'block':
                if (is_numeric($id)) {
                    return Block::where('block_int_id', (int) $id)->firstOrFail();
                }
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
            'alternative' => 'Alternative Infrastructure',
            
            // Excel Study Modules Grouped
            'disease_trends' => [
                'title' => 'Disease Surveillance Trends',
                'submodules' => [
                    'study_disease_trend' => 'Block-Level Disease Trend',
                    'study_dengue_distribution' => 'Dengue LSGD Distribution',
                    'study_lepto_distribution' => 'Leptospirosis LSGD Distribution',
                    'study_hepa_distribution' => 'Hepatitis A LSGD Distribution',
                    'study_outcome_trend' => 'Outcome-Based Trend Analysis'
                ]
            ],
            'disease_categories' => [
                'title' => 'Disease Categorization',
                'submodules' => [
                    'study_transmission_trend' => 'Transmission Trends',
                    'study_vector_disease' => 'Vector-Borne Diseases',
                    'study_water_disease' => 'Waterborne Diseases',
                    'study_air_disease' => 'Airborne Diseases',
                    'study_blood_disease' => 'Blood-Borne Diseases',
                    'study_zoonotic_disease' => 'Zoonotic Diseases'
                ]
            ],
            'workforce_governance' => [
                'title' => 'Workforce & Governance',
                'submodules' => [
                    'study_committee_member' => 'One Health Committee Members',
                    'study_response_workforce' => 'Pandemic Response Workforce',
                    'study_screening_checkpoint' => 'Screening Checkpoints',
                    'study_control_room_team' => 'Key Control Room Team'
                ]
            ],
            'triggers_protocols' => [
                'title' => 'Triggers & Protocols',
                'submodules' => [
                    'study_warning_trigger' => 'Early Warning Triggers',
                    'study_communicator' => 'Key Communicators',
                    'study_reporting_schedule' => 'Reporting Schedule & Protocol'
                ]
            ],
            'resources_collaboration' => [
                'title' => 'Resources & Collaboration',
                'submodules' => [
                    'study_resource_inventory' => 'Resource Inventory Contacts',
                    'study_collaboration' => 'NGOs & CSR Collaboration',
                    'study_coordination' => 'Interdepartmental Coordination',
                    'study_facility_conversion' => 'Community Facilities Conversion'
                ]
            ],
        ];

        // Retrieve and append dynamic user sections
        $dynamicSections = $entity->planSections;
        foreach ($dynamicSections as $sec) {
            $modules['section_' . $sec->id] = $sec->title;
        }

        // Helper to check if a key exists anywhere in modules
        $isValid = false;
        $activeModuleTitle = '';
        
        if ($sectionId) {
            if (array_key_exists($sectionId, $modules) && !is_array($modules[$sectionId])) {
                $isValid = true;
                $activeModuleTitle = $modules[$sectionId];
            } else {
                foreach ($modules as $groupKey => $group) {
                    if (is_array($group) && isset($group['submodules']) && array_key_exists($sectionId, $group['submodules'])) {
                        $isValid = true;
                        $activeModuleTitle = $group['submodules'][$sectionId];
                        break;
                    }
                }
            }
        }

        if (!$isValid) {
            $sectionId = 'overview';
            $activeModuleTitle = 'Nutshell Overview';
        }
        $activeModule = $sectionId;
        
        // Data Collections
        $overviewStats = [];
        $subdivisionsList = collect();
        $healthcareList = collect();
        $alternativeList = collect();
        $activeSectionContent = null;
        $studyRecords = collect();

        // If it is a dynamic section
        if (str_starts_with($activeModule, 'section_')) {
            $activeSectionId = (int) substr($activeModule, 8);
            $activeSectionContent = $dynamicSections->firstWhere('id', $activeSectionId);
        }

        // If it is an Excel study module table
        if (str_starts_with($activeModule, 'study_')) {
            $query = \Illuminate\Support\Facades\DB::table($activeModule);
            
            if ($type === 'district') {
                $blockIntIds = \App\Models\Block::where('distric_int_id', $entity->district_code)->pluck('block_int_id');
                $query->whereIn('block_int_id', $blockIntIds);
            } elseif ($type === 'block') {
                $query->where('block_int_id', $entity->block_int_id);
            } elseif ($type === 'localbody') {
                $block = $entity->block;
                $blockIntId = $block ? $block->block_int_id : 0;
                $query->where('block_int_id', $blockIntId);
                
                // If table has 'lsgd' column, filter by GP name
                if (Schema::hasColumn($activeModule, 'lsgd')) {
                    $query->where('lsgd', 'like', '%' . $entity->name . '%');
                }
            } elseif ($type === 'institution') {
                $block = $entity->localbody ? $entity->localbody->block : null;
                $blockIntId = $block ? $block->block_int_id : 39;
                $query->where('block_int_id', $blockIntId);
            }
            $studyRecords = $query->get();
        }
        
        if ($type === 'district') {
            $distCode = $entity->district_code;

            // Nutshell Stats
            $overviewStats['total_blocks'] = \App\Models\Block::where('distric_int_id', $distCode)->count();
            $overviewStats['total_localbodies'] = \App\Models\Localbody::where('dist_id', $distCode)->count();
            $overviewStats['total_institutions'] = \App\Models\HealthInstitution::whereHas('localbody', function($q) use ($distCode) {
                $q->where('dist_id', $distCode);
            })->count();
            
            $institutions = \App\Models\HealthInstitution::whereHas('localbody', function($q) use ($distCode) {
                $q->where('dist_id', $distCode);
            })->get();
            $overviewStats['total_beds'] = $institutions->sum('capacity_beds');
            $overviewStats['total_icu'] = $institutions->sum('capacity_icu');
            $overviewStats['total_oxygen_beds'] = $institutions->sum('capacity_oxygen_beds');
            $overviewStats['total_oxygen_storage'] = $institutions->sum('oxygen_storage_liters');

            // Module Data
            if ($activeModule === 'subdivisions') {
                $subdivisionsList = \App\Models\Block::where('distric_int_id', $distCode)
                    ->with('localbodies')
                    ->withSum('localbodies as total_population', 'population')
                    ->get();
            }
            if ($activeModule === 'healthcare') {
                $healthcareList = \App\Models\HealthInstitution::whereHas('localbody', function($q) use ($distCode) {
                    $q->where('dist_id', $distCode);
                })->with('localbody')->get();
            }
            if ($activeModule === 'alternative') {
                $alternativeList = \App\Models\InfrastructureConversion::whereHas('localbody', function($q) use ($distCode) {
                    $q->where('dist_id', $distCode);
                })->with('localbody')->get();
            }
        } elseif ($type === 'block') {
            $overviewStats['total_localbodies'] = \App\Models\Localbody::whereHas('blocks', function($q) use ($id) {
                $q->where('block_int_id', (int) $id);
            })->count();
            $overviewStats['total_institutions'] = \App\Models\HealthInstitution::whereHas('localbody.blocks', function($q) use ($id) {
                $q->where('block_int_id', (int) $id);
            })->count();
            
            $institutions = \App\Models\HealthInstitution::whereHas('localbody.blocks', function($q) use ($id) {
                $q->where('block_int_id', (int) $id);
            })->get();
            
            $overviewStats['total_beds'] = $institutions->sum('capacity_beds');
            $overviewStats['total_icu'] = $institutions->sum('capacity_icu');
            $overviewStats['total_oxygen_beds'] = $institutions->sum('capacity_oxygen_beds');
            $overviewStats['total_oxygen_storage'] = $institutions->sum('oxygen_storage_liters');
            
            if ($activeModule === 'subdivisions') {
                $subdivisionsList = \App\Models\Localbody::whereHas('blocks', function($q) use ($id) {
                    $q->where('block_int_id', (int) $id);
                })->get();
            }
            if ($activeModule === 'healthcare') {
                $healthcareList = \App\Models\HealthInstitution::whereHas('localbody.blocks', function($q) use ($id) {
                    $q->where('block_int_id', (int) $id);
                })->with('localbody')->get();
            }
            if ($activeModule === 'alternative') {
                $alternativeList = \App\Models\InfrastructureConversion::whereHas('localbody.blocks', function($q) use ($id) {
                    $q->where('block_int_id', (int) $id);
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

        return view('plans.show', compact(
            'entity', 'type', 'modules', 'activeModule', 'activeModuleTitle', 
            'overviewStats', 'subdivisionsList', 'healthcareList', 'alternativeList', 
            'activeSectionContent', 'studyRecords'
        ));
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
