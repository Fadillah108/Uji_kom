<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa {{ $motor->merk }} - Penyewaan Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b-4 border-indigo-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('penyewa.motor.show', $motor->id) }}" class="text-indigo-600 hover:text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Sewa Motor</h1>
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Motor Summary -->
            <div class="card">
                <div class="card-header--brand" style="background: linear-gradient(90deg, rgba(99,102,241,0.2), rgba(147,51,234,0.2));">
                    <h2 class="text-lg font-semibold">Motor yang Dipilih</h2>
                </div>
                <div class="card-body">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md">
                            {{ strtoupper(substr($motor->merk, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $motor->merk }}</h3>
                            <p class="text-gray-600">{{ $motor->tipe_cc }}cc • {{ $motor->no_plat }}</p>
                        </div>
                    </div>

                    <!-- Tarif Display -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Tarif Sewa</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Per Hari:</span>
                                <span class="font-semibold">Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Per Minggu:</span>
                                <span class="font-semibold">Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Per Bulan:</span>
                                <span class="font-semibold">Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="card">
                <div class="card-header--brand" style="background: linear-gradient(90deg, rgba(99,102,241,0.2), rgba(147,51,234,0.2));">
                    <h2 class="text-lg font-semibold">Form Pemesanan</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('penyewa.motor.store') }}" id="bookingForm">
                        @csrf
                        <input type="hidden" name="motor_id" value="{{ $motor->id }}">

                        <!-- Tanggal Mulai -->
                        <div class="form-group mb-6">
                            <label class="form-label">Tanggal Mulai Sewa</label>
                            <input type="date" name="tanggal_mulai" id="tanggalMulai" 
                                   class="form-control border border-indigo-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 rounded-xl" required 
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('tanggal_mulai') }}">
                        </div>

                        <!-- Tipe Durasi -->
                        <div class="form-group mb-6">
                            <label class="form-label">Tipe Sewa</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="radio-card">
                                    <input type="radio" name="tipe_durasi" value="harian" 
                                           {{ old('tipe_durasi', 'harian') == 'harian' ? 'checked' : '' }}
                                           class="sr-only" id="harian">
                                    <div class="radio-card-content">
                                        <div class="text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0M8 7h8m-5 5a1 1 0 11-2 0 1 1 0 012 0z" />
                                            </svg>
                                            <div class="font-medium">Harian</div>
                                            <div class="text-xs text-gray-500 mt-1">Fleksibel</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="tipe_durasi" value="mingguan" 
                                           {{ old('tipe_durasi') == 'mingguan' ? 'checked' : '' }}
                                           class="sr-only" id="mingguan">
                                    <div class="radio-card-content">
                                        <div class="text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0M8 7h8m-5 5a1 1 0 11-2 0 1 1 0 012 0z" />
                                            </svg>
                                            <div class="font-medium">Mingguan</div>
                                            <div class="text-xs text-gray-500 mt-1">Hemat</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="tipe_durasi" value="bulanan" 
                                           {{ old('tipe_durasi') == 'bulanan' ? 'checked' : '' }}
                                           class="sr-only" id="bulanan">
                                    <div class="radio-card-content">
                                        <div class="text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0M8 7h8m-5 5a1 1 0 11-2 0 1 1 0 012 0z" />
                                            </svg>
                                            <div class="font-medium">Bulanan</div>
                                            <div class="text-xs text-gray-500 mt-1">Paling Hemat</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Durasi -->
                        <div class="form-group mb-6">
                <label class="form-label">Durasi (<span id="durasiLabel">Hari</span>)</label>
                <input type="number" name="durasi" id="durasi" 
                    class="form-control border border-indigo-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 rounded-xl" required min="1" max="365"
                                   value="{{ old('durasi', 1) }}">
                        </div>

                        <!-- Price Summary -->
                        <div id="priceSummary" class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-2xl mb-6">
                            <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Biaya</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span>Tanggal Mulai:</span>
                                    <span id="displayTanggalMulai">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Tanggal Selesai:</span>
                                    <span id="displayTanggalSelesai">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Durasi:</span>
                                    <span id="displayDurasi">-</span>
                                </div>
                                <hr class="border-gray-200">
                                <div class="flex justify-between font-bold text-lg">
                                    <span>Total Biaya:</span>
                                    <span id="displayTotal" class="text-indigo-600">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-3">
                            <a href="{{ route('penyewa.motor.show', $motor->id) }}" 
                               class="btn btn-outline flex-1 text-center">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary flex-2 shadow-lg hover:shadow-xl"
                                    id="submitBtn" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Konfirmasi Pemesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const motorId = {{ $motor->id }};
        const tarifs = {
            harian: {{ $motor->tarifRental->tarif_harian }},
            mingguan: {{ $motor->tarifRental->tarif_mingguan }},
            bulanan: {{ $motor->tarifRental->tarif_bulanan }}
        };

        const tanggalMulaiInput = document.getElementById('tanggalMulai');
        const durasiInput = document.getElementById('durasi');
        const tipeDurasiInputs = document.querySelectorAll('input[name="tipe_durasi"]');
        const durasiLabel = document.getElementById('durasiLabel');
        const submitBtn = document.getElementById('submitBtn');

        // Update durasi label berdasarkan tipe
        function updateDurasiLabel() {
            const tipeDurasi = document.querySelector('input[name="tipe_durasi"]:checked').value;
            const labels = {
                harian: 'Hari',
                mingguan: 'Minggu',
                bulanan: 'Bulan'
            };
            durasiLabel.textContent = labels[tipeDurasi];
            calculatePrice();
        }

        // Kalkulasi harga
        function calculatePrice() {
            const tanggalMulai = tanggalMulaiInput.value;
            const durasi = parseInt(durasiInput.value) || 0;
            const tipeDurasi = document.querySelector('input[name="tipe_durasi"]:checked')?.value;

            if (!tanggalMulai || !durasi || !tipeDurasi) {
                document.getElementById('displayTanggalMulai').textContent = '-';
                document.getElementById('displayTanggalSelesai').textContent = '-';
                document.getElementById('displayDurasi').textContent = '-';
                document.getElementById('displayTotal').textContent = 'Rp 0';
                submitBtn.disabled = true;
                return;
            }

            // Hitung tanggal selesai
            const startDate = new Date(tanggalMulai);
            let endDate = new Date(startDate);
            
            switch(tipeDurasi) {
                case 'harian':
                    endDate.setDate(startDate.getDate() + durasi - 1);
                    break;
                case 'mingguan':
                    endDate.setDate(startDate.getDate() + (durasi * 7) - 1);
                    break;
                case 'bulanan':
                    endDate.setMonth(startDate.getMonth() + durasi);
                    endDate.setDate(endDate.getDate() - 1);
                    break;
            }

            // Hitung total harga
            const totalHarga = tarifs[tipeDurasi] * durasi;

            // Update display
            document.getElementById('displayTanggalMulai').textContent = startDate.toLocaleDateString('id-ID');
            document.getElementById('displayTanggalSelesai').textContent = endDate.toLocaleDateString('id-ID');
            document.getElementById('displayDurasi').textContent = `${durasi} ${durasiLabel.textContent}`;
            document.getElementById('displayTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);

            submitBtn.disabled = false;
        }

        // Event listeners
        tanggalMulaiInput.addEventListener('change', calculatePrice);
        durasiInput.addEventListener('input', calculatePrice);
        tipeDurasiInputs.forEach(input => {
            input.addEventListener('change', updateDurasiLabel);
        });

        // Initialize
        updateDurasiLabel();
        calculatePrice();
    </script>

    <style>
        .radio-card {
            display: block;
            cursor: pointer;
        }
        .radio-card-content {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.2s ease;
        }
        .radio-card input:checked + .radio-card-content {
            border-color: #4f46e5;
            background-color: #eef2ff;
        }
        .radio-card:hover .radio-card-content {
            border-color: #6366f1;
        }
    </style>
</body>
</html>