<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Motor Terdaftar - Admin</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
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
                    <h1 class="text-xl font-bold text-gray-800">Daftar Motor Terdaftar</h1>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="window.print()" class="btn btn-outline text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H3a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <span class="text-gray-600">Admin: {{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-6 rounded-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100">Total Motor</p>
                        <p class="text-3xl font-bold">{{ $motors->count() }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 text-white p-6 rounded-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100">Tersedia</p>
                        <p class="text-3xl font-bold">{{ $motors->where('status', 'tersedia')->count() }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-amber-500 to-yellow-600 text-white p-6 rounded-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100">Disewa</p>
                        <p class="text-3xl font-bold">{{ $motors->where('status', 'disewa')->count() }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-red-500 to-pink-600 text-white p-6 rounded-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100">Perawatan</p>
                        <p class="text-3xl font-bold">{{ $motors->where('status', 'perawatan')->count() }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                <h2 class="text-xl font-semibold">Detail Daftar Motor</h2>
                <p class="text-indigo-100">Data lengkap motor yang terdaftar di sistem</p>
            </div>
            
            @if($motors->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarif</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($motors as $motor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($motor->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($motor->photo))
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($motor->photo) }}" alt="{{ $motor->merk }}" class="h-16 w-16 object-cover rounded-lg">
                                    @else
                                        <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $motor->merk }}</div>
                                    <div class="text-sm text-gray-500">{{ $motor->tipe_cc }}cc • {{ $motor->no_plat }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $motor->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $motor->user->email }}</div>
                                    <div class="text-xs text-gray-500">{{ $motor->user->phone ?? 'No phone' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($motor->tarifRental)
                                        <div class="space-y-1">
                                            <div class="text-sm"><span class="text-gray-500">Harian:</span> <span class="font-medium text-gray-900">Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}</span></div>
                                            <div class="text-sm"><span class="text-gray-500">Mingguan:</span> <span class="font-medium text-gray-900">Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}</span></div>
                                            <div class="text-sm"><span class="text-gray-500">Bulanan:</span> <span class="font-medium text-gray-900">Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}</span></div>
                                        </div>
                                    @else
                                        <div class="text-red-600 text-sm">Belum ada tarif</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php($statusClass = match($motor->status) {
                                        'tersedia' => 'bg-green-100 text-green-800',
                                        'disewa' => 'bg-yellow-100 text-yellow-800',
                                        'perawatan' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    })
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($motor->status) }}
                                    </span>
                                    @unless($motor->is_approved)
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Menunggu Konfirmasi
                                        </span>
                                    @endunless
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($motor->created_at)->format('d M Y') }}
                                    <div class="text-xs text-gray-500">{{ $motor->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center gap-3">
                                        @if(!$motor->is_approved)
                                            <form action="{{ route('admin.motor.approve', $motor->id) }}" method="POST" onsubmit="return confirm('Konfirmasi motor ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-primary text-sm">Konfirmasi Motor</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.motor.update-status', $motor->id) }}" method="POST">
                                            @csrf
                                            <div class="flex items-center gap-2">
                                                <select name="status" class="border border-gray-300 rounded-lg px-2 py-1 text-sm">
                                                    <option value="tersedia" {{ $motor->status==='tersedia'?'selected':'' }}>Tersedia</option>
                                                    <option value="disewa" {{ $motor->status==='disewa'?'selected':'' }}>Disewa</option>
                                                    <option value="perawatan" {{ $motor->status==='perawatan'?'selected':'' }}>Perawatan</option>
                                                </select>
                                                <button type="submit" class="btn btn-outline text-sm">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Motor Terdaftar</h3>
                    <p class="text-gray-500">Daftar motor akan muncul di sini setelah pemilik mendaftarkan motor</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>