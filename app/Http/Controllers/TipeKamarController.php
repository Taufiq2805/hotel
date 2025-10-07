<?php

namespace App\Http\Controllers;

use App\Models\TipeKamar;
use Illuminate\Http\Request;

class TipeKamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipeKamar = TipeKamar::all();
        return view('admin.tipekamar.index', compact('tipeKamar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tipekamar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
        ]);

        TipeKamar::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Tipe kamar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipeKamar $tipeKamar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipeKamar $tipeKamar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipeKamar $tipeKamar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipeKamar $tipeKamar)
    {
        //
    }
}
