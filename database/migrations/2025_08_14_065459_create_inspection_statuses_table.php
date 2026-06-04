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
        Schema::create('inspection_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('inspection_id');
            $table->integer('user_id');
            $table->enum('inspec_status', ['S', 'P', 'F', 'V']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_statuses');
    }
};
