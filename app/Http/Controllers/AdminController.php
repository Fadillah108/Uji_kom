<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Models\BagiHasil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }
    }

    public function riwayatPenyewaan()
    {
        $this->checkAdmin();
        
        $penyewaans = Penyewaan::with(['motor', 'penyewa', 'transaksi'])
            ->latest()
            ->get();

        return view('admin.riwayat-penyewaan', compact('penyewaans'));
    }

    public function daftarMotor()
    {
        $this->checkAdmin();
        
        $motors = Motor::with(['user', 'tarifRental'])
            ->latest()
            ->get();
        
        return view('admin.daftar-motor', compact('motors'));
    }

    public function approveMotor($id)
    {
        $this->checkAdmin();
        $motor = Motor::findOrFail($id);
        $motor->is_approved = true;
        $motor->approved_at = now();
        // If motor was pending, set to tersedia so renters can see it
        if ($motor->status === 'perawatan') {
            $motor->status = 'tersedia';
        }
        $motor->save();

        return redirect()->back()->with('success', 'Motor telah dikonfirmasi dan tersedia untuk disewa.');
    }

    public function entriTransaksi()
    {
        $this->checkAdmin();
        
        // Get pending payments that need admin confirmation
        $pendingPenyewaans = Penyewaan::whereIn('status', ['dibayar', 'menunggu_konfirmasi'])
            ->with(['motor', 'penyewa', 'transaksi'])
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('admin.entri-transaksi', compact('pendingPenyewaans'));
    }

    public function storeTransaksi(Request $request)
    {
        $this->checkAdmin();
        
        $request->validate([
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,ewallet,e_wallet',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $penyewaan = Penyewaan::findOrFail($request->penyewaan_id);

        // Normalize method (form may send e_wallet)
        $method = $request->metode_pembayaran === 'e_wallet' ? 'ewallet' : $request->metode_pembayaran;

        // Create transaction (use correct columns)
        Transaksi::create([
            'pemesanan_id' => $penyewaan->id,
            'jumlah' => (int) $request->jumlah_bayar,
            'metode_pembayaran' => $method,
            'status' => $request->jumlah_bayar >= $penyewaan->harga ? 'paid' : 'pending',
            'tanggal' => now(),
        ]);

        // Update rental status if payment complete
        if ($request->jumlah_bayar >= $penyewaan->harga) {
            $penyewaan->status = 'menunggu_konfirmasi';
            $penyewaan->save();
        }

        return redirect()->route('admin.entri-transaksi')
                         ->with('success', 'Transaksi berhasil dicatat');
    }

    public function konfirmasiPembayaran(Request $request, $id)
    {
        $this->checkAdmin();
        
        $penyewaan = Penyewaan::with(['motor', 'transaksi'])->findOrFail($id);
        
        $action = $request->input('action');
        
        if ($action === 'approve') {
            // Approve payment and start rental
            $penyewaan->update([
                'status' => 'disewa',
                'tanggal_konfirmasi' => now()
            ]);
            
            // Update motor status to rented
            $penyewaan->motor->update(['status' => 'disewa']);
            
            // Update transaction verification
            if ($penyewaan->transaksi) {
                $penyewaan->transaksi->update(['status_verifikasi' => 'verified']);
            }
            
            $message = 'Pembayaran dikonfirmasi. Motor sekarang berstatus disewa.';
            
        } elseif ($action === 'reject') {
            // Reject payment
            $penyewaan->update([
                'status' => 'menunggu_pembayaran',
                'tanggal_pembayaran' => null
            ]);
            
            if ($penyewaan->transaksi) {
                $penyewaan->transaksi->update(['status_verifikasi' => 'rejected']);
            }
            
            $message = 'Pembayaran ditolak. Penyewa perlu melakukan pembayaran ulang.';
        }
        
        return redirect()->route('admin.entri-transaksi')->with('success', $message);
    }

    public function autoCheckExpiredRentals()
    {
        $this->checkAdmin();
        
        // Find all active rentals that have passed their end date
        $expiredRentals = Penyewaan::with('motor')
            ->where('status', 'disewa')
            ->where('tanggal_selesai', '<', now())
            ->get();
        
        foreach ($expiredRentals as $rental) {
            $rental->update(['status' => 'perlu_dikembalikan']);
        }
        
        return redirect()->back()->with('success', 
            'Ditemukan ' . $expiredRentals->count() . ' penyewaan yang sudah melewati batas waktu.');
    }

    public function konfirmasiPengembalian(Request $request, $id)
    {
        $this->checkAdmin();
        
        $penyewaan = Penyewaan::with(['motor', 'penyewa'])->findOrFail($id);
        
        // Update rental status to completed
        $penyewaan->update([
            'status' => 'selesai',
            'tanggal_pengembalian' => now()
        ]);
        
        // Update motor status back to available
        $penyewaan->motor->update(['status' => 'tersedia']);
        
        // Generate revenue sharing (bagi hasil)
        $this->generateBagiHasil($penyewaan);
        
        return redirect()->back()->with('success', 'Motor berhasil dikembalikan. Bagi hasil sudah digenerate.');
    }

    private function generateBagiHasil($penyewaan)
    {
        // Calculate revenue sharing (example: 70% to owner, 30% to admin)
        $totalRevenue = $penyewaan->harga;
        $ownerShare = $totalRevenue * 0.70; // 70% for owner
        $adminShare = $totalRevenue * 0.30;  // 30% for admin
        
        BagiHasil::create([
            'penyewaan_id' => $penyewaan->id,
            'motor_id' => $penyewaan->motor_id,
            'pemilik_id' => $penyewaan->motor->pemilik_id,
            'total_pendapatan' => $totalRevenue,
            'bagi_hasil_pemilik' => $ownerShare,
            'bagi_hasil_admin' => $adminShare,
            'tanggal_bagi_hasil' => now()
        ]);
    }

    public function pengembalianMotor()
    {
        $this->checkAdmin();
        
        // Get all rentals that need to be returned
        $needReturn = Penyewaan::with(['motor', 'penyewa'])
            ->whereIn('status', ['disewa', 'perlu_dikembalikan'])
            ->orderBy('tanggal_selesai', 'asc')
            ->get();
        
        return view('admin.pengembalian-motor', compact('needReturn'));
    }

    public function dashboard()
    {
        $stats = [
            'total_motor' => Motor::count(),
            'motor_disewa' => Motor::where('status', 'disewa')->count(),
            'total_penyewaan' => Penyewaan::count(),
            'pendapatan_admin' => BagiHasil::sum('bagi_hasil_admin'),
            'penyewaan_bulan_ini' => Penyewaan::whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))->count(),
        ];

        // Data untuk chart
        $chartData = [
            'penyewaan_per_bulan' => array_map(function($month) {
                return Penyewaan::whereMonth('created_at', $month)
                    ->whereYear('created_at', date('Y'))
                    ->count();
            }, range(1, 12)),
            
            'pendapatan_per_bulan' => array_map(function($month) {
                return BagiHasil::whereHas('penyewaan', function($q) use ($month) {
                    $q->whereMonth('created_at', $month)->whereYear('created_at', date('Y'));
                })->sum('bagi_hasil_admin');
            }, range(1, 12))
        ];

        return view('dashboard', compact('stats', 'chartData'));
    }
}