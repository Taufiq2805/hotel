<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Maintenance;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class HousekeepingDashboardController extends Controller
{
    public function index()
    {
        $totalKamar = Kamar::count();
        $kamarTersedia = Kamar::where('status', 'tersedia')->count();
        $kamarMaintenance = Kamar::where('status', 'maintenance')->count();
        $totalMaintenance = Maintenance::count();

        // Sum total_harga, bukan jumlah
        $pengeluaranBulanIni = Pengeluaran::whereMonth('created_at', now()->month)
            ->sum('total_harga');

        return view('housekeeping.index', compact(
            'totalKamar',
            'kamarTersedia',
            'kamarMaintenance',
            'totalMaintenance',
            'pengeluaranBulanIni'
        ));
    }

}
