<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanUmpanBalikController extends Controller
{
    /**
     * Menampilkan daftar laporan
     */
    public function index(Request $request)
    {
        $laporan = Laporan::with(['pertanyaan', 'bus'])
            ->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('status_perbaikan', $request->status);
            })
            ->when($request->filled('prioritas'), function ($query) use ($request) {
                return $query->where('kategori_prioritas', $request->prioritas);
            })
            ->when($request->filled('bus'), function ($query) use ($request) {
                return $query->whereHas('bus', function ($query) use ($request) {
                    $query->where('id', $request->bus);
                });
            })
            ->when($request->filled('target'), function ($query) use ($request) {
                return $query->where('target', $request->target);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $bus = Bus::orderBy('nama', 'asc')->get();

        return view('laporan_umpan_balik.index', compact('laporan', 'bus'));
    }

    /**
     * Menampilkan detail laporan
     */
    public function show($id)
    {
        $laporan = Laporan::with(['pertanyaan', 'bus', 'jawaban.user'])->find($id);
        // dd($laporan->toArray());

        if (! $laporan) {
            abort(404, 'Laporan tidak ditemukan');
        }

        return view('laporan_umpan_balik.show', compact('laporan'));
    }

    /**
     * Update status laporan
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_perbaikan' => 'required|in:menunggu,diproses,selesai',
        ]);

        DB::table('laporan')
            ->where('id', $id)
            ->update([
                'status_perbaikan' => $request->status_perbaikan,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    /**
     * Hapus laporan
     */
    public function destroy($id)
    {
        $laporan = Laporan::find($id);

        if (! $laporan) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan!');
        }

        $laporan->delete();

        return redirect()->route('laporan_umpan_balik')->with('success', 'Laporan berhasil dihapus!');
    }
}
