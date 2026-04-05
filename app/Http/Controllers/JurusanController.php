<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Fakultas;
use App\Models\User;

class JurusanController extends Controller
{
    public function index()
    {
        $data = Jurusan::with('fakultas')->orderBy('nama', 'asc')->get();
        $fakultas = Fakultas::orderBy('nama', 'asc')->get();

        return view('jurusan.index', compact('data', 'fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_fakultas' => 'required|exists:fakultas,id',
            'kode' => 'required|unique:jurusan,kode',
            'nama' => 'required',
        ]);

        Jurusan::create($request->all());

        return redirect()->back()->with('success', 'Jurusan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_fakultas' => 'required|exists:fakultas,id',
            'kode' => 'required|unique:jurusan,kode,' . $id,
            'nama' => 'required',
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->all());

        return redirect()->back()->with('success', 'Jurusan berhasil diubah!');
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        // Cek apakah jurusan masih digunakan di tabel User
        $userCount = User::where('id_jurusan', $id)->count();

        if ($userCount > 0) {
            return redirect()->back()->with('error', 'Jurusan masih digunakan dan tidak dapat dihapus.');
        }

        // Jika tidak digunakan, baru boleh dihapus
        $jurusan->delete();

        return redirect()->back()->with('success', 'Jurusan berhasil dihapus!');
    }

}
