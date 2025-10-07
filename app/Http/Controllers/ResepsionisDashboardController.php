<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use Carbon\Carbon;
use App\Models\Reservasi;

class ResepsionisDashboardController extends Controller
{
public function index()
{
    $today = Carbon::today();

    $totalKamar = Kamar::count();
    $kamarTersedia = Kamar::where('status', 'tersedia')->count();
    $kamarTerisi = Kamar::where('status', 'terisi')->count();
    $kamarMaintenance = Kamar::where('status', 'maintenance')->count();

    $reservasiHariIni = Reservasi::whereDate('tanggal_checkin', $today)->count();
    $jumlahCheckin = Reservasi::whereDate('tanggal_checkin', $today)
                        ->where('status', 'terisi')
                        ->count();

    return view('resepsionis.index', compact(
        'totalKamar',
        'kamarTersedia',
        'kamarTerisi',
        'kamarMaintenance',
        'reservasiHariIni',
        'jumlahCheckin'
    ));
}
}
