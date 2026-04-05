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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->foreignId('id_bus');
            $table->string('jenis_user', 50);

            $table->decimal('avg_safety', 8, 2);
            $table->decimal('avg_operational', 8, 2);
            $table->decimal('avg_comfort', 8, 2);
            $table->decimal('nilai_fuzzy', 8, 2)->nullable();

            $table->string('kategori_prioritas', 100)->nullable();
            $table->string('status_perbaikan', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_bus')->references('id')->on('bus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
