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
        Schema::table('lunar_orders', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable();
            $table->index('completed_at', 'completed_at_index');
            $table->dateTime('cancelled_at')->nullable();
            $table->index('cancelled_at', 'cancelled_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lunar_orders', function (Blueprint $table) {
            $table->dropIndex('completed_at_index');
            $table->dropColumn('completed_at');
            $table->dropIndex('cancelled_at_index');
            $table->dropColumn('cancelled_at');
        });
    }
};
