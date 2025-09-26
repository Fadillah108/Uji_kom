@php($motors = auth()->user()->motors()->latest()->get())

<!-- Alert Messages -->
@if(session('success'))
<div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white p-4 rounded-xl shadow-lg border border-green-200 flex items-center fade-in">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 rounded-xl shadow-lg border border-red-200 flex items-center fade-in">
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

<!-- Ringkasan Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700 text-white p-6">
        <div class="flex items-center gap-4">
            <div class="bg-white/20 p-3 rounded-xl">
                <!-- icon car -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 13.5h19.5M4.5 17.25h1.5M18 17.25h1.5M6 9l2.25-4.5h7.5L18 9m-12 0h12m-12 0l-2.4 4.8a2.25 2.25 0 0 0 2.02 3.2H18.4a2.25 2.25 0 0 0 2.02-3.2L18 9"/></svg>
            </div>
            <div>
                <div class="text-white/80">Total Motor</div>
                <div class="text-3xl font-bold">{{ $motors->count() }}</div>
            </div>
        </div>
    </div>
    <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 text-white p-6">
        <div class="flex items-center gap-4">
            <div class="bg-white/20 p-3 rounded-xl">
                <!-- icon clock -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div>
                <div class="text-white/80">Sedang Disewa</div>
                <div class="text-3xl font-bold">{{ $motors->where('status','disewa')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gradient-to-br from-amber-500 via-amber-600 to-amber-700 text-white p-6">
        <div class="flex items-center gap-4">
            <div class="bg-white/20 p-3 rounded-xl">
                <!-- icon currency -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m0 0c-3 0-5.5-2.015-5.5-4.5S9 9 12 9m0 9c3 0 5.5-2.015 5.5-4.5S15 9 12 9"/></svg>
            </div>
            <div>
                <div class="text-white/80">Pendapatan Pemilik</div>
                <div class="text-3xl font-bold">Rp {{ number_format(\App\Models\BagiHasil::whereHas('penyewaan', fn($q)=>$q->whereIn('motor_id',$motors->pluck('id')))->sum('bagi_hasil_pemilik'),0,',','.') }}</div>
            </div>
        </div>
    </div>
    
</div>

<!-- Tambah Motor Card -->
<div class="rounded-2xl overflow-hidden shadow-lg mb-8 border border-gray-100 bg-white">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 flex items-center justify-between">
        <h2 class="text-lg md:text-xl font-semibold">Tambah Motor Baru</h2>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('pemilik.motor.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Merk</label>
                <input type="text" name="merk" value="{{ old('merk') }}" 
                       class="w-full border-2 border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none" 
                       placeholder="Merk Motor" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis (CC)</label>
                <select name="tipe_cc" 
                        class="w-full border-2 border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none bg-white" required>
                    <option value="">-- Pilih CC --</option>
                    <option value="100" {{ old('tipe_cc')=='100'?'selected':'' }}>100cc</option>
                    <option value="125" {{ old('tipe_cc')=='125'?'selected':'' }}>125cc</option>
                    <option value="150" {{ old('tipe_cc')=='150'?'selected':'' }}>150cc</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Polisi</label>
                <input type="text" name="no_plat" value="{{ old('no_plat') }}" 
                       class="w-full border-2 border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none" 
                       placeholder="B 1234 ABC" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Motor</label>
                <textarea name="deskripsi" rows="4" 
                          class="w-full border-2 border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 rounded-xl p-3 transition-all duration-200 outline-none resize-none" 
                          placeholder="Deskripsikan motor Anda: kondisi, warna, model, tahun, fitur khusus, dll.">{{ old('deskripsi') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Opsional. Maksimal 1000 karakter. Informasi ini akan membantu penyewa mengenal motor Anda lebih baik.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Motor</label>
                <div class="border-2 border-dashed border-gray-300 hover:border-blue-400 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100 rounded-xl p-4 text-center transition-all duration-200 cursor-pointer" id="photo-drop">
                    <input type="file" name="photo" accept="image/*" class="hidden" id="photo-input">
                    <div class="text-gray-500" id="photo-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xs font-medium text-gray-600">Pilih gambar</p>
                    </div>
                    <img id="photo-preview" class="mx-auto rounded-lg hidden max-h-32 object-cover shadow-md" alt="Preview">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Kepemilikan (PDF/JPG/PNG)</label>
                <div class="border-2 border-dashed border-gray-300 hover:border-blue-400 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100 rounded-xl p-4 text-center transition-all duration-200 cursor-pointer" id="doc-drop">
                    <input type="file" name="dokumen_kepemilikan" accept=".pdf,image/*" class="hidden" id="doc-input">
                    <div class="text-gray-500" id="doc-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-xs font-medium text-gray-600">Pilih dokumen</p>
                    </div>
                    <div id="doc-preview" class="text-xs text-gray-600 hidden bg-gray-50 p-2 rounded-lg mt-2"></div>
                </div>
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="flex items-center px-6 py-3 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200" 
                        style="background: linear-gradient(135deg, #3b82f6, #6366f1); border: none;" 
                        onmouseover="this.style.background='linear-gradient(135deg, #2563eb, #4f46e5)'" 
                        onmouseout="this.style.background='linear-gradient(135deg, #3b82f6, #6366f1)'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="white" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span style="color: white; font-weight: 600;">Simpan Motor</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Motor -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100">
    <div class="px-6 py-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold">Daftar Motor Anda</h3>
        <span class="text-sm text-gray-500">Total: {{ $motors->count() }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm border-collapse">
            <thead>
                <tr class="text-gray-600 border-b-2 border-gray-200 bg-gray-50">
                    <th class="py-3 px-6 border-r border-gray-200">Foto</th>
                    <th class="py-3 px-6 border-r border-gray-200">Merk</th>
                    <th class="py-3 px-6 border-r border-gray-200">CC</th>
                    <th class="py-3 px-6 border-r border-gray-200">No Plat</th>
                    <th class="py-3 px-6 border-r border-gray-200">Status</th>
                    <th class="py-3 px-6 border-r border-gray-200">Dokumen</th>
                    <th class="py-3 px-6 border-r border-gray-200">Ditambahkan</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($motors as $m)
                <tr class="hover:bg-gray-50 border-b border-gray-100">
                    <td class="py-3 px-6 border-r border-gray-200">
                        @if($m->photo)
                            <img src="{{ asset('storage/'.$m->photo) }}" class="w-14 h-14 rounded-xl object-cover ring-1 ring-gray-200" alt="Foto {{ $m->merk }}">
                        @else
                            <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">N/A</div>
                        @endif
                    </td>
                    <td class="py-3 px-6 font-medium border-r border-gray-200">{{ $m->merk }}</td>
                    <td class="py-3 px-6 border-r border-gray-200">{{ $m->tipe_cc }}cc</td>
                    <td class="py-3 px-6 border-r border-gray-200">{{ $m->no_plat }}</td>
                    <td class="py-3 px-6 border-r border-gray-200">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold tracking-wide 
                            {{ $m->status=='tersedia'?'bg-emerald-100 text-emerald-700':($m->status=='disewa'?'bg-indigo-100 text-indigo-700':'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst($m->status) }}
                        </span>
                        @unless($m->is_approved)
                            <span class="ml-2 px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">Menunggu Konfirmasi</span>
                        @endunless
                    </td>
                    <td class="py-3 px-6 border-r border-gray-200">
                        @if($m->dokumen_kepemilikan)
                            <a href="{{ asset('storage/'.$m->dokumen_kepemilikan) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat Dokumen</a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 border-r border-gray-200 text-gray-500">{{ $m->created_at->format('d M Y') }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="relative inline-block text-left">
                            <button class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" onclick="toggleDropdown({{ $m->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01" />
                                </svg>
                            </button>
                            <div id="dropdown-{{ $m->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    <a href="{{ route('pemilik.motor.edit', $m->id) }}" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Motor
                                    </a>
                                    <form action="{{ route('pemilik.motor.update-status', $m->id) }}" method="POST" class="px-2 py-2 border-t border-gray-100">
                                        @csrf
                                        <div class="text-left mb-2 text-xs text-gray-500">Ubah Status</div>
                                        <div class="flex items-center gap-2">
                                            <select name="status" class="w-full border border-gray-300 rounded-lg px-2 py-1 text-sm">
                                                <option value="tersedia" {{ $m->status==='tersedia'?'selected':'' }}>Tersedia</option>
                                                <option value="perawatan" {{ $m->status==='perawatan'?'selected':'' }}>Perawatan</option>
                                            </select>
                                            <button type="submit" class="btn btn-outline text-xs">Simpan</button>
                                        </div>
                                        @if(!$m->is_approved)
                                            <div class="mt-2 text-[11px] text-purple-600">Status akan tampil sebagai "Menunggu Konfirmasi" sampai admin menyetujui.</div>
                                        @endif
                                    </form>
                                    <button class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150" onclick="deleteMotor({{ $m->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus Motor
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-8 text-center text-gray-500 border-t border-gray-200">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p class="text-gray-500">Belum ada motor yang ditambahkan</p>
                            <p class="text-sm text-gray-400 mt-1">Mulai dengan menambahkan motor pertama Anda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
// Simple drag & drop + preview for photo
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
  drop.addEventListener('dragover', e => { e.preventDefault(); drop.classList.add('border-blue-500','bg-blue-50','ring-4','ring-blue-100'); });
  drop.addEventListener('dragleave', () => drop.classList.remove('border-blue-500','bg-blue-50','ring-4','ring-blue-100'));
  drop.addEventListener('drop', e => { e.preventDefault(); drop.classList.remove('border-blue-500','bg-blue-50','ring-4','ring-blue-100'); const f = e.dataTransfer.files?.[0]; if (f) { input.files = e.dataTransfer.files; setPreview(f); }});
  input.addEventListener('change', e => setPreview(e.target.files?.[0]));
})();

// Simple drag & drop for document
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
  drop.addEventListener('dragover', e => { e.preventDefault(); drop.classList.add('border-blue-500','bg-blue-50','ring-4','ring-blue-100'); });
  drop.addEventListener('dragleave', () => drop.classList.remove('border-blue-500','bg-blue-50','ring-4','ring-blue-100'));
  drop.addEventListener('drop', e => { e.preventDefault(); drop.classList.remove('border-blue-500','bg-blue-50','ring-4','ring-blue-100'); const f = e.dataTransfer.files?.[0]; if (f) { input.files = e.dataTransfer.files; setInfo(f); }});
  input.addEventListener('change', e => setInfo(e.target.files?.[0]));
})();

// Dropdown functionality
function toggleDropdown(motorId) {
    const dropdown = document.getElementById(`dropdown-${motorId}`);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d !== dropdown) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('button')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            d.classList.add('hidden');
        });
    }
});

// Delete motor function
function deleteMotor(motorId) {
    if (confirm('Apakah Anda yakin ingin menghapus motor ini? Data yang sudah dihapus tidak dapat dikembalikan.')) {
        // Create form to submit delete request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pemilik/motor/${motorId}/delete`;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add method field for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
