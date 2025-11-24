<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Kamar;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with(['kamar', 'user', 'pengeluaran'])->latest()->get();
        $kamars = Kamar::all();

        return view('housekeeping.maintenance.index', compact('maintenances', 'kamars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal'  => 'required|date',
            'status'   => 'required|in:tersedia,terisi,dibersihkan,maintenance',
            'catatan'  => 'nullable|string',
        ]);

        // Simpan data maintenance
        $maintenance = Maintenance::create([
            'kamar_id' => $request->kamar_id,
            'user_id'  => Auth::id(),
            'tanggal'  => $request->tanggal,
            'status'   => $request->status ?? 'maintenance',
            'catatan'  => $request->catatan,
        ]);

        // Sinkron status kamar
        $maintenance->kamar->update(['status' => $maintenance->status]);

        // Jika ada catatan kerusakan → otomatis buat pengeluaran
        if (!empty($maintenance->catatan)) {
            Pengeluaran::create([
                'maintenance_id'      => $maintenance->id,
                'kamar_id'            => $maintenance->kamar_id,
                'tanggal_pengeluaran' => now(),
                'nama_barang'         => $maintenance->catatan,
                'jumlah_barang'       => 1,
                'harga_satuan'        => 0,
                'total_harga'         => 0,
                'created_by'          => Auth::id(),
            ]);
        }

        return redirect()->route('housekeeping.maintenance.index')
            ->with('success', 'Maintenance berhasil ditambahkan.');
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        // Hilangkan pembatasan user_id supaya housekeeping lain juga bisa edit
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal'  => 'required|date',
            'status'   => 'required|in:tersedia,terisi,dibersihkan,maintenance',
            'catatan'  => 'nullable|string',
        ]);

        $maintenance->update([
            'kamar_id' => $request->kamar_id,
            'tanggal'  => $request->tanggal,
            'status'   => $request->status,
            'catatan'  => $request->catatan,
        ]);

        // Sinkron status kamar
       // Sinkron status kamar hanya kalau statusnya 'tersedia' (selesai)
if ($request->status === 'tersedia') {
    $maintenance->kamar->update(['status' => 'tersedia']);
}


        // Jika sudah ada pengeluaran → update
        if ($maintenance->pengeluaran) {
            $maintenance->pengeluaran->update([
                'nama_barang' => $request->catatan ?? '-',
            ]);
        } elseif (!empty($request->catatan)) {
            // Jika belum ada pengeluaran dan catatan baru ditambah → buat baru
            Pengeluaran::create([
                'maintenance_id'      => $maintenance->id,
                'kamar_id'            => $maintenance->kamar_id,
                'tanggal_pengeluaran' => now(),
                'nama_barang'         => $request->catatan,
                'jumlah_barang'       => 1,
                'harga_satuan'        => 0,
                'total_harga'         => 0,
                'created_by'          => Auth::id(),
            ]);
        }

        return redirect()->route('housekeeping.maintenance.index')
            ->with('success', 'Maintenance berhasil diperbarui.');
    }

    public function destroy(Maintenance $maintenance)
    {
        if ($maintenance->pengeluaran) {
            $maintenance->pengeluaran->delete();
        }

        $maintenance->delete();

        return redirect()->route('housekeeping.maintenance.index')
            ->with('success', 'Maintenance berhasil dihapus.');
    }

    public function updateStatus($id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $maintenance->update(['status' => 'tersedia']);
        $maintenance->kamar->update(['status' => 'tersedia']);

        return back()->with('success', 'Maintenance selesai. Kamar kembali tersedia.');
    }

   public function updateCatatan(Request $request)
{
    $maintenance = Maintenance::findOrFail($request->id);

    $catatan = $request->catatan;

    // Cari jumlah di catatan, misal "Lampu rusak 2pcs"
    preg_match('/(\d+)/', $catatan, $matches);
    $jumlah = isset($matches[1]) ? (int)$matches[1] : 1;

    // Buang angka dari catatan untuk nama_barang
    $nama_barang = trim(preg_replace('/\d+\s*(pcs|unit)?/i', '', $catatan));

    // Update catatan di maintenance (tetap simpan apa yang ditulis)
    $maintenance->update(['catatan' => $catatan]);

    // Update atau buat pengeluaran
    if ($maintenance->pengeluaran) {
        $maintenance->pengeluaran->update([
            'nama_barang'   => $nama_barang ?: $catatan,
            'jumlah_barang' => $jumlah,
            'harga_satuan'  => 0,
            'total_harga'   => 0,
        ]);
    } elseif (!empty($catatan)) {
        \App\Models\Pengeluaran::create([
            'maintenance_id'      => $maintenance->id,
            'kamar_id'            => $maintenance->kamar_id,
            'tanggal_pengeluaran' => now(),
            'nama_barang'         => $nama_barang ?: $catatan,
            'jumlah_barang'       => $jumlah,
            'harga_satuan'        => 0,
            'total_harga'         => 0,
            'created_by'          => Auth::id(),
        ]);
    }

    return response()->json(['success' => true]);
}

}
