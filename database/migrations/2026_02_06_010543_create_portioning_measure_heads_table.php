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
        Schema::create('portioning_measure_heads', function (Blueprint $table) {
            $table->id();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->string('measure_by', 100)->nullable();
            $table->string('equipment', 150)->nullable();
            $table->string('table_name', 150)->nullable();
            $table->unsignedInteger('people_qty')->nullable();
            $table->string(25)->nullable();
            $table->boolean('pre_op_complete')->default(false);
            $table->enum('status', ['pending', 'completed'])
                  ->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portioning_measure_heads');
    }
};
