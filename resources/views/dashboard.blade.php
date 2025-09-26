<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css') <!-- Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen">

    <!-- Header (no sidebar) -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <div class="flex items-center gap-3">
                <input type="text" placeholder="Search..." class="hidden md:block px-3 py-2 rounded-lg border border-gray-300">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8 min-h-screen overflow-y-auto">
        @auth
            @if(auth()->user()->role === 'admin')
                @include('dashboard.admin')
            @elseif(auth()->user()->role === 'pemilik')
                @include('dashboard.pemilik')
            @else
                @include('dashboard.penyewa')
            @endif
        @else
            <div class="bg-white p-6 rounded-2xl shadow-lg">Silakan login untuk mengakses dashboard.</div>
        @endauth
    </main>

    <!-- Optional: per-partial scripts include -->
</body>
</html>
