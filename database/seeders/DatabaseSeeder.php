<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\District;
use App\Models\Block;
use App\Models\Localbody;
use App\Models\HealthInstitution;
use App\Models\InfrastructureConversion;
use App\Models\PlanSection;
use App\Services\DocxParser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@ipprs.in'],
            [
                'name' => 'State Health Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Seed Districts from districts.sql
        $this->call(DistrictSqlSeeder::class);

        $parser = new DocxParser();

        DB::transaction(function () use ($parser) {
            
            // 2. Seed District: Alappuzha (from districts.sql with ID 318)
            $districtPath = base_path('Req/Alappuzha District Final_PPP.docx');
            $this->command->info("Parsing District Plan: Alappuzha...");
            
            $district = District::updateOrCreate(
                ['id' => 318],
                [
                    'name' => 'Alappuzha',
                    'state' => 'Kerala',
                    'code' => 'ALP',
                    'population' => 2127063, // 2011 Census
                    'area_sq_km' => 1414.0,
                ]
            );

            $districtSections = $parser->parse($districtPath);
            foreach ($districtSections as $index => $section) {
                $district->planSections()->create([
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'section_order' => $index,
                ]);
            }
            $this->command->info("Parsed " . count($districtSections) . " sections for District: Alappuzha.");

            // 3. Seed Block: Muthukulam
            $blockPath = base_path('Req/12.MUTHUKULAM BLOCK_FINAL.docx');
            $this->command->info("Parsing Block Plan: Muthukulam...");

            $block = Block::create([
                'district_id' => $district->id,
                'name' => 'Muthukulam',
                'code' => 'MKL-BLK',
                'population' => 268000,
                'area_sq_km' => 90.67,
            ]);

            $blockSections = $parser->parse($blockPath);
            foreach ($blockSections as $index => $section) {
                $block->planSections()->create([
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'section_order' => $index,
                ]);
            }
            $this->command->info("Parsed " . count($blockSections) . " sections for Block: Muthukulam.");

            // 4. Seed Localbodies
            $localbodyFiles = [
                'Muthukulam' => [
                    'file' => 'final_MUTHUKULAM PPP@2026.docx',
                    'population' => 28000,
                    'vulnerable' => 4500,
                ],
                'Cheppad' => [
                    'file' => 'CHEPPAD PPT@PPP march 2026.docx',
                    'population' => 31000,
                    'vulnerable' => 5200,
                ],
                'Pathiyoor' => [
                    'file' => 'FINAL_PATHIYOOR PPP@PPT march 2026 (1).docx',
                    'population' => 38000,
                    'vulnerable' => 6100,
                ],
                'Kandalloor' => [
                    'file' => 'KANDALLOOR-PPP@PPT.docx',
                    'population' => 24000,
                    'vulnerable' => 3800,
                ],
                'Devikulangara' => [
                    'file' => 'final-DEVIKULANGARA PPP .docx',
                    'population' => 22000,
                    'vulnerable' => 3100,
                ],
                'Arattupuzha' => [
                    'file' => 'final-ppp arattupuzha march .docx',
                    'population' => 42000,
                    'vulnerable' => 7800, // high coastal vulnerability
                ],
                'Krishnapuram' => [
                    'file' => 'final_KRISHNAPURAM PPP@2026.docx',
                    'population' => 35000,
                    'vulnerable' => 4900,
                ],
            ];

            foreach ($localbodyFiles as $name => $meta) {
                $filePath = base_path('Req/Muthukulam-FINAL/' . $meta['file']);
                $this->command->info("Parsing Localbody Plan: $name Grama Panchayat...");

                $lb = Localbody::create([
                    'block_id' => $block->id,
                    'name' => $name,
                    'type' => 'Grama Panchayat',
                    'code' => strtoupper(substr($name, 0, 3)) . '-GP',
                    'population' => $meta['population'],
                    'vulnerable_population' => $meta['vulnerable'],
                ]);

                $lbSections = $parser->parse($filePath);
                foreach ($lbSections as $index => $section) {
                    $lb->planSections()->create([
                        'title' => $section['title'],
                        'content' => $section['content'],
                        'section_order' => $index,
                    ]);
                }
                $this->command->info("Parsed " . count($lbSections) . " sections for Localbody: $name GP.");

                // 5. Seed Infrastructure Conversions (Auditoriums / Schools) for each GP
                $infraTypes = [
                    ['name' => "$name Town Hall & Auditorium", 'type' => 'Auditorium', 'beds' => rand(80, 120)],
                    ['name' => "$name Higher Secondary School", 'type' => 'School', 'beds' => rand(100, 160)],
                    ['name' => "$name Community Health Hall", 'type' => 'Community Space', 'beds' => rand(50, 90)]
                ];

                foreach ($infraTypes as $infra) {
                    InfrastructureConversion::create([
                        'localbody_id' => $lb->id,
                        'name' => $infra['name'],
                        'type' => $infra['type'],
                        'potential_beds' => $infra['beds'],
                        'status' => 'Planned',
                    ]);
                }
            }

            // 6. Seed Institutions (TDMCH and GH Alappuzha)
            // TDMCH
            $tdmchPath = base_path('Req/1.TDMCH-Alappuzha.docx');
            $this->command->info("Parsing Institution Plan: TDMCH Alappuzha (Medical College Hospital)...");
            
            // Get Muthukulam Localbody to link it to
            $muthukulamLb = Localbody::where('name', 'Muthukulam')->first();

            $tdmch = HealthInstitution::create([
                'localbody_id' => $muthukulamLb ? $muthukulamLb->id : null,
                'name' => 'Government T. D. Medical College (TDMCH)',
                'type' => 'Medical College Hospital',
                'capacity_beds' => 1250,
                'capacity_icu' => 180,
                'capacity_oxygen_beds' => 450,
                'oxygen_storage_liters' => 60000,
                'lat' => 9.387222,
                'lng' => 76.353333,
            ]);

            $tdmchSections = $parser->parse($tdmchPath);
            foreach ($tdmchSections as $index => $section) {
                $tdmch->planSections()->create([
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'section_order' => $index,
                ]);
            }
            $this->command->info("Parsed " . count($tdmchSections) . " sections for TDMCH.");

            // GH Alappuzha
            $ghPath = base_path('Req/2.GH Alpy _FINAL.docx');
            $this->command->info("Parsing Institution Plan: General Hospital Alappuzha...");

            $gh = HealthInstitution::create([
                'localbody_id' => $muthukulamLb ? $muthukulamLb->id : null,
                'name' => 'General Hospital Alappuzha (GH Alpy)',
                'type' => 'Medical College Hospital',
                'capacity_beds' => 650,
                'capacity_icu' => 60,
                'capacity_oxygen_beds' => 220,
                'oxygen_storage_liters' => 24000,
                'lat' => 9.492500,
                'lng' => 76.331389,
            ]);

            $ghSections = $parser->parse($ghPath);
            foreach ($ghSections as $index => $section) {
                $gh->planSections()->create([
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'section_order' => $index,
                ]);
            }
            $this->command->info("Parsed " . count($ghSections) . " sections for General Hospital Alappuzha.");
            
            // Seed Ebola Cases
            $this->call(EbolaCaseSeeder::class);
        });
    }
}
