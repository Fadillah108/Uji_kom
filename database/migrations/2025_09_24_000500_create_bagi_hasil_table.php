<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bagi_hasils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained('penyewaans')->cascadeOnDelete();
            $table->unsignedInteger('bagi_hasil_pemilik');
            $table->unsignedInteger('bagi_hasil_admin');
            $table->timestamp('settled_at')->nullable();
            $table->timestamp('tanggal')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bagi_hasils');
    }
};
