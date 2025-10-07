<?php

namespace App\Http\Controllers;

use App\Models\ReportSewa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PemasukanController extends Controller
{
   public function index(Request $request)
{
    $pemasukans = ReportSewa::with('reservasi.kamar.tipe')->get();

    $data = $pemasukans->map(function ($item) {
        $tipe = $item->reservasi->kamar->tipe ?? null;
        $harga = $tipe->harga ?? 0;

        $checkin = \Carbon\Carbon::parse($item->reservasi->tanggal_checkin);
        $checkout = \Carbon\Carbon::parse($item->reservasi->tanggal_checkout);
        $lama = $checkin->diffInDays($checkout);

        return [
            'username' => $item->reservasi->nama_tamu ?? '-',
            'pesan' => 'melakukan pembayaran kamar selama ' . $lama . ' hari',
            'total' => $harga * $lama,
            'tanggal' => $item->created_at->format('d-m-Y, H:i:s'),
        ];
    });

    // Filter pencarian
    if ($request->filled('search')) {
        $search = strtolower($request->search);
        $data = $data->filter(function ($item) use ($search) {
            return str_contains(strtolower($item['username']), $search) ||
                   str_contains(strtolower($item['pesan']), $search);
        })->values(); // Reset index
    }

    return view('admin.pemasukan.index', compact('data'));
}

}
