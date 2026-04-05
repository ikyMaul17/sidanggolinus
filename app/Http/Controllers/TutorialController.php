<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    // Menampilkan daftar FAQ
    public function index()
    {
        $data = Tutorial::all();
        return view('tutorial.index', compact('data'));
    }

    // Menyimpan Tutorial baru
    public function store(Request $request)
    {
        $request->validate([
            'step' => 'required',
            'deskripsi' => 'required',
        ]);

        Tutorial::create([
            'step' => $request->step,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Tutorial berhasil ditambahkan!');
    }

    // Memperbarui data FAQ
    public function update(Request $request, $id)
    {
        $request->validate([
            'step' => 'required',
            'deskripsi' => 'required',
        ]);

        $tutorial = Tutorial::findOrFail($id);
        $tutorial->update([
            'step' => $request->step,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Tutorial berhasil diperbarui!');
    }

    // Menghapus FAQ
    public function destroy($id)
    {
        $tutorial = Tutorial::findOrFail($id);
        $tutorial->delete();

        return redirect()->back()->with('success', 'Tutorial berhasil dihapus!');
    }
}
