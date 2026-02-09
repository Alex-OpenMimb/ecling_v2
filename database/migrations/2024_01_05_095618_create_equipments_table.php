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
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('equipment_classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('equipment_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model')->unique();
            $table->boolean('status')->default(1);

            $table->unsignedBigInteger('equipment_class_id');
            $table->foreign('equipment_class_id')->references('id')->on('equipment_classes');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('volts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('volt_measurement')->unique();///Se realiza altertable para cambiar a tipo de dato double
            $table->enum('unit',['Voltios']);
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });



        Schema::create('amperes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('amperage_measurement')->unique(); ///Se realiza altertable para cambiar a tipo de dato double
            $table->enum('unit',['Amperios']);
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('material_name');
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('spare_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('spare_part_name');
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit_name');
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('equipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('description')->nullable();
            $table->string('name')->nullable()->unique();
            $table->string('slug')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('asset_assignment')->default(0);
            $table->boolean('routine_assignment')->default(0);

            $table->unsignedBigInteger('equipment_model_id');
            $table->foreign('equipment_model_id')->references('id')->on('equipment_models');


            $table->unsignedBigInteger('equipment_class_id');
            $table->foreign('equipment_class_id')->references('id')->on('equipment_classes');

            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->unsignedBigInteger('volt_id')->nullable();
            $table->foreign('volt_id')->references('id')->on('volts');

            $table->unsignedBigInteger('ampere_id')->nullable();
            $table->foreign('ampere_id')->references('id')->on('amperes');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('equipment_models');
        Schema::dropIfExists('equipment_classes');
        Schema::dropIfExists('volts');
        Schema::dropIfExists('amperes');
        Schema::dropIfExists('equipments');
    }
};
