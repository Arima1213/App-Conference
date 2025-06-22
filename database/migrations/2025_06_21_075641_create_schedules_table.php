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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_id')->constrained('speakers')->onDelete('cascade');
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('title');        // Judul sesi, e.g., "Teamwork all you need"
            $table->string('subtitle')->nullable(); // Subjudul, e.g., "to know the manager"
            $table->text('description')->nullable(); // Deskripsi lengkap
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};