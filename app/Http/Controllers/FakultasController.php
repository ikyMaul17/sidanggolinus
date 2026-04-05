<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas; // Pastikan model Fakultas telah dibuat
use App\Models\Jurusan;
use App\Models\User;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = Fakultas::orderBy('nama', 'asc')->get();

        return view('fakultas.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
        ]);

        Fakultas::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
        ]);

        $fakultas = Fakultas::findOrFail($id);
        $fakultas->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $fakultas = Fakultas::findOrFail($id);

        // Cek apakah fakultas masih digunakan di tabel Jurusan atau User
        $jurusanCount = Jurusan::where('id_fakultas', $id)->count();
        $userCount = User::where('id_fakultas', $id)->count();

        if ($jurusanCount > 0 || $userCount > 0) {
            return redirect()->back()->with('error', 'Fakultas masih digunakan dan tidak dapat dihapus.');
        }

        // Jika tidak digunakan, baru boleh dihapus
        $fakultas->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }


}