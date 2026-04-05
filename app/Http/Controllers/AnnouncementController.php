<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $data = Announcement::all();
        return view('announcement.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->storeAs('announcement', $imageName, 'public');

        Announcement::create([
            'caption' => $request->caption,
            'image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'Announcement berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'caption' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $announcement = Announcement::findOrFail($id);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete('announcement/' . $announcement->image);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('announcement', $imageName, 'public');
            $announcement->image = $imageName;
        }

        $announcement->caption = $request->caption;
        $announcement->save();

        return redirect()->back()->with('success', 'Announcement berhasil diubah!');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        Storage::disk('public')->delete('announcement/' . $announcement->image);
        $announcement->delete();

        return redirect()->back()->with('success', 'Announcement berhasil dihapus!');
    }
}
