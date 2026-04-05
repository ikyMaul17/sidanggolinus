<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;

class PenumpangController extends Controller
{
    public function index()
    {
        $data = User::where('role', 'penumpang')->get();
        $fakultas = Fakultas::all();

        return view('penumpang.index', compact('data', 'fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'no_wa' => 'required|string|max:15',
            'nim' => 'required|string|max:20',
            'id_fakultas' => 'required|exists:fakultas,id',
            'id_jurusan' => 'required|exists:jurusan,id',
            'jk' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'username' => $request->nama,
            'nama' => $request->nama,
            'no_wa' => $request->no_wa,
            'nim' => $request->nim,
            'id_fakultas' => $request->id_fakultas,
            'id_jurusan' => $request->id_jurusan,
            'jk' => $request->jk,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'penumpang',
        ]);

        return redirect()->back()->with('success', 'Penumpang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'no_wa' => 'required|string|max:15',
            'nim' => 'required|string|max:20',
            'id_fakultas' => 'required|exists:fakultas,id',
            'id_jurusan' => 'required|exists:jurusan,id',
            'jk' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'username' => $request->nama,
            'nama' => $request->nama,
            'no_wa' => $request->no_wa,
            'nim' => $request->nim,
            'id_fakultas' => $request->id_fakultas,
            'id_jurusan' => $request->id_jurusan,
            'jk' => $request->jk,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->back()->with('success', 'Penumpang berhasil diperbarui.');
    }

    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status penumpang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Penumpang berhasil dihapus.');
    }

    public function getJurusan($idFakultas)
    {
        $jurusan = Jurusan::where('id_fakultas', $idFakultas)->get();
        return response()->json($jurusan);
    }
}
