<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add missing fields to geo.master_district
        Schema::table('geo.master_district', function (Blueprint $table) {
            if (!Schema::hasColumn('geo.master_district', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('geo.master_district', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('geo.master_district', 'population')) {
                $table->integer('population')->nullable();
            }
            if (!Schema::hasColumn('geo.master_district', 'area_sq_km')) {
                $table->double('area_sq_km')->nullable();
            }
        });

        // 2. Add missing fields to geo.master_block
        Schema::table('geo.master_block', function (Blueprint $table) {
            if (!Schema::hasColumn('geo.master_block', 'district_id')) {
                $table->uuid('district_id')->nullable();
            }
            if (!Schema::hasColumn('geo.master_block', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('geo.master_block', 'population')) {
                $table->integer('population')->nullable();
            }
            if (!Schema::hasColumn('geo.master_block', 'area_sq_km')) {
                $table->double('area_sq_km')->nullable();
            }
        });

        // 3. Add missing fields to geo.master_local_body
        Schema::table('geo.master_local_body', function (Blueprint $table) {
            if (!Schema::hasColumn('geo.master_local_body', 'block_id')) {
                $table->uuid('block_id')->nullable();
            }
            if (!Schema::hasColumn('geo.master_local_body', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('geo.master_local_body', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('geo.master_local_body', 'population')) {
                $table->integer('population')->nullable();
            }
            if (!Schema::hasColumn('geo.master_local_body', 'vulnerable_population')) {
                $table->integer('vulnerable_population')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('geo.master_district', function (Blueprint $table) {
            $table->dropColumn(['state', 'code', 'population', 'area_sq_km']);
        });

        Schema::table('geo.master_block', function (Blueprint $table) {
            $table->dropColumn(['district_id', 'code', 'population', 'area_sq_km']);
        });

        Schema::table('geo.master_local_body', function (Blueprint $table) {
            $table->dropColumn(['block_id', 'type', 'code', 'population', 'vulnerable_population']);
        });
    }
};
