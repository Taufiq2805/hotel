<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Kamar;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::with(['kamar', 'maintenance', 'user'])->latest()->get();
        return view('housekeeping.pengeluaran.index', compact('pengeluarans'));
    }

    public function create()
    {
        $kamars = \App\Models\Kamar::all();
        $maintenances = \App\Models\Maintenance::all();
        return view('housekeeping.pengeluaran.create', compact('kamars', 'maintenances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pengeluaran' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'jumlah_barang' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:0',
        ]);

        $total = $request->jumlah_barang * $request->harga_satuan;

        Pengeluaran::create([
            'kamar_id' => $request->kamar_id,
            'maintenance_id' => $request->maintenance_id,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'nama_barang' => $request->nama_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $total,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('housekeeping.pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $kamars = \App\Models\Kamar::all();
        $maintenances = \App\Models\Maintenance::all();
        return view('housekeeping.pengeluaran.edit', compact('pengeluaran', 'kamars', 'maintenances'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'tanggal_pengeluaran' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'jumlah_barang' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:0',
        ]);

        $pengeluaran->update([
            'kamar_id' => $request->kamar_id,
            'maintenance_id' => $request->maintenance_id,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'nama_barang' => $request->nama_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $request->jumlah_barang * $request->harga_satuan,
        ]);

        return redirect()->route('housekeeping.pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return redirect()->route('housekeeping.pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
