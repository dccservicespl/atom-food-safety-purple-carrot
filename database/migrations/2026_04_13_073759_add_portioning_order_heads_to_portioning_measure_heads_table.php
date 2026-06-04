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
        Schema::table('portioning_measure_heads', function (Blueprint $table) {
            $table->unsignedBigInteger('portioning_order_head_id')->nullable()->after('id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portioning_measure_heads', function (Blueprint $table) {
            //
        });
    }
};
