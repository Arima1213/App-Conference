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
        Schema::create('educational_institutions', function (Blueprint $table) {
            $table->id();
            $table->string('lembaga')->nullable();
            $table->string('kelompok_koordinator')->nullable();
            $table->string('npsn')->nullable();
            $table->string('nama_pt')->nullable();
            $table->string('nm_bp')->nullable();
            $table->string('provinsi_pt')->nullable();
            $table->text('jln')->nullable();
            $table->string('kec_pt')->nullable();
            $table->string('kabupaten_kota')->nullable();
            $table->string('website')->nullable();
            $table->string('no_tel')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_institutions');
    }
};
