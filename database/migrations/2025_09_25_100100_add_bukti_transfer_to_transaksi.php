<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('bukti_transfer')->nullable()->after('status');
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending')->after('bukti_transfer');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer', 'status_verifikasi']);
        });
    }
};