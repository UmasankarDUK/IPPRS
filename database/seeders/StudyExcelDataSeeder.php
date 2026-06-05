<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudyExcelDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = 'reference - ppp.xlsx';
        if (!file_exists($filePath)) {
            echo "Excel file reference - ppp.xlsx not found!\n";
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $blockIntId = 39; // Muthukulam Block ID in PostgreSQL

        // 1. Block-Level Disease Trend
        echo "Importing Block-Level Disease Trend...\n";
        $sheet = $spreadsheet->getSheetByName('Block-Level Disease Trend (2021');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $disease = $sheet->getCell('C' . $row)->getValue();
                if (!$disease) continue;
                DB::table('study_disease_trend')->insert([
                    'block_int_id' => $blockIntId,
                    'disease' => trim((string)$disease),
                    'y2023' => (int)$sheet->getCell('D' . $row)->getValue(),
                    'y2024' => (int)$sheet->getCell('E' . $row)->getValue(),
                    'y2025' => (int)$sheet->getCell('F' . $row)->getValue(),
                    'trend' => trim((string)$sheet->getCell('G' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Helper to import LSGD wise distribution sheets
        $importLsgdDist = function ($sheetName, $tableName) use ($spreadsheet, $blockIntId) {
            echo "Importing $sheetName to $tableName...\n";
            $sheet = $spreadsheet->getSheetByName($sheetName);
            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 3; $row <= $highestRow; $row++) {
                    $lsgd = $sheet->getCell('C' . $row)->getValue();
                    if (!$lsgd || strtolower(trim((string)$lsgd)) === 'total') continue;
                    DB::table($tableName)->insert([
                        'block_int_id' => $blockIntId,
                        'lsgd' => trim((string)$lsgd),
                        'y2023' => (int)$sheet->getCell('D' . $row)->getValue(),
                        'y2024' => (int)$sheet->getCell('E' . $row)->getValue(),
                        'y2025' => (int)$sheet->getCell('F' . $row)->getValue(),
                        'total' => (int)$sheet->getCell('G' . $row)->getValue(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        };

        // 2. Dengue – LSGD-wise Yearly Distr
        $importLsgdDist('Dengue – LSGD-wise Yearly Distr', 'study_dengue_distribution');

        // 3. Leptospirosis – LSGD- wise Year
        $importLsgdDist('Leptospirosis – LSGD- wise Year', 'study_lepto_distribution');

        // 4. Hepatitis A – Ward-wise Yearly
        $importLsgdDist('Hepatitis A – Ward-wise Yearly ', 'study_hepa_distribution');

        // 5. Outcome-Based Trend Analysis
        echo "Importing Outcome-Based Trend Analysis...\n";
        $sheet = $spreadsheet->getSheetByName('Outcome-Based Trend Analysis—2');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            $currentDisease = '';
            for ($row = 3; $row <= $highestRow; $row++) {
                $diseaseVal = $sheet->getCell('C' . $row)->getValue();
                if ($diseaseVal) {
                    $currentDisease = trim((string)$diseaseVal);
                }
                $ageGroup = $sheet->getCell('D' . $row)->getValue();
                if (!$ageGroup) continue;

                DB::table('study_outcome_trend')->insert([
                    'block_int_id' => $blockIntId,
                    'disease' => $currentDisease,
                    'age_group' => trim((string)$ageGroup),
                    'gender_male' => (int)$sheet->getCell('E' . $row)->getValue(),
                    'gender_female' => (int)$sheet->getCell('F' . $row)->getValue(),
                    'survived' => (int)$sheet->getCell('G' . $row)->getValue(),
                    'deceased' => (int)$sheet->getCell('H' . $row)->getValue(),
                    'treated' => (int)$sheet->getCell('I' . $row)->getValue(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 6. Transmission Trend 2025
        echo "Importing Transmission Trend 2025...\n";
        $sheet = $spreadsheet->getSheetByName('Transmission Trend 2025');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $mode = $sheet->getCell('C' . $row)->getValue();
                if (!$mode) continue;
                DB::table('study_transmission_trend')->insert([
                    'block_int_id' => $blockIntId,
                    'mode_of_transmission' => trim((string)$mode),
                    'cases' => (int)$sheet->getCell('D' . $row)->getValue(),
                    'deaths' => (int)$sheet->getCell('E' . $row)->getValue(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Helper to import standard disease sheets (Vector, Water, Air, Blood, Zoonotic)
        $importDiseaseCases = function ($sheetName, $tableName) use ($spreadsheet, $blockIntId) {
            echo "Importing $sheetName to $tableName...\n";
            $sheet = $spreadsheet->getSheetByName($sheetName);
            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    $disease = $sheet->getCell('C' . $row)->getValue();
                    if (!$disease) continue;
                    DB::table($tableName)->insert([
                        'block_int_id' => $blockIntId,
                        'disease' => trim((string)$disease),
                        'cases' => (int)$sheet->getCell('D' . $row)->getValue(),
                        'deaths' => (int)$sheet->getCell('E' . $row)->getValue(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        };

        // 7. Vector-Borne Disease
        $importDiseaseCases('Vector-Borne Disease', 'study_vector_disease');

        // 8. Waterborne Disease
        $importDiseaseCases('Waterborne Disease', 'study_water_disease');

        // 9. Airborne Disease
        $importDiseaseCases('Airborne Disease', 'study_air_disease');

        // 10. Blood-Borne Disease
        $importDiseaseCases('Blood-Borne Disease', 'study_blood_disease');

        // 11. Zoonotic Disease
        $importDiseaseCases('Zoonotic Disease', 'study_zoonotic_disease');

        // 12. One Health Committee Member
        echo "Importing One Health Committee Member...\n";
        $sheet = $spreadsheet->getSheetByName('One Health Committee Member');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $name = $sheet->getCell('C' . $row)->getValue();
                if (!$name) continue;
                DB::table('study_committee_member')->insert([
                    'block_int_id' => $blockIntId,
                    'name' => trim((string)$name),
                    'designation' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'department' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'role_in_committee' => trim((string)$sheet->getCell('F' . $row)->getValue()),
                    'contact_number' => trim((string)$sheet->getCell('G' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 13. Pandemic Response Workforce
        echo "Importing Response Workforce...\n";
        $sheet = $spreadsheet->getSheetByName('Pandemic Response Workforce');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $teamName = $sheet->getCell('C' . $row)->getValue();
                if (!$teamName) continue;
                DB::table('study_response_workforce')->insert([
                    'block_int_id' => $blockIntId,
                    'team_name' => trim((string)$teamName),
                    'composition' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'key_responsibilities' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'team_leader' => trim((string)$sheet->getCell('F' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 14. Screening Checkpoints
        echo "Importing Screening Checkpoints...\n";
        $sheet = $spreadsheet->getSheetByName('Screening Checkpoints');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $location = $sheet->getCell('C' . $row)->getValue();
                if (!$location) continue;
                DB::table('study_screening_checkpoint')->insert([
                    'block_int_id' => $blockIntId,
                    'location' => trim((string)$location),
                    'type' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'staff_deployed' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'screening_method' => trim((string)$sheet->getCell('F' . $row)->getValue()),
                    'reporting_authority' => trim((string)$sheet->getCell('G' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 15. Key Control Room Team
        echo "Importing Control Room Team...\n";
        $sheet = $spreadsheet->getSheetByName('Key Control Room Team ');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $role = $sheet->getCell('C' . $row)->getValue();
                if (!$role) continue;
                DB::table('study_control_room_team')->insert([
                    'block_int_id' => $blockIntId,
                    'role' => trim((string)$role),
                    'name' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'designation' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'contact_number' => trim((string)$sheet->getCell('F' . $row)->getValue()),
                    'responsibility' => trim((string)$sheet->getCell('G' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 16. Early Warning Triggers
        echo "Importing Early Warning Triggers...\n";
        $sheet = $spreadsheet->getSheetByName('Early Warning Triggers and Imme');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $category = $sheet->getCell('C' . $row)->getValue();
                if (!$category) continue;
                DB::table('study_warning_trigger')->insert([
                    'block_int_id' => $blockIntId,
                    'category' => trim((string)$category),
                    'trigger_point' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'immediate_action' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 17. Key Communicators
        echo "Importing Key Communicators...\n";
        $sheet = $spreadsheet->getSheetByName('Key Communicators for Public He');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $channel = $sheet->getCell('C' . $row)->getValue();
                if (!$channel) continue;
                DB::table('study_communicator')->insert([
                    'block_int_id' => $blockIntId,
                    'channel' => trim((string)$channel),
                    'responsible_person' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'contact' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 18. Reporting Schedule
        echo "Importing Reporting Schedule...\n";
        $sheet = $spreadsheet->getSheetByName('Reporting Schedule and Protocol');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $toWhom = $sheet->getCell('C' . $row)->getValue();
                if (!$toWhom) continue;
                DB::table('study_reporting_schedule')->insert([
                    'block_int_id' => $blockIntId,
                    'to_whom' => trim((string)$toWhom),
                    'what_to_report' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'frequency' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'nodal_person' => trim((string)$sheet->getCell('F' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 19. Resource Inventory
        echo "Importing Resource Inventory...\n";
        $sheet = $spreadsheet->getSheetByName('Resource Inventory and Contacts');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $category = $sheet->getCell('C' . $row)->getValue();
                if (!$category) continue;
                DB::table('study_resource_inventory')->insert([
                    'block_int_id' => $blockIntId,
                    'resource_category' => trim((string)$category),
                    'source' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'contact' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 20. NGOs CSR Collaboration
        echo "Importing NGOs CSR Collaboration...\n";
        $sheet = $spreadsheet->getSheetByName('NGOs_PPP_CSR Collaboration');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $org = $sheet->getCell('C' . $row)->getValue();
                if (!$org) continue;
                DB::table('study_collaboration')->insert([
                    'block_int_id' => $blockIntId,
                    'organization' => trim((string)$org),
                    'type' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'support_offered' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'contact_person' => trim((string)$sheet->getCell('F' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 21. Interdepartmental Coordination
        echo "Importing Interdepartmental Coordination...\n";
        $sheet = $spreadsheet->getSheetByName('Interdepartmental Coordination');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $dept = $sheet->getCell('C' . $row)->getValue();
                if (!$dept) continue;
                DB::table('study_coordination')->insert([
                    'block_int_id' => $blockIntId,
                    'department' => trim((string)$dept),
                    'representative' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'key_role' => trim((string)$sheet->getCell('E' . $row)->getValue()),
                    'contact' => trim((string)$sheet->getCell('F' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 22. Community Facilities Conversion
        echo "Importing Community Facilities Conversion...\n";
        $sheet = $spreadsheet->getSheetByName('Community Facilities Conversion');
        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $facName = $sheet->getCell('C' . $row)->getValue();
                if (!$facName) continue;
                DB::table('study_facility_conversion')->insert([
                    'block_int_id' => $blockIntId,
                    'facility_name' => trim((string)$facName),
                    'facility_type' => trim((string)$sheet->getCell('D' . $row)->getValue()),
                    'no_of_buildings' => (int)$sheet->getCell('E' . $row)->getValue(),
                    'ward' => trim((string)$sheet->getCell('F' . $row)->getValue()),
                    'surge_capacity_beds' => (int)$sheet->getCell('G' . $row)->getValue(),
                    'nodal_person' => trim((string)$sheet->getCell('H' . $row)->getValue()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
