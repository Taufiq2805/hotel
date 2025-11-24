<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Tampilkan semua data shift.
     */
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shift.index', compact('shifts'));
    }

    /**
     * Tampilkan form tambah shift.
     */
    public function create()
    {
        return view('admin.shift.create');
    }

    /**
     * Simpan shift baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_shift' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        Shift::create($request->all());

        return redirect()->route('admin.shift.index')->with('success', 'Shift berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail shift (opsional).
     */
    public function show(string $id)
    {
        $shift = Shift::findOrFail($id);
        return view('admin.shift.show', compact('shift'));
    }

    /**
     * Tampilkan form edit shift.
     */
    public function edit(string $id)
    {
        $shift = Shift::findOrFail($id);
        return view('admin.shift.edit', compact('shift'));
    }

    /**
     * Update shift di database.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_shift' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->update($request->all());

        return redirect()->route('admin.shift.index')->with('success', 'Shift berhasil diperbarui.');
    }

    /**
     * Hapus shift.
     */
    public function destroy(string $id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return redirect()->route('admin.shift.index')->with('success', 'Shift berhasil dihapus.');
    }
}
