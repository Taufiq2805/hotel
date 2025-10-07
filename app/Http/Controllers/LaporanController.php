<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReportSewa;
use App\Models\Pengeluaran;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua bulan unik dari created_at untuk dropdown
        $bulanOptions = ReportSewa::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

        $pemasukan = [];
        $pengeluaran = [];

        // Filter pemasukan berdasarkan bulan dari created_at
        if ($request->filled('bulan')) {
            $pemasukan = ReportSewa::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->bulan])->get();
        }

        // Filter pengeluaran berdasarkan tanggal
        if ($request->filled('dari') && $request->filled('sampai')) {
           $pengeluaran = Pengeluaran::whereBetween('tanggal_pengeluaran', [$request->dari, $request->sampai])->get();  
        }

        return view('admin.laporan.index', compact('bulanOptions', 'pemasukan', 'pengeluaran'));
    }

public function exportPDF(Request $request)
{
    $bulanOptions = ReportSewa::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan")
        ->groupBy('bulan')
        ->orderBy('bulan', 'desc')
        ->get();

    $pemasukan = [];
    $pengeluaran = [];

    if ($request->filled('bulan')) {
        $pemasukan = ReportSewa::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->bulan])->get();
    }

    if ($request->filled('dari') && $request->filled('sampai')) {
        $pengeluaran = Pengeluaran::whereBetween('tanggal_pengeluaran', [$request->dari, $request->sampai])->get();
    }

    $pdf = PDF::loadView('admin.laporan.pdf', compact('pemasukan', 'pengeluaran', 'request'));
    return $pdf->stream('laporan-keuangan.pdf');
}

}
