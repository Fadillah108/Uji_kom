<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Models\Motor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestController extends Controller
{
    public function index()
    {
        return view('test-payment');
    }

    public function createPenyewaan(Request $request)
    {
        try {
            $motor = Motor::findOrFail($request->motor_id);
            $penyewa = User::where('role', 'penyewa')->findOrFail($request->penyewa_id);
            
            // Calculate price (example: 3 days rental)
            $days = Carbon::parse($request->tanggal_selesai)->diffInDays(Carbon::parse($request->tanggal_mulai)) + 1;
            $tarif = $motor->tarifRentals()->first();
            $harga = $tarif ? $tarif->tarif_harian * $days : 50000 * $days;

            $penyewaan = Penyewaan::create([
                'penyewa_id' => $request->penyewa_id,
                'motor_id' => $request->motor_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tipe_durasi' => 'harian',
                'harga' => $harga,
                'status' => 'menunggu_pembayaran'
            ]);

            // Update motor status
            $motor->update(['status' => 'disewa']);

            return redirect()->back()->with('success', "Penyewaan created with ID: {$penyewaan->id}, Harga: Rp " . number_format($harga));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function createPayment(Request $request)
    {
        try {
            $penyewaan = Penyewaan::findOrFail($request->penyewaan_id);
            
            $transaksi = Transaksi::create([
                'pemesanan_id' => $request->penyewaan_id,
                'jumlah' => $request->jumlah,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'pending',
                'tanggal' => now(),
                'status_verifikasi' => 'pending'
            ]);

            // Update penyewaan status
            $penyewaan->update(['status' => 'menunggu_konfirmasi']);

            return redirect()->back()->with('success', "Payment created with ID: {$transaksi->id}");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}