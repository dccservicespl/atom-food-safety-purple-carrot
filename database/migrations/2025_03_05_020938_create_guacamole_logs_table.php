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

        Schema::create('guacamole_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_measure_id');
            $table->date('measure_date');
            $table->time('measure_time');
            $table->unsignedBigInteger('guacamole_item_id');
            $table->unsignedBigInteger('guacamole_measure_id');
            $table->enum('md_model_result_old', ['Kick out', 'Belt Stop']);
            $table->enum('md_model_result_new', ['Kick out', 'Belt Stop']);
            $table->enum('md_fe_old', ['P', 'F']);
            $table->enum('md_fe_new', ['P', 'F']);
            $table->enum('md_nfe_old', ['P', 'F']);
            $table->enum('md_nfe_new', ['P', 'F']);
            $table->enum('md_st_old', ['P', 'F']);
            $table->enum('md_st_new', ['P', 'F']);
            $table->enum('sc_batch_1_old', ['P', 'F']);
            $table->enum('sc_batch_1_new', ['P', 'F']);
            $table->enum('sc_batch_2_old', ['P', 'F']);
            $table->enum('sc_batch_2_new', ['P', 'F']);
            $table->decimal('weight_checks_1_old', 8, 2);
            $table->decimal('weight_checks_1_new', 8, 2);
            $table->decimal('weight_checks_2_old', 8, 2);
            $table->decimal('weight_checks_2_new', 8, 2);
            $table->decimal('weight_checks_3_old', 8, 2);
            $table->decimal('weight_checks_3_new', 8, 2);
            $table->decimal('weight_checks_4_old', 8, 2);
            $table->decimal('weight_checks_4_new', 8, 2);
            $table->decimal('oxygen_levels_1_old', 5, 2);
            $table->decimal('oxygen_levels_1_new', 5, 2);
            $table->decimal('oxygen_levels_2_old', 5, 2);
            $table->decimal('oxygen_levels_2_new', 5, 2);
            $table->decimal('oxygen_levels_3_old', 5, 2);
            $table->decimal('oxygen_levels_3_new', 5, 2);
            $table->decimal('oxygen_levels_4_old', 5, 2);
            $table->decimal('oxygen_levels_4_new', 5, 2);
            $table->integer('total_containers_old');
            $table->integer('total_containers_new');
            $table->boolean('retains_collected_old');
            $table->boolean('retains_collected_new');
            $table->date('best_by_date_old');
            $table->date('best_by_date_new');
            $table->string('item_bar_code_old');
            $table->string('item_bar_code_new');
            $table->text('comments_old')->nullable();
            $table->text('comments_new')->nullable();
            $table->string('initial_old');
            $table->string('initial_new');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guacamole_logs');
    }
};
