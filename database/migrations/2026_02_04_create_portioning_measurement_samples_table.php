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
        Schema::create('portioning_measurement_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measure_id')->constrained('portioning_measurements')->onDelete('cascade');
            $table->integer('sample_number');
            $table->decimal('sample_value', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portioning_measurement_samples');
    }
};
