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
        Schema::create('mixing_measures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_measure_id');
            $table->date('measure_date');
            $table->time('measure_time');
            $table->unsignedBigInteger('mixing_item_id');
            $table->enum('odor', ['P', 'F']);
            $table->string('appearance')->nullable();
            $table->decimal('temperature_1', 5, 2)->nullable();
            $table->decimal('temperature_2', 5, 2)->nullable();
            $table->decimal('weight_1', 10, 2)->nullable();
            $table->decimal('weight_2', 10, 2)->nullable();
            $table->decimal('weight_3', 10, 2)->nullable();
            $table->decimal('weight_4', 10, 2)->nullable();
            $table->string('table_line')->nullable();
            $table->string('scale')->nullable();
            $table->text('comments')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewed_note')->nullable();

            // Foreign key constraints (if needed)
            // $table->foreign('daily_measure_id')->references('id')->on('daily_measures')->onDelete('cascade');
            // $table->foreign('mixing_item_id')->references('id')->on('mixing_items')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mixing_measures');
    }
};
