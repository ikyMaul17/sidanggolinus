<?php

namespace App\Http\Controllers;

use App\Models\HaltePergi;
use Illuminate\Http\Request;

class HaltePergiController extends Controller
{
    // Display the list of HaltePergi
    public function index()
    {
        $data = HaltePergi::all();
        return view('halte_pergi.index', compact('data'));
    }

    // Store new HaltePergi
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        HaltePergi::create($request->all());

        return redirect()->back()->with('success', 'Halte Pergi berhasil ditambahkan.');
    }

    // Update existing HaltePergi
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $halte = HaltePergi::findOrFail($id);
        $halte->update($request->all());

        return redirect()->back()->with('success', 'Halte Pergi berhasil diupdate.');
    }

    // Delete HaltePergi
    public function destroy($id)
    {
        $halte = HaltePergi::findOrFail($id);
        $halte->delete();

        return redirect()->back()->with('success', 'Halte Pergi berhasil dihapus.');
    }
}
