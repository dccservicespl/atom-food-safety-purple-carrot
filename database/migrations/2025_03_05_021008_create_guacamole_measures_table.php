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
        Schema::create('guacamole_measures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_measure_id');
            $table->date('measure_date');
            $table->time('measure_time');
            $table->unsignedBigInteger('guacamole_item_id');
            $table->string('lot_number');
            $table->decimal('temperature', 5, 2);
            $table->enum('md_model_result', ['Kick out', 'Belt Stop']);
            $table->enum('md_fe', ['P', 'F']);
            $table->enum('md_nfe', ['P', 'F']);
            $table->enum('md_st', ['P', 'F']);
            $table->enum('sc_batch_1', ['P', 'F']);
            $table->enum('sc_batch_2', ['P', 'F']);
            $table->decimal('weight_checks_1', 8, 2);
            $table->decimal('weight_checks_2', 8, 2);
            $table->decimal('weight_checks_3', 8, 2);
            $table->decimal('weight_checks_4', 8, 2);
            $table->decimal('oxygen_levels_1', 5, 2);
            $table->decimal('oxygen_levels_2', 5, 2);
            $table->decimal('oxygen_levels_3', 5, 2);
            $table->decimal('oxygen_levels_4', 5, 2);
            $table->integer('total_containers');
            $table->boolean('retains_collected');
            $table->date('best_by_date');
            $table->text('comments')->nullable();
            $table->string('initial');
            $table->string('item_bar_code');
            $table->string('status');
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
        Schema::dropIfExists('guacamole_measures');
    }
};
