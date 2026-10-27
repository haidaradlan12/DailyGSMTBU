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
        Schema::create('umsticket_settings', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint_url')->default('https://umsticket.artyc.id');
            $table->string('api_key')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable();
            $table->boolean('auto_sync')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->string('last_sync_status')->nullable(); // success, failed
            $table->text('last_sync_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umsticket_settings');
    }
};
