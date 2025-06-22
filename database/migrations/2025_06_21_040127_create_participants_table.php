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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nik')->nullable();
            $table->string('university')->nullable();
            $table->string('phone');
            $table->string('participant_code')->unique();
            $table->string('paper_title')->nullable();
            $table->string('qrcode')->nullable(); // path ke file QR atau base64
            $table->enum('status', ['unverified', 'verified', 'arrived'])->default('unverified');
            $table->boolean('seminar_kit_status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};