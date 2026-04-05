<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Actions\Fuzzy;
use App\Models\Faq;
use App\Models\JawabanLaporan;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\HaltePergi;
use App\Models\HaltePulang;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\FeedbackSupir;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

class SupirHomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       $announcement = Announcement::orderBy('id', 'desc')->limit(3)->get();
       $faq = Faq::orderBy('id', 'asc')->get();
       $needsInspection = $this->needsDailyInspection(Auth::user()->id_bus, Auth::id());

       return view('page_supir.home', compact('announcement','faq', 'needsInspection'));
    }

    public function shuttle()
    {
       if ($this->needsDailyInspection(Auth::user()->id_bus, Auth::id())) {
            return redirect()->route('cek_harian_bus')->with('error', 'check bus condition');
       }

       $penumpang = Transaksi::where('transaksi.status', 'aktif')
                ->where('transaksi.id_bus', Auth::user()->id_bus)
                ->when(true, function ($query) {
                    // Join untuk penjemputan
                    $query->leftJoin('halte_pergi as penjemputan_pergi', function ($join) {
                        $join->on('penjemputan_pergi.id', '=', 'transaksi.id_penjemputan')
                            ->where('transaksi.rute', 'pergi');
                    })
                    ->leftJoin('halte_pulang as penjemputan_pulang', function ($join) {
                        $join->on('penjemputan_pulang.id', '=', 'transaksi.id_penjemputan')
                            ->where('transaksi.rute', 'pulang');
                    })
                    // Join untuk tujuan
                    ->leftJoin('halte_pergi as tujuan_pergi', function ($join) {
                        $join->on('tujuan_pergi.id', '=', 'transaksi.id_tujuan')
                            ->where('transaksi.rute', 'pergi');
                    })
                    ->leftJoin('halte_pulang as tujuan_pulang', function ($join) {
                        $join->on('tujuan_pulang.id', '=', 'transaksi.id_tujuan')
                            ->where('transaksi.rute', 'pulang');
                    });
                })
                ->select(
                    'transaksi.*',
                    DB::raw('CASE WHEN transaksi.rute = "pergi" THEN penjemputan_pergi.nama ELSE penjemputan_pulang.nama END as nama_penjemputan'),
                    DB::raw('CASE WHEN transaksi.rute = "pergi" THEN tujuan_pergi.nama ELSE tujuan_pulang.nama END as nama_tujuan')
                )
                ->orderBy('transaksi.estimasi_waktu', 'desc')
                ->get();

       $count_penumpang = Transaksi::where('status','aktif')->where('id_bus', Auth::user()->id_bus)->count();
       $bus = Bus::where('id', Auth::user()->id_bus)->first();

       return view('page_supir.shuttle', compact('penumpang','count_penumpang','bus'));
    }

    public function antrian()
    {
        if ($this->needsDailyInspection(Auth::user()->id_bus, Auth::id())) {
            return redirect()->route('cek_harian_bus')->with('error', 'check bus condition');
        }

        //$antrian = Transaksi::where('status','pending')->where('id_bus', Auth::user()->id_bus)->orderBy('estimasi_waktu', 'desc')->get();

        $antrian = Transaksi::where('transaksi.status', 'pending')
                ->where('transaksi.id_bus', Auth::user()->id_bus)
                ->when(true, function ($query) {
                    // Join untuk penjemputan
                    $query->leftJoin('halte_pergi as penjemputan_pergi', function ($join) {
                        $join->on('penjemputan_pergi.id', '=', 'transaksi.id_penjemputan')
                            ->where('transaksi.rute', 'pergi');
                    })
                    ->leftJoin('halte_pulang as penjemputan_pulang', function ($join) {
                        $join->on('penjemputan_pulang.id', '=', 'transaksi.id_penjemputan')
                            ->where('transaksi.rute', 'pulang');
                    })
                    // Join untuk tujuan
                    ->leftJoin('halte_pergi as tujuan_pergi', function ($join) {
                        $join->on('tujuan_pergi.id', '=', 'transaksi.id_tujuan')
                            ->where('transaksi.rute', 'pergi');
                    })
                    ->leftJoin('halte_pulang as tujuan_pulang', function ($join) {
                        $join->on('tujuan_pulang.id', '=', 'transaksi.id_tujuan')
                            ->where('transaksi.rute', 'pulang');
                    });
                })
                ->select(
                    'transaksi.*',
                    DB::raw('CASE WHEN transaksi.rute = "pergi" THEN penjemputan_pergi.nama ELSE penjemputan_pulang.nama END as nama_penjemputan'),
                    DB::raw('CASE WHEN transaksi.rute = "pergi" THEN tujuan_pergi.nama ELSE tujuan_pulang.nama END as nama_tujuan')
                )
                ->orderBy('transaksi.estimasi_waktu', 'desc')
                ->get();


        $rute = DB::table('tracking')->where('id_supir', Auth::user()->id)->first();

        $rute = $rute->rute;
        $table = $rute === 'pergi' ? 'halte_pergi' : 'halte_pulang';

        $excludedIds = DB::table('tracking')
                ->where('id_supir', Auth::user()->id)
                ->whereNotNull('latitude') // Hanya ambil baris yang id_halte-nya tidak null
                ->pluck('id_halte')
                ->toArray(); 

        if (empty($excludedIds)) {
            $halte = DB::table($table)
                ->orderBy('id')
                ->get();
        } else {
            $halte = DB::table($table)
                //->whereNotIn('id', $excludedIds)
                ->where('id', '>', $excludedIds)
                ->orderBy('id')
                ->get();
        }

       return view('page_supir.antrian', compact('antrian','halte'));
    }

    public function tracking()
    {
       return view('page_supir.tracking');
    }

    public function submit_halte(Request $request)
    {
        $count_penumpang = DB::table('transaksi')->where('id_bus', Auth::user()->id_bus)->where('status','pending')->count();
        DB::table('tracking')->where('id_supir', Auth::user()->id)->update([
           'id_halte' => $request->id_halte,
           'latitude' => $request->latitude,
           'longitude' => $request->longitude,
           'kapasitas' => $count_penumpang
        ]);

        $update_status = DB::table('transaksi')->where('id_bus', Auth::user()->id_bus)
                            ->where('id_penjemputan', $request->id_halte)
                            ->where('status','pending')
                            ->update(['status' => 'aktif']);

        //perharui status transaksi jika penumpang sudah sampai tujuan
        $get_aktif = DB::table('transaksi')->where('id_bus', Auth::user()->id_bus)->where('status','aktif')->get();

        $cek_rute = DB::table('tracking')->where('id_bus', Auth::user()->id_bus)->first();

        if ($get_aktif->isNotEmpty()) {
            foreach ($get_aktif as $data) {
                
                if ($data->id_tujuan == $request->id_halte) {
                    // Jika sudah sampai tujuan, tandai transaksi selesai
                    DB::table('transaksi')
                        ->where('id', $data->id)
                        ->update([
                            'status' => 'selesai',
                            'flag_feedback' => 'false'
                        ]);
                } elseif ($data->id_penjemputan == $request->id_halte) {
                    // Jika halte saat ini adalah halte penjemputan, tandai konfirmasi
                    DB::table('transaksi')
                        ->where('id', $data->id)
                        ->update([
                            'flag_konfirmasi' => 'false'
                        ]);
                }

            }
        }

        $get_pending = DB::table('transaksi')->where('id_bus', Auth::user()->id_bus)->where('status','pending')->get();

        if ($get_pending->isNotEmpty()) {
            foreach ($get_pending as $data) {
                //flag reminder
                if($cek_rute->rute == 'pergi'){
                    $namaPenjemputan = DB::table('halte_pergi')
                        ->where('id', $data->id_penjemputan)
                        ->value('nama'); // Ambil nama halte penjemputan

                    $namaHalte = DB::table('halte_pergi')
                        ->where('id', $request->id_halte)
                        ->value('nama'); // Ambil nama halte tujuan

                    // Ambil semua data halte dan buat array urutan berdasarkan nama
                    $halteUrutan = DB::table('halte_pergi')
                        ->orderBy('created_at', 'asc') // Ganti dengan kolom yang sesuai
                        ->pluck('nama')
                        ->toArray();
                }else{
                    $namaPenjemputan = DB::table('halte_pulang')
                        ->where('id', $data->id_penjemputan)
                        ->value('nama'); // Ambil nama halte penjemputan

                    $namaHalte = DB::table('halte_pulang')
                        ->where('id', $request->id_halte)
                        ->value('nama'); // Ambil nama halte tujuan

                    // Ambil semua data halte dan buat array urutan berdasarkan nama
                    $halteUrutan = DB::table('halte_pulang')
                        ->orderBy('created_at', 'asc') // Ganti dengan kolom yang sesuai
                        ->pluck('nama')
                        ->toArray();
                }

                // Cari urutan dari nama halte penjemputan dan nama halte tujuan
                $urutanPenjemputan = array_search($namaPenjemputan, $halteUrutan);
                $urutanHalte = array_search($namaHalte, $halteUrutan);

                if (abs($urutanPenjemputan - $urutanHalte) == 1) {
                    DB::table('transaksi')
                        ->where('id', $data->id)
                        ->update([
                            'flag_reminder' => 'false'
                        ]);
                }

            }
        }



        if($cek_rute->rute == 'pergi'){
            $get_last = DB::table('halte_pergi')->orderBy('id','desc')->first();
            $get_first = DB::table('halte_pergi')->orderBy('id','asc')->first();

            if($get_last->id == $request->id_halte){
                $update_rute_bus = DB::table('bus')->where('id', Auth::user()->id_bus)->update(['rute' => 'pulang']);

                DB::table('tracking')->where('id_supir', Auth::user()->id)->update([
                    'id_halte' => $get_first->id,
                    'latitude' => null,
                    'longitude' => null,
                    'kapasitas' => 0,
                    'rute' => 'pulang'
                 ]);
            }
        }else{

            $get_last = DB::table('halte_pulang')->orderBy('id','desc')->first();
            $get_first = DB::table('halte_pulang')->orderBy('id','asc')->first();

            if($get_last->id == $request->id_halte){
                $update_rute_bus = DB::table('bus')->where('id', Auth::user()->id_bus)->update(['rute' => 'pergi']);

                DB::table('tracking')->where('id_supir', Auth::user()->id)->update([
                    'id_halte' => $get_first->id,
                    'latitude' => null,
                    'longitude' => null,
                    'kapasitas' => 0,
                    'rute' => 'pergi'
                 ]);
            }
        }

        return redirect()->back()->with('success', 'Konfirmasi berhasil!');
    }

    public function submit_kendala(Request $request)
    {
        //insert kendala
        DB::table('kendala')->insert([
            'keterangan' => $request->keterangan,
            'id_bus' => Auth::user()->id_bus,
            'id_supir' => Auth::user()->id,
            'created_at' => now()
        ]);

        //perharui status transaksi jika penumpang sudah sampai tujuan
        $get_aktif = DB::table('transaksi')->where('id_bus', Auth::user()->id_bus)->whereNotIn('status', ['cancel', 'selesai'])->get();

        $cek_rute = DB::table('tracking')->where('id_bus', Auth::user()->id_bus)->first();

        if ($get_aktif->isNotEmpty()) {
            foreach ($get_aktif as $data) {
                
                // Jika sudah sampai tujuan, tandai transaksi selesai
                DB::table('transaksi')
                    ->where('id', $data->id)
                    ->update([
                        'status' => 'selesai',
                        'flag_kendala' => 'false'
                    ]);

            }
        }

        if($cek_rute->rute == 'pergi'){
            $get_first = DB::table('halte_pergi')->orderBy('id','asc')->first();

            $update_rute_bus = DB::table('bus')->where('id', Auth::user()->id_bus)->update(['rute' => 'pulang']);

            DB::table('tracking')->where('id_supir', Auth::user()->id)->update([
                'id_halte' => $get_first->id,
                'latitude' => null,
                'longitude' => null,
                'kapasitas' => 0,
                'rute' => 'pulang'
                ]);
            
        }else{

            $get_first = DB::table('halte_pulang')->orderBy('id','asc')->first();

            $update_rute_bus = DB::table('bus')->where('id', Auth::user()->id_bus)->update(['rute' => 'pergi']);

            DB::table('tracking')->where('id_supir', Auth::user()->id)->update([
                'id_halte' => $get_first->id,
                'latitude' => null,
                'longitude' => null,
                'kapasitas' => 0,
                'rute' => 'pergi'
                ]);
            
        }

        return redirect()->back()->with('success', 'Submit kendala berhasil!');
    }

    public function feedback_supir()
    {
       return view('page_supir.feedback');
    }

    // Mengambil semua data feedback
    public function feedback_data()
    {
        $feedback = FeedbackSupir::all();
        return response()->json($feedback);
    }

    public function feedback_store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:mahasiswa,kendaraan',
            'rating' => 'required|integer|min:1|max:5',
            'pesan' => 'required|string',
        ]);

        $feedback = FeedbackSupir::create([
            'user_input' => Auth::user()->nama, // Ambil nama pengguna dari Auth
            'tipe' => $request->tipe,
            'rating' => $request->rating,
            'pesan' => $request->pesan,
        ]);

        return response()->json(['success' => true, 'data' => $feedback]);
    }

    public function profile()
    {
       return view('page_supir.profile');
    }

    public function update_profile(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        $data = [
            'nama' => $request->nama,
            'no_wa' => $request->no_wa,
            'email' => $request->email,
            'jk' => $request->jk,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            // Simpan avatar baru
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function setting_bus()
    {
       $id = Auth::user()->id_bus;
       $bus = Bus::findOrFail($id);

       return view('page_supir.setting_bus', compact('bus'));
    }

    public function update_setting_bus(Request $request)
    {
        $id = Auth::user()->id_bus;
        $bus = Bus::findOrFail($id);
        $bus->update([
            'nama' => $request->nama,
            'kapasitas' => $request->kapasitas,
        ]);

        return redirect()->back()->with('success', 'Bus berhasil diperbarui.');
    }

    public function history_supir()
    {
        $query = DB::table('transaksi')
            ->where('transaksi.id_bus', Auth::user()->id_bus)
            ->join('halte_pergi', 'halte_pergi.id', '=', 'transaksi.id_penjemputan')
            ->join('halte_pulang', 'halte_pulang.id', '=', 'transaksi.id_tujuan')
            ->join('bus', 'bus.id', '=', 'transaksi.id_bus')
            ->join('users as p', 'p.id', '=', 'transaksi.id_penumpang')
            ->select(
                'transaksi.kode',
                'bus.nama as nama_bus',
                'halte_pergi.nama as halte_penjemputan',
                'halte_pulang.nama as halte_tujuan',
                'transaksi.status',
                'p.nama as nama_penumpang',
                DB::raw('DATE_FORMAT(transaksi.created_at, "%d-%m-%Y %H:%i:%s") as created_at')
            )
            ->orderBy('transaksi.estimasi_waktu', 'desc');

        $history = $query->get();

       return view('page_supir.history', compact('history'));
    }

    //new code
    public function cek_harian_bus()
    {
       $id = Auth::user()->id_bus;
       $bus = Bus::findOrFail($id);
       $items = DB::table('inspection_items')
            ->where('status', 'aktif')
            ->orderBy('nama', 'asc')
            ->get();
       $alreadyChecked = $this->hasCompletedDailyInspection($id, Auth::id());

       return view('page_supir.cek_harian_bus', compact('bus', 'items', 'alreadyChecked'));
    }

    public function insert_cek_harian_bus(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'items' => 'required|array',
            'items.*.status' => 'required|in:1,2',
        ]);

        if ($this->hasCompletedDailyInspection($request->bus_id, Auth::id())) {
            return redirect()->back()->with('error', 'Pengecekan hari ini sudah disubmit.');
        }

        $items = DB::table('inspection_items')
            ->where('status', 'aktif')
            ->orderBy('nama', 'asc')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada item inspeksi yang aktif.');
        }

        $submittedItems = $request->items;
        foreach ($items as $item) {
            if (!isset($submittedItems[$item->id]) || !isset($submittedItems[$item->id]['status'])) {
                return redirect()->back()->with('error', 'Semua komponen harus dicek kondisinya (Baik atau Rusak).');
            }
            $status = (int) $submittedItems[$item->id]['status'];
            if ($status === 2) {
                $keterangan = $submittedItems[$item->id]['keterangan_rusak'] ?? '';
                if (empty(trim($keterangan))) {
                    return redirect()->back()->with('error', 'Silakan jelaskan kondisi kerusakan untuk komponen: ' . $item->nama);
                }
            }
        }

        $inspectionId = DB::table('daily_inspections')->insertGetId([
            'id_bus' => $request->bus_id,
            'id_supir' => Auth::user()->id,
            'inspected_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $results = [];
        foreach ($items as $item) {
            $status = (int) $submittedItems[$item->id]['status'];
            $keterangan = null;
            if ($status === 2) {
                $keterangan = trim($submittedItems[$item->id]['keterangan_rusak'] ?? '');
            }

            $results[] = [
                'daily_inspection_id' => $inspectionId,
                'inspection_item_id' => $item->id,
                'status' => $status,
                'keterangan_rusak' => $keterangan,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('daily_inspection_results')->insert($results);

        return redirect()->back()->with('success', 'Pengecekan berhasil disubmit.');
    }

   //new code
    public function umpan_balik_supir()
    {
        $bus = Bus::find(Auth::user()->id_bus);

        $pertanyaanSafety = collect();
        $pertanyaanOperational = collect();
        $pertanyaanComfort = collect();

        return view('page_supir.umpan_balik_supir', compact(
            'bus',
            'pertanyaanSafety',
            'pertanyaanOperational',
            'pertanyaanComfort'
        ));
    }

    public function pertanyaan_by_bus_supir(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
        ]);

        if ((int) $request->input('bus_id') !== (int) Auth::user()->id_bus) {
            abort(403);
        }

        $laporan = Laporan::where('id_bus', $request->input('bus_id'))
            ->where('target', 'supir')
            ->whereHas('pertanyaan', function ($query) {
                $query->where('status', 'aktif');
            })
            ->whereDoesntHave('jawaban', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('pertanyaan', 'bus')
            ->get();

        $grouped = [
            'safety' => [],
            'operational' => [],
            'comfort' => [],
        ];

        foreach ($laporan as $item) {
            if (isset($grouped[$item->pertanyaan->kategori])) {
                $grouped[$item->pertanyaan->kategori][] = [
                    'id' => $item->id,
                    'teks_pertanyaan' => $item->pertanyaan->teks_pertanyaan,
                    'kategori' => $item->pertanyaan->kategori,
                    'status' => $item->pertanyaan->status,
                ];
            }
        }

        return response()->json($grouped);
    }

    public function list_umpan_balik_supir(Request $request)
    {
        $userId = Auth::id();

        $answers = JawabanLaporan::where('user_id', $userId)
            ->with(['laporan.pertanyaan', 'user', 'laporan.bus'])
            ->orderBy('created_at', 'desc')
            ->paginate();

        // Cek Harian Bus data
        $id = Auth::user()->id_bus;
        $bus = Bus::findOrFail($id);
        $items = DB::table('inspection_items')
            ->where('status', 'aktif')
            ->orderBy('nama', 'asc')
            ->get();
        $alreadyChecked = $this->hasCompletedDailyInspection($id, Auth::id());

        $activeTab = $request->query('tab', 'cek_harian');

        return view('page_supir.list_umpan_balik_supir', compact('answers', 'bus', 'items', 'alreadyChecked', 'activeTab'));
    }

    public function store_umpan_balik_supir(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|integer|between:1,5',
        ]);

        if ((int) $request->input('bus_id') !== (int) Auth::user()->id_bus) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            $answers = $request->input('jawaban') ?? [];
            foreach ($answers as $laporanId => $jawaban) {
                $laporan = Laporan::where('id', $laporanId)
                    ->where('id_bus', $request->input('bus_id'))
                    ->where('target', 'supir')
                    ->lockForUpdate()
                    ->first();

                if (! $laporan) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', 'Pertanyaan untuk bus ini tidak valid.');
                }

                JawabanLaporan::create([
                    'laporan_id' => $laporan->id,
                    'user_id' => $request->user()->id,
                    'nilai' => $jawaban,
                ]);

                $laporan->load('jawaban');
                $fuzzy = new Fuzzy;
                $result = $fuzzy->execute($laporan->jawaban->pluck('nilai')->toArray());
                $laporan->nilai_fuzzy = $result['score'];
                $laporan->kategori_prioritas = strtolower($result['label']);
                $laporan->save();
            }

            DB::commit();

            return redirect()->route('list_umpan_balik_supir')
                ->with('success', 'Terima kasih atas laporan keluhan Anda! Laporan telah berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Detail laporan
    public function detail_umpan_balik_supir($id)
    {
        $userId = Auth::id();

        $laporan = JawabanLaporan::where('id', $id)
            ->where('user_id', $userId)
            ->with(['laporan.pertanyaan', 'user', 'laporan.bus'])
            ->first();

        if (! $laporan) {
            abort(404);
        }

        return view('page_supir.detail_umpan_balik_supir', compact('laporan'));
    }

    private function hasCompletedDailyInspection($busId, $supirId)
    {
        if (!$busId || !$supirId) {
            return false;
        }

        return DB::table('daily_inspections')
            ->where('id_bus', $busId)
            ->where('id_supir', $supirId)
            ->whereDate('inspected_at', Carbon::today())
            ->exists();
    }

    private function needsDailyInspection($busId, $supirId)
    {
        if (!$busId || !$supirId) {
            return false;
        }

        $hasActiveItems = DB::table('inspection_items')
            ->where('status', 'aktif')
            ->exists();

        if (!$hasActiveItems) {
            return false;
        }

        return !$this->hasCompletedDailyInspection($busId, $supirId);
    }
}
