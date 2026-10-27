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
        Schema::create('dorcats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('issue_title');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('percentage')->default(0); // 0 - 100
            $table->string('status')->default('open'); // open, in_progress, resolved
            $table->string('priority')->default('high'); // normal, high, critical
            $table->string('pic_name')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dorcats');
    }
};
