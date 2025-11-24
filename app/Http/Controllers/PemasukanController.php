<?php

namespace App\Http\Controllers;

use App\Models\ReportSewa;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index(Request $request)
    {
        $pemasukans = ReportSewa::with('reservasi.kamar.tipe')->get();

        $data = $pemasukans->map(function ($item) {
            return [
                'username' => $item->reservasi->nama_tamu ?? '-',
                'pesan'    => 'Pembayaran selesai',
                'total'    => $item->total ?? 0,   // <- FIX pakai total di DB
                'tanggal'  => $item->created_at->format('d-m-Y, H:i:s'),
            ];
        });

        // Filter search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $data = $data->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['username']), $search) ||
                       str_contains(strtolower($item['pesan']), $search);
            })->values();
        }

        return view('admin.pemasukan.index', compact('data'));
    }
}

