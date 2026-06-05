<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$sqlitePath = 'database/database.sqlite';
if (!file_exists($sqlitePath)) {
    echo "SQLite database file not found at $sqlitePath\n";
    exit(1);
}

try {
    $sqlite = new PDO('sqlite:' . $sqlitePath);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mappings from SQLite IDs to PostgreSQL IDs
    $lbMap = [
        1 => 42, // Muthukulam
        2 => 13, // Cheppad
        3 => 51, // Pathiyoor
        4 => 25, // Kandalloor
        5 => 20, // Devikulangara
        6 => 1,  // Arattupuzha
        7 => 31  // Krishnapuram
    ];
    
    $districtId = 4; // Alapuzha District Code/ID
    $blockId = 39;   // Muthukulam Block Code/ID
    
    $districtUuid = '160ee8bf-fdb4-4025-974b-866babdebccb'; // Alapuzha District UUID
    $blockUuid = '36ea4ad3-e6c9-400b-a02c-72627e52d880';    // Muthukulam Block UUID
    
    // Begin PG Transaction
    DB::beginTransaction();
    
    // Clear existing tables in PG
    echo "Clearing legacy public tables in PostgreSQL...\n";
    DB::statement('TRUNCATE TABLE public.plan_sections RESTART IDENTITY CASCADE');
    DB::statement('TRUNCATE TABLE public.ebola_daily_reports RESTART IDENTITY CASCADE');
    DB::statement('TRUNCATE TABLE public.ebola_cases RESTART IDENTITY CASCADE');
    DB::statement('TRUNCATE TABLE public.infrastructure_conversions RESTART IDENTITY CASCADE');
    DB::statement('TRUNCATE TABLE public.health_institutions RESTART IDENTITY CASCADE');
    DB::statement('TRUNCATE TABLE public.localbodies RESTART IDENTITY CASCADE');
    DB::statement('TRUNCATE TABLE public.blocks RESTART IDENTITY CASCADE');
    DB::statement('TRUNCATE TABLE public.districts RESTART IDENTITY CASCADE');
    
    // 1. Populate public.districts
    echo "Seeding public.districts...\n";
    $sqliteDist = $sqlite->query("SELECT * FROM districts WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
    if ($sqliteDist) {
        DB::table('public.districts')->insert([
            'id' => $districtId,
            'name' => 'Alapuzha',
            'state_id' => 1,
            'state' => $sqliteDist['state'],
            'code' => $sqliteDist['code'],
            'population' => $sqliteDist['population'],
            'area_sq_km' => $sqliteDist['area_sq_km'],
            'created_at' => $sqliteDist['created_at'],
            'updated_at' => $sqliteDist['updated_at']
        ]);
    }
    
    // 2. Populate public.blocks
    echo "Seeding public.blocks...\n";
    $sqliteBlock = $sqlite->query("SELECT * FROM blocks WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
    if ($sqliteBlock) {
        DB::table('public.blocks')->insert([
            'id' => $blockId,
            'district_id' => $districtId,
            'name' => $sqliteBlock['name'],
            'code' => $sqliteBlock['code'],
            'population' => $sqliteBlock['population'],
            'area_sq_km' => $sqliteBlock['area_sq_km'],
            'created_at' => $sqliteBlock['created_at'],
            'updated_at' => $sqliteBlock['updated_at']
        ]);
    }
    
    // 3. Populate public.localbodies
    echo "Seeding public.localbodies...\n";
    $sqliteLbs = $sqlite->query("SELECT * FROM localbodies")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sqliteLbs as $lb) {
        $mappedId = $lbMap[$lb['id']] ?? null;
        if ($mappedId) {
            DB::table('public.localbodies')->insert([
                'id' => $mappedId,
                'block_id' => $blockId,
                'name' => $lb['name'],
                'type' => $lb['type'],
                'code' => $lb['code'],
                'population' => $lb['population'],
                'vulnerable_population' => $lb['vulnerable_population'],
                'created_at' => $lb['created_at'],
                'updated_at' => $lb['updated_at']
            ]);
        }
    }
    
    // 4. Populate public.health_institutions
    echo "Seeding public.health_institutions...\n";
    $sqliteInsts = $sqlite->query("SELECT * FROM institutions")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sqliteInsts as $inst) {
        // Map localbody_id (1 in SQLite corresponds to Muthukulam, which is 42 in PG)
        $mappedLbId = $lbMap[$inst['localbody_id']] ?? null;
        
        DB::table('public.health_institutions')->insert([
            'id' => $inst['id'],
            'localbody_id' => $mappedLbId,
            'name' => $inst['name'],
            'type' => $inst['type'],
            'capacity_beds' => $inst['capacity_beds'],
            'capacity_icu' => $inst['capacity_icu'],
            'capacity_oxygen_beds' => $inst['capacity_oxygen_beds'],
            'oxygen_storage_liters' => $inst['oxygen_storage_liters'],
            'lat' => $inst['lat'],
            'lng' => $inst['lng'],
            'created_at' => $inst['created_at'],
            'updated_at' => $inst['updated_at']
        ]);
    }
    
    // Reset sequence for health_institutions
    DB::statement("SELECT setval('public.health_institutions_id_seq', COALESCE((SELECT MAX(id)+1 FROM public.health_institutions), 1), false)");
    
    // 5. Populate public.infrastructure_conversions
    echo "Seeding public.infrastructure_conversions...\n";
    $sqliteConvs = $sqlite->query("SELECT * FROM infrastructure_conversions")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sqliteConvs as $conv) {
        $mappedLbId = $lbMap[$conv['localbody_id']] ?? null;
        
        DB::table('public.infrastructure_conversions')->insert([
            'id' => $conv['id'],
            'localbody_id' => $mappedLbId,
            'name' => $conv['name'],
            'type' => $conv['type'],
            'potential_beds' => $conv['potential_beds'],
            'status' => $conv['status'],
            'created_at' => $conv['created_at'],
            'updated_at' => $conv['updated_at']
        ]);
    }
    
    // Reset sequence
    DB::statement("SELECT setval('public.infrastructure_conversions_id_seq', COALESCE((SELECT MAX(id)+1 FROM public.infrastructure_conversions), 1), false)");
    
    // 6. Migrate Ebola Daily Reports
    echo "Seeding Ebola daily reports...\n";
    $sqliteReports = $sqlite->query("SELECT * FROM ebola_reports")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sqliteReports as $rep) {
        DB::table('public.ebola_daily_reports')->insert([
            'id' => $rep['id'],
            'date_of_reporting' => $rep['date_of_reporting'],
            'health_institution_id' => $rep['institution_id'],
            'total_cases_reported' => $rep['total_cases_reported'],
            'home_quarantine' => $rep['suspect_home_quarantine'],
            'inst_quarantine' => $rep['probable_institutional_quarantine'],
            'isolation_no_o2' => $rep['probable_isolation_no_o2'],
            'isolation_with_o2' => $rep['probable_isolation_with_o2'],
            'icu_no_o2' => $rep['probable_icu_no_o2'],
            'icu_with_o2' => $rep['probable_icu_with_o2'],
            'icu_ventilator' => $rep['probable_icu_ventilator'],
            'deaths_probable' => 0,
            'tests_sent' => $rep['confirmatory_tests_sent'],
            'lab_confirmed' => $rep['confirmed_cases'],
            'positives_home' => $rep['positives_home_quarantine'],
            'positives_inst' => $rep['positives_institutional_quarantine'],
            'positives_isolation' => $rep['positives_isolation'],
            'positives_icu_no_o2' => $rep['positives_icu_no_o2'],
            'positives_icu_with_o2' => $rep['positives_icu_with_o2'],
            'positives_icu_ventilator' => $rep['positives_icu_ventilator'],
            'created_at' => $rep['created_at'],
            'updated_at' => $rep['updated_at']
        ]);
    }
    
    // Reset sequence
    DB::statement("SELECT setval('public.ebola_daily_reports_id_seq', COALESCE((SELECT MAX(id)+1 FROM public.ebola_daily_reports), 1), false)");
    
    // 7. Migrate Plan Sections
    echo "Seeding plan sections...\n";
    $sqliteSections = $sqlite->query("SELECT * FROM plan_sections")->fetchAll(PDO::FETCH_ASSOC);
    
    $insertBuffer = [];
    $count = 0;
    
    foreach ($sqliteSections as $sec) {
        $mappedType = $sec['planable_type'];
        $mappedId = $sec['planable_id'];
        
        if ($sec['planable_type'] === 'App\\Models\\District') {
            $mappedId = $districtId;
        } elseif ($sec['planable_type'] === 'App\\Models\\Block') {
            $mappedId = $blockId;
        } elseif ($sec['planable_type'] === 'App\\Models\\Localbody') {
            $mappedId = $lbMap[$sec['planable_id']] ?? null;
        } elseif ($sec['planable_type'] === 'App\\Models\\Institution') {
            $mappedType = 'App\\Models\\HealthInstitution';
            $mappedId = $sec['planable_id'];
        }
        
        if ($mappedId !== null) {
            $insertBuffer[] = [
                'planable_type' => $mappedType,
                'planable_id' => $mappedId,
                'title' => $sec['title'],
                'content' => $sec['content'],
                'section_order' => $sec['section_order'],
                'created_at' => $sec['created_at'],
                'updated_at' => $sec['updated_at']
            ];
            $count++;
            
            if (count($insertBuffer) >= 100) {
                DB::table('public.plan_sections')->insert($insertBuffer);
                $insertBuffer = [];
            }
        }
    }
    
    if (count($insertBuffer) > 0) {
        DB::table('public.plan_sections')->insert($insertBuffer);
    }
    echo "Plan sections migrated successfully: $count rows.\n";
    
    // Reset sequence
    DB::statement("SELECT setval('public.plan_sections_id_seq', COALESCE((SELECT MAX(id)+1 FROM public.plan_sections), 1), false)");
    
    // 8. Run ppp:migrate-legacy
    echo "Running ppp:migrate-legacy console command to sync new schemas...\n";
    $exitCode = Artisan::call('ppp:migrate-legacy');
    echo "Artisan ppp:migrate-legacy completed with exit code: $exitCode\n";
    
    // 9. Run EbolaCaseSeeder to seed cases
    echo "Seeding Ebola cases...\n";
    $seeder = new \Database\Seeders\EbolaCaseSeeder();
    $seeder->run();
    echo "Ebola cases seeded successfully.\n";
    
    DB::commit();
    echo "All operations committed successfully!\n";
    
} catch (\Throwable $e) {
    DB::rollBack();
    echo "Error during migration: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
