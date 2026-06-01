<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if PostGIS extension is available on this PostgreSQL server
        $hasPostgis = !empty(DB::select("SELECT 1 FROM pg_available_extensions WHERE name = 'postgis'"));

        if ($hasPostgis) {
            // Enable PostGIS extension
            DB::statement('CREATE EXTENSION IF NOT EXISTS postgis;');

            // Add native geometry Point columns
            DB::statement('ALTER TABLE org.institution ADD COLUMN IF NOT EXISTS geom geometry(Point, 4326);');
            DB::statement('ALTER TABLE org.facility ADD COLUMN IF NOT EXISTS geom geometry(Point, 4326);');
            DB::statement('ALTER TABLE onehealth.veterinary_facility ADD COLUMN IF NOT EXISTS geom geometry(Point, 4326);');

            // Add native geometry MultiPolygon columns
            DB::statement('ALTER TABLE geo.master_district ADD COLUMN IF NOT EXISTS geom_spatial geometry(MultiPolygon, 4326);');
            DB::statement('ALTER TABLE geo.master_block ADD COLUMN IF NOT EXISTS geom_spatial geometry(MultiPolygon, 4326);');
            DB::statement('ALTER TABLE geo.master_lsg ADD COLUMN IF NOT EXISTS geom_spatial geometry(MultiPolygon, 4326);');
            DB::statement('ALTER TABLE geo.master_ward ADD COLUMN IF NOT EXISTS geom_spatial geometry(MultiPolygon, 4326);');

            // Add GiST spatial indexes
            DB::statement('CREATE INDEX IF NOT EXISTS institution_geom_gist ON org.institution USING gist(geom);');
            DB::statement('CREATE INDEX IF NOT EXISTS facility_geom_gist ON org.facility USING gist(geom);');
            DB::statement('CREATE INDEX IF NOT EXISTS vet_facility_geom_gist ON onehealth.veterinary_facility USING gist(geom);');
            DB::statement('CREATE INDEX IF NOT EXISTS master_district_geom_gist ON geo.master_district USING gist(geom_spatial);');
            DB::statement('CREATE INDEX IF NOT EXISTS master_block_geom_gist ON geo.master_block USING gist(geom_spatial);');
            DB::statement('CREATE INDEX IF NOT EXISTS master_lsg_geom_gist ON geo.master_lsg USING gist(geom_spatial);');
            DB::statement('CREATE INDEX IF NOT EXISTS master_ward_geom_gist ON geo.master_ward USING gist(geom_spatial);');
        } else {
            // Fallback: Add columns as TEXT to hold WKT (Well-Known Text) or GeoJSON data,
            // matching the existing database tables (like geo.master_block.geom_boundary)
            DB::statement('ALTER TABLE org.institution ADD COLUMN IF NOT EXISTS geom text;');
            DB::statement('ALTER TABLE org.facility ADD COLUMN IF NOT EXISTS geom text;');
            DB::statement('ALTER TABLE onehealth.veterinary_facility ADD COLUMN IF NOT EXISTS geom text;');

            DB::statement('ALTER TABLE geo.master_district ADD COLUMN IF NOT EXISTS geom_spatial text;');
            DB::statement('ALTER TABLE geo.master_block ADD COLUMN IF NOT EXISTS geom_spatial text;');
            DB::statement('ALTER TABLE geo.master_lsg ADD COLUMN IF NOT EXISTS geom_spatial text;');
            DB::statement('ALTER TABLE geo.master_ward ADD COLUMN IF NOT EXISTS geom_spatial text;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop GiST indexes if they exist
        DB::statement('DROP INDEX IF EXISTS org.institution_geom_gist;');
        DB::statement('DROP INDEX IF EXISTS org.facility_geom_gist;');
        DB::statement('DROP INDEX IF EXISTS onehealth.vet_facility_geom_gist;');
        DB::statement('DROP INDEX IF EXISTS geo.master_district_geom_gist;');
        DB::statement('DROP INDEX IF EXISTS geo.master_block_geom_gist;');
        DB::statement('DROP INDEX IF EXISTS geo.master_lsg_geom_gist;');
        DB::statement('DROP INDEX IF EXISTS geo.master_ward_geom_gist;');

        // 2. Drop columns
        DB::statement('ALTER TABLE org.institution DROP COLUMN IF EXISTS geom;');
        DB::statement('ALTER TABLE org.facility DROP COLUMN IF EXISTS geom;');
        DB::statement('ALTER TABLE onehealth.veterinary_facility DROP COLUMN IF EXISTS geom;');
        DB::statement('ALTER TABLE geo.master_district DROP COLUMN IF EXISTS geom_spatial;');
        DB::statement('ALTER TABLE geo.master_block DROP COLUMN IF EXISTS geom_spatial;');
        DB::statement('ALTER TABLE geo.master_lsg DROP COLUMN IF EXISTS geom_spatial;');
        DB::statement('ALTER TABLE geo.master_ward DROP COLUMN IF EXISTS geom_spatial;');
    }
};
