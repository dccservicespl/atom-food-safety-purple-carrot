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
        Schema::create('portioning_measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('measure_id')->index();

            $table->date('measure_date')->nullable();
            $table->time('measure_time')->nullable();

            $table->string('measure_by', 100)->nullable();
            $table->string('equipment', 150)->nullable();
            $table->string('table', 150)->nullable();
            $table->string('pre_op_complete')->nullable();
            $table->unsignedInteger('people_qty')->nullable();
            $table->decimal('scale', 10, 2)->nullable();
            $table->string('lot_number', 100)->nullable();
            $table->string('temperature', 25)->nullable();
            $table->string('allergen', 255)->nullable();
            $table->string('allergen_result', 255)->nullable();
            $table->string('pack_size', 100)->nullable();
            $table->string('kit_letter', 50)->nullable();
            $table->string('qty_produces_final')->nullable();
            $table->string('fs_initial', 50)->nullable();
            $table->string('attachment', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('reviewed_by', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portioning_measurements');
    }
};
