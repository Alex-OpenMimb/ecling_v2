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
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('status')->default(1);
            $table->string('nit')->unique();;
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomenclature_main')->nullable();
            $table->string('number_main')->nullable();
            $table->string('nomenclature_second')->nullable();
            $table->string('number_second')->nullable();
            $table->string('number')->nullable();
            $table->text('observations')->nullable();

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $columns    = ['nomenclature_main','number_main','number','city_id'];
            $table->unique( $columns,'idx_address' );
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('headquarters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('main')->default(0);
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->references('id')->on('addresses');

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('clients_has_equipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('internal_id')->unique();
            $table->string('serial')->unique()->nullable();
            $table->text('observations')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('preventive_services')->default(0);
            $table->boolean('preventive_services_first')->default(0);
            $table->boolean('schedule_assigned')->default(0);

            $table->unsignedBigInteger('equipment_id'); //Relations many has many
            $table->foreign('equipment_id')->references('id')->on('equipments');

            $table->unsignedBigInteger('client_id'); //Relations many has many
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');

            $table->unsignedBigInteger('headquarter_id');
            $table->foreign('headquarter_id')->references('id')->on('headquarters');

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('active')->default(1);
            $table->boolean('service_order')->default(0);
            $table->date('last_date')->nullable();
            $table->date('next_date')->nullable();
            $table->integer('days')->nullable();
            $table->integer('frequency')->nullable();
            $table->text('observations')->nullable();
            $table->enum('status',['A tiempo','Urgente','Agendada','Agendada-Orden','Por vencer'])->default('A tiempo');

            $table->unsignedBigInteger('preventive_routine_id');
            $table->foreign('preventive_routine_id')->references('id')->on('preventive_routines');

            $table->unsignedBigInteger('client_has_equipment_id');
            $table->foreign('client_has_equipment_id')->references('id')->on('clients_has_equipments');

            $table->integer('equipment_id_flag')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('headquarters');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('clients_has_equipments');
        Schema::dropIfExists('schedules');
    }
};
