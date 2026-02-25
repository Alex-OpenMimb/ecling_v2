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
        Schema::create('preventive_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('activity')->unique();
            $table->string('description')->nullable();
            $table->boolean('status')->default(1);

            $table->unsignedBigInteger('equipment_class_id');
            $table->foreign('equipment_class_id')->references('id')->on('equipment_classes');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('preventive_routines', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->integer('frequency');

            $table->unsignedBigInteger('equipment_class_id');
            $table->foreign('equipment_class_id')->references('id')->on('equipment_classes');


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('preventive_routines_activities', function (Blueprint $table){
            $table->bigIncrements('id');

            $table->unsignedBigInteger('preventive_activity_id');
            $table->foreign('preventive_activity_id')->references('id')->on('preventive_activities');

            $table->unsignedBigInteger('preventive_routine_id');
            $table->foreign('preventive_routine_id')->references('id')->on('preventive_routines');

            $table->timestamps();

        });



        Schema::create('corrective_activities', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('activity')->unique();
            $table->string('description')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('assigned')->default(0);


            $table->unsignedBigInteger('equipment_class_id');
            $table->foreign('equipment_class_id')->references('id')->on('equipment_classes');

            $table->timestamps();
            $table->softDeletes();
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_activities');
        Schema::dropIfExists('preventive_routines');
        Schema::dropIfExists('preventive_routines_activities');
        Schema::dropIfExists('corrective_activities');

    }
};
