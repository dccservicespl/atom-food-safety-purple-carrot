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
        

        Schema::create('mixing_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->decimal('ph_min', 5, 2);
            $table->decimal('ph_max', 5, 2);
            $table->decimal('temperature', 5, 2);
            $table->decimal('weight', 10, 2);
            $table->enum('status', ['Active', 'Inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mixing_items');
    }
};
