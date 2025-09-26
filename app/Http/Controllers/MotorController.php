<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MotorController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) abort(401);

        $motor = Motor::findOrFail($id);

        // Validate target status
        $request->validate([
            'status' => 'required|in:tersedia,disewa,perawatan'
        ]);

        $target = $request->input('status');

        // Role-based permissions
        if ($user->role === 'admin') {
            // Admin can set any of the allowed statuses
            $motor->status = $target;
            // If admin sets to tersedia, mark approved if not yet
            if (!$motor->is_approved && $target === 'tersedia') {
                $motor->is_approved = true;
                $motor->approved_at = now();
            }
            $motor->save();
            return back()->with('success', 'Status motor diperbarui oleh admin.');
        }

        if ($user->role === 'pemilik') {
            // Only owner of the motor can change
            if ($motor->pemilik_id !== $user->id) abort(403, 'Bukan motor Anda.');

            // Owner cannot set to disewa secara manual
            if ($target === 'disewa') {
                return back()->with('error', 'Status disewa ditentukan oleh proses penyewaan.');
            }

            // If not approved, regardless of what owner sets, it remains pending approval visually
            $motor->status = $target; // tersedia atau perawatan
            $motor->save();
            return back()->with('success', 'Status motor diperbarui.');
        }

        abort(403);
    }
    public function store(Request $request)
    {
        // Only pemilik can store
        if (Auth::user()->role !== 'pemilik') {
            abort(403);
        }

        $data = $request->validate([
            'merk' => ['required','string','max:100'],
            'tipe_cc' => ['required','in:100,125,150'],
            'no_plat' => ['required','string','max:20','unique:motors,no_plat'],
            'deskripsi' => ['nullable','string','max:1000'],
            'photo' => ['nullable','image','max:2048'],
            'dokumen_kepemilikan' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:4096'],
        ]);

        $photoPath = null;
        $docPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('motor/photos', 'public');
        }
        if ($request->hasFile('dokumen_kepemilikan')) {
            $docPath = $request->file('dokumen_kepemilikan')->store('motor/dokumen', 'public');
        }

        Motor::create([
            'pemilik_id' => Auth::id(),
            'merk' => $data['merk'],
            'tipe_cc' => $data['tipe_cc'],
            'no_plat' => $data['no_plat'],
            // Newly added motors must be approved by admin first
            'status' => 'perawatan',
            'is_approved' => false,
            'photo' => $photoPath,
            'dokumen_kepemilikan' => $docPath,
            'deskripsi' => $data['deskripsi'] ?? null,
        ]);

    return redirect()->route('pemilik.dashboard')->with('success', 'Motor berhasil didaftarkan dan menunggu konfirmasi admin.');
    }

    public function destroy($id)
    {
        // Only pemilik can delete
        if (Auth::user()->role !== 'pemilik') {
            abort(403);
        }

        $motor = Motor::findOrFail($id);
        
        // Check if motor belongs to current user
        if ($motor->pemilik_id !== Auth::id()) {
            abort(403, 'Anda hanya bisa menghapus motor milik sendiri.');
        }

        // Check if motor is currently rented
        $activeRental = $motor->penyewaans()->whereIn('status', ['menunggu_konfirmasi', 'disewa'])->exists();
        if ($activeRental) {
            return redirect()->route('pemilik.dashboard')->with('error', 'Motor tidak bisa dihapus karena sedang dalam proses penyewaan.');
        }

        // Delete files from storage
        if ($motor->photo) {
            Storage::disk('public')->delete($motor->photo);
        }
        if ($motor->dokumen_kepemilikan) {
            Storage::disk('public')->delete($motor->dokumen_kepemilikan);
        }

        // Delete motor
        $motor->delete();

        return redirect()->route('pemilik.dashboard')->with('success', 'Motor berhasil dihapus.');
    }

    public function edit($id)
    {
        // Only pemilik can edit
        if (Auth::user()->role !== 'pemilik') {
            abort(403);
        }

        $motor = Motor::findOrFail($id);
        
        // Check if motor belongs to current user
        if ($motor->pemilik_id !== Auth::id()) {
            abort(403, 'Anda hanya bisa mengedit motor milik sendiri.');
        }

        return view('pemilik.edit-motor', compact('motor'));
    }

    public function update(Request $request, $id)
    {
        // Only pemilik can update
        if (Auth::user()->role !== 'pemilik') {
            abort(403);
        }

        $motor = Motor::findOrFail($id);
        
        // Check if motor belongs to current user
        if ($motor->pemilik_id !== Auth::id()) {
            abort(403, 'Anda hanya bisa mengupdate motor milik sendiri.');
        }

        $data = $request->validate([
            'merk' => ['required','string','max:100'],
            'tipe_cc' => ['required','in:100,125,150'],
            'no_plat' => ['required','string','max:20','unique:motors,no_plat,'.$id],
            'deskripsi' => ['nullable','string','max:1000'],
            'photo' => ['nullable','image','max:2048'],
            'dokumen_kepemilikan' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:4096'],
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($motor->photo) {
                Storage::disk('public')->delete($motor->photo);
            }
            $data['photo'] = $request->file('photo')->store('motor/photos', 'public');
        }

        // Handle document upload  
        if ($request->hasFile('dokumen_kepemilikan')) {
            // Delete old document
            if ($motor->dokumen_kepemilikan) {
                Storage::disk('public')->delete($motor->dokumen_kepemilikan);
            }
            $data['dokumen_kepemilikan'] = $request->file('dokumen_kepemilikan')->store('motor/dokumen', 'public');
        }

        // Update motor
        $motor->update($data);

        return redirect()->route('pemilik.dashboard')->with('success', 'Motor berhasil diupdate.');
    }
}
