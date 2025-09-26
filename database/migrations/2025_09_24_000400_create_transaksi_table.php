<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained('penyewaans')->cascadeOnDelete();
            $table->unsignedInteger('jumlah');
            $table->enum('metode_pembayaran', ['cash','transfer','ewallet']);
            $table->enum('status', ['pending','paid','failed','refunded'])->default('pending');
            $table->timestamp('tanggal')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
