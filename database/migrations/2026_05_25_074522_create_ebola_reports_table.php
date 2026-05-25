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
        Schema::create('ebola_cases', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->integer('age');
            $table->string('gender'); // Male, Female, Other
            $table->foreignId('health_institution_id')->constrained('health_institutions')->cascadeOnDelete();
            
            // Suspect, Probable, Confirmed
            $table->string('status');
            
            // Home Quarantine, Institutional Quarantine, Isolation (No O2), Isolation (With O2), ICU (No O2), ICU (With O2), ICU (Ventilator)
            $table->string('quarantine_type');
            
            // Not Tested, Sent for Test, Positive, Negative
            $table->string('test_status');
            
            // Active, Recovered, Deceased
            $table->string('outcome');
            
            $table->date('date_of_reporting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebola_cases');
    }
};
