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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('state')->index(); // state, district, block, localbody
            $table->integer('district_code')->nullable()->index();
            $table->integer('block_int_id')->nullable()->index();
            $table->integer('localbody_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'district_code', 'block_int_id', 'localbody_id']);
        });
    }
};
