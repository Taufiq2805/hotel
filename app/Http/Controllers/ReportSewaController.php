<?php

namespace App\Http\Controllers;

use App\Models\ReportSewa;
use App\Models\Reservasi;
use App\Models\Maintenance; // <- tambahkan ini
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportSewaController extends Controller
{
    /**
     * Tampilkan halaman riwayat ke admin.
     */
    public function index()
    {
        // Tampilkan data reservasi yang sudah masuk ke report_sewas (riwayat)
        $riwayats = ReportSewa::with('reservasi.kamar')->latest()->get();
        return view('admin.riwayat.index', compact('riwayats'));
    }

    /**
     * Tandai reservasi sebagai selesai dan masukkan ke riwayat + maintenance
     */
    public function selesai($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status = 'selesai';
        $reservasi->save();

        // Tambah ke tabel report_sewas (riwayat admin)
        ReportSewa::firstOrCreate([
            'id_reservasi' => $reservasi->id
        ]);

        // Tambah ke tabel maintenance (untuk housekeeping)
        Maintenance::create([
            'kamar_id' => $reservasi->kamar_id,
            'user_id' => 1, // Ganti ke Auth::id() jika login user housekeeping
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'maintenance',
            'catatan' => 'Otomatis setelah check-out',
        ]);

        return back()->with('success', 'Reservasi selesai dan masuk ke laporan serta maintenance.');
    }
}
