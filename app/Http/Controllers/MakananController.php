<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MakananController extends Controller
{
    // Tampilkan daftar menu makanan
    public function index()
    {
        $makanan = Makanan::all();
        return view('admin.makanan.index', compact('makanan'));
    }

    // Simpan menu baru
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'harga' => 'required|numeric|min:0',
        'deskripsi' => 'nullable|string',
        'foto' => 'nullable|image|max:2048',
    ]);

    $data = [
        'nama' => $request->nama,
        'harga' => $request->harga,
        'deskripsi' => $request->deskripsi,
    ];

    // Upload foto jika ada
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('makanan', $file, $filename);
        $data['foto'] = $filename;
    }

    // Simpan ke database
    Makanan::create($data);

    return redirect()->route('admin.makanan.index')->with('success', 'Menu makanan berhasil ditambahkan.');
}


    // Update menu
    public function update(Request $request, Makanan $makanan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('nama', 'deskripsi', 'harga');

        // Jika ada foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($makanan->foto && Storage::disk('public')->exists('makanan/' . $makanan->foto)) {
                Storage::disk('public')->delete('makanan/' . $makanan->foto);
            }

            // Upload foto baru
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('makanan', $file, $filename);
            $data['foto'] = $filename;
        }

        $makanan->update($data);

        return redirect()->route('admin.makanan.index')->with('success', 'Menu makanan berhasil diperbarui.');
    }

    // Hapus menu
    public function destroy(Makanan $makanan)
    {
        if ($makanan->foto && Storage::disk('public')->exists('makanan/' . $makanan->foto)) {
            Storage::disk('public')->delete('makanan/' . $makanan->foto);
        }

        $makanan->delete();

        return redirect()->route('admin.makanan.index')->with('success', 'Menu makanan berhasil dihapus.');
    }
}
