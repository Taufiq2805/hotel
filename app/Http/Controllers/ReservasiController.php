<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\TipeKamar;
use App\Models\Kamar;
use App\Models\Maintenance;
use App\Models\Makanan;
use App\Models\ReportSewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        // Load kamar + makanan
        $reservasis = Reservasi::with(['kamar', 'makanans'])->latest()->get();
        $tipeKamars = TipeKamar::all();
        $kamars     = Kamar::with('tipe')->get();
        $makanans   = Makanan::all();   // ← WAJIB DITAMBAHKAN

        // Auto-update status jika checkout lewat
        foreach ($reservasis as $reservasi) {
            if ($reservasi->status === 'terisi' && Carbon::now()->gt($reservasi->tanggal_checkout)) {

                $reservasi->kamar->update(['status' => 'maintenance']);
                $reservasi->update(['status' => 'selesai']);

                $alreadyExists = Maintenance::where('kamar_id', $reservasi->kamar_id)
                    ->whereDate('tanggal', Carbon::now()->toDateString())
                    ->exists();

                if (!$alreadyExists) {
                    Maintenance::create([
                        'kamar_id' => $reservasi->kamar_id,
                        'user_id' => auth()->id(),
                        'tanggal' => Carbon::now()->toDateString(),
                        'status'  => 'maintenance',
                        'catatan' => 'Otomatis setelah check-out',
                    ]);
                }
            }
        }

        return view('resepsionis.reservasi.index', compact(
            'reservasis',
            'tipeKamars',
            'kamars',
            'makanans'
        ));
    }

    public function selesai($id)
{
    $reservasi = Reservasi::with('kamar.tipe', 'makanans')->findOrFail($id);

    // ======================
    // HITUNG TOTAL
    // ======================
    $checkin  = \Carbon\Carbon::parse($reservasi->tanggal_checkin);
    $checkout = \Carbon\Carbon::parse($reservasi->tanggal_checkout);
    $lama = $checkin->diffInDays($checkout);
    if ($lama == 0) $lama = 1;

    // Harga kamar
    $hargaKamar = $reservasi->kamar->tipe->harga ?? 0;
    $totalKamar = $hargaKamar * $lama;

    // Total makanan
    $totalMakanan = 0;
    foreach ($reservasi->makanans as $m) {
        $totalMakanan += ($m->pivot->qty ?? 1) * ($m->harga ?? 0);
    }

    $total = $totalKamar + $totalMakanan;

    // SIMPAN KE REPORT SEWA
    ReportSewa::updateOrCreate(
        ['id_reservasi' => $reservasi->id],
        ['total' => $total]
    );

    // HAPUS RESERVASI (SOFT DELETE)
    $reservasi->delete();


    // ======================
    // MASUKKAN KE MAINTENANCE
    // ======================
    $existing = Maintenance::where('kamar_id', $reservasi->kamar_id)
        ->whereDate('tanggal', now()->toDateString())
        ->first();

    if (!$existing) {
        Maintenance::create([
            'kamar_id' => $reservasi->kamar_id,
            'user_id'  => auth()->id() ?? 1,
            'tanggal'  => now()->toDateString(),
            'status'   => 'maintenance',
            'catatan'  => '-',
        ]);
    }

    // Update status kamar
    $reservasi->kamar->update(['status' => 'maintenance']);

    return back()->with('success', 'Reservasi selesai dan total berhasil dicatat.');
}


    public function create()
    {
        $kamars = Kamar::where('status', 'tersedia')->get();
        $makanans = Makanan::all();

        return view('resepsionis.reservasi.create', compact('kamars', 'makanans'));
    }

    public function store(Request $request)
{
    $request->validate([
        'kamar_id'         => 'required|exists:kamars,id',
        'nama_tamu'        => 'required|string',
        'tanggal_checkin'  => 'required|date',
        'tanggal_checkout' => 'required|date|after_or_equal:tanggal_checkin',

        'makanan_id'       => 'nullable|array',
        'makanan_id.*'     => 'exists:makanans,id',
        'harga_makanan'    => 'nullable|array', // ← harga dari JS
    ]);

    // Ambil harga kamar
    $kamar = Kamar::with('tipe')->findOrFail($request->kamar_id);
    $hargaPerMalam = $kamar->tipe->harga;

    // Hitung durasi hari
    $durasi = Carbon::parse($request->tanggal_checkin)
                    ->diffInDays(Carbon::parse($request->tanggal_checkout));

    if ($durasi === 0) $durasi = 1;

    // Total kamar
    $totalKamar = $durasi * $hargaPerMalam;

    // ================================
    // TOTAL MAKANAN — TANPA QTY
    // ================================
    $totalMakanan = 0;

    if ($request->makanan_id) {
        foreach ($request->makanan_id as $i => $makananId) {

            $harga = $request->harga_makanan[$i] ?? 0;

            // Tambah total makanan (tanpa qty)
            $totalMakanan += $harga;
        }
    }

    // TOTAL AKHIR
    $grandTotal = $totalKamar + $totalMakanan;

    // Simpan reservasi
    $reservasi = Reservasi::create([
        'kamar_id'         => $request->kamar_id,
        'nama_tamu'        => $request->nama_tamu,
        'tanggal_checkin'  => $request->tanggal_checkin,
        'tanggal_checkout' => $request->tanggal_checkout,
        'status'           => 'terisi',
        'total'            => $grandTotal,
    ]);

    // Update status kamar jadi terisi
    $kamar->update(['status' => 'terisi']);

    // Simpan makanan ke pivot
    if ($request->makanan_id) {
        foreach ($request->makanan_id as $makananId) {

            // qty = 1 karena paket
            $reservasi->makanans()->attach($makananId, [
                'qty' => 1
            ]);
        }
    }

    return redirect()->route('resepsionis.reservasi.index')
        ->with('success', 'Reservasi berhasil ditambahkan.');
}


    public function edit($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $kamars = Kamar::all();
        $makanans = Makanan::all();

        return view('resepsionis.reservasi.edit', compact('reservasi', 'kamars', 'makanans'));
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        $request->validate([
            'kamar_id'         => 'required|exists:kamars,id',
            'nama_tamu'        => 'required|string',
            'tanggal_checkin'  => 'required|date',
            'tanggal_checkout' => 'required|date|after_or_equal:tanggal_checkin',
            'status'           => 'required|in:tersedia,terisi,dibersihkan,maintenance,selesai',
        ]);

        $reservasi->update($request->all());
        $reservasi->kamar->update(['status' => $request->status]);

        return redirect()->route('resepsionis.reservasi.index')
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->kamar->update(['status' => 'tersedia']);
        $reservasi->delete();

        return redirect()->route('resepsionis.reservasi.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }

    public function getFotoTipe($id)
    {
        $tipe = TipeKamar::find($id);

        if (!$tipe || !$tipe->foto) {
            return response()->json(['foto' => asset('images/no-image.jpg')]);
        }

        $fotoPath = 'storage/' . $tipe->foto;

        if (Storage::disk('public')->exists(str_replace('tipekamar/', '', $tipe->foto))) {
            return response()->json(['foto' => asset($fotoPath)]);
        }

        return response()->json(['foto' => asset('images/no-image.jpg')]);
    }

    public function exportPdf($id)
{
    $reservasi = Reservasi::with(['kamar.tipe', 'makanans'])->findOrFail($id);

    $pdf = \PDF::loadView('pdf.invoice', compact('reservasi'))
               ->setPaper('A4', 'portrait');

    return $pdf->download('Invoice-'.$reservasi->nama_tamu.'.pdf');
}

}
