<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PertanyaanController extends Controller
{
    // Controller dengan filter
    public function index(Request $request)
    {
        $query = Pertanyaan::query()->with('bus');

        // Filter berdasarkan kategori jika ada
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $data = $query->orderBy('kategori', 'asc')
            ->orderBy('teks_pertanyaan', 'asc')
            ->get();

        $buses = DB::table('bus')
            ->orderBy('nama', 'asc')
            ->get();

        return view('pertanyaan.index', compact('data', 'buses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:safety,operational,comfort',
            'teks_pertanyaan' => 'required|unique:pertanyaan,teks_pertanyaan',
            'status' => 'required|in:aktif,nonaktif',
            'target_pengguna' => 'required|array',
            'target_pengguna.*' => 'in:supir,penumpang',
            'bus_ids' => 'required|array',
            'bus_ids.*' => 'exists:bus,id',
        ]);

        $targetPengguna = $request->input('target_pengguna', []);
        $buses = $request->input('bus_ids', []);

        DB::beginTransaction();

        try {

            $pertanyaan = Pertanyaan::create([
                'kategori' => $request->input('kategori'),
                'teks_pertanyaan' => $request->input('teks_pertanyaan'),
                'status' => $request->input('status'),
                'target_pengguna' => $request->input('target_pengguna'),
            ]);

            foreach ($targetPengguna as $pengguna) {
                foreach ($buses as $busId) {
                    Laporan::create([
                        'id_pertanyaan' => $pertanyaan->id,
                        'id_bus' => $busId,
                        'target' => $pengguna,
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: '.$th->getMessage());
        }

        return redirect()->back()->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|in:safety,operational,comfort',
            'teks_pertanyaan' => 'required|unique:pertanyaan,teks_pertanyaan,'.$id,
            'status' => 'required|in:aktif,nonaktif',
            'target_pengguna' => 'required|array',
            'target_pengguna.*' => 'in:supir,penumpang',
            'bus_ids' => 'required|array',
            'bus_ids.*' => 'exists:bus,id|numeric',
        ]);

        $pertanyaan = Pertanyaan::findOrFail($id);

        DB::beginTransaction();

        try {

            $buses = $request->input('bus_ids', []);
            $targetPengguna = $request->input('target_pengguna', []);

            $allowedPairs = [];
            foreach ($targetPengguna as $pengguna) {
                foreach ($buses as $busId) {
                    $allowedPairs[] = [$busId, $pengguna];
                }
            }

            $laporanQuery = Laporan::where('id_pertanyaan', $pertanyaan->id);
            if (empty($allowedPairs)) {
                $laporanQuery->delete();
            } else {
                $laporanQuery
                    ->whereNot(function ($query) use ($allowedPairs) {
                        foreach ($allowedPairs as [$busId, $pengguna]) {
                            $query->orWhere(function ($subQuery) use ($busId, $pengguna) {
                                $subQuery->where('id_bus', $busId)
                                    ->where('target', $pengguna);
                            });
                        }
                    })
                    ->delete();
            }

            $pertanyaan->update([
                'kategori' => $request->kategori,
                'teks_pertanyaan' => $request->teks_pertanyaan,
                'status' => $request->status,
                'target_pengguna' => $request->target_pengguna,
            ]);

            foreach ($targetPengguna as $pengguna) {
                foreach ($buses as $busId) {
                    Laporan::firstOrCreate([
                        'id_pertanyaan' => $pertanyaan->id,
                        'id_bus' => $busId,
                        'target' => $pengguna,
                    ]);
                }
            }

            DB::commit();

        } catch (\Throwable $th) {
            // throw $th;
        }

        // $pertanyaan->bus()->sync($request->bus_ids);

        return redirect()->back()->with('success', 'Pertanyaan berhasil diubah!');
    }

    public function destroy($id)
    {
        // Cek apakah pertanyaan digunakan di tabel lain (misalnya: jawaban_survey)
        // $count = DB::table('jawaban_survey')->where('pertanyaan_id', $id)->count();

        // if ($count > 0) {
        //     return redirect()->back()->with('error', 'Pertanyaan masih digunakan dan tidak dapat dihapus.');
        // }

        DB::table('pertanyaan')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
