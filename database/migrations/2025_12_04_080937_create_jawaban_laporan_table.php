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
        Schema::create('jawaban_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id');
            $table->integer('pertanyaan_id');
            $table->integer('nilai');
            $table->timestamps();

            $table->foreign('laporan_id')->references('id')->on('laporan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_laporan');
    }
};
