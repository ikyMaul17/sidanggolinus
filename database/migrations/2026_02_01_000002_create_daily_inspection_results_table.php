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
        Schema::create('daily_inspection_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_inspection_id');
            $table->unsignedBigInteger('inspection_item_id');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('daily_inspection_id')->references('id')->on('daily_inspections')->onDelete('cascade');
            $table->foreign('inspection_item_id')->references('id')->on('inspection_items')->onDelete('cascade');
            $table->unique(['daily_inspection_id', 'inspection_item_id'], 'daily_inspect_id_inspect_item_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_inspection_results');
    }
};
