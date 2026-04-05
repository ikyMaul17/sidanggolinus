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
        Schema::table('laporan', function (Blueprint $table) {
            $table->foreignId('id_pertanyaan');
            $table->dropColumn([
                'id_user',
                'jenis_user',
                'avg_safety',
                'avg_operational',
                'avg_comfort',
            ]);

            $table->foreign('id_pertanyaan')->references('id')->on('pertanyaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->integer('id_user');
            $table->decimal('avg_safety', 8, 2);
            $table->decimal('avg_operational', 8, 2);
            $table->decimal('avg_comfort', 8, 2);
            $table->string('jenis_user', 50);
            $table->dropColumn('id_pertanyaan');
        });
    }
};
