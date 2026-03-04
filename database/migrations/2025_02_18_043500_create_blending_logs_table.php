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
        Schema::create('blending_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_measure_id');
            $table->date('measure_date');
            $table->time('measure_time');
            $table->unsignedBigInteger('blending_item_id');
            $table->unsignedBigInteger('blending_measure_id');
            $table->decimal('ph_result_old', 8, 2)->nullable();
            $table->decimal('ph_result_new', 8, 2)->nullable();
            $table->decimal('temperature_old', 8, 2)->nullable();
            $table->decimal('temperature_new', 8, 2)->nullable();
            $table->enum('appearance_old', ['P', 'F'])->nullable();
            $table->enum('appearance_new', ['P', 'F'])->nullable();
            $table->enum('odor_old', ['P', 'F'])->nullable();
            $table->enum('odor_new', ['P','F'])->nullable();
            $table->enum('taste_old',['P','F'])->nullable();
            $table->enum('taste_new',['P','F'])->nullable();
            $table->text('comments_old')->nullable();
            $table->text('comments_new')->nullable();
            $table->text('initial_old')->nullable();
            $table->text('initial_new')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            // Foreign keys (Optional: Uncomment if needed)
            // $table->foreign('daily_measure_id')->references('id')->on('daily_measures')->onDelete('cascade');
            // $table->foreign('blending_item_id')->references('id')->on('blending_items')->onDelete('cascade');
            // $table->foreign('blending_measure_id')->references('id')->on('blending_measures')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blending_logs');
    }
};
