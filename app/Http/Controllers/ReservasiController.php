<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\TipeKamar;
use App\Models\Kamar;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Models\ReportSewa;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = Reservasi::with('kamar')->latest()->get();

        foreach ($reservasis as $reservasi) {
            if ($reservasi->status === 'terisi' && Carbon::now()->gt($reservasi->tanggal_checkout)) {

                // Update status kamar jadi maintenance
                $reservasi->kamar->update(['status' => 'maintenance']);

                // Update status reservasi jadi selesai
                $reservasi->update(['status' => 'selesai']);

                // Cek jika belum ada maintenance hari ini untuk kamar ini
                $alreadyExists = Maintenance::where('kamar_id', $reservasi->kamar_id)
                    ->whereDate('tanggal', Carbon::now()->toDateString())
                    ->exists();

                if (!$alreadyExists) {
                    Maintenance::create([
                        'kamar_id' => $reservasi->kamar_id,
                        'user_id' => auth()->id(),
                        'tanggal' => Carbon::now()->toDateString(),
                        'status' => 'maintenance',
                        'catatan' => 'Otomatis setelah check-out',
                    ]);
                }
            }
        }

        $tipeKamars = TipeKamar::all();
        $kamars = Kamar::with('tipe')->where('status', 'tersedia')->get();

        return view('resepsionis.reservasi.index', compact('reservasis', 'tipeKamars', 'kamars'));
    }

public function selesai($id)
{
    $reservasi = Reservasi::findOrFail($id);

    // Insert ke report_sewa
    ReportSewa::create(['id_reservasi' => $reservasi->id]);

    // Hapus dari tabel reservasi
    $reservasi->delete();

    // Cek apakah maintenance untuk kamar & tanggal hari ini sudah ada
    $existing = Maintenance::where('kamar_id', $reservasi->kamar_id)
        ->whereDate('tanggal', now()->toDateString())
        ->where('catatan', 'Otomatis setelah check-out')
        ->first();

    // Jika belum ada, baru buat
    if (!$existing) {
        Maintenance::create([
            'kamar_id' => $reservasi->kamar_id,
            'user_id'  => auth()->id() ?? 1,
            'tanggal'  => now()->toDateString(),
            'status'   => 'maintenance',
            'catatan'  => 'Otomatis setelah check-out',
        ]);
    }

    return back()->with('success', 'Reservasi selesai dan data dipindahkan ke riwayat.');
}



    public function create()
    {
        $kamars = Kamar::where('status', 'tersedia')->get();
        return view('resepsionis.reservasi.create', compact('kamars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'nama_tamu' => 'required|string',
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after_or_equal:tanggal_checkin',
        ]);

        $kamar = Kamar::with('tipe')->findOrFail($request->kamar_id);
        $hargaPerMalam = $kamar->tipe->harga;

        $durasi = Carbon::parse($request->tanggal_checkin)->diffInDays(Carbon::parse($request->tanggal_checkout));
        if ($durasi === 0) $durasi = 1;

        $total = $durasi * $hargaPerMalam;

        Reservasi::create([
            'kamar_id' => $request->kamar_id,
            'nama_tamu' => $request->nama_tamu,
            'tanggal_checkin' => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'status' => 'terisi',
            'total' => $total,
        ]);

        // Update kamar jadi 'terisi'
        $kamar->update(['status' => 'terisi']);

        return redirect()->route('resepsionis.reservasi.index')->with('success', 'Reservasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $kamars = Kamar::all();
        return view('resepsionis.reservasi.edit', compact('reservasi', 'kamars'));
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'nama_tamu' => 'required|string',
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after_or_equal:tanggal_checkin',
            'status' => 'required|in:tersedia,terisi,dibersihkan,maintenance,selesai'
        ]);

        $reservasi->update($request->all());

        // Update status kamar sesuai status reservasi
        $reservasi->kamar->update(['status' => $request->status]);

        return redirect()->route('resepsionis.reservasi.index')->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);

        // Set kamar jadi tersedia saat reservasi dihapus
        $reservasi->kamar->update(['status' => 'tersedia']);
        $reservasi->delete();

        return redirect()->route('resepsionis.reservasi.index')->with('success', 'Reservasi berhasil dihapus.');
    }
    public function getFotoTipe($id)
{
    $tipe = TipeKamar::find($id);

    if (!$tipe || !$tipe->foto) {
        // Gambar default jika tidak ada foto
        return response()->json([
            'foto' => asset('images/no-image.jpg') // Pastikan kamu punya file ini di public/images/
        ]);
    }

    // Pastikan path storage publik bisa diakses
    $fotoPath = 'storage/' . $tipe->foto;

    // Kalau file-nya benar-benar ada
    if (Storage::disk('public')->exists(str_replace('tipekamar/', '', $tipe->foto))) {
        return response()->json(['foto' => asset($fotoPath)]);
    }

    // Fallback kalau file tidak ditemukan
    return response()->json([
        'foto' => asset('images/no-image.jpg')
    ]);
}
}
