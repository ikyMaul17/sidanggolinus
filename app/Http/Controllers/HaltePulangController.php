<?php

namespace App\Http\Controllers;

use App\Models\HaltePulang;
use Illuminate\Http\Request;

class HaltePulangController extends Controller
{
    // Display the list of HaltePulang
    public function index()
    {
        $data = HaltePulang::all();
        return view('halte_pulang.index', compact('data'));
    }

    // Store new HaltePulang
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        HaltePulang::create($request->all());

        return redirect()->back()->with('success', 'Halte Pulang berhasil ditambahkan.');
    }

    // Update existing HaltePulang
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $halte = HaltePulang::findOrFail($id);
        $halte->update($request->all());

        return redirect()->back()->with('success', 'Halte Pulang berhasil diupdate.');
    }

    // Delete HaltePulang
    public function destroy($id)
    {
        $halte = HaltePulang::findOrFail($id);
        $halte->delete();

        return redirect()->back()->with('success', 'Halte Pulang berhasil dihapus.');
    }
}
