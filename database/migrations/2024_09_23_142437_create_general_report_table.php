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
        Schema::create('general_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial');
            $table->date('date')->nullable();
            $table->string('start_hour')->nullable();
            $table->string('end_hour')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('time_spent')->nullable();
            $table->string('first_photo')->nullable();
            $table->string('second_photo')->nullable();
            $table->integer('operator')->default(1);
            $table->text('description_service')->nullable();
            $table->text('observations')->nullable();
            $table->text('pending_note')->nullable();
            $table->string('receptor_name')->nullable();
            $table->string('request_name')->nullable();
            $table->string('receptor_signature')->nullable();
            $table->string('receptor_document')->nullable();
            $table->enum('receptor_document_type',['cc'])->nullable();
            $table->string('receptor_position')->nullable();
            $table->string('preventive_routine')->nullable();
            $table->boolean('preventive');
            $table->boolean('corrective');
            $table->boolean('stored')->default(0);
            $table->boolean('pending')->default(0);
            $table->enum('sent',['Enviando','Rechazado','Entregado'])->nullable();
            $table->enum('status',['Abierto','Cerrado','Cancelado'])->default('Abierto');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('headquarter_id');
            $table->foreign('headquarter_id')->references('id')->on('headquarters');

            $table->unsignedBigInteger('client_has_equipment_id')->nullable();
            $table->foreign('client_has_equipment_id')->references('id')->on('clients_has_equipments');

            $table->unsignedBigInteger('equipment_class_id');
            $table->foreign('equipment_class_id')->references('id')->on('equipment_classes');

            $table->unsignedBigInteger('service_order_id');
            $table->foreign('service_order_id')->references('id')->on('service_orders');
            $table->string('stored_time')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_report_preventive', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('preventive_activity_id');
            $table->foreign('preventive_activity_id')->references('id')->on('preventive_activities');

            $table->unsignedBigInteger('general_report_id');
            $table->foreign('general_report_id')->references('id')->on('general_reports');


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_report_corrective', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('corrective_activity_id');
            $table->foreign('corrective_activity_id')->references('id')->on('corrective_activities');

            $table->unsignedBigInteger('general_report_id');
            $table->foreign('general_report_id')->references('id')->on('general_reports');


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_report_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('amount');

            $table->unsignedBigInteger('material_id');
            $table->foreign('material_id')->references('id')->on('materials');

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');

            $table->unsignedBigInteger('general_report_id');
            $table->foreign('general_report_id')->references('id')->on('general_reports');


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_report_spare_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('amount');

            $table->unsignedBigInteger('spare_part_id');
            $table->foreign('spare_part_id')->references('id')->on('spare_parts');

            $table->unsignedBigInteger('general_report_id');
            $table->foreign('general_report_id')->references('id')->on('general_reports');


            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('pending_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('pending_note');
            $table->text('management_observations')->nullable();
            $table->enum('status',['Abierto','Cerrado','Rechazado'])->default('Abierto');
            $table->integer('client_id_flag')->nullable();
            $table->integer('headquarter_id_flag')->nullable();
            $table->integer('client_has_equipment_id_flag')->nullable();
            $table->integer('equipment_class_id_flag')->nullable();
            $table->integer('service_order_id_flag')->nullable();
            $table->boolean('preventive')->default(0);
            $table->boolean('corrective')->default(0);

            $table->unsignedBigInteger('general_report_id');
            $table->foreign('general_report_id')->references('id')->on('general_reports');


            $table->timestamps();
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_reports');
        Schema::dropIfExists('general_report_preventive');
        Schema::dropIfExists('general_report_corrective');
        Schema::dropIfExists('general_report_materials');
        Schema::dropIfExists('general_report_spare_parts');
        Schema::dropIfExists('pending_activities');
    }
};
