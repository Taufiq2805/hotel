<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class RiwayatPengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('nama_barang', 'like', "%{$search}%");
        }

        $pengeluarans = $query->latest()->get();

        return view('admin.pengeluaran.index', compact('pengeluarans'));
    }
}
