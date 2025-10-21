<?php

namespace App\Http\Controllers;

use App\Models\TipeKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TipeKamarController extends Controller
{
    public function index()
    {
        $tipeKamar = TipeKamar::all();
        return view('admin.tipekamar.index', compact('tipeKamar'));
    }

    public function create()
    {
        return view('admin.tipekamar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('nama', 'harga', 'deskripsi');

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/tipekamar'), $filename);
            $data['foto'] = $filename;
        }

        TipeKamar::create($data);

        return redirect()->route('admin.tipekamar.index')->with('success', 'Tipe kamar berhasil ditambahkan.');
    }

    public function edit(TipeKamar $tipeKamar)
    {
        return view('admin.tipekamar.edit', compact('tipeKamar'));
    }

    public function update(Request $request, TipeKamar $tipeKamar)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('nama', 'harga', 'deskripsi');

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($tipeKamar->foto && file_exists(public_path('uploads/tipekamar/' . $tipeKamar->foto))) {
                File::delete(public_path('uploads/tipekamar/' . $tipeKamar->foto));
            }

            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/tipekamar'), $filename);
            $data['foto'] = $filename;
        }

        $tipeKamar->update($data);

        return redirect()->route('admin.tipekamar.index')->with('success', 'Tipe kamar berhasil diperbarui.');
    }

    public function destroy(TipeKamar $tipeKamar)
    {
        // Hapus foto dari folder
        if ($tipeKamar->foto && file_exists(public_path('uploads/tipekamar/' . $tipeKamar->foto))) {
            File::delete(public_path('uploads/tipekamar/' . $tipeKamar->foto));
        }

        $tipeKamar->delete();

        return redirect()->route('admin.tipekamar.index')->with('success', 'Tipe kamar berhasil dihapus.');
    }

    // ✅ Optional: API untuk ambil foto berdasarkan ID tipe kamar
    public function getFotoTipe($id)
{
    $tipe = \App\Models\TipeKamar::find($id);

    if (!$tipe || !$tipe->foto) {
        return response()->json([
            'foto' => asset('images/no-image.jpg')
        ]);
    }

    // Path relatif dan absolut
    $relativePath = 'storage/tipekamar/' . $tipe->foto;
    $absolutePath = storage_path('app/public/tipekamar/' . $tipe->foto);

    if (file_exists($absolutePath)) {
        return response()->json(['foto' => asset($relativePath)]);
    }

    return response()->json([
        'foto' => asset('images/no-image.jpg')
    ]);
}

}
