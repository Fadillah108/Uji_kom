<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            // Add new status for rental returns
            $table->enum('status', ['menunggu_pembayaran','dibayar','menunggu_konfirmasi','disewa','perlu_dikembalikan','selesai','dibatalkan'])->default('menunggu_pembayaran')->change();
            $table->timestamp('tanggal_pembayaran')->nullable()->after('harga');
            $table->timestamp('tanggal_konfirmasi')->nullable()->after('tanggal_pembayaran');
            $table->timestamp('tanggal_pengembalian')->nullable()->after('tanggal_konfirmasi');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            $table->enum('status', ['menunggu_pembayaran','menunggu_konfirmasi','disewa','selesai','dibatalkan'])->default('menunggu_pembayaran')->change();
            $table->dropColumn(['tanggal_pembayaran', 'tanggal_konfirmasi', 'tanggal_pengembalian']);
        });
    }
};