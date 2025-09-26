<?php

// Simple test script to verify the system works
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test database connection
    echo "=== Testing Database Connection ===\n";
    $users = \App\Models\User::count();
    echo "Users count: $users\n";
    
    $motors = \App\Models\Motor::count();
    echo "Motors count: $motors\n";
    
    $penyewaans = \App\Models\Penyewaan::count();
    echo "Penyewaans count: $penyewaans\n";
    
    $transaksis = \App\Models\Transaksi::count();
    echo "Transaksis count: $transaksis\n";

    // Test models
    echo "\n=== Testing Models ===\n";
    $admin = \App\Models\User::where('role', 'admin')->first();
    if ($admin) {
        echo "Admin found: {$admin->name} ({$admin->email})\n";
    }
    
    $pemilik = \App\Models\User::where('role', 'pemilik')->first();
    if ($pemilik) {
        echo "Pemilik found: {$pemilik->name} ({$pemilik->email})\n";
    }
    
    $penyewa = \App\Models\User::where('role', 'penyewa')->first();
    if ($penyewa) {
        echo "Penyewa found: {$penyewa->name} ({$penyewa->email})\n";
    }

    // Test motor with tarif
    echo "\n=== Testing Motor & Tarif ===\n";
    $motorWithTarif = \App\Models\Motor::with('tarifRental')->first();
    if ($motorWithTarif) {
        echo "Motor: {$motorWithTarif->merk} - Status: {$motorWithTarif->status}\n";
        if ($motorWithTarif->tarifRental) {
            $tarif = $motorWithTarif->tarifRental;
            echo "Tarif harian: Rp " . number_format($tarif->tarif_harian) . "\n";
        } else {
            echo "No tarif found for this motor\n";
        }
    }

    // Test create penyewaan
    echo "\n=== Testing Create Penyewaan ===\n";
    if ($penyewa && $motorWithTarif) {
        $penyewaan = \App\Models\Penyewaan::create([
            'penyewa_id' => $penyewa->id,
            'motor_id' => $motorWithTarif->id,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(3)->format('Y-m-d'),
            'tipe_durasi' => 'harian',
            'harga' => 150000,
            'status' => 'menunggu_pembayaran'
        ]);
        echo "Penyewaan created with ID: {$penyewaan->id}\n";

        // Test create transaksi
        $transaksi = \App\Models\Transaksi::create([
            'pemesanan_id' => $penyewaan->id,
            'jumlah' => 150000,
            'metode_pembayaran' => 'transfer',
            'status' => 'pending',
            'tanggal' => now(),
            'status_verifikasi' => 'pending'
        ]);
        echo "Transaksi created with ID: {$transaksi->id}\n";
        
        // Test update status
        $penyewaan->update(['status' => 'menunggu_konfirmasi']);
        echo "Penyewaan status updated to: {$penyewaan->status}\n";
        
        // Test konfirmasi pembayaran
        $transaksi->update(['status_verifikasi' => 'verified', 'status' => 'paid']);
        $penyewaan->update([
            'status' => 'disewa',
            'tanggal_konfirmasi' => now()
        ]);
        echo "Payment confirmed - Status: {$penyewaan->status}\n";
    }

    echo "\n=== Test Complete - System Working! ===\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}