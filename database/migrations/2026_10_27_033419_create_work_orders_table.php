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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('department'); // mep, sipil
            $table->string('category'); // minor, mayor, project
            $table->unsignedInteger('waiting_schedule')->default(0); // Menunggu Jadwal
            $table->unsignedInteger('in_progress')->default(0); // Terjadwal & OnProgress
            $table->unsignedInteger('completed')->default(0); // Selesai
            $table->string('source')->default('umsticket'); // umsticket, manual
            $table->timestamp('last_synced_at')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['date', 'department', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
