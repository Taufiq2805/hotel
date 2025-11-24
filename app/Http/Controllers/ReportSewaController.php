<?php

namespace App\Http\Controllers;

use App\Models\ReportSewa;
use App\Models\Reservasi;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportSewaController extends Controller
{
    public function index()
    {
        // Ambil semua report + reservasi + kamar
        $riwayats = ReportSewa::with('reservasi.kamar.tipe')->latest()->get();
        return view('admin.riwayat.index', compact('riwayats'));
    }

    public function selesai($id)
    {
        // Ambil reservasi + tipe kamar + makanan
        $reservasi = Reservasi::with(['kamar.tipe', 'makanan'])->findOrFail($id);

        if (!$reservasi->kamar || !$reservasi->kamar->tipe) {
            return back()->with('error', 'Kamar atau tipe kamar belum terisi.');
        }

        // Update status reservasi menjadi selesai
        $reservasi->update(['status' => 'selesai']);

        // Hitung lama menginap
        $checkin  = Carbon::parse($reservasi->tanggal_checkin);
        $checkout = Carbon::parse($reservasi->tanggal_checkout);
        $lama     = max(1, $checkin->diffInDays($checkout)); // minimal 1 hari

        // Hitung total kamar (harga per malam * durasi)
        $totalKamar = $reservasi->kamar->tipe->harga * $lama;

        // Hitung total makanan
        $totalMakanan = $reservasi->makanan->sum(function ($m) {
            $harga = $m->pivot->harga ?? $m->harga ?? 0;
            $qty   = $m->pivot->qty ?? 1;
            return $harga * $qty;
        });

        // Total akhir
        $total = $totalKamar + $totalMakanan;

        // Simpan total ke tabel report_sewas
        ReportSewa::updateOrCreate(
            ['id_reservasi' => $reservasi->id],
            ['total' => $total]
        );

        // Tambahkan maintenance otomatis setelah checkout
        Maintenance::firstOrCreate(
            [
                'kamar_id' => $reservasi->kamar_id,
                'tanggal' => now()->toDateString(),
            ],
            [
                'user_id' => auth()->id() ?? 1,
                'status' => 'maintenance',
                'catatan' => 'Otomatis setelah check-out'
            ]
        );

        return back()->with('success', "Reservasi selesai. Total: Rp " . number_format($total, 0, ',', '.'));
    }
}
