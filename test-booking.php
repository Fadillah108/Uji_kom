<?php

require_once 'bootstrap/app.php';

use App\Models\Motor;
use App\Models\User;
use App\Models\TarifRental;
use App\Models\Penyewaan;

echo "=== Testing Booking System ===\n";

try {
    // Get test data
    $penyewa = User::where('role', 'penyewa')->first();
    if (!$penyewa) {
        echo "❌ No penyewa user found\n";
        exit;
    }
    echo "✅ Penyewa: {$penyewa->name} ({$penyewa->email})\n";

    // Get available motor with tarif
    $motor = Motor::with('tarifRental')
        ->where('status', 'tersedia')
        ->whereHas('tarifRental')
        ->first();
    
    if (!$motor) {
        echo "❌ No available motor with tarif found\n";
        exit;
    }
    echo "✅ Motor: {$motor->merk} - Status: {$motor->status}\n";
    echo "✅ Tarif harian: Rp " . number_format($motor->tarifRental->tarif_harian) . "\n";

    // Test create booking
    echo "\n=== Creating Test Booking ===\n";
    
    $tanggalMulai = date('Y-m-d');
    $tanggalSelesai = date('Y-m-d', strtotime('+2 days'));
    $durasi = 3; // 3 hari
    $harga = $motor->tarifRental->tarif_harian * $durasi;
    
    $penyewaan = Penyewaan::create([
        'penyewa_id' => $penyewa->id,
        'motor_id' => $motor->id,
        'tanggal_mulai' => $tanggalMulai,
        'tanggal_selesai' => $tanggalSelesai,
        'tipe_durasi' => 'harian',
        'harga' => $harga,
        'status' => 'menunggu_pembayaran'
    ]);
    
    echo "✅ Penyewaan created with ID: {$penyewaan->id}\n";
    echo "✅ Total harga: Rp " . number_format($harga) . " ({$durasi} hari)\n";
    echo "✅ Status: {$penyewaan->status}\n";
    
    // Verify booking exists
    $verifyBooking = Penyewaan::with(['penyewa', 'motor'])->find($penyewaan->id);
    if ($verifyBooking) {
        echo "✅ Booking verified:\n";
        echo "   - Penyewa: {$verifyBooking->penyewa->name}\n";
        echo "   - Motor: {$verifyBooking->motor->merk}\n";
        echo "   - Periode: {$verifyBooking->tanggal_mulai} s/d {$verifyBooking->tanggal_selesai}\n";
        echo "   - Status: {$verifyBooking->status}\n";
    }
    
    echo "\n✅ Booking system working correctly!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}