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




        Schema::create('visit_reasons', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);

            $table->timestamps();
        });

        Schema::create('quotation_status', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);

            $table->timestamps();
        });



        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->string('client_name')->nullable();
            $table->string('headquarter_name')->nullable();
            $table->text('observations')->nullable();
            $table->text('report')->nullable();
            $table->boolean('status')->default(1);

            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->unsignedBigInteger('headquarter_id')->nullable();
            $table->foreign('headquarter_id')->references('id')->on('headquarters');
            $table->unsignedBigInteger('visit_reason_id');
            $table->foreign('visit_reason_id')->references('id')->on('visit_reasons');

            $table->timestamps();
        });




        Schema::create('visits_users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('visit_id');
            $table->foreign('visit_id')->references('id')->on('visits');

            $table->timestamps();
        });

        Schema::create('quotations', function (Blueprint $table) {

            $table->id();

            $table->string('number')->unique();
            $table->dateTime('date');
            $table->dateTime('expiration_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->string('client_name');
            $table->string('headquarter_name');
            $table->string('quotation_status_name');

            $table->foreignId('quotation_status_id')
                ->constrained('quotation_status');

            $table->foreignId('client_id')
                ->constrained('clients');

            $table->foreignId('headquarter_id')
                ->constrained('headquarters');

            $table->timestamps();

        });

        Schema::create('quotations_has_visits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('quotation_id');
            $table->foreign('quotation_id')->references('id')->on('quotations')
                ->onDelete('cascade');

            $table->unsignedBigInteger('visit_id');
            $table->foreign('visit_id')->references('id')->on('visits')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations_has_visits');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('visit_reasons');
        Schema::dropIfExists('visits_users');
        Schema::dropIfExists('quotation_status');
    }
};
