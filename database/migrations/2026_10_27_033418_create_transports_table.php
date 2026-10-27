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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->decimal('pa_lv', 5, 2)->default(0); // PA LV %
            $table->decimal('atr_lv', 5, 2)->default(0); // ATR LV %
            $table->decimal('pa_bus', 5, 2)->default(0); // PA Bus %
            $table->unsignedInteger('unit_ready_lv')->default(0);
            $table->unsignedInteger('unit_breakdown_lv')->default(0);
            $table->unsignedInteger('unit_ready_bus')->default(0);
            $table->unsignedInteger('unit_breakdown_bus')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
