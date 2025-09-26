<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

    // Only owners and renters can register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'pemilik' => redirect()->route('pemilik.dashboard'),
        default => redirect()->route('penyewa.dashboard'),
    };
})->middleware('auth')->name('dashboard');

// Optional role-specific entry points (same dashboard view; protected by role check)
Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/motor/{id}/approve', [AdminController::class, 'approveMotor'])->name('admin.motor.approve');
    Route::post('/admin/motor/{id}/status', [\App\Http\Controllers\MotorController::class, 'updateStatus'])->name('admin.motor.update-status');

    // Admin Tarif Routes
    Route::post('/admin/tarif', [TarifController::class, 'store'])->name('admin.tarif.store');
    Route::put('/admin/tarif/{tarif}', [TarifController::class, 'update'])->name('admin.tarif.update');
    
    // Admin Report Routes
    Route::get('/admin/riwayat-penyewaan', [AdminController::class, 'riwayatPenyewaan'])->name('admin.riwayat-penyewaan');
    Route::get('/admin/daftar-motor', [AdminController::class, 'daftarMotor'])->name('admin.daftar-motor');
    Route::get('/admin/entri-transaksi', [AdminController::class, 'entriTransaksi'])->name('admin.entri-transaksi');
    Route::post('/admin/entri-transaksi', [AdminController::class, 'storeTransaksi'])->name('admin.store-transaksi');
    Route::post('/admin/konfirmasi-pembayaran/{id}', [AdminController::class, 'konfirmasiPembayaran'])->name('admin.konfirmasi-pembayaran');
    Route::get('/admin/pengembalian-motor', [AdminController::class, 'pengembalianMotor'])->name('admin.pengembalian-motor');
    Route::post('/admin/konfirmasi-pengembalian/{id}', [AdminController::class, 'konfirmasiPengembalian'])->name('admin.konfirmasi-pengembalian');
    Route::post('/admin/auto-check-expired', [AdminController::class, 'autoCheckExpiredRentals'])->name('admin.auto-check-expired');

    Route::get('/pemilik', function () {
        if (Auth::user()->role !== 'pemilik') abort(403);
        return view('dashboard');
    })->name('pemilik.dashboard');

    // Pemilik: motor management
    Route::post('/pemilik/motor', [MotorController::class, 'store'])->name('pemilik.motor.store');
    Route::get('/pemilik/motor/{id}/edit', [MotorController::class, 'edit'])->name('pemilik.motor.edit');
    Route::put('/pemilik/motor/{id}', [MotorController::class, 'update'])->name('pemilik.motor.update');
    Route::delete('/pemilik/motor/{id}/delete', [MotorController::class, 'destroy'])->name('pemilik.motor.delete');
    Route::post('/pemilik/motor/{id}/status', [MotorController::class, 'updateStatus'])->name('pemilik.motor.update-status');

    Route::get('/penyewa', function () {
        if (Auth::user()->role !== 'penyewa') abort(403);
        return view('dashboard');
    })->name('penyewa.dashboard');

    // Penyewa: lihat dan sewa motor
    Route::get('/motors', [PenyewaController::class, 'index'])->name('penyewa.motors');
    Route::get('/motors/{id}', [PenyewaController::class, 'show'])->name('penyewa.motor.show');
    Route::get('/motors/{id}/book', [PenyewaController::class, 'book'])->name('penyewa.motor.book');
    Route::post('/motors/book', [PenyewaController::class, 'store'])->name('penyewa.motor.store');
    Route::post('/calculate-price', [PenyewaController::class, 'calculatePrice'])->name('penyewa.calculate.price');
    
    // Penyewa: pembayaran dan riwayat
    Route::get('/pembayaran/{id}', [PenyewaController::class, 'showPembayaran'])->name('penyewa.pembayaran');
    Route::post('/pembayaran/{id}', [PenyewaController::class, 'prosesPembayaran'])->name('penyewa.proses-pembayaran');
    Route::get('/riwayat-penyewaan', [PenyewaController::class, 'riwayatPenyewaan'])->name('penyewa.riwayat');
});

// Test Routes (only for development)
Route::get('/test-payment', [\App\Http\Controllers\TestController::class, 'index']);
Route::post('/test/create-penyewaan', [\App\Http\Controllers\TestController::class, 'createPenyewaan']);
Route::post('/test/create-payment', [\App\Http\Controllers\TestController::class, 'createPayment']);
