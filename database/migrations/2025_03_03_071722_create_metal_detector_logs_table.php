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
        Schema::create('metal_detector_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_measure_id');
            $table->date('measure_date');
            $table->time('measure_time');
            $table->unsignedBigInteger('metal_detector_item_id');
            $table->unsignedBigInteger('metal_detector_measure_id');
        
            // Measurement results
            $table->string('md_model_result_old')->nullable();
            $table->string('md_model_result_new')->nullable();
        
            // Metal detector values (ENUM type for Pass/Fail)
            $table->enum('mm_2_fe_old', ['P', 'F'])->nullable();
            $table->enum('mm_2_fe_new', ['P', 'F'])->nullable();
            $table->enum('mm_3_nfe_old', ['P', 'F'])->nullable();
            $table->enum('mm_3_nfe_new', ['P', 'F'])->nullable();
            $table->enum('mm_4_ss_old', ['P', 'F'])->nullable();
            $table->enum('mm_4_ss_new', ['P', 'F'])->nullable();
        
            // Labels and comments
            $table->enum('confirm_label_old', ['P', 'F'])->nullable();
            $table->enum('confirm_label_new', ['P', 'F'])->nullable();
            $table->text('comments_old')->nullable();
            $table->text('comments_new')->nullable();
        
            // Initial values
            $table->string('initial_old')->nullable();
            $table->string('initial_new')->nullable();
        
            // User and timestamps
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metal_detector_logs');
    }
};
