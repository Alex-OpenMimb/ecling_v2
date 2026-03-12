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

        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable();
            $table->string('day')->nullable();
            $table->string('start_hour')->nullable();
            $table->string('end_hour')->nullable();
            $table->boolean('closed')->default(0);
            $table->enum('activity',['Preventiva','Correctiva','Instalación','Mixta','Otra']);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->boolean('service_order')->default(0)->comment('Para indicar si es un evento de orden de servicio');
            $table->timestamps();

        });

        Schema::create('service_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial');
            $table->enum('status',['Abierta','Cerrada','Rechazada','Declinada','Facturada']);
            $table->text('observations_status')->nullable();
            $table->text('observations')->nullable();
            $table->enum('activity',['Preventiva','Correctiva','Instalación','Mixta']);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->foreign('rejected_by')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('service_orders_has_events', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('service_order_id');
            $table->foreign('service_order_id')->references('id')->on('service_orders');

            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events');


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('service_orders_has_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('service_order_id');
            $table->foreign('service_order_id')->references('id')->on('service_orders');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('schedules_has_service_orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('service_order_id');
            $table->foreign('service_order_id')->references('id')->on('service_orders');

            $table->unsignedBigInteger('schedule_id');
            $table->foreign('schedule_id')->references('id')->on('schedules');

            $table->unsignedBigInteger('client_has_equipment_id')->nullable();
            $table->foreign('client_has_equipment_id')->references('id')->on('clients_has_equipments');


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('schedules_has_events', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')
                ->onDelete('cascade');

            $table->unsignedBigInteger('schedule_id');
            $table->foreign('schedule_id')->references('id')->on('schedules');

            $table->unsignedBigInteger('service_order_id')->nullable();
            $table->foreign('service_order_id')->references('id')->on('service_orders');

            $columns    = ['event_id','schedule_id'];
            $table->unique( $columns,'idx_events' );

            $table->timestamps();

        });

        Schema::create('events_has_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')
                ->onDelete('cascade');

            $columns    = ['event_id','user_id'];
            $table->unique( $columns,'idx_events' );

            $table->timestamps();

        });

        Schema::create('corrective_services', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->enum('status',['Abierto','Cerrado','Rechazado','Agendado','Agendado-Orden'])->default('Abierto');
            $table->text('observations')->nullable();

            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events');

            $table->unsignedBigInteger('service_order_id')->nullable();
            $table->foreign('service_order_id')->references('id')->on('service_orders');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();


        });


        Schema::create('clients_equipments_correctives', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('client_has_equipment_id');
            $table->foreign('client_has_equipment_id')->references('id')->on('clients_has_equipments');

            $table->unsignedBigInteger('corrective_activity_id');
            $table->foreign('corrective_activity_id')->references('id')->on('corrective_activities');

            $table->unsignedBigInteger('equipment_class_id');
            $table->foreign('equipment_class_id')->references('id')->on('equipment_classes');

            $table->unsignedBigInteger('corrective_service_id');
            $table->foreign('corrective_service_id')->references('id')->on('corrective_services')
                ->onDelete('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('service_orders');
        Schema::dropIfExists('service_orders_has_users');
        Schema::dropIfExists('schedules_has_service_orders');
        Schema::dropIfExists('schedules_has_events');
        Schema::dropIfExists('events_has_users');
        Schema::dropIfExists('corrective_services');
        Schema::dropIfExists('clients_equipments_corrective');
        Schema::dropIfExists('service_orders_has_events');
    }
};
