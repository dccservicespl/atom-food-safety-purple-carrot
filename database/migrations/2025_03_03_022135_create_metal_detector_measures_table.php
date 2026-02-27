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
        Schema::create('metal_detector_measures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_measure_id');
            $table->date('measure_date');
            $table->time('measure_time');
            $table->unsignedBigInteger('metal_detector_item_id');
            $table->enum('md_model_result', ['Kick out', 'Belt Stop']);
            $table->enum('2mm_fe', ['P', 'F']);
            $table->enum('3mm_nfe', ['P', 'F']);
            $table->enum('4mm_ss', ['P', 'F']);
            $table->enum('confirm_label', ['P', 'F']);
            $table->text('comments')->nullable();
            $table->string('initial');
            $table->enum('status', ['Pending', 'Completed', 'Verified'])->default('Pending');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewed_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metal_detector_measures');
    }
};
