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
        Schema::create('downloads_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pin_id')->constrained('pins')->onDelete('cascade');
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->timestamp('downloaded_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads_log');
    }
};
