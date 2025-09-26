<!-- Alert Messages -->
@if(session('success'))
<div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white p-4 rounded-xl shadow-lg border border-green-200 flex items-center">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 rounded-xl shadow-lg border border-red-200 flex items-center">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg>
    {{ session('error') }}
</div>
@endif

<!-- Layout wrapper: Sidebar + Content -->
<div class="md:flex md:gap-6">
    <!-- Sidebar Toggle (mobile) -->
    <div class="md:hidden mb-4">
        <button onclick="toggleAdminSidebar()" class="btn btn-outline w-full flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            Menu Aksi
        </button>
    </div>

    <!-- Sidebar (Premium collapsible) -->
    <aside id="adminSidebar" class="hidden md:flex md:flex-col md:w-72 md:flex-shrink-0 transition-all duration-300 theme-light">
        <style>
            /* Sidebar collapse helpers */
            #adminSidebar.collapsed { width: 5rem; }
            #adminSidebar.collapsed .sidebar-label, 
            #adminSidebar.collapsed .sidebar-search, 
            #adminSidebar.collapsed .sidebar-profile .info { display: none; }
            #adminSidebar .nav-link.active { background: linear-gradient(90deg, rgb(59,130,246), rgb(99,102,241)); color: #fff; }
            #adminSidebar .nav-link.active svg { color: #fff; }

            /* Light/Dark themes */
            #adminSidebar .sidebar-shell { background: #ffffff; color: #312e81; }
            #adminSidebar .nav-link { color: #4338ca; }
            #adminSidebar .nav-link svg { color: #6366f1; }
            #adminSidebar .nav-link:hover { background: #eef2ff; }
            #adminSidebar .sidebar-search .field { background: #f3f4f6; color: #374151; }
            #adminSidebar .sidebar-profile { background: #f9fafb; }

            #adminSidebar.theme-dark .sidebar-shell { background: #0f172a; color: #e5e7eb; }
            #adminSidebar.theme-dark .nav-link { color: #c7d2fe; }
            #adminSidebar.theme-dark .nav-link svg { color: #93c5fd; }
            #adminSidebar.theme-dark .nav-link:hover { background: rgba(255,255,255,0.06); }
            #adminSidebar.theme-dark .sidebar-search .field { background: #111827; color: #e5e7eb; }
            #adminSidebar.theme-dark .sidebar-profile { background: #111827; }
            #adminSidebar.theme-dark .collapse-icon svg { color: #a5b4fc; }
        </style>
        <div class="sidebar-shell rounded-2xl shadow-lg p-4 sticky top-24 flex flex-col h-[calc(100vh-7rem)]">
            <!-- Header: logo + collapse -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-red-400"></span>
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-yellow-400"></span>
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-green-400"></span>
                    <span class="sidebar-label font-semibold text-indigo-700 ml-2">Admin Menu</span>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" onclick="toggleAdminSidebarTheme()" class="p-2 rounded-lg hover:bg-indigo-50"
                            title="Toggle light/dark">
                        <svg id="themeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                    </button>
                    <button type="button" onclick="toggleAdminSidebarCompact()" class="collapse-icon p-2 rounded-lg hover:bg-indigo-50" title="Collapse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="sidebar-search mb-4">
                <div class="field flex items-center gap-2 rounded-xl px-3 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z" />
                    </svg>
                    <input type="text" placeholder="Search" class="w-full bg-transparent outline-none text-sm">
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 space-y-1">
                <a href="{{ route('admin.entri-transaksi') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-indigo-700 hover:bg-indigo-50 {{ request()->routeIs('admin.entri-transaksi') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2M7 7h10" />
                    </svg>
                    <span class="sidebar-label">Entry Transaksi</span>
                </a>

                <a href="{{ route('admin.riwayat-penyewaan') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-indigo-700 hover:bg-indigo-50 {{ request()->routeIs('admin.riwayat-penyewaan') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0117 5.414V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="sidebar-label">Riwayat Penyewaan</span>
                </a>

                <a href="{{ route('admin.daftar-motor') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-indigo-700 hover:bg-indigo-50 {{ request()->routeIs('admin.daftar-motor') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="sidebar-label">Daftar Motor</span>
                </a>

                <button type="button" onclick="exportLaporan()"
                        class="nav-link w-full flex items-center gap-3 px-3 py-2 rounded-xl text-indigo-700 hover:bg-indigo-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0117 5.414V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="sidebar-label">Export Laporan</span>
                </button>
            </nav>

            <!-- Profile footer -->
            <div class="sidebar-profile mt-4 rounded-xl p-3 flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-indigo-400 to-purple-400 text-white flex items-center justify-center font-semibold">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A',0,1)) }}
                </div>
                <div class="info">
                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1">
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="stat-card stat-indigo">
        <div>
            <div class="stat-title">Jumlah Motor Terdaftar</div>
            <div class="stat-value">{{ \App\Models\Motor::count() }}</div>
        </div>
    </div>
    <div class="stat-card stat-emerald">
        <div>
            <div class="stat-title">Motor Disewa</div>
            <div class="stat-value">{{ \App\Models\Motor::where('status','disewa')->count() }}</div>
        </div>
    </div>
    <div class="stat-card stat-amber">
        <div>
            <div class="stat-title">Total Pendapatan (Admin)</div>
            <div class="stat-value">Rp {{ number_format(\App\Models\BagiHasil::sum('bagi_hasil_admin'),0,',','.') }}</div>
        </div>
    </div>
</div>

<!-- Grafik Penyewaan per Periode -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Chart Penyewaan -->
    <div class="card">
        <div class="card-header--brand">
            <h3 class="text-lg font-semibold text-white">Grafik Penyewaan per Periode</h3>
        </div>
        <div class="card-body">
            <canvas id="penyewaanChart" width="400" height="200"></canvas>
        </div>
    </div>
    
    <!-- Chart Pendapatan -->
    <div class="card">
        <div class="card-header--brand">
            <h3 class="text-lg font-semibold text-white">Grafik Pendapatan Bulanan</h3>
        </div>
        <div class="card-body">
            <canvas id="pendapatanChart" width="400" height="200"></canvas>
        </div>
    </div>
    </div>

<!-- Kelola Motor & Tarif -->
<div class="card mb-8">
    <div class="card-header--brand flex items-center justify-between">
        <h3 class="text-lg font-semibold text-white">Kelola Motor & Tarif Rental</h3>
        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">{{ \App\Models\Motor::count() }} Motor</span>
    </div>
    <div class="card-body p-0">
        @php($motors = \App\Models\Motor::with(['user', 'tarifRental'])->latest()->get())
        @if($motors->count() > 0)
            <div class="overflow-x-auto">
                <table class="table border-collapse">
                    <thead>
                        <tr class="text-gray-600 border-b-2 border-gray-200 bg-gray-50">
                            <th class="py-3 px-6 border-r border-gray-200">Foto</th>
                            <th class="py-3 px-6 border-r border-gray-200">Motor</th>
                            <th class="py-3 px-6 border-r border-gray-200">Pemilik</th>
                            <th class="py-3 px-6 border-r border-gray-200">Status Tarif</th>
                            <th class="py-3 px-6 border-r border-gray-200">Tarif Saat Ini</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($motors as $m)
                        <tr class="hover:bg-gray-50 border-b border-gray-100">
                            <td class="py-3 px-6 border-r border-gray-200">
                                @if($m->photo)
                                    <img src="{{ asset('storage/'.$m->photo) }}" class="w-12 h-12 rounded-lg object-cover ring-1 ring-gray-200" alt="{{ $m->merk }}">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-bold">
                                        {{ strtoupper(substr($m->merk, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-6 border-r border-gray-200">
                                <div class="font-semibold">{{ $m->merk }}</div>
                                <div class="text-sm text-gray-500">{{ $m->tipe_cc }}cc • {{ $m->no_plat }}</div>
                            </td>
                            <td class="py-3 px-6 border-r border-gray-200">
                                <div class="font-medium">{{ $m->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $m->user->email }}</div>
                            </td>
                            <td class="py-3 px-6 border-r border-gray-200">
                                @if($m->tarifRental)
                                    <span class="badge badge-success">Tarif Sudah Ditetapkan</span>
                                @else
                                    <span class="badge badge-warning">Belum Ada Tarif</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 border-r border-gray-200">
                                @if($m->tarifRental)
                                    <div class="text-sm">
                                        <div>Harian: <span class="font-semibold">Rp {{ number_format($m->tarifRental->tarif_harian, 0, ',', '.') }}</span></div>
                                        <div>Mingguan: <span class="font-semibold">Rp {{ number_format($m->tarifRental->tarif_mingguan, 0, ',', '.') }}</span></div>
                                        <div>Bulanan: <span class="font-semibold">Rp {{ number_format($m->tarifRental->tarif_bulanan, 0, ',', '.') }}</span></div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Belum ditetapkan</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-center">
                                <button onclick="openTarifModal({{ $m->id }}, '{{ $m->merk }}', {{ $m->tarifRental ? json_encode($m->tarifRental) : 'null' }})" 
                                        class="btn btn-primary text-sm px-3 py-1.5">
                                    {{ $m->tarifRental ? 'Edit Tarif' : 'Set Tarif' }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Motor</h3>
                <p class="text-gray-500">Motor akan muncul di sini setelah pemilik menambahkan motor baru</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Set/Edit Tarif -->
<div id="tarifModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-t-2xl">
            <h3 class="text-lg font-semibold" id="modalTitle">Set Tarif Rental</h3>
            <p class="text-sm opacity-90" id="modalSubtitle">Motor: -</p>
        </div>
        <form id="tarifForm" method="POST" action="{{ route('admin.tarif.store') }}">
            @csrf
            <input type="hidden" name="motor_id" id="modal_motor_id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tarif Harian (Rp)</label>
                    <input type="number" name="harga_harian" id="harga_harian" 
                           class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none" 
                           placeholder="50000" required min="1000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tarif Mingguan (Rp)</label>
                    <input type="number" name="harga_mingguan" id="harga_mingguan" 
                           class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none" 
                           placeholder="300000" required min="1000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tarif Bulanan (Rp)</label>
                    <input type="number" name="harga_bulanan" id="harga_bulanan" 
                           class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none" 
                           placeholder="1000000" required min="1000">
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" onclick="closeTarifModal()" 
                        class="flex-1 btn btn-outline text-center">Batal</button>
                <button type="submit" 
                        class="flex-1 btn btn-primary text-center">Simpan Tarif</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTarifModal(motorId, motorName, currentTarif) {
    document.getElementById('tarifModal').classList.remove('hidden');
    document.getElementById('modal_motor_id').value = motorId;
    document.getElementById('modalSubtitle').textContent = `Motor: ${motorName}`;
    
    // Set form action for update if tarif exists
    const form = document.getElementById('tarifForm');
    if (currentTarif) {
        document.getElementById('modalTitle').textContent = 'Edit Tarif Rental';
        form.action = `{{ url('/admin/tarif') }}/${currentTarif.id}`;
        
        // Add method field for PUT
        let methodField = document.querySelector('input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';
            form.appendChild(methodField);
        }
        
        // Fill existing values
        document.getElementById('harga_harian').value = currentTarif.tarif_harian;
        document.getElementById('harga_mingguan').value = currentTarif.tarif_mingguan;
        document.getElementById('harga_bulanan').value = currentTarif.tarif_bulanan;
    } else {
        document.getElementById('modalTitle').textContent = 'Set Tarif Rental';
        form.action = '{{ route('admin.tarif.store') }}';
        
        // Remove method field
        const methodField = document.querySelector('input[name="_method"]');
        if (methodField) methodField.remove();
        
        // Clear values
        document.getElementById('harga_harian').value = '';
        document.getElementById('harga_mingguan').value = '';
        document.getElementById('harga_bulanan').value = '';
    }
}

function closeTarifModal() {
    document.getElementById('tarifModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('tarifModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTarifModal();
    }
});

// Charts Implementation
document.addEventListener('DOMContentLoaded', function() {
    // Penyewaan Chart
    const ctx1 = document.getElementById('penyewaanChart').getContext('2d');
    const penyewaanChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Penyewaan per Bulan',
                data: @json($chartData['penyewaan_per_bulan']),
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Pendapatan Chart
    const ctx2 = document.getElementById('pendapatanChart').getContext('2d');
    const pendapatanChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chartData['pendapatan_per_bulan']),
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(59, 130, 246, 0.8)', 
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                    'rgba(251, 146, 60, 0.8)',
                    'rgba(14, 165, 233, 0.8)',
                    'rgba(132, 204, 22, 0.8)',
                    'rgba(244, 63, 94, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});

// Feature Functions
function openTransaksiModal() {
    alert('Entry Transaksi - Fitur akan segera tersedia!\nAdmin dapat mengelola pembayaran manual di sini.');
}

function generateRiwayat() {
    // Show loading
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mx-auto"></div>';
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        window.open('/admin/riwayat-penyewaan', '_blank');
    }, 1500);
}

function generateDaftarMotor() {
    // Show loading  
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-gray-600 mx-auto"></div>';
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        window.open('/admin/daftar-motor', '_blank');
    }, 1500);
}

function exportLaporan() {
    // Show options modal
    const options = [
        'Laporan Penyewaan Bulanan',
        'Laporan Pendapatan Admin', 
        'Laporan Motor Terdaftar',
        'Laporan Lengkap (All-in-One)'
    ];
    
    const choice = prompt(`Pilih jenis laporan:\n${options.map((opt, i) => `${i+1}. ${opt}`).join('\n')}\n\nMasukkan nomor (1-4):`);
    
    if (choice && choice >= 1 && choice <= 4) {
        alert(`Generating ${options[choice-1]}...\nLaporan akan didownload dalam format Excel/PDF.`);
        // Here you would trigger actual export functionality
    }
}
</script>

<script>
// Toggle sidebar for mobile
function toggleAdminSidebar() {
    const el = document.getElementById('adminSidebar');
    if (el.classList.contains('hidden')) {
        el.classList.remove('hidden');
        el.classList.add('block');
    } else {
        el.classList.add('hidden');
        el.classList.remove('block');
    }
}

function toggleAdminSidebarCompact() {
    const el = document.getElementById('adminSidebar');
    el.classList.toggle('collapsed');
}

// Toggle theme (light/dark) and persist
function toggleAdminSidebarTheme() {
    const el = document.getElementById('adminSidebar');
    const isDark = el.classList.toggle('theme-dark');
    if (isDark) {
        el.classList.remove('theme-light');
        localStorage.setItem('adminSidebarTheme', 'dark');
        document.getElementById('themeIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />';
    } else {
        el.classList.add('theme-light');
        localStorage.setItem('adminSidebarTheme', 'light');
        document.getElementById('themeIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />';
    }
}

// Initialize theme from localStorage
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('adminSidebarTheme');
    const el = document.getElementById('adminSidebar');
    if (saved === 'dark') {
        el.classList.add('theme-dark');
        el.classList.remove('theme-light');
        const icon = document.getElementById('themeIcon');
        if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />';
    }
});
</script>

<!-- Close layout wrapper -->
</div>
