@php($user = auth()->user())

<!-- Alert Messages -->
@if(session('success'))
<div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white p-4 rounded-xl shadow-lg border border-green-200 flex items-center fade-in">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 rounded-xl shadow-lg border border-red-200 flex items-center fade-in">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg>
    {{ session('error') }}
</div>
@endif

<!-- Welcome Header -->
<div class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 rounded-3xl p-6 mb-8 border border-white/60 shadow-xl fade-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang, {{ $user->name }}!</h1>
            <p class="text-gray-600">Kelola aktivitas penyewaan motor Anda dengan mudah</p>
        </div>
        <div class="hidden md:block">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="stat-card stat-indigo fade-in">
        <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <div class="stat-title">Sewa Aktif</div>
            <div class="stat-value">{{ \App\Models\Penyewaan::where('penyewa_id',$user->id)->whereIn('status',['menunggu_konfirmasi','disewa'])->count() }}</div>
        </div>
    </div>
    <div class="stat-card stat-emerald fade-in">
        <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <div class="stat-title">Total Riwayat</div>
            <div class="stat-value">{{ \App\Models\Penyewaan::where('penyewa_id',$user->id)->count() }}</div>
        </div>
    </div>
    <div class="stat-card stat-amber fade-in">
        <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
        </div>
        <div>
            <div class="stat-title">Pembayaran Sukses</div>
            <div class="stat-value">{{ \App\Models\Transaksi::whereHas('penyewaan', fn($q)=>$q->where('penyewa_id',$user->id))->where('status','paid')->count() }}</div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="flex flex-col md:flex-row gap-4 mb-6">
    <a href="{{ route('penyewa.motors') }}" class="btn btn-primary shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        Cari Motor
    </a>
    <button class="btn btn-outline shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Lihat Semua Riwayat
    </button>
</div>

<!-- Enhanced Table Card -->
<div class="card shadow-xl border-0 fade-in">
    <div class="card-header--brand flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold">Riwayat Penyewaan Terbaru</h3>
        </div>
        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">{{ \App\Models\Penyewaan::where('penyewa_id',$user->id)->count() }} Total</span>
    </div>
    <div class="card-body p-0">
        @php($rentals = \App\Models\Penyewaan::with('motor')->where('penyewa_id',$user->id)->latest()->limit(8)->get())
        @if($rentals->count() > 0)
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Motor & Detail</th>
                            <th>Periode Sewa</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentals as $s)
                        <tr class="border-b border-gray-100">
                            <td>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ strtoupper(substr($s->motor->merk, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800">{{ $s->motor->merk }}</div>
                                        <div class="text-sm text-gray-500">{{ $s->motor->tipe_cc }}cc • {{ $s->motor->no_plat }}</div>
                                        <div class="text-xs text-indigo-600 font-medium mt-1">{{ ucfirst($s->tipe_durasi) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($s->tanggal_mulai)->format('d M Y') }}</div>
                                    <div class="text-gray-500">hingga {{ \Carbon\Carbon::parse($s->tanggal_selesai)->format('d M Y') }}</div>
                                    <div class="text-xs text-indigo-600 mt-1">{{ \Carbon\Carbon::parse($s->tanggal_mulai)->diffInDays($s->tanggal_selesai) + 1 }} hari</div>
                                </div>
                            </td>
                            <td>
                                <div class="font-bold text-lg text-gray-800">Rp {{ number_format($s->harga,0,',','.') }}</div>
                                <div class="text-xs text-gray-500">{{ $s->transaksi ? ucfirst($s->transaksi->metode_pembayaran ?? 'Belum bayar') : 'Belum bayar' }}</div>
                            </td>
                            <td>
                                @php($statusClass = match($s->status) {
                                    'menunggu_pembayaran' => 'badge-warning',
                                    'menunggu_konfirmasi' => 'badge-info', 
                                    'disewa' => 'badge-info',
                                    'selesai' => 'badge-success',
                                    default => 'badge-warning'
                                })
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_',' ',$s->status)) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if(in_array($s->status, ['menunggu_pembayaran']))
                                        <button class="btn-primary text-xs px-3 py-1.5 rounded-lg">Bayar</button>
                                    @endif
                                    <button class="text-gray-400 hover:text-indigo-600 p-1 rounded transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 px-6">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Penyewaan</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Mulai petualangan Anda dengan menyewa motor pertama! Jelajahi berbagai pilihan motor yang tersedia.</p>
                <a href="{{ route('penyewa.motors') }}" class="btn btn-primary shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Mulai Cari Motor
                </a>
            </div>
        @endif
    </div>
</div>
