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
        Schema::create('health_institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('localbody_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('type');
            $table->integer('capacity_beds')->default(0);
            $table->integer('capacity_icu')->default(0);
            $table->integer('capacity_oxygen_beds')->default(0);
            $table->integer('oxygen_storage_liters')->default(0);
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_institutions');
    }
};
