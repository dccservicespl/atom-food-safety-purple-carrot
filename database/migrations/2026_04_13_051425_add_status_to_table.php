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
        Schema::table('portioning_order_heads', function (Blueprint $table) {
            $table->enum('status', ['Not Started', 'In Process', 'Completed'])->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portioning_order_heads', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
