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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            $table->foreignId('seminar_fee_id')->constrained('seminar_fees')->onDelete('cascade');
            $table->string('nik')->nullable();
            $table->foreignId('educational_institution_id')
                ->constrained('educational_institutions')
                ->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('participant_code')->unique();
            $table->string('paper_title')->nullable();
            $table->string('qrcode')->nullable();
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