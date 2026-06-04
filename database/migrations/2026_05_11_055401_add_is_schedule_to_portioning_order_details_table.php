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
        Schema::table('portioning_order_details', function (Blueprint $table) {
            $table->enum('is_scheduled', ['Yes', 'No'])
                ->default('No')
                ->after('scheduled_day');

            $table->date('old_schedule_date')
                ->nullable()
                ->after('is_scheduled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portioning_order_details', function (Blueprint $table) {
            //
        });
    }
};
