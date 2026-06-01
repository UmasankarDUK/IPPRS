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
        // Create org.surge_facility table
        Schema::create('org.surge_facility', function (Blueprint $table) {
            $table->uuid('surge_facility_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('facility_id');
            $table->foreign('facility_id')->references('facility_id')->on('org.facility')->cascadeOnDelete();
            
            $table->string('surge_type'); // QUARANTINE, ISOLATION, RELIEF_CAMP, FIELD_HOSPITAL, SHELTER
            $table->integer('max_capacity')->default(0);
            $table->integer('current_capacity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org.surge_facility');
    }
};
