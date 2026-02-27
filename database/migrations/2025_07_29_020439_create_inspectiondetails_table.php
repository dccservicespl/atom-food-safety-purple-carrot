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
        Schema::create('inspectiondetails', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('daily_measure_id');
            $table->unsignedBigInteger('inspection_head_id');
            $table->unsignedBigInteger('measure_categories_id');
            $table->unsignedBigInteger('item_id');

            $table->enum('coo_present', ['P', 'F'])->default('F');
            $table->enum('best_by_accurate', ['P', 'F'])->default('F');
            $table->enum('nutritional_acts', ['P', 'F'])->default('F');
            $table->enum('allergen_statement', ['P', 'F'])->default('F');
            $table->enum('ingredient_statement', ['P', 'F'])->default('F');
            $table->enum('barcode_clear', ['P', 'F'])->default('F');

            $table->string('verify_by')->nullable();
            $table->dateTime('verify_datetime')->nullable();
            $table->text('note')->nullable();

            $table->enum('status', ['V', 'S', 'P', 'F'])->default('F');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspectiondetails');
    }
};
