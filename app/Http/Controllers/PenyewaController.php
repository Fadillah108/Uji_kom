<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\TarifRental;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class PenyewaController extends BaseController
{
    public function index()
    {
        // Check role
        if (Auth::user()->role !== 'penyewa') {
            abort(403, 'Akses ditolak. Hanya penyewa yang dapat mengakses halaman ini.');
        }
        
        // Tampilkan motor yang statusnya tersedia (aktif/tersedia)
        $motors = Motor::where('status', 'tersedia')
            ->where('is_approved', true)
            ->with(['tarifRental', 'user'])
            ->paginate(12);
        return view('penyewa.motors', compact('motors'));
    }

    public function show($id)
    {
        // Check role
        if (Auth::user()->role !== 'penyewa') {
            abort(403, 'Akses ditolak. Hanya penyewa yang dapat mengakses halaman ini.');
        }
        
        $motor = Motor::with(['tarifRental', 'user'])->findOrFail($id);
        
        // Pastikan motor tersedia dan sudah disetujui admin
        if ($motor->status !== 'tersedia' || !$motor->is_approved) {
            return redirect()->route('penyewa.motors')->with('error', 'Motor tidak tersedia untuk disewa.');
        }
        
        return view('penyewa.motor-detail', compact('motor'));
    }

    public function book($id)
    {
        // Check role
        if (Auth::user()->role !== 'penyewa') {
            abort(403, 'Akses ditolak. Hanya penyewa yang dapat mengakses halaman ini.');
        }
        
        $motor = Motor::with('tarifRental')->findOrFail($id);
        
        // Pastikan motor tersedia untuk disewa dan sudah disetujui admin
        if ($motor->status !== 'tersedia' || !$motor->is_approved) {
            return redirect()->route('penyewa.motors')->with('error', 'Motor tidak tersedia untuk disewa.');
        }

        return view('penyewa.book-motor', compact('motor'));
    }

    public function store(Request $request)
    {
        // Check role
        if (Auth::user()->role !== 'penyewa') {
            abort(403, 'Akses ditolak. Hanya penyewa yang dapat mengakses halaman ini.');
        }
        
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan'
        ]);

        $motor = Motor::with('tarifRental')->findOrFail($request->motor_id);
        
        // Hitung tanggal selesai
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = $this->hitungTanggalSelesai($tanggalMulai, $request->durasi, $request->tipe_durasi);
        
        // Hitung harga total
        $hargaTotal = $this->hitungHargaTotal($motor->tarifRental, $request->durasi, $request->tipe_durasi);

        // Cek apakah motor tersedia di periode tersebut
        $konflik = Penyewaan::where('motor_id', $motor->id)
            ->where(function($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                      ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                      ->orWhere(function($q) use ($tanggalMulai, $tanggalSelesai) {
                          $q->where('tanggal_mulai', '<=', $tanggalMulai)
                            ->where('tanggal_selesai', '>=', $tanggalSelesai);
                      });
            })
            ->whereNotIn('status', ['dibatalkan', 'selesai'])
            ->exists();

        if ($konflik) {
            return back()->withErrors(['tanggal_mulai' => 'Motor sudah disewa pada periode tersebut.']);
        }

        // Buat penyewaan baru
        $penyewaan = Penyewaan::create([
            'penyewa_id' => Auth::id(),
            'motor_id' => $motor->id,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'durasi' => $request->durasi,
            'tipe_durasi' => $request->tipe_durasi,
            'harga' => $hargaTotal,
            'status' => 'menunggu_pembayaran'
        ]);

        return redirect()->route('penyewa.pembayaran', $penyewaan->id)->with('success', 'Pemesanan berhasil! Silakan lakukan pembayaran.');
    }

    private function hitungTanggalSelesai($tanggalMulai, $durasi, $tipeDurasi)
    {
        $durasi = (int) $durasi; // ensure integer for Carbon add* methods
        switch ($tipeDurasi) {
            case 'harian':
                return $tanggalMulai->copy()->addDays($durasi - 1);
            case 'mingguan':
                return $tanggalMulai->copy()->addWeeks($durasi)->subDay();
            case 'bulanan':
                return $tanggalMulai->copy()->addMonths($durasi)->subDay();
            default:
                return $tanggalMulai->copy()->addDays($durasi - 1);
        }
    }

    private function hitungHargaTotal($tarifRental, $durasi, $tipeDurasi)
    {
        $durasi = (int) $durasi; // ensure math uses integer
        if (!$tarifRental) {
            return 0;
        }

        switch ($tipeDurasi) {
            case 'harian':
                return $tarifRental->tarif_harian * $durasi;
            case 'mingguan':
                return $tarifRental->tarif_mingguan * $durasi;
            case 'bulanan':
                return $tarifRental->tarif_bulanan * $durasi;
            default:
                return $tarifRental->tarif_harian * $durasi;
        }
    }

    public function calculatePrice(Request $request)
    {
        // Check role
        if (Auth::user()->role !== 'penyewa') {
            abort(403, 'Akses ditolak. Hanya penyewa yang dapat mengakses halaman ini.');
        }
        
        $motor = Motor::with('tarifRental')->findOrFail($request->motor_id);
        $durasi = (int) $request->durasi;
        $tipeDurasi = $request->tipe_durasi;

        $hargaTotal = $this->hitungHargaTotal($motor->tarifRental, $durasi, $tipeDurasi);
        
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = $this->hitungTanggalSelesai($tanggalMulai, $durasi, $tipeDurasi);

        return response()->json([
            'harga_total' => $hargaTotal,
            'harga_formatted' => 'Rp ' . number_format($hargaTotal, 0, ',', '.'),
            'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
            'tanggal_selesai_formatted' => $tanggalSelesai->format('d M Y')
        ]);
    }

    public function showPembayaran($id)
    {
        $penyewaan = Penyewaan::with(['motor', 'penyewa'])->findOrFail($id);
        
        // Pastikan hanya penyewa yang bersangkutan yang bisa akses
        if (Auth::id() !== $penyewaan->penyewa_id) {
            abort(403, 'Akses ditolak');
        }

        return view('penyewa.pembayaran', compact('penyewaan'));
    }

    public function prosesPembayaran(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pembayaran' => 'required|in:transfer,ewallet,e_wallet',
            'catatan' => 'nullable|string|max:255'
        ]);

        $penyewaan = Penyewaan::findOrFail($id);
        
        // Pastikan hanya penyewa yang bersangkutan yang bisa akses
        if (Auth::id() !== $penyewaan->penyewa_id) {
            abort(403, 'Akses ditolak');
        }

        // Upload bukti transfer
        $buktiTransferPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiTransferPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
        }

        // Update status penyewaan dan buat transaksi
        $penyewaan->update([
            'status' => 'dibayar',
            'tanggal_pembayaran' => now()
        ]);

        // Normalize method value (map e_wallet to ewallet to match enum)
        $method = $request->metode_pembayaran === 'e_wallet' ? 'ewallet' : $request->metode_pembayaran;

        // Buat record transaksi untuk admin (sesuai kolom di tabel transaksis)
        Transaksi::create([
            'pemesanan_id' => $penyewaan->id,
            'jumlah' => (int) $penyewaan->harga,
            'metode_pembayaran' => $method,
            'status' => 'pending',
            'tanggal' => now(),
            'bukti_transfer' => $buktiTransferPath,
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('penyewa.riwayat')->with('success', 'Pembayaran berhasil diupload! Menunggu konfirmasi admin.');
    }

    public function riwayatPenyewaan()
    {
        $penyewaans = Penyewaan::with(['motor', 'transaksi'])
                               ->where('penyewa_id', Auth::id())
                               ->orderBy('created_at', 'desc')
                               ->get();
        
        return view('penyewa.riwayat', compact('penyewaans'));
    }
}