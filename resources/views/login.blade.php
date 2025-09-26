<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Penyewaan Motor</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 flex items-center justify-center p-4">
	<div class="w-full max-w-md">
		<!-- Logo/Brand -->
		<div class="text-center mb-8">
			<div class="w-20 h-20 bg-gradient-to-br from-slate-500 to-blue-500 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-2xl">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
				</svg>
			</div>
			<h1 class="text-3xl font-bold bg-gradient-to-r from-slate-600 to-blue-600 bg-clip-text text-transparent">Penyewaan Motor</h1>
			<p class="text-slate-600 mt-2">Masuk ke akun Anda</p>
		</div>

		<!-- Login Form -->
		<form id="login-form" method="POST" action="{{ route('login.perform') }}" class="bg-white/70 backdrop-blur-lg p-8 rounded-3xl shadow-2xl border border-white/20">
			@csrf

			@if(session('success'))
				<div class="bg-gradient-to-r from-blue-500 to-slate-500 text-white p-4 rounded-2xl mb-6 flex items-center shadow-lg">
					<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
					</svg>
					{{ session('success') }}
				</div>
			@endif

			@if($errors->any())
				<div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 rounded-2xl mb-6 flex items-center shadow-lg">
					<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
					</svg>
					{{ $errors->first() }}
				</div>
			@endif

			<div class="space-y-5">
				<!-- Email -->
				<div>
					<label class="block text-sm font-semibold text-slate-700 mb-3">Email</label>
					<div class="relative">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
							</svg>
						</div>
						<input type="email" name="email" value="{{ old('email') }}" required
							class="w-full pl-10 pr-4 py-3 bg-white/60 border border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 outline-none placeholder-slate-400"
							placeholder="nama@email.com">
					</div>
				</div>

				<!-- Password -->
				<div>
					<label class="block text-sm font-semibold text-slate-700 mb-3">Password</label>
					<div class="relative">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
							</svg>
						</div>
						<input type="password" name="password" required
							class="w-full pl-10 pr-4 py-3 bg-white/60 border border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 outline-none placeholder-slate-400"
							placeholder="••••••••">
					</div>
				</div>

				<!-- Role -->
				<div>
					<label class="block text-sm font-semibold text-slate-700 mb-3">Masuk sebagai</label>
					<div class="relative">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
							</svg>
						</div>
						<select name="role" required
							class="w-full pl-10 pr-10 py-3 bg-white/60 border border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 outline-none appearance-none text-slate-700">
							<option value="">-- Pilih Role --</option>
							<option value="admin">Admin</option>
							<option value="pemilik">Pemilik Motor</option>
							<option value="penyewa">Penyewa</option>
						</select>
						<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
							</svg>
						</div>
					</div>
				</div>

				<!-- Submit with loading state -->
				<button id="login-submit" type="submit"
					class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 mt-6 disabled:opacity-75 disabled:cursor-not-allowed">
					<div class="flex items-center justify-center gap-2">
						<svg id="login-submit-spinner" class="hidden h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
							<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
						</svg>
						<span id="login-submit-text" class="text-lg">Masuk</span>
					</div>
				</button>

				<!-- Register Link -->
				<div class="text-center mt-6">
					<p class="text-slate-600 text-sm">Belum punya akun? 
						<a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition-colors duration-200 ml-1">
							Daftar sekarang
						</a>
					</p>
				</div>
			</div>
		</form>

		<!-- Footer -->
		<div class="text-center mt-8 text-slate-500 text-sm">
			<p>&copy; 2025 Penyewaan Motor. All rights reserved.</p>
		</div>
	</div>

	<!-- Inline script to handle loading state on submit -->
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const form = document.getElementById('login-form');
			const btn = document.getElementById('login-submit');
			const spinner = document.getElementById('login-submit-spinner');
			const btnText = document.getElementById('login-submit-text');

			if (form && btn) {
				form.addEventListener('submit', function () {
					btn.disabled = true;
					if (spinner) spinner.classList.remove('hidden');
					if (btnText) btnText.textContent = 'Memproses...';
				});
			}
		});
	</script>

	</body>
	</html>
