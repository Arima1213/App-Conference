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
        Schema::table('payments', function (Blueprint $table) {
            $table->text('snap_token')->nullable()->after('va_number');
            $table->timestamp('snap_token_created_at')->nullable()->after('snap_token');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'challenge', 'expired'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'snap_token_created_at']);
        });
    }
};
