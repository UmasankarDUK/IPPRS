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
        // 1. Indexes for org.facility
        Schema::table('org.facility', function (Blueprint $table) {
            $table->index('lsg_id');
            $table->index('facility_type_id');
        });

        // 2. Indexes for org.institution
        Schema::table('org.institution', function (Blueprint $table) {
            $table->index('lsg_id');
            $table->index('facility_type_id');
        });

        // 3. Indexes for prep.committee
        Schema::table('prep.committee', function (Blueprint $table) {
            $table->index('district_id');
            $table->index('block_id');
            $table->index('lsg_id');
        });

        // 4. Indexes for inventory.asset
        Schema::table('inventory.asset', function (Blueprint $table) {
            $table->index('asset_type');
        });

        // 5. Indexes for prep.volunteer
        Schema::table('prep.volunteer', function (Blueprint $table) {
            $table->index('district_id');
            $table->index('block_id');
            $table->index('lsg_id');
        });

        // 6. Indexes for org.contact_directory
        Schema::table('org.contact_directory', function (Blueprint $table) {
            $table->index('contact_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Remove indexes for org.facility
        Schema::table('org.facility', function (Blueprint $table) {
            $table->dropIndex(['lsg_id']);
            $table->dropIndex(['facility_type_id']);
        });

        // 2. Remove indexes for org.institution
        Schema::table('org.institution', function (Blueprint $table) {
            $table->dropIndex(['lsg_id']);
            $table->dropIndex(['facility_type_id']);
        });

        // 3. Remove indexes for prep.committee
        Schema::table('prep.committee', function (Blueprint $table) {
            $table->dropIndex(['district_id']);
            $table->dropIndex(['block_id']);
            $table->dropIndex(['lsg_id']);
        });

        // 4. Remove indexes for inventory.asset
        Schema::table('inventory.asset', function (Blueprint $table) {
            $table->dropIndex(['asset_type']);
        });

        // 5. Remove indexes for prep.volunteer
        Schema::table('prep.volunteer', function (Blueprint $table) {
            $table->dropIndex(['district_id']);
            $table->dropIndex(['block_id']);
            $table->dropIndex(['lsg_id']);
        });

        // 6. Remove indexes for org.contact_directory
        Schema::table('org.contact_directory', function (Blueprint $table) {
            $table->dropIndex(['contact_role']);
        });
    }
};
