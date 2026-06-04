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
        Schema::create('purple_carrot_item_msts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained('purple_carrot_categories')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->text('component_details')->nullable();
            $table->string('label');
            $table->string('unite')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purple_carrot_item_msts');
    }
};
