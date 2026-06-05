<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StudyCrudController extends Controller
{
    /**
     * Resolve the Model class based on table parameter.
     */
    protected function getModelClass(string $table)
    {
        $map = [
            'study_disease_trend' => \App\Models\StudyDiseaseTrend::class,
            'study_dengue_distribution' => \App\Models\StudyDengueDistribution::class,
            'study_lepto_distribution' => \App\Models\StudyLeptoDistribution::class,
            'study_hepa_distribution' => \App\Models\StudyHepaDistribution::class,
            'study_outcome_trend' => \App\Models\StudyOutcomeTrend::class,
            'study_transmission_trend' => \App\Models\StudyTransmissionTrend::class,
            'study_vector_disease' => \App\Models\StudyVectorDisease::class,
            'study_water_disease' => \App\Models\StudyWaterDisease::class,
            'study_air_disease' => \App\Models\StudyAirDisease::class,
            'study_blood_disease' => \App\Models\StudyBloodDisease::class,
            'study_zoonotic_disease' => \App\Models\StudyZoonoticDisease::class,
            'study_committee_member' => \App\Models\StudyCommitteeMember::class,
            'study_response_workforce' => \App\Models\StudyResponseWorkforce::class,
            'study_screening_checkpoint' => \App\Models\StudyScreeningCheckpoint::class,
            'study_control_room_team' => \App\Models\StudyControlRoomTeam::class,
            'study_warning_trigger' => \App\Models\StudyWarningTrigger::class,
            'study_communicator' => \App\Models\StudyCommunicator::class,
            'study_reporting_schedule' => \App\Models\StudyReportingSchedule::class,
            'study_resource_inventory' => \App\Models\StudyResourceInventory::class,
            'study_collaboration' => \App\Models\StudyCollaboration::class,
            'study_coordination' => \App\Models\StudyCoordination::class,
            'study_facility_conversion' => \App\Models\StudyFacilityConversion::class,
        ];

        return $map[$table] ?? null;
    }

    /**
     * Get schema description and fields for forms.
     */
    protected function getTableSchema(string $table)
    {
        // 1. LSGD Distributions
        if (in_array($table, ['study_dengue_distribution', 'study_lepto_distribution', 'study_hepa_distribution'])) {
            $nameMap = [
                'study_dengue_distribution' => 'Dengue Distribution',
                'study_lepto_distribution' => 'Leptospirosis Distribution',
                'study_hepa_distribution' => 'Hepatitis A Distribution'
            ];
            return [
                'title' => $nameMap[$table],
                'fields' => [
                    'lsgd' => ['type' => 'text', 'label' => 'LSGD / GP Name', 'rules' => 'required|string|max:255'],
                    'y2023' => ['type' => 'number', 'label' => 'Cases 2023', 'rules' => 'required|integer|min:0'],
                    'y2024' => ['type' => 'number', 'label' => 'Cases 2024', 'rules' => 'required|integer|min:0'],
                    'y2025' => ['type' => 'number', 'label' => 'Cases 2025', 'rules' => 'required|integer|min:0'],
                    'total' => ['type' => 'number', 'label' => 'Total Cases', 'rules' => 'required|integer|min:0'],
                ]
            ];
        }

        // 2. Standard Disease Cases (Vector, Water, Air, Blood, Zoonotic)
        if (in_array($table, ['study_vector_disease', 'study_water_disease', 'study_air_disease', 'study_blood_disease', 'study_zoonotic_disease'])) {
            $nameMap = [
                'study_vector_disease' => 'Vector-Borne Disease',
                'study_water_disease' => 'Waterborne Disease',
                'study_air_disease' => 'Airborne Disease',
                'study_blood_disease' => 'Blood-Borne Disease',
                'study_zoonotic_disease' => 'Zoonotic Disease'
            ];
            return [
                'title' => $nameMap[$table],
                'fields' => [
                    'disease' => ['type' => 'text', 'label' => 'Disease', 'rules' => 'required|string|max:255'],
                    'cases' => ['type' => 'number', 'label' => 'No. of Cases', 'rules' => 'required|integer|min:0'],
                    'deaths' => ['type' => 'number', 'label' => 'No. of Deaths', 'rules' => 'required|integer|min:0'],
                ]
            ];
        }

        $schemas = [
            'study_disease_trend' => [
                'title' => 'Block-Level Disease Trend',
                'fields' => [
                    'disease' => ['type' => 'text', 'label' => 'Disease', 'rules' => 'required|string|max:255'],
                    'y2023' => ['type' => 'number', 'label' => 'Cases 2023', 'rules' => 'required|integer|min:0'],
                    'y2024' => ['type' => 'number', 'label' => 'Cases 2024', 'rules' => 'required|integer|min:0'],
                    'y2025' => ['type' => 'number', 'label' => 'Cases 2025', 'rules' => 'required|integer|min:0'],
                    'trend' => ['type' => 'text', 'label' => 'Trend Trend', 'rules' => 'required|string|max:100'],
                ]
            ],
            'study_outcome_trend' => [
                'title' => 'Outcome-Based Trend Analysis',
                'fields' => [
                    'disease' => ['type' => 'text', 'label' => 'Disease Type', 'rules' => 'required|string|max:255'],
                    'age_group' => ['type' => 'text', 'label' => 'Age Group', 'rules' => 'required|string|max:255'],
                    'gender_male' => ['type' => 'number', 'label' => 'Male Cases', 'rules' => 'required|integer|min:0'],
                    'gender_female' => ['type' => 'number', 'label' => 'Female Cases', 'rules' => 'required|integer|min:0'],
                    'survived' => ['type' => 'number', 'label' => 'Survived', 'rules' => 'required|integer|min:0'],
                    'deceased' => ['type' => 'number', 'label' => 'Deceased', 'rules' => 'required|integer|min:0'],
                    'treated' => ['type' => 'number', 'label' => 'Treated', 'rules' => 'required|integer|min:0'],
                ]
            ],
            'study_transmission_trend' => [
                'title' => 'Transmission Trend',
                'fields' => [
                    'mode_of_transmission' => ['type' => 'text', 'label' => 'Mode of Transmission', 'rules' => 'required|string|max:255'],
                    'cases' => ['type' => 'number', 'label' => 'No. of Cases', 'rules' => 'required|integer|min:0'],
                    'deaths' => ['type' => 'number', 'label' => 'No. of Deaths', 'rules' => 'required|integer|min:0'],
                ]
            ],
            'study_committee_member' => [
                'title' => 'One Health Committee Member',
                'fields' => [
                    'name' => ['type' => 'text', 'label' => 'Name', 'rules' => 'required|string|max:255'],
                    'designation' => ['type' => 'text', 'label' => 'Designation', 'rules' => 'required|string|max:255'],
                    'department' => ['type' => 'text', 'label' => 'Department', 'rules' => 'required|string|max:255'],
                    'role_in_committee' => ['type' => 'text', 'label' => 'Role in Committee', 'rules' => 'required|string|max:255'],
                    'contact_number' => ['type' => 'text', 'label' => 'Contact Number', 'rules' => 'required|string|max:20'],
                ]
            ],
            'study_response_workforce' => [
                'title' => 'Pandemic Response Workforce Team',
                'fields' => [
                    'team_name' => ['type' => 'text', 'label' => 'Team Name', 'rules' => 'required|string|max:255'],
                    'composition' => ['type' => 'textarea', 'label' => 'Composition', 'rules' => 'required|string'],
                    'key_responsibilities' => ['type' => 'textarea', 'label' => 'Key Responsibilities', 'rules' => 'required|string'],
                    'team_leader' => ['type' => 'text', 'label' => 'Team Leader', 'rules' => 'required|string|max:255'],
                ]
            ],
            'study_screening_checkpoint' => [
                'title' => 'Screening Checkpoint',
                'fields' => [
                    'location' => ['type' => 'text', 'label' => 'Location', 'rules' => 'required|string|max:255'],
                    'type' => ['type' => 'text', 'label' => 'Type (e.g., Bus Stand, Railway)', 'rules' => 'required|string|max:255'],
                    'staff_deployed' => ['type' => 'text', 'label' => 'Staff Deployed', 'rules' => 'required|string|max:255'],
                    'screening_method' => ['type' => 'text', 'label' => 'Screening Method', 'rules' => 'required|string|max:255'],
                    'reporting_authority' => ['type' => 'text', 'label' => 'Reporting Authority', 'rules' => 'required|string|max:255'],
                ]
            ],
            'study_control_room_team' => [
                'title' => 'Control Room Team Role',
                'fields' => [
                    'role' => ['type' => 'text', 'label' => 'Role', 'rules' => 'required|string|max:255'],
                    'name' => ['type' => 'text', 'label' => 'Name', 'rules' => 'required|string|max:255'],
                    'designation' => ['type' => 'text', 'label' => 'Designation', 'rules' => 'required|string|max:255'],
                    'contact_number' => ['type' => 'text', 'label' => 'Contact Number', 'rules' => 'required|string|max:20'],
                    'responsibility' => ['type' => 'textarea', 'label' => 'Responsibility Description', 'rules' => 'required|string'],
                ]
            ],
            'study_warning_trigger' => [
                'title' => 'Early Warning Trigger & Action',
                'fields' => [
                    'category' => ['type' => 'text', 'label' => 'Category', 'rules' => 'required|string|max:255'],
                    'trigger_point' => ['type' => 'textarea', 'label' => 'Trigger Point (Red Flag)', 'rules' => 'required|string'],
                    'immediate_action' => ['type' => 'textarea', 'label' => 'Immediate Action Required', 'rules' => 'required|string'],
                ]
            ],
            'study_communicator' => [
                'title' => 'Key Communicator',
                'fields' => [
                    'channel' => ['type' => 'text', 'label' => 'Channel', 'rules' => 'required|string|max:255'],
                    'responsible_person' => ['type' => 'text', 'label' => 'Responsible Person', 'rules' => 'required|string|max:255'],
                    'contact' => ['type' => 'text', 'label' => 'Contact Details', 'rules' => 'required|string|max:255'],
                ]
            ],
            'study_reporting_schedule' => [
                'title' => 'Reporting Schedule & Protocol',
                'fields' => [
                    'to_whom' => ['type' => 'text', 'label' => 'To Whom', 'rules' => 'required|string|max:255'],
                    'what_to_report' => ['type' => 'textarea', 'label' => 'What to Report', 'rules' => 'required|string'],
                    'frequency' => ['type' => 'text', 'label' => 'Frequency', 'rules' => 'required|string|max:255'],
                    'nodal_person' => ['type' => 'text', 'label' => 'Nodal Nodal Person', 'rules' => 'required|string|max:255'],
                ]
            ],
            'study_resource_inventory' => [
                'title' => 'Resource Inventory and Contact',
                'fields' => [
                    'resource_category' => ['type' => 'text', 'label' => 'Resource Category', 'rules' => 'required|string|max:255'],
                    'source' => ['type' => 'text', 'label' => 'Source (District/State/Private)', 'rules' => 'required|string|max:255'],
                    'contact' => ['type' => 'text', 'label' => 'Contact Details', 'rules' => 'required|string|max:255'],
                ]
            ],
            'study_collaboration' => [
                'title' => 'NGO / PPP / CSR Collaboration',
                'fields' => [
                    'organization' => ['type' => 'text', 'label' => 'Organization Name', 'rules' => 'required|string|max:255'],
                    'type' => ['type' => 'text', 'label' => 'Type', 'rules' => 'required|string|max:255'],
                    'support_offered' => ['type' => 'textarea', 'label' => 'Support Offered', 'rules' => 'required|string'],
                    'contact_person' => ['type' => 'text', 'label' => 'Contact Person', 'rules' => 'required|string|max:255'],
                ]
            ],
            'study_coordination' => [
                'title' => 'Interdepartmental Coordination Role',
                'fields' => [
                    'department' => ['type' => 'text', 'label' => 'Department Name', 'rules' => 'required|string|max:255'],
                    'representative' => ['type' => 'text', 'label' => 'Representative', 'rules' => 'required|string|max:255'],
                    'key_role' => ['type' => 'textarea', 'label' => 'Key Role', 'rules' => 'required|string'],
                    'contact' => ['type' => 'text', 'label' => 'Contact Details', 'rules' => 'required|string|max:255'],
                ]
            ],
            'study_facility_conversion' => [
                'title' => 'Community Facilities Conversion Surge Plan',
                'fields' => [
                    'facility_name' => ['type' => 'text', 'label' => 'Facility Name', 'rules' => 'required|string|max:255'],
                    'facility_type' => ['type' => 'text', 'label' => 'Facility Type', 'rules' => 'required|string|max:255'],
                    'no_of_buildings' => ['type' => 'number', 'label' => 'No. of Buildings', 'rules' => 'required|integer|min:0'],
                    'ward' => ['type' => 'text', 'label' => 'Ward', 'rules' => 'required|string|max:100'],
                    'surge_capacity_beds' => ['type' => 'number', 'label' => 'Surge Capacity (Beds)', 'rules' => 'required|integer|min:0'],
                    'nodal_person' => ['type' => 'text', 'label' => 'Nodal Person', 'rules' => 'required|string|max:255'],
                ]
            ]
        ];

        return $schemas[$table] ?? [
            'title' => ucfirst(str_replace('_', ' ', $table)),
            'fields' => []
        ];
    }

    /**
     * Authorize jurisdiction editing.
     */
    protected function authorizeJurisdiction($blockIntId)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        if ($user->role === 'state') {
            return true;
        }

        if ($user->role === 'district') {
            $block = Block::where('block_int_id', $blockIntId)->first();
            if ($block && $block->distric_int_id == $user->district_code) {
                return true;
            }
        }

        if ($user->role === 'block' || $user->role === 'localbody') {
            if ($blockIntId == $user->block_int_id) {
                return true;
            }
        }

        abort(403, 'Unauthorized jurisdiction access. You cannot edit records for this block.');
    }

    /**
     * Render Form to Create a study record.
     */
    public function create(string $table, Request $request)
    {
        $modelClass = $this->getModelClass($table);
        if (!$modelClass) {
            abort(404, 'Study table not found');
        }

        $blockIntId = $request->input('block_int_id', 39); // Default to Muthukulam (39)
        $this->authorizeJurisdiction($blockIntId);

        $schema = $this->getTableSchema($table);
        $blocks = Block::orderBy('block_name_en')->get();

        return view('study.create', compact('table', 'schema', 'blockIntId', 'blocks'));
    }

    /**
     * Store the study record.
     */
    public function store(string $table, Request $request)
    {
        $modelClass = $this->getModelClass($table);
        if (!$modelClass) {
            abort(404, 'Study table not found');
        }

        $blockIntId = $request->input('block_int_id');
        $this->authorizeJurisdiction($blockIntId);

        $schema = $this->getTableSchema($table);
        $rules = ['block_int_id' => 'required|integer'];
        foreach ($schema['fields'] as $name => $field) {
            $rules[$name] = $field['rules'];
        }

        $validated = $request->validate($rules);

        $modelClass::create($validated);

        return redirect()->route('plans.show', [
            'type' => 'block',
            'id' => $blockIntId,
            'sectionId' => $table
        ])->with('success', 'Record created successfully!');
    }

    /**
     * Render Form to Edit a study record.
     */
    public function edit(string $table, $id)
    {
        $modelClass = $this->getModelClass($table);
        if (!$modelClass) {
            abort(404, 'Study table not found');
        }

        $record = $modelClass::findOrFail($id);
        $this->authorizeJurisdiction($record->block_int_id);

        $schema = $this->getTableSchema($table);
        $blocks = Block::orderBy('block_name_en')->get();

        return view('study.edit', compact('table', 'record', 'schema', 'blocks'));
    }

    /**
     * Update the study record.
     */
    public function update(string $table, $id, Request $request)
    {
        $modelClass = $this->getModelClass($table);
        if (!$modelClass) {
            abort(404, 'Study table not found');
        }

        $record = $modelClass::findOrFail($id);
        $this->authorizeJurisdiction($record->block_int_id);

        // Check jurisdiction of the newly submitted block_int_id too!
        $newBlockIntId = $request->input('block_int_id');
        $this->authorizeJurisdiction($newBlockIntId);

        $schema = $this->getTableSchema($table);
        $rules = ['block_int_id' => 'required|integer'];
        foreach ($schema['fields'] as $name => $field) {
            $rules[$name] = $field['rules'];
        }

        $validated = $request->validate($rules);

        $record->update($validated);

        return redirect()->route('plans.show', [
            'type' => 'block',
            'id' => $newBlockIntId,
            'sectionId' => $table
        ])->with('success', 'Record updated successfully!');
    }

    /**
     * Delete the study record.
     */
    public function destroy(string $table, $id)
    {
        $modelClass = $this->getModelClass($table);
        if (!$modelClass) {
            abort(404, 'Study table not found');
        }

        $record = $modelClass::findOrFail($id);
        $blockIntId = $record->block_int_id;
        $this->authorizeJurisdiction($blockIntId);

        $record->delete();

        return redirect()->back()->with('success', 'Record deleted successfully!');
    }
}
