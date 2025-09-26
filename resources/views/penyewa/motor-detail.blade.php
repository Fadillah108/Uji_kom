<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $motor->merk }} - Detail Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b-4 border-indigo-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('penyewa.motors') }}" class="text-indigo-600 hover:text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Detail Motor</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">Hai, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline text-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Motor Image -->
            <div class="card">
                <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 rounded-t-2xl">
                    @if($motor->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($motor->photo))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($motor->photo) }}" alt="{{ $motor->merk }}" 
                             class="w-full h-full object-cover rounded-t-2xl">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <p class="text-indigo-600 font-medium text-lg">{{ $motor->merk }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Motor Details -->
            <div>
                <div class="card card-body">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $motor->merk }}</h1>
                            <p class="text-gray-600 text-lg">{{ $motor->tipe_cc }}cc • {{ $motor->no_plat }}</p>
                        </div>
                        <span class="badge badge-success text-base px-4 py-2">Tersedia</span>
                    </div>

                    <!-- Specifications -->
                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-sm">Merk</p>
                                <p class="font-semibold">{{ $motor->merk }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-sm">CC Motor</p>
                                <p class="font-semibold">{{ $motor->tipe_cc }}cc</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-sm">No. Plat</p>
                                <p class="font-semibold">{{ $motor->no_plat }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-sm">Pemilik</p>
                                <p class="font-semibold">{{ $motor->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($motor->deskripsi)
                    <div class="bg-blue-50 p-6 rounded-2xl mb-6 border border-blue-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Deskripsi Motor
                        </h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $motor->deskripsi }}</p>
                    </div>
                    @endif

                    <!-- Document Section -->
                    @if($motor->dokumen_kepemilikan && \Illuminate\Support\Facades\Storage::disk('public')->exists($motor->dokumen_kepemilikan))
                    <div class="bg-green-50 p-6 rounded-2xl mb-6 border border-green-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Dokumen Kepemilikan
                        </h3>
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($motor->dokumen_kepemilikan) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat Dokumen
                        </a>
                    </div>
                    @endif

                    @if($motor->tarifRental)
                        <!-- Pricing -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-2xl mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tarif Sewa</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-white rounded-xl shadow-sm">
                                    <p class="text-gray-500 text-sm mb-1">Per Hari</p>
                                    <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-center p-4 bg-white rounded-xl shadow-sm">
                                    <p class="text-gray-500 text-sm mb-1">Per Minggu</p>
                                    <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Hemat {{ number_format(($motor->tarifRental->tarif_harian * 7) - $motor->tarifRental->tarif_mingguan, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-center p-4 bg-white rounded-xl shadow-sm">
                                    <p class="text-gray-500 text-sm mb-1">Per Bulan</p>
                                    <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Hemat {{ number_format(($motor->tarifRental->tarif_harian * 30) - $motor->tarifRental->tarif_bulanan, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="flex gap-3">
                            <a href="{{ route('penyewa.motors') }}" class="btn btn-outline flex-1 text-center">
                                Kembali
                            </a>
                            <a href="{{ route('penyewa.motor.book', $motor->id) }}" class="btn btn-primary flex-2 text-center shadow-lg hover:shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0M8 7h8m-5 5a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                                Sewa Motor Ini
                            </a>
                        </div>
                    @else
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-6">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-amber-800">Tarif Belum Tersedia</h3>
                            </div>
                            <p class="text-amber-700 mb-4">Tarif untuk motor ini belum ditetapkan oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                            <a href="{{ route('penyewa.motors') }}" class="btn btn-outline">Kembali ke Daftar Motor</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>