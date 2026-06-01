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
        // 1. Create prep.alert_threshold table
        Schema::create('prep.alert_threshold', function (Blueprint $table) {
            $table->uuid('threshold_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('geo_level_type'); // district, block, lsg
            $table->uuid('geo_level_id');
            
            $table->string('alert_level'); // Green, Yellow, Orange, Red
            $table->string('alert_stage'); // Preparedness, Alert, Response, Surge, Recovery
            $table->string('trigger_metric'); // active_cases, icu_occupancy, oxygen_depletion_hours
            $table->decimal('trigger_value', 12, 2);
            
            $table->text('action_matrix')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index
            $table->index(['geo_level_type', 'geo_level_id']);
        });

        // 2. Create prep.alert_activation table
        Schema::create('prep.alert_activation', function (Blueprint $table) {
            $table->uuid('activation_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('geo_level_type');
            $table->uuid('geo_level_id');
            
            $table->string('previous_level')->nullable();
            $table->string('current_level');
            
            $table->uuid('activated_by_user_id')->nullable();
            $table->foreign('activated_by_user_id')->references('user_id')->on('sec.user_account')->nullOnDelete();
            
            $table->text('reason')->nullable();
            $table->timestampTz('activated_at')->default(DB::raw('now()'));
            $table->timestamps();

            // Index
            $table->index(['geo_level_type', 'geo_level_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prep.alert_activation');
        Schema::dropIfExists('prep.alert_threshold');
    }
};
