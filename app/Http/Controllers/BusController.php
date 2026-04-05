<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\User;

class BusController extends Controller
{
    public function index()
    {
        $data = Bus::orderBy('nama', 'asc')->get();
        return view('bus.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'plat_no' => 'required|string|max:15|unique:bus,plat_no',
            'kapasitas' => 'required|integer|min:1',
            'rute' => 'required',
            'keterangan' => 'nullable',
        ]);

        Bus::create($request->all());
        return redirect()->back()->with('success', 'Bus berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'plat_no' => 'required|string|max:15|unique:bus,plat_no,' . $id,
            'kapasitas' => 'required|integer|min:1',
            'rute' => 'required',
            'keterangan' => 'nullable',
        ]);

        $bus = Bus::findOrFail($id);
        $bus->update($request->all());
        return redirect()->back()->with('success', 'Bus berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $bus = Bus::findOrFail($id);

        // Cek apakah bus masih digunakan di tabel users
        if (User::where('id_bus', $id)->exists()) {
            return redirect()->back()->with('error', 'Bus tidak bisa dihapus karena masih digunakan oleh pengguna.');
        }

        // Hapus jika tidak digunakan
        $bus->delete();
        return redirect()->back()->with('success', 'Bus berhasil dihapus!');
    }

}
