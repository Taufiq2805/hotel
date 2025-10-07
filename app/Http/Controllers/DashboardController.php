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

        // Total Pemasukan (dari riwayat sewa)
        $totalPemasukan = ReportSewa::with('reservasi.kamar.tipe')->get()->sum(function ($item) {
            $tipe = $item->reservasi->kamar->tipe ?? null;
            $harga = $tipe->harga ?? 0;

            $checkin = Carbon::parse($item->reservasi->tanggal_checkin);
            $checkout = Carbon::parse($item->reservasi->tanggal_checkout);
            $lama = $checkin->diffInDays($checkout);

            return $harga * $lama;
        });

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
            'totalPengeluaran',
            'sisaUang'
        ));
    }
}
