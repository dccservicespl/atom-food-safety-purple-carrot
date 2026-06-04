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
        Schema::create('portioning_order_heads', function (Blueprint $table) {
            $table->bigIncrements('order_head_id');
            $table->string('week');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('updated_by');
            $table->string('total_qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portioning_order_heads');
    }
};
