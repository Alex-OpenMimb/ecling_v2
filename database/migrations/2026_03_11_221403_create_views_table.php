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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->string('client_name')->unique();
            $table->string('headquarter_name')->unique();
            $table->text('observations')->nullable();
            $table->text('report')->nullable();
            $table->boolean('status')->default(0);

            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->unsignedBigInteger('headquarter_id');
            $table->foreign('headquarter_id')->references('id')->on('headquarters');

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
