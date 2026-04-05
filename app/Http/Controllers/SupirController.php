<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupirController extends Controller
{
    // Menampilkan daftar supir
    public function index()
    {
        $data = User::where('role', 'supir')->get();
        $bus = Bus::orderBy('nama', 'asc')->get();

        return view('supir.index', compact('data', 'bus'));
    }

    // Menyimpan data supir baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'no_wa' => 'required|string|max:20',
            'id_bus' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'username' => $request->nama,
            'nama' => $request->nama,
            'no_wa' => $request->no_wa,
            'id_bus' => $request->id_bus,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'supir',
        ]);

        return redirect()->back()->with('success', 'Data supir berhasil ditambahkan.');
    }

    // Mengupdate data supir
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'no_wa' => 'required|string|max:20',
            'id_bus' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'username' => $request->nama,
            'nama' => $request->nama,
            'no_wa' => $request->no_wa,
            'id_bus' => $request->id_bus,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->back()->with('success', 'Data supir berhasil diperbarui.');
    }

    // Menghapus data supir
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Data supir berhasil dihapus.');
    }
}
