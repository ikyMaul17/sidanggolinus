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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10);
            $table->integer('id_bus')->nullable();
            $table->integer('id_penumpang')->nullable();
            $table->string('status', 20)->default('pending');
            $table->integer('estimasi_waktu');
            $table->string('rute', 20);
            $table->integer('id_penjemputan');
            $table->integer('id_tujuan');
            $table->string('flag_feedback', 20)->default('true');
            $table->string('flag_reminder', 20)->default('true');
            $table->string('flag_konfirmasi', 20)->default('true');
            $table->string('flag_kendala', 20)->default('true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
