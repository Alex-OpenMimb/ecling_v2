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
        Schema::table('volts', function (Blueprint $table) {
            $table->double('volt_measurement')->change();
        });

        Schema::table('amperes', function (Blueprint $table) {
            $table->double('amperage_measurement')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
