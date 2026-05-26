<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebola_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_reporting');
            $table->foreignId('health_institution_id')->constrained('health_institutions')->onDelete('cascade');

            // Daily - Cases
            $table->unsignedInteger('total_cases_reported')->default(0);

            // Daily - Admissions of Probable Cases
            $table->unsignedInteger('home_quarantine')->default(0);       // Col E
            $table->unsignedInteger('inst_quarantine')->default(0);       // Col F
            $table->unsignedInteger('isolation_no_o2')->default(0);       // Col G
            $table->unsignedInteger('isolation_with_o2')->default(0);     // Col H
            $table->unsignedInteger('icu_no_o2')->default(0);             // Col I
            $table->unsignedInteger('icu_with_o2')->default(0);           // Col J
            $table->unsignedInteger('icu_ventilator')->default(0);        // Col K
            // Col L (total_admissions) = G+H+I+J+K — computed, not stored

            // Daily - Deaths
            $table->unsignedInteger('deaths_probable')->default(0);       // Col M

            // Daily - Testing
            $table->unsignedInteger('tests_sent')->default(0);            // Col N
            $table->unsignedInteger('lab_confirmed')->default(0);         // Col O

            // Daily - Test Positives by tier
            $table->unsignedInteger('positives_home')->default(0);        // Col P
            $table->unsignedInteger('positives_inst')->default(0);        // Col Q
            $table->unsignedInteger('positives_isolation')->default(0);   // Col R
            $table->unsignedInteger('positives_icu_no_o2')->default(0);   // Col S
            $table->unsignedInteger('positives_icu_with_o2')->default(0); // Col T
            $table->unsignedInteger('positives_icu_ventilator')->default(0); // Col U
            // Col V (total_positives) = P+Q+R+S+T+U — computed, not stored

            $table->timestamps();

            $table->unique(['date_of_reporting', 'health_institution_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebola_daily_reports');
    }
};
