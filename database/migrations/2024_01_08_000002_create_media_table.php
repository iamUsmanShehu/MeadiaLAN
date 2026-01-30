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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['movie', 'series']);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('filename')->unique();
            $table->bigInteger('size');
            $table->string('poster_url')->nullable();
            $table->integer('duration')->nullable(); // in minutes
            $table->string('year')->nullable();
            $table->string('director')->nullable();
            $table->text('cast')->nullable();
            $table->integer('episodes')->nullable(); // for series
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
