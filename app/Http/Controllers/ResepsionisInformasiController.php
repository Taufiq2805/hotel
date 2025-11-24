<?php

namespace App\Http\Controllers;

use App\Models\Informasi;

class ResepsionisInformasiController extends Controller
{
    public function index()
    {
        $informasi = Informasi::all();
        return view('resepsionis.informasi.index', compact('informasi'));
    }
}
