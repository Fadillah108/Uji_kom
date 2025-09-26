<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Rental Motor</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg border-b-4 border-indigo-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Pembayaran Sewa Motor</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Ada kesalahan!</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Ringkasan Pesanan -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Ringkasan Pesanan</h2>
                
                <div class="space-y-4">
                    <!-- Motor Info -->
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        @if($penyewaan->motor->foto)
                            <img src="{{ Storage::url($penyewaan->motor->foto) }}" alt="{{ $penyewaan->motor->merk }}" class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $penyewaan->motor->merk }}</h3>
                            <p class="text-gray-600">{{ $penyewaan->motor->tipe_cc }}cc • {{ $penyewaan->motor->no_plat }}</p>
                        </div>
                    </div>

                    <!-- Rental Details -->
                    <div class="border-t pt-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Mulai</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($penyewaan->tanggal_mulai)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Selesai</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Durasi</p>
                                <p class="font-semibold">{{ $penyewaan->durasi }} {{ ucfirst($penyewaan->tipe_durasi) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ ucfirst(str_replace('_', ' ', $penyewaan->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Harga -->
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total Pembayaran</span>
                            <span class="text-indigo-600">Rp {{ number_format($penyewaan->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Pembayaran</h2>
                
                <!-- Metode Transfer -->
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-indigo-800 mb-2">Transfer Bank</h3>
                    <div class="space-y-2 text-sm text-indigo-700">
                        <p><strong>Bank BCA</strong></p>
                        <p>No. Rekening: 1234567890</p>
                        <p>Atas Nama: PT Rental Motor Nusantara</p>
                    </div>
                </div>

                <form action="{{ route('penyewa.proses-pembayaran', $penyewaan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <select id="metode_pembayaran" name="metode_pembayaran" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="e_wallet">E-Wallet (OVO/GoPay/Dana)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                        <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    </div>

                    <div class="mb-6">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea id="catatan" name="catatan" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Catatan tambahan untuk pembayaran..."></textarea>
                    </div>

                    <button type="submit" class="w-full btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>