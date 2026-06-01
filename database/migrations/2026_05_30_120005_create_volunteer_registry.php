<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create prep.volunteer table
        Schema::create('prep.volunteer', function (Blueprint $table) {
            $table->uuid('volunteer_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('person_id');
            $table->foreign('person_id')->references('person_id')->on('sec.person')->cascadeOnDelete();
            
            $table->uuid('district_id');
            $table->foreign('district_id')->references('district_id')->on('geo.master_district')->cascadeOnDelete();
            
            $table->uuid('block_id');
            $table->foreign('block_id')->references('block_id')->on('geo.master_block')->cascadeOnDelete();
            
            $table->uuid('lsg_id');
            $table->foreign('lsg_id')->references('lsg_id')->on('geo.master_lsg')->cascadeOnDelete();
            
            $table->uuid('ward_id');
            $table->foreign('ward_id')->references('ward_id')->on('geo.master_ward')->cascadeOnDelete();
            
            $table->string('availability_status')->default('AVAILABLE'); // AVAILABLE, DEPLOYED, INACTIVE
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Create prep.volunteer_skill table
        Schema::create('prep.volunteer_skill', function (Blueprint $table) {
            $table->uuid('volunteer_skill_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('volunteer_id');
            $table->foreign('volunteer_id')->references('volunteer_id')->on('prep.volunteer')->cascadeOnDelete();
            
            $table->string('skill_name'); // First Aid, Nursing, Ambulance Driver, Boat Operator, Logistics, Counselling
            $table->string('certification_no')->nullable();
            $table->timestamps();
        });

        // 3. Create prep.volunteer_assignment table
        Schema::create('prep.volunteer_assignment', function (Blueprint $table) {
            $table->uuid('assignment_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('volunteer_id');
            $table->foreign('volunteer_id')->references('volunteer_id')->on('prep.volunteer')->cascadeOnDelete();
            
            $table->string('assigned_geo_type');
            $table->uuid('assigned_geo_id');
            $table->string('assignment_role');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('ACTIVE'); // ACTIVE, COMPLETED, CANCELLED
            $table->timestamps();

            // Index
            $table->index(['assigned_geo_type', 'assigned_geo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prep.volunteer_assignment');
        Schema::dropIfExists('prep.volunteer_skill');
        Schema::dropIfExists('prep.volunteer');
    }
};
