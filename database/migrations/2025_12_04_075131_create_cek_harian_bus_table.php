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
        Schema::create('cek_harian_bus', function (Blueprint $table) {
            $table->id();
            $table->integer('id_bus');
            $table->integer('id_supir');

            $table->integer('rem');
            $table->integer('ban');
            $table->integer('oli');
            $table->integer('ac');
            $table->integer('pintu');
            $table->integer('lampu');
            $table->integer('mesin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cek_harian_bus');
    }
};
