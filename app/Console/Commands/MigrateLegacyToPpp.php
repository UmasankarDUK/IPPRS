<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateLegacyToPpp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppp:migrate-legacy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate historical IPPRS legacy tables in the public schema to the authoritative PPP custom schemas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting historical legacy data migration to authoritative PPP schemas...');

        DB::transaction(function () {
            // 1. Migrate State (make sure standard State exists)
            $stateId = (string) Str::uuid();
            DB::table('geo.master_state')->updateOrInsert(
                ['state_code' => 'KL'],
                [
                    'state_id' => $stateId,
                    'state_name_en' => 'Kerala',
                    'country_name_en' => 'India',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            $this->info('Default state established.');

            // 2. Migrate Districts
            $legacyDistricts = DB::table('districts')->get();
            $districtMappings = [];
            foreach ($legacyDistricts as $d) {
                $uuid = (string) Str::uuid();
                DB::table('geo.master_district')->updateOrInsert(
                    ['district_code' => (int) $d->id],
                    [
                        'district_id' => $uuid,
                        'state_id' => $stateId,
                        'district_name_en' => $d->name,
                        'is_active' => $d->is_active,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'state' => $d->state,
                        'code' => $d->code,
                        'population' => $d->population,
                        'area_sq_km' => $d->area_sq_km
                    ]
                );
                $districtMappings[$d->id] = $uuid;
            }
            $this->info('Districts backfilled: ' . count($legacyDistricts));

            // 3. Migrate Blocks
            $legacyBlocks = DB::table('blocks')->get();
            $blockMappings = [];
            foreach ($legacyBlocks as $b) {
                $uuid = (string) Str::uuid();
                $districtUuid = $districtMappings[$b->district_id] ?? null;
                
                DB::table('geo.master_block')->updateOrInsert(
                    ['block_code' => $b->code],
                    [
                        'block_id' => $uuid,
                        'block_name_en' => $b->name,
                        'is_active' => true,
                        'district_id' => $districtUuid, // Additive linkage (UUID)
                        'block_int_id' => (int) $b->id,
                        'distric_int_id' => (int) $b->district_id,
                        'code' => $b->code,
                        'population' => $b->population,
                        'area_sq_km' => $b->area_sq_km,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
                $blockMappings[$b->id] = $uuid;
            }
            $this->info('Blocks backfilled: ' . count($legacyBlocks));

            // 4. Migrate Localbodies
            $legacyLocalbodies = DB::table('localbodies')->get();
            $lsgMappings = [];
            foreach ($legacyLocalbodies as $l) {
                $uuid = (string) Str::uuid();
                $blockUuid = $blockMappings[$l->block_id] ?? null;
                
                // Fetch district_id from blocks
                $legacyBlock = DB::table('blocks')->where('id', $l->block_id)->first();
                $distId = $legacyBlock ? (int) $legacyBlock->district_id : 0;

                // Backfill to master_local_body
                DB::table('geo.master_local_body')->updateOrInsert(
                    ['localbody_id' => (int) $l->id],
                    [
                        'localbody_code' => $l->code,
                        'dist_id' => $distId,
                        'localbody_name_en' => $l->name,
                        'localbody_name_mal' => $l->name,
                        'localbody_type_id' => 1,
                        'is_active' => true,
                        'block_id' => $blockUuid,
                        'type' => $l->type,
                        'code' => $l->code,
                        'population' => $l->population,
                        'vulnerable_population' => $l->vulnerable_population
                    ]
                );

                // Backfill to master_lsg for safety
                DB::table('geo.master_lsg')->updateOrInsert(
                    ['lsg_code' => $l->code],
                    [
                        'lsg_id' => $uuid,
                        'block_id' => $blockUuid,
                        'lsg_name_en' => $l->name,
                        'lsg_type' => $l->type,
                        'population_latest' => $l->population,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
                $lsgMappings[$l->id] = $uuid;
            }
            $this->info('Localbodies/LSGs backfilled: ' . count($legacyLocalbodies));

            // 5. Migrate Health Institutions (to org.institution & org.facility)
            $legacyInst = DB::table('health_institutions')->get();
            $instMappings = [];
            foreach ($legacyInst as $h) {
                $instUuid = (string) Str::uuid();
                $lsgUuid = $lsgMappings[$h->localbody_id] ?? null;

                // Create Institution
                DB::table('org.institution')->updateOrInsert(
                    ['institution_code' => 'INST_' . $h->id],
                    [
                        'institution_id' => $instUuid,
                        'lsg_id' => $lsgUuid,
                        'institution_name_en' => $h->name,
                        'ownership_type' => 'Government',
                        'latitude' => $h->lat,
                        'longitude' => $h->lng,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );

                // Create Subordinate Facility
                $facUuid = (string) Str::uuid();
                
                // Establish default facility type first in references
                $existingType = DB::table('ref.master_facility_type')->where('facility_type_code', 'GEN_HOSP')->first();
                if ($existingType) {
                    $facTypeId = $existingType->facility_type_id;
                } else {
                    $facTypeId = (string) Str::uuid(); // Default placeholder Type ID
                    DB::table('ref.master_facility_type')->insert([
                        'facility_type_id' => $facTypeId,
                        'facility_type_code' => 'GEN_HOSP',
                        'facility_type_name_en' => 'General Hospital',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                DB::table('org.facility')->updateOrInsert(
                    ['facility_code' => 'FAC_' . $h->id],
                    [
                        'facility_id' => $facUuid,
                        'institution_id' => $instUuid,
                        'facility_name_en' => $h->name . ' - Clinical Wing',
                        'facility_type_id' => $facTypeId,
                        'lsg_id' => $lsgUuid,
                        'latitude' => $h->lat,
                        'longitude' => $h->lng,
                        'operational_status' => 'ACTIVE',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );

                $instMappings[$h->id] = [
                    'institution_id' => $instUuid,
                    'facility_id' => $facUuid
                ];
            }
            $this->info('Health Institutions mapped to org.institution & org.facility: ' . count($legacyInst));
        });

        $this->info('Data backfill migration completed successfully!');
    }
}
