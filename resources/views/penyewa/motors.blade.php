<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Motor - Penyewaan Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b-4 border-indigo-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Daftar Motor</h1>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Pilih Motor Favoritmu</h2>
            <p class="text-gray-600">{{ $motors->total() }} motor tersedia untuk disewa</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($motors->count() > 0)
            <!-- Motor Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($motors as $motor)
                <div class="card shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    <!-- Motor Image -->
                    <div class="relative h-48 bg-gradient-to-br from-indigo-100 to-purple-100">
                        @if($motor->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($motor->photo))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($motor->photo) }}" alt="{{ $motor->merk }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-20 h-20 bg-indigo-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <p class="text-indigo-600 font-medium">{{ $motor->merk }}</p>
                                </div>
                            </div>
                        @endif
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="badge badge-success">Tersedia</span>
                        </div>
                    </div>

                    <!-- Motor Info -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $motor->merk }}</h3>
                                <p class="text-gray-600">{{ $motor->tipe_cc }}cc • {{ $motor->no_plat }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-indigo-600">
                                    @if($motor->tarifRental)
                                        Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}
                                        <span class="text-sm text-gray-500 font-normal">/hari</span>
                                    @else
                                        <span class="text-sm text-gray-500">Tarif belum ditetapkan</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Description Preview -->
                        @if($motor->deskripsi)
                        <div class="mb-3">
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ Str::limit($motor->deskripsi, 100, '...') }}
                            </p>
                        </div>
                        @endif

                        <!-- Pricing Info -->
                        @if($motor->tarifRental)
                            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div class="text-center">
                                        <p class="text-gray-500">Harian</p>
                                        <p class="font-semibold">Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-500">Mingguan</p>
                                        <p class="font-semibold">Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-500">Bulanan</p>
                                        <p class="font-semibold">Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                                <div class="flex items-center text-amber-800 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    Tarif belum ditetapkan oleh admin
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('penyewa.motor.show', $motor->id) }}" 
                               class="flex-1 btn btn-outline text-center">
                                Detail
                            </a>
                            @if($motor->tarifRental)
                                <a href="{{ route('penyewa.motor.book', $motor->id) }}" 
                                   class="flex-1 btn btn-primary text-center">
                                    Sewa Sekarang
                                </a>
                            @else
                                <button class="flex-1 btn bg-gray-300 text-gray-500 cursor-not-allowed text-center" disabled>
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $motors->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Motor Tersedia</h3>
                <p class="text-gray-500 mb-6">Saat ini belum ada motor yang tersedia untuk disewa.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            </div>
        @endif
    </div>
</body>
</html>