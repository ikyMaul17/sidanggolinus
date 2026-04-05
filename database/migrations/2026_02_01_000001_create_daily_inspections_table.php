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
        Schema::create('daily_inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bus');
            $table->unsignedBigInteger('id_supir');
            $table->dateTime('inspected_at');
            $table->timestamps();

            $table->foreign('id_bus')->references('id')->on('bus')->onDelete('cascade');
            $table->foreign('id_supir')->references('id')->on('users')->onDelete('cascade');
            $table->index(['id_bus', 'id_supir', 'inspected_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_inspections');
    }
};
