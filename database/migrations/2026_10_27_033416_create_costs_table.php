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
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('category'); // tiket, npe, office, mess, building
            $table->decimal('plan_amount', 15, 2)->default(0);
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['date', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costs');
    }
};
