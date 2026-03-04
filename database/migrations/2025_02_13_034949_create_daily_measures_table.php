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
        Schema::create('daily_measures', function (Blueprint $table) {
            $table->id();
            $table->date('measure_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->boolean('is_lock')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_measures');
    }
};
