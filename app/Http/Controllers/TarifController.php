<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\TarifRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarifController extends Controller
{
    public function store(Request $request)
    {
        // Only admin can set tariffs
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'harga_harian' => 'required|numeric|min:1000',
            'harga_mingguan' => 'required|numeric|min:1000',
            'harga_bulanan' => 'required|numeric|min:1000',
        ]);

        $motor = Motor::findOrFail($request->motor_id);

        // Check if tariff already exists
        $tarif = TarifRental::where('motor_id', $motor->id)->first();

        if ($tarif) {
            // Update existing tariff
            $tarif->update([
                'tarif_harian' => $request->harga_harian,
                'tarif_mingguan' => $request->harga_mingguan,
                'tarif_bulanan' => $request->harga_bulanan,
            ]);
            $message = 'Tarif berhasil diperbarui';
        } else {
            // Create new tariff
            TarifRental::create([
                'motor_id' => $motor->id,
                'tarif_harian' => $request->harga_harian,
                'tarif_mingguan' => $request->harga_mingguan,
                'tarif_bulanan' => $request->harga_bulanan,
            ]);
            $message = 'Tarif berhasil ditetapkan';
        }

        return redirect()->route('dashboard')->with('success', $message);
    }

    public function update(Request $request, TarifRental $tarif)
    {
        // Only admin can update tariffs
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $request->validate([
            'harga_harian' => 'required|numeric|min:1000',
            'harga_mingguan' => 'required|numeric|min:1000',
            'harga_bulanan' => 'required|numeric|min:1000',
        ]);

        $tarif->update([
            'tarif_harian' => $request->harga_harian,
            'tarif_mingguan' => $request->harga_mingguan,
            'tarif_bulanan' => $request->harga_bulanan,
        ]);

        return redirect()->route('dashboard')->with('success', 'Tarif berhasil diperbarui');
    }
}