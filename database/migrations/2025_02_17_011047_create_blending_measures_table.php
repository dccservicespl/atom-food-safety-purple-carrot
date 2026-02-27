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
        Schema::create('blending_measures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_measure_id');
            $table->date('measure_date');
            $table->time('measure_time');
            $table->unsignedBigInteger('blending_item_id');
            $table->decimal('ph_result', 5, 2);
            $table->decimal('temperature', 8, 2);
            $table->enum('appearance', ['P', 'F']);
            $table->enum('odor', ['P', 'F']);
            $table->enum('taste', ['P', 'F']);
            $table->text('comments')->nullable();
            $table->string('initial');
            $table->enum('status', ['Pending', 'Completed', 'Verified'])->default('Pending');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewed_note')->nullable();
            $table->timestamps();
            
            // Foreign keys (Assuming related tables exist)
            $table->foreign('daily_measure_id')->references('id')->on('daily_measures')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blending_measures');
    }
};
