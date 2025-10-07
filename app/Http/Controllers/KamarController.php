<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\TipeKamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $tipeKamar = TipeKamar::all();

    $query = Kamar::with('tipe');

    if ($request->has('tipe_id') && $request->tipe_id != '') {
        $query->where('tipe_id', $request->tipe_id);
    }

    $kamar = $query->get();

    return view('admin.kamar.index', compact('kamar', 'tipeKamar'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $tipeKamar = TipeKamar::all(); // Ambil semua tipe kamar untuk ditampilkan di dropdown
         return view('admin.kamar.create', compact('tipeKamar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'nomor_kamar' => 'required|string|unique:kamars,nomor_kamar',
            'tipe_id' => 'required|exists:tipe_kamars,id',
            'status' => 'required|in:tersedia,terisi,dibersihkan,maintenance',
        ]);

        Kamar::create([
            'nomor_kamar' => $request->nomor_kamar,
            'tipe_id' => $request->tipe_id,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Kamar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kamar $kamar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kamar $kamar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kamar $kamar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kamar $kamar)
    {
        //
    }
}
