<!DOCTYPE html>
<html>
<head>
    <title>Test Payment Flow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Test Payment Flow</h1>
        
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Test Create Penyewaan -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Create Test Penyewaan</h2>
                <form action="/test/create-penyewaan" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Motor ID:</label>
                            <input type="number" name="motor_id" value="1" class="mt-1 block w-full rounded border p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Penyewa ID:</label>
                            <input type="number" name="penyewa_id" value="3" class="mt-1 block w-full rounded border p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Tanggal Mulai:</label>
                            <input type="date" name="tanggal_mulai" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded border p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Tanggal Selesai:</label>
                            <input type="date" name="tanggal_selesai" value="{{ date('Y-m-d', strtotime('+3 days')) }}" class="mt-1 block w-full rounded border p-2">
                        </div>
                        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                            Create Penyewaan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Test Create Payment -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Create Test Payment</h2>
                <form action="/test/create-payment" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Penyewaan ID:</label>
                            <input type="number" name="penyewaan_id" value="1" class="mt-1 block w-full rounded border p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Jumlah:</label>
                            <input type="number" name="jumlah" value="150000" class="mt-1 block w-full rounded border p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Metode:</label>
                            <select name="metode_pembayaran" class="mt-1 block w-full rounded border p-2">
                                <option value="transfer">Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600">
                            Create Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Data -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Current Data</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium mb-2">Penyewaan:</h3>
                    <div class="text-sm space-y-1">
                        @php
                            $penyewaans = \App\Models\Penyewaan::with('penyewa', 'motor')->latest()->take(5)->get();
                        @endphp
                        @foreach($penyewaans as $p)
                        <div class="border-b pb-1">
                            ID: {{ $p->id }} | Status: {{ $p->status }} | Motor: {{ $p->motor->nama ?? 'N/A' }} | Penyewa: {{ $p->penyewa->name ?? 'N/A' }}
                        </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="font-medium mb-2">Transaksi:</h3>
                    <div class="text-sm space-y-1">
                        @php
                            $transaksis = \App\Models\Transaksi::with('penyewaan')->latest()->take(5)->get();
                        @endphp
                        @foreach($transaksis as $t)
                        <div class="border-b pb-1">
                            ID: {{ $t->id }} | Status: {{ $t->status }} | Verifikasi: {{ $t->status_verifikasi ?? 'N/A' }} | Jumlah: {{ number_format($t->jumlah) }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif
    </div>
</body>
</html>