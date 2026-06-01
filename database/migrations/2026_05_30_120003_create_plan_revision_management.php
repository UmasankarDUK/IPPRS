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
        // Create prep.plan_revision table
        Schema::create('prep.plan_revision', function (Blueprint $table) {
            $table->uuid('revision_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('plan_document_id');
            $table->foreign('plan_document_id')->references('plan_document_id')->on('prep.plan_document')->cascadeOnDelete();
            
            $table->string('revision_no');
            
            $table->uuid('approved_by_person_id')->nullable();
            $table->foreign('approved_by_person_id')->references('person_id')->on('sec.person')->nullOnDelete();
            
            $table->timestampTz('approved_at')->nullable();
            $table->text('revision_reason')->nullable();
            $table->boolean('is_current')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('plan_document_id');
            $table->index('is_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prep.plan_revision');
    }
};
