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
        // 1. study_disease_trend
        Schema::create('study_disease_trend', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('disease')->nullable();
            $table->integer('y2023')->default(0);
            $table->integer('y2024')->default(0);
            $table->integer('y2025')->default(0);
            $table->string('trend')->nullable();
            $table->timestamps();
        });

        // 2. study_dengue_distribution
        Schema::create('study_dengue_distribution', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('lsgd')->nullable();
            $table->integer('y2023')->default(0);
            $table->integer('y2024')->default(0);
            $table->integer('y2025')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });

        // 3. study_lepto_distribution
        Schema::create('study_lepto_distribution', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('lsgd')->nullable();
            $table->integer('y2023')->default(0);
            $table->integer('y2024')->default(0);
            $table->integer('y2025')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });

        // 4. study_hepa_distribution
        Schema::create('study_hepa_distribution', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('lsgd')->nullable();
            $table->integer('y2023')->default(0);
            $table->integer('y2024')->default(0);
            $table->integer('y2025')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });

        // 5. study_outcome_trend
        Schema::create('study_outcome_trend', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('disease')->nullable();
            $table->string('age_group')->nullable();
            $table->integer('gender_male')->default(0);
            $table->integer('gender_female')->default(0);
            $table->integer('survived')->default(0);
            $table->integer('deceased')->default(0);
            $table->integer('treated')->default(0);
            $table->timestamps();
        });

        // 6. study_transmission_trend
        Schema::create('study_transmission_trend', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('mode_of_transmission')->nullable();
            $table->integer('cases')->default(0);
            $table->integer('deaths')->default(0);
            $table->timestamps();
        });

        // 7. study_vector_disease
        Schema::create('study_vector_disease', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('disease')->nullable();
            $table->integer('cases')->default(0);
            $table->integer('deaths')->default(0);
            $table->timestamps();
        });

        // 8. study_water_disease
        Schema::create('study_water_disease', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('disease')->nullable();
            $table->integer('cases')->default(0);
            $table->integer('deaths')->default(0);
            $table->timestamps();
        });

        // 9. study_air_disease
        Schema::create('study_air_disease', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('disease')->nullable();
            $table->integer('cases')->default(0);
            $table->integer('deaths')->default(0);
            $table->timestamps();
        });

        // 10. study_blood_disease
        Schema::create('study_blood_disease', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('disease')->nullable();
            $table->integer('cases')->default(0);
            $table->integer('deaths')->default(0);
            $table->timestamps();
        });

        // 11. study_zoonotic_disease
        Schema::create('study_zoonotic_disease', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('disease')->nullable();
            $table->integer('cases')->default(0);
            $table->integer('deaths')->default(0);
            $table->timestamps();
        });

        // 12. study_committee_member
        Schema::create('study_committee_member', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('role_in_committee')->nullable();
            $table->string('contact_number')->nullable();
            $table->timestamps();
        });

        // 13. study_response_workforce
        Schema::create('study_response_workforce', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('team_name')->nullable();
            $table->text('composition')->nullable();
            $table->text('key_responsibilities')->nullable();
            $table->string('team_leader')->nullable();
            $table->timestamps();
        });

        // 14. study_screening_checkpoint
        Schema::create('study_screening_checkpoint', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('location')->nullable();
            $table->string('type')->nullable();
            $table->string('staff_deployed')->nullable();
            $table->string('screening_method')->nullable();
            $table->string('reporting_authority')->nullable();
            $table->timestamps();
        });

        // 15. study_control_room_team
        Schema::create('study_control_room_team', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('role')->nullable();
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('responsibility')->nullable();
            $table->timestamps();
        });

        // 16. study_warning_trigger
        Schema::create('study_warning_trigger', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('category')->nullable();
            $table->text('trigger_point')->nullable();
            $table->text('immediate_action')->nullable();
            $table->timestamps();
        });

        // 17. study_communicator
        Schema::create('study_communicator', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('channel')->nullable();
            $table->string('responsible_person')->nullable();
            $table->string('contact')->nullable();
            $table->timestamps();
        });

        // 18. study_reporting_schedule
        Schema::create('study_reporting_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('to_whom')->nullable();
            $table->text('what_to_report')->nullable();
            $table->string('frequency')->nullable();
            $table->string('nodal_person')->nullable();
            $table->timestamps();
        });

        // 19. study_resource_inventory
        Schema::create('study_resource_inventory', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('resource_category')->nullable();
            $table->string('source')->nullable();
            $table->string('contact')->nullable();
            $table->timestamps();
        });

        // 20. study_collaboration
        Schema::create('study_collaboration', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('organization')->nullable();
            $table->string('type')->nullable();
            $table->text('support_offered')->nullable();
            $table->string('contact_person')->nullable();
            $table->timestamps();
        });

        // 21. study_coordination
        Schema::create('study_coordination', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('department')->nullable();
            $table->string('representative')->nullable();
            $table->text('key_role')->nullable();
            $table->string('contact')->nullable();
            $table->timestamps();
        });

        // 22. study_facility_conversion
        Schema::create('study_facility_conversion', function (Blueprint $table) {
            $table->id();
            $table->integer('block_int_id')->nullable()->index();
            $table->string('facility_name')->nullable();
            $table->string('facility_type')->nullable();
            $table->integer('no_of_buildings')->default(0);
            $table->string('ward')->nullable();
            $table->integer('surge_capacity_beds')->default(0);
            $table->string('nodal_person')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_facility_conversion');
        Schema::dropIfExists('study_coordination');
        Schema::dropIfExists('study_collaboration');
        Schema::dropIfExists('study_resource_inventory');
        Schema::dropIfExists('study_reporting_schedule');
        Schema::dropIfExists('study_communicator');
        Schema::dropIfExists('study_warning_trigger');
        Schema::dropIfExists('study_control_room_team');
        Schema::dropIfExists('study_screening_checkpoint');
        Schema::dropIfExists('study_response_workforce');
        Schema::dropIfExists('study_committee_member');
        Schema::dropIfExists('study_zoonotic_disease');
        Schema::dropIfExists('study_blood_disease');
        Schema::dropIfExists('study_air_disease');
        Schema::dropIfExists('study_water_disease');
        Schema::dropIfExists('study_vector_disease');
        Schema::dropIfExists('study_transmission_trend');
        Schema::dropIfExists('study_outcome_trend');
        Schema::dropIfExists('study_hepa_distribution');
        Schema::dropIfExists('study_lepto_distribution');
        Schema::dropIfExists('study_dengue_distribution');
        Schema::dropIfExists('study_disease_trend');
    }
};
