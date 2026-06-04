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
        Schema::create('mix_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_measure_id');
            $table->date('measure_date');
            $table->time('measure_time');
            $table->unsignedBigInteger('mixing_item_id');
            $table->unsignedBigInteger('mix_measure_id');
            
            $table->string('appearance_old')->nullable();
            $table->string('appearance_new')->nullable();
            $table->string('odor_old')->nullable();
            $table->string('odor_new')->nullable();
            
            $table->decimal('temperature_1_old', 8, 2)->nullable();
            $table->decimal('temperature_1_new', 8, 2)->nullable();
            $table->decimal('temperature_2_old', 8, 2)->nullable();
            $table->decimal('temperature_2_new', 8, 2)->nullable();
            
            $table->decimal('weight_1_old', 10, 3)->nullable();
            $table->decimal('weight_1_new', 10, 3)->nullable();
            $table->decimal('weight_2_old', 10, 3)->nullable();
            $table->decimal('weight_2_new', 10, 3)->nullable();
            $table->decimal('weight_3_old', 10, 3)->nullable();
            $table->decimal('weight_3_new', 10, 3)->nullable();
            $table->decimal('weight_4_old', 10, 3)->nullable();
            $table->decimal('weight_4_new', 10, 3)->nullable();
            
            $table->string('table_line_old')->nullable();
            $table->string('table_line_new')->nullable();
            $table->string('scale_old')->nullable();
            $table->string('scale_new')->nullable();
            $table->text('commets_old')->nullable();
            $table->text('commets_new')->nullable();
            
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mix_logs');
    }
};
