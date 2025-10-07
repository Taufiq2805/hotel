<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with(['kamar', 'user'])->latest()->get();
        $kamars = Kamar::all();

        return view('housekeeping.maintenance.index', compact('maintenances', 'kamars'));
    }

    public function create()
    {
        $kamars = Kamar::all();
        return view('housekeeping.maintenance.create', compact('kamars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal'  => 'required|date',
            'status'   => 'required|in:tersedia,terisi,dibersihkan,maintenance',
            'catatan'  => 'nullable|string',
        ]);

        Maintenance::create([
            'kamar_id' => $request->kamar_id,
            'user_id'  => Auth::id(),
            'tanggal'  => $request->tanggal,
            'status'   => $request->status,
            'catatan'  => $request->catatan,
        ]);

        // Sinkron status kamar
        Kamar::where('id', $request->kamar_id)->update(['status' => $request->status]);

        return redirect()->route('housekeeping.maintenance.index')
            ->with('success', 'Maintenance berhasil ditambahkan.');
    }

    public function edit(Maintenance $maintenance)
    {
        if ($maintenance->user_id !== Auth::id()) {
            abort(403);
        }

        $kamars = Kamar::all();
        return view('housekeeping.maintenance.edit', compact('maintenance', 'kamars'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        if ($maintenance->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal'  => 'required|date',
            'status'   => 'required|in:tersedia,terisi,dibersihkan,maintenance',
            'catatan'  => 'nullable|string',
        ]);

        $maintenance->update([
            'kamar_id' => $request->kamar_id,
            'tanggal'  => $request->tanggal,
            'status'   => $request->status,
            'catatan'  => $request->catatan,
        ]);

        // Sinkron status kamar
        $maintenance->kamar->update(['status' => $request->status]);

        return redirect()->route('housekeeping.maintenance.index')
            ->with('success', 'Maintenance berhasil diperbarui.');
    }

    public function destroy(Maintenance $maintenance)
    {

        $maintenance->delete();

        return redirect()->route('housekeeping.maintenance.index')
            ->with('success', 'Maintenance berhasil dihapus.');
    }

   public function updateStatus($id)
{
    $maintenance = Maintenance::findOrFail($id);

    $maintenance->update(['status' => 'tersedia']);
    $maintenance->kamar->update(['status' => 'tersedia']);

    return back()->with('success', 'Maintenance selesai. Kamar kembali tersedia.');
}

}
