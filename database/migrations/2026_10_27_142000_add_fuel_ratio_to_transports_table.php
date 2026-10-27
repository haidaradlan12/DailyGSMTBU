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
        Schema::table('transports', function (Blueprint $table) {
            $table->decimal('fuel_ratio_bus', 5, 2)->default(3.60)->after('coverage_pengantaran'); // km/l (target >= 3.5)
            $table->decimal('fuel_ratio_lv', 5, 2)->default(7.20)->after('fuel_ratio_bus'); // km/l (target >= 7.0)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropColumn(['fuel_ratio_bus', 'fuel_ratio_lv']);
        });
    }
};
