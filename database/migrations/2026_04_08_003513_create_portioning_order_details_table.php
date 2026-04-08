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
        Schema::create('portioning_order_details', function (Blueprint $table) {
            $table->bigIncrements('order_detail_id');
            $table->bigInteger('order_head_id');
            $table->unsignedBigInteger('portioning_category_id');
            $table->date('scheduled_day')->nullable();
            $table->string('letter')->nullable();
            $table->text('component_details')->nullable();
            $table->text('label')->nullable();
            $table->string('allergen')->nullable();
            $table->string('weight')->nullable();
            $table->string('packaging')->nullable();
            $table->string('quantity')->nullable();
            $table->string('film_size')->nullable();
            $table->string('95_percent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portioning_order_details');
    }
};
