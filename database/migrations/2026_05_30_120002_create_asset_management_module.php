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
        // 1. Create inventory.asset table
        Schema::create('inventory.asset', function (Blueprint $table) {
            $table->uuid('asset_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('facility_id');
            $table->foreign('facility_id')->references('facility_id')->on('org.facility')->cascadeOnDelete();
            
            $table->string('asset_name');
            $table->string('asset_type'); // Ventilator, Monitor, PSA Plant, Ambulance, Boat, Generator, Oxygen Concentrator
            $table->string('model_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('status')->default('ACTIVE'); // ACTIVE, INACTIVE, UNDER_REPAIR, RETIRED
            $table->integer('critical_threshold')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Create inventory.asset_transaction table
        Schema::create('inventory.asset_transaction', function (Blueprint $table) {
            $table->uuid('txn_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('asset_id');
            $table->foreign('asset_id')->references('asset_id')->on('inventory.asset')->cascadeOnDelete();
            
            $table->timestampTz('txn_datetime')->default(DB::raw('now()'));
            $table->string('txn_type'); // Addition, Transfer, Disposal, Audit
            $table->integer('quantity')->default(1);
            
            $table->uuid('from_facility_id')->nullable();
            $table->foreign('from_facility_id')->references('facility_id')->on('org.facility')->nullOnDelete();
            
            $table->uuid('to_facility_id')->nullable();
            $table->foreign('to_facility_id')->references('facility_id')->on('org.facility')->nullOnDelete();
            
            $table->text('remarks')->nullable();
            $table->uuid('created_by_user_id')->nullable();
        });

        // 3. Create inventory.asset_maintenance table
        Schema::create('inventory.asset_maintenance', function (Blueprint $table) {
            $table->uuid('maintenance_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('asset_id');
            $table->foreign('asset_id')->references('asset_id')->on('inventory.asset')->cascadeOnDelete();
            
            $table->string('maintenance_type'); // Preventive, Breakdown, Calibration
            $table->date('scheduled_date');
            $table->date('performed_date')->nullable();
            $table->string('technician_name')->nullable();
            $table->string('maintenance_status')->default('SCHEDULED'); // SCHEDULED, COMPLETED, CANCELLED
            $table->decimal('cost', 12, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory.asset_maintenance');
        Schema::dropIfExists('inventory.asset_transaction');
        Schema::dropIfExists('inventory.asset');
    }
};
