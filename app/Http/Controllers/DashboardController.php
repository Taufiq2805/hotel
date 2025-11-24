<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipeKamar;
use App\Models\Kamar;
use App\Models\Reservasi;
use App\Models\ReportSewa;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // hanya user login
    }

    public function index()
    {
        // Total data
        $totalTipeKamar  = TipeKamar::count();
        $totalKamar      = Kamar::count();
        $totalReservasi  = Reservasi::count();
        $totalCheckin    = Reservasi::whereRaw("LOWER(TRIM(status)) = 'checkin'")->count();

        // Total pemasukan kamar dari ReportSewa (total sudah termasuk kamar + makanan jika dicatat)
    $totalPemasukanRooms = ReportSewa::with('reservasi.kamar.tipe')->get()->sum(function ($item) {
    $res = $item->reservasi;
    if (!$res || !$res->kamar) return 0;

    $hargaKamar = $res->kamar->tipe->harga ?? 0;
    $checkin  = Carbon::parse($res->tanggal_checkin ?? $res->check_in ?? now());
    $checkout = Carbon::parse($res->tanggal_checkout ?? $res->check_out ?? now());
    $lama     = $checkin->diffInDays($checkout);
    if ($lama <= 0) $lama = 1;

    return $hargaKamar * $lama;
});

$totalPemasukanMakanan = ReportSewa::with('reservasi.makanans')->get()->sum(function ($item) {
    $res = $item->reservasi;
    if (!$res) return 0;

    return $res->makanans->sum(function ($m) {
        $harga = $m->pivot->harga ?? $m->harga ?? 0;
        $qty   = $m->pivot->qty ?? 1;
        return $harga * $qty;
    });
});

$totalPemasukan = $totalPemasukanRooms + $totalPemasukanMakanan;



        // Gabungkan semua pemasukan
        $totalPemasukan = $totalPemasukanRooms + $totalPemasukanMakanan;

        // Total Pengeluaran housekeeping
        $totalPengeluaran = Pengeluaran::sum('total_harga');

        // Sisa uang kas
        $sisaUang = $totalPemasukan - $totalPengeluaran;

        return view('admin.index', compact(
            'totalTipeKamar',
            'totalKamar',
            'totalReservasi',
            'totalCheckin',
            'totalPemasukan',
            'totalPemasukanRooms',
            'totalPemasukanMakanan',
            'totalPengeluaran',
            'sisaUang'
        ));
    }
}
