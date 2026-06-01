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
        // Create org.contact_directory table
        Schema::create('org.contact_directory', function (Blueprint $table) {
            $table->uuid('contact_id')->primary()->default(DB::raw('gen_random_uuid()'));
            
            $table->uuid('person_id')->nullable();
            $table->foreign('person_id')->references('person_id')->on('sec.person')->nullOnDelete();
            
            $table->uuid('district_id');
            $table->foreign('district_id')->references('district_id')->on('geo.master_district')->cascadeOnDelete();
            
            $table->uuid('block_id');
            $table->foreign('block_id')->references('block_id')->on('geo.master_block')->cascadeOnDelete();
            
            $table->uuid('lsg_id');
            $table->foreign('lsg_id')->references('lsg_id')->on('geo.master_lsg')->cascadeOnDelete();
            
            $table->uuid('ward_id')->nullable();
            $table->foreign('ward_id')->references('ward_id')->on('geo.master_ward')->nullOnDelete();
            
            $table->string('contact_role'); // DMO, BMO, MO, PHN, JHI, ASHA, Police, Fire, Veterinary, NGO, Ambulance
            $table->string('phone_number');
            $table->string('alternative_phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org.contact_directory');
    }
};
