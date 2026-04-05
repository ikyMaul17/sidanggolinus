<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectionItemController extends Controller
{
    public function index()
    {
        $items = DB::table('inspection_items')
            ->orderBy('nama', 'asc')
            ->get();

        return view('inspection_items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::table('inspection_items')->insert([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Item inspeksi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::table('inspection_items')
            ->where('id', $id)
            ->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Item inspeksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::table('inspection_items')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Item inspeksi berhasil dihapus!');
    }
}
