<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Motor - Penyewaan Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b-4 border-indigo-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('pemilik.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Edit Motor</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">{{ $motor->merk }} - {{ $motor->no_plat }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline text-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white p-4 rounded-xl shadow-lg border border-green-200 flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 rounded-xl shadow-lg border border-red-200 flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Edit Motor Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-t-2xl">
                <h2 class="text-lg md:text-xl font-semibold">Edit Data Motor</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('pemilik.motor.update', $motor->id) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merk Motor *</label>
                        <input type="text" name="merk" value="{{ old('merk', $motor->merk) }}" 
                               class="w-full border-2 border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none" 
                               placeholder="Honda, Yamaha, Suzuki, dll" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis (CC) *</label>
                        <select name="tipe_cc" 
                                class="w-full border-2 border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none bg-white" required>
                            <option value="">-- Pilih CC --</option>
                            <option value="100" {{ old('tipe_cc', $motor->tipe_cc)=='100'?'selected':'' }}>100cc</option>
                            <option value="125" {{ old('tipe_cc', $motor->tipe_cc)=='125'?'selected':'' }}>125cc</option>
                            <option value="150" {{ old('tipe_cc', $motor->tipe_cc)=='150'?'selected':'' }}>150cc</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Polisi *</label>
                        <input type="text" name="no_plat" value="{{ old('no_plat', $motor->no_plat) }}" 
                               class="w-full border-2 border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none" 
                               placeholder="B 1234 ABC" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Motor</label>
                        <textarea name="deskripsi" rows="4" 
                                  class="w-full border-2 border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none resize-none" 
                                  placeholder="Deskripsikan motor Anda: kondisi, warna, model, tahun, fitur khusus, dll.">{{ old('deskripsi', $motor->deskripsi) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 1000 karakter. Informasi ini akan membantu penyewa mengenal motor Anda lebih baik.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Motor</label>
                        
                        <!-- Current Photo -->
                        @if($motor->photo)
                        <div class="mb-3">
                            <p class="text-xs text-gray-600 mb-2">Foto saat ini:</p>
                            <img src="{{ asset('storage/'.$motor->photo) }}" class="w-32 h-32 rounded-xl object-cover ring-2 ring-gray-200" alt="Foto {{ $motor->merk }}">
                        </div>
                        @endif
                        
                        <div class="border-2 border-dashed border-gray-300 hover:border-blue-400 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100 rounded-xl p-4 text-center transition-all duration-200 cursor-pointer" id="photo-drop">
                            <input type="file" name="photo" accept="image/*" class="hidden" id="photo-input">
                            <div class="text-gray-500" id="photo-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs font-medium text-gray-600">{{ $motor->photo ? 'Ganti foto' : 'Pilih foto baru' }}</p>
                            </div>
                            <img id="photo-preview" class="mx-auto rounded-lg hidden max-h-32 object-cover shadow-md" alt="Preview">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Kepemilikan (PDF/JPG/PNG)</label>
                        
                        <!-- Current Document -->
                        @if($motor->dokumen_kepemilikan)
                        <div class="mb-3">
                            <p class="text-xs text-gray-600 mb-2">Dokumen saat ini:</p>
                            <a href="{{ asset('storage/'.$motor->dokumen_kepemilikan) }}" target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm text-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif
                        
                        <div class="border-2 border-dashed border-gray-300 hover:border-blue-400 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100 rounded-xl p-4 text-center transition-all duration-200 cursor-pointer" id="doc-drop">
                            <input type="file" name="dokumen_kepemilikan" accept=".pdf,image/*" class="hidden" id="doc-input">
                            <div class="text-gray-500" id="doc-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-xs font-medium text-gray-600">{{ $motor->dokumen_kepemilikan ? 'Ganti dokumen' : 'Pilih dokumen baru' }}</p>
                            </div>
                            <div id="doc-preview" class="text-xs text-gray-600 hidden bg-gray-50 p-2 rounded-lg mt-2"></div>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex justify-between pt-4">
                        <a href="{{ route('pemilik.dashboard') }}" 
                           class="flex items-center px-6 py-3 text-gray-700 font-semibold rounded-xl border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Batal
                        </a>
                        
                        <button type="submit" class="flex items-center px-6 py-3 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200" 
                                style="background: linear-gradient(135deg, #3b82f6, #6366f1); border: none;" 
                                onmouseover="this.style.background='linear-gradient(135deg, #2563eb, #4f46e5)'" 
                                onmouseout="this.style.background='linear-gradient(135deg, #3b82f6, #6366f1)'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="white" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span style="color: white; font-weight: 600;">Update Motor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Photo drag & drop with preview
    (() => {
      const drop = document.getElementById('photo-drop');
      const input = document.getElementById('photo-input');
      const ph = document.getElementById('photo-placeholder');
      const preview = document.getElementById('photo-preview');
      if (!drop || !input) return;
      
      const open = () => input.click();
      const setPreview = (file) => {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
          preview.src = e.target.result;
          preview.classList.remove('hidden');
          ph.classList.add('hidden');
        };
        reader.readAsDataURL(file);
      };
      
      drop.addEventListener('click', open);
      drop.addEventListener('dragover', e => { 
        e.preventDefault(); 
        drop.classList.add('border-blue-500','bg-blue-50','ring-4','ring-blue-100'); 
      });
      drop.addEventListener('dragleave', () => 
        drop.classList.remove('border-blue-500','bg-blue-50','ring-4','ring-blue-100')
      );
      drop.addEventListener('drop', e => { 
        e.preventDefault(); 
        drop.classList.remove('border-blue-500','bg-blue-50','ring-4','ring-blue-100'); 
        const f = e.dataTransfer.files?.[0]; 
        if (f) { 
          input.files = e.dataTransfer.files; 
          setPreview(f); 
        }
      });
      input.addEventListener('change', e => setPreview(e.target.files?.[0]));
    })();

    // Document drag & drop 
    (() => {
      const drop = document.getElementById('doc-drop');
      const input = document.getElementById('doc-input');
      const ph = document.getElementById('doc-placeholder');
      const preview = document.getElementById('doc-preview');
      if (!drop || !input) return;
      
      const open = () => input.click();
      const setInfo = (file) => {
        if (!file) return;
        preview.textContent = `${file.name} • ${(file.size/1024).toFixed(1)} KB`;
        preview.classList.remove('hidden');
        ph.classList.add('hidden');
      };
      
      drop.addEventListener('click', open);
      drop.addEventListener('dragover', e => { 
        e.preventDefault(); 
        drop.classList.add('border-blue-500','bg-blue-50','ring-4','ring-blue-100'); 
      });
      drop.addEventListener('dragleave', () => 
        drop.classList.remove('border-blue-500','bg-blue-50','ring-4','ring-blue-100')
      );
      drop.addEventListener('drop', e => { 
        e.preventDefault(); 
        drop.classList.remove('border-blue-500','bg-blue-50','ring-4','ring-blue-100'); 
        const f = e.dataTransfer.files?.[0]; 
        if (f) { 
          input.files = e.dataTransfer.files; 
          setInfo(f); 
        }
      });
      input.addEventListener('change', e => setInfo(e.target.files?.[0]));
    })();
    </script>
</body>
</html>