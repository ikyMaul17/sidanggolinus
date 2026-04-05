<?php

namespace App\Http\Controllers;

use App\Actions\Fuzzy;
use App\Mail\ResetEmail;
use App\Models\Announcement;
use App\Models\Fakultas;
use App\Models\Faq;
use App\Models\FeedbackPenumpang;
use App\Models\HaltePergi;
use App\Models\HaltePulang;
use App\Models\JawabanLaporan;
use App\Models\Laporan;
use App\Models\Transaksi;
use App\Models\Tutorial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

class PenumpangHomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        if (Auth::check() && Auth::user()->status == 'tidak aktif') {
            Auth::logout();
            redirect('/tidak_aktif')->with('error', 'Akun Anda tidak aktif.')->send();
        }
    }

    public function tidak_aktif()
    {
        return view('tidak_aktif');
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
        $halte_pergi = HaltePergi::orderBy('id', 'desc')->get();

        return view('page_penumpang.home', compact('announcement', 'faq', 'halte_pergi'));
    }

    public function register_penumpang()
    {
        $fakultas = Fakultas::all();

        return view('page_penumpang.register', compact('fakultas'));
    }

    public function register_penumpang_store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'jk.required' => 'Jenis kelamin wajib dipilih.',
            'jk.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'username' => $request->nama,
            'nama' => $request->nama,
            'no_wa' => $request->no_wa,
            'nim' => $request->nim,
            'id_fakultas' => $request->id_fakultas,
            'id_jurusan' => $request->id_jurusan,
            'jk' => $request->jk,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'penumpang',
        ]);

        return redirect()->route('login_penumpang')->with('success', 'Register penumpang berhasil.');
    }

    public function tracking()
    {
        $data_pending = DB::table('transaksi')->where('id_penumpang', Auth::user()->id)->where('status', 'pending')->first();

        return view('page_penumpang.tracking', ['data_pending' => $data_pending]);
    }

    public function get_estimasi_penumpang()
    {
        $transaksi = DB::table('transaksi')->where('id_penumpang', Auth::user()->id)->where('status', 'pending')->first();

        if ($transaksi) {
            $rute = $transaksi->rute;
            $idPenjemputan = $transaksi->id_penjemputan;

            // Ambil data tracking berdasarkan rute awal
            $tracking = DB::table('tracking')->where('rute', $rute)->first();

            if (! $tracking) {
                return response()->json(['error' => 'Rute bus tidak ditemukan.']);
            }

            $idTrackingHalte = $tracking->id_halte;

            if ($rute === 'pergi') {
                if ($idPenjemputan < $idTrackingHalte) {
                    // Cari di rute 'pulang'
                    $tracking = DB::table('tracking')->where('rute', 'pulang')->first();
                    $idTrackingHalte = $tracking->id_halte ?? null;
                }
            } elseif ($rute === 'pulang') {
                if ($idPenjemputan < $idTrackingHalte) {
                    // Cari di rute 'pergi'
                    $tracking = DB::table('tracking')->where('rute', 'pergi')->first();
                    $idTrackingHalte = $tracking->id_halte ?? null;
                }
            }

            if (is_null($idTrackingHalte)) {
                return response()->json(['error' => 'Data halte tidak ditemukan pada rute lainnya.']);
            }

            // Hitung selisih ID halte
            $selisihId = abs($idPenjemputan - $idTrackingHalte);
            $estimasi = $selisihId * 5; // Waktu estimasi per ID adalah 5 menit

            // Periksa kapasitas bus
            $bus = DB::table('bus')
                ->join('tracking', 'bus.id', '=', 'tracking.id_bus')
                ->where('tracking.rute', $rute)
                ->where('tracking.id_halte', $idTrackingHalte)
                ->first();

            $nama_bus = $bus->nama;

            // if ($bus && $bus->kapasitas == $tracking->kapasitas) {
            //     return response()->json(['error' => 'Bus sudah penuh, tidak bisa melakukan booking.']);
            // }
        } else {
            $estimasi = 0;
            $nama_bus = '';
        }

        return response()->json(['estimasi' => $estimasi, 'nama_bus' => $nama_bus]);
    }

    public function history_penumpang()
    {
        return view('page_penumpang.history');
    }

    public function history_data(Request $request)
    {
        $search = $request->get('search');
        // $transaksi = \DB::table('transaksi')
        //     ->join('halte_pergi','halte_pergi.id','=','transaksi.id_penjemputan')
        //     ->join('halte_pulang','halte_pulang.id','=','transaksi.id_tujuan')
        //     ->join('bus','bus.id','=','transaksi.id_bus')
        //     ->when($search, function ($query, $search) {
        //         $query->where('transaksi.kode', 'like', "%$search%")
        //               ->orWhere('bus.nama', 'like', "%$search%");
        //     })
        //     ->select('transaksi.kode', 'bus.nama as nama_bus', 'halte_pergi.nama as halte_penjemputan', 'halte_pulang.nama as halte_tujuan', 'transaksi.status', \DB::raw('DATE_FORMAT(transaksi.created_at, "%d-%m-%Y %H:%i:%s") as created_at'))
        //     ->get();

        $transaksi = Transaksi::where('transaksi.status', 'pending')
            ->where('transaksi.id_penumpang', Auth::user()->id)
            ->join('bus', 'bus.id', '=', 'transaksi.id_bus')
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
                'bus.nama as nama_bus',
                DB::raw('CASE WHEN transaksi.rute = "pergi" THEN penjemputan_pergi.nama ELSE penjemputan_pulang.nama END as halte_penjemputan'),
                DB::raw('CASE WHEN transaksi.rute = "pergi" THEN tujuan_pergi.nama ELSE tujuan_pulang.nama END as halte_tujuan'),
            )
            ->when($search, function ($query, $search) {
                $query->where('transaksi.kode', 'like', "%$search%")
                    ->orWhere('bus.nama', 'like', "%$search%");
            })
            ->orderBy('transaksi.estimasi_waktu', 'desc')
            ->get();

        return response()->json($transaksi);
    }

    public function feedback_penumpang()
    {
        return view('page_penumpang.feedback');
    }

    // Mengambil semua data feedback
    public function feedback_data()
    {
        $feedback = FeedbackPenumpang::all();

        return response()->json($feedback);
    }

    // Menyimpan feedback baru
    public function feedback_store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:keluhan,apresiasi',
            'rating' => 'required|integer|min:1|max:5',
            'pesan' => 'required|string',
        ]);

        $feedback = FeedbackPenumpang::create([
            'user_input' => Auth::user()->nama, // Ambil nama pengguna dari Auth
            'tipe' => $request->tipe,
            'rating' => $request->rating,
            'pesan' => $request->pesan,
        ]);

        return response()->json(['success' => true, 'data' => $feedback]);
    }

    public function tutorial_penumpang()
    {
        $tutorial = Tutorial::orderBy('id', 'asc')->get();

        return view('page_penumpang.tutorial', compact('tutorial'));
    }

    public function profile()
    {
        return view('page_penumpang.profile');
    }

    public function update_profile(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        $data = [
            'nama' => $request->nama,
            'no_wa' => $request->no_wa,
            'nim' => $request->nim,
            'email' => $request->email,
            'jk' => $request->jk,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::delete('public/'.$user->avatar);
            }

            // Simpan avatar baru
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function getBusPositions()
    {
        $buses = DB::table('tracking')
            ->select('tracking.id', 'bus.nama', 'latitude', 'longitude', 'users.nama as nama_supir')
            ->join('bus', 'bus.id', '=', 'tracking.id_bus')
            ->join('users', 'users.id', '=', 'tracking.id_supir')
            ->whereNotNull('latitude')
            ->get();

        return response()->json($buses);
    }

    public function getBusStops()
    {
        $haltePulang = HaltePulang::all(['id', 'nama as name', 'latitude', 'longitude']);
        $haltePergi = HaltePergi::all(['id', 'nama as name', 'latitude', 'longitude']);

        $stops = $haltePulang->merge($haltePergi);

        return response()->json($stops);
    }

    public function booking()
    {
        $halte_pulang = HaltePulang::orderBy('id', 'desc')->get();
        $halte_pergi = HaltePergi::orderBy('id', 'desc')->get();

        return view('page_penumpang.booking', compact('halte_pulang', 'halte_pergi'));
    }

    public function reset_password(Request $request)
    {
        $user = DB::table('users')
            ->where('email', $request->email)
            ->first();

        Mail::to($user->email)->send(new ResetEmail($user));

        return redirect()->back()->with('success', 'Permintaan reset password dikirim');
    }

    public function store_reset_password(Request $request)
    {
        $cek_user = DB::table('users')->where('email', $request->email)->first();

        $user = DB::table('users')
            ->where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        if ($cek_user->role == 'penumpang') {
            return redirect()->route('login_penumpang')->with('success', 'Berhasil reset password, silahkan login kembali');
        } else {
            return redirect()->route('login_supir')->with('success', 'Berhasil reset password, silahkan login kembali');
        }
    }

    // new code
    public function umpan_balik_penumpang()
    {
        // Pertanyaan akan dimuat dinamis berdasarkan bus yang dipilih
        $pertanyaanSafety = collect();
        $pertanyaanOperational = collect();
        $pertanyaanComfort = collect();

        // Ambil data bus yang bisa dinilai
        $buses = DB::table('bus')->get();

        return view('page_penumpang.umpan_balik_penumpang', compact(
            'pertanyaanSafety',
            'pertanyaanOperational',
            'pertanyaanComfort',
            'buses'
        ));
    }

    public function pertanyaan_by_bus(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
        ]);

        $laporan = Laporan::whereHas('bus', function ($query) use ($request) {
            $query->where('id', $request->input('bus_id'));
        })
            ->where('target', 'penumpang')
            ->whereHas('pertanyaan')
            ->where(function ($query) {
                $query->whereDoesntHave('jawaban', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->orWhere('status_perbaikan', 'selesai');
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

    public function list_umpan_balik_penumpang()
    {
        $userId = Auth::id();

        // $laporan = DB::table('laporan')
        //     ->leftJoin('bus', 'laporan.id_bus', '=', 'bus.id')
        //     ->where('laporan.id_user', $userId)
        //     ->select('laporan.*', 'bus.nama as nama_bus')
        //     ->orderBy('laporan.created_at', 'desc')
        //     ->paginate(10);

        $answers = JawabanLaporan::where('user_id', $userId)
            ->with(['laporan.pertanyaan', 'user', 'laporan.bus'])
            ->paginate();

        return view('page_penumpang.list_umpan_balik_penumpang', compact('answers'));
    }

    public function store_umpan_balik_penumpang(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
        ]);

        DB::beginTransaction();

        try {
            $answers = $request->input('jawaban') ?? [];
            foreach ($answers as $laporanId => $jawaban) {
                JawabanLaporan::create([
                    'laporan_id' => $laporanId,
                    'user_id' => $request->user()->id,
                    'nilai' => $jawaban,
                ]);

                $laporan = Laporan::with('pertanyaan', 'jawaban')
                    ->lockForUpdate()
                    ->find($laporanId);

                // Reset status to 'menunggu' if re-submitting after 'selesai'
                if ($laporan->status_perbaikan === 'selesai') {
                    $laporan->status_perbaikan = 'menunggu';
                }

                $fuzzy = new Fuzzy;
                $result = $fuzzy->execute($laporan->jawaban->pluck('nilai')->toArray());
                $laporan->nilai_fuzzy = $result['score'];
                $laporan->kategori_prioritas = strtolower($result['label']);
                $laporan->save();

            }

            DB::commit();

            return redirect()->route('list_umpan_balik_penumpang')
                ->with('success', 'Terima kasih atas laporan keluhan Anda! Laporan telah berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
        // // Validasi jawaban untuk setiap pertanyaan
        // $requiredPertanyaan = DB::table('pertanyaan')
        //     ->join('pertanyaan_bus', 'pertanyaan.id', '=', 'pertanyaan_bus.pertanyaan_id')
        //     ->where('pertanyaan_bus.bus_id', $request->bus_id)
        //     ->where('pertanyaan.status', 'aktif')
        //     ->pluck('pertanyaan.id')
        //     ->toArray();

        // if (empty($requiredPertanyaan)) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'Pertanyaan untuk bus ini belum tersedia.');
        // }

        // $jawabanIds = array_map('intval', array_keys($request->jawaban ?? []));
        // $invalidJawaban = array_diff($jawabanIds, $requiredPertanyaan);

        // if (! empty($invalidJawaban)) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'Ada pertanyaan yang tidak sesuai dengan bus yang dipilih.');
        // }

        // foreach ($requiredPertanyaan as $pertanyaanId) {
        //     $request->validate([
        //         "jawaban.{$pertanyaanId}" => 'required|integer|between:1,5',
        //     ]);
        // }

        // DB::beginTransaction();

        // try {
        //     // ============================
        //     // 1. HITUNG RATA-RATA PER KATEGORI
        //     // ============================
        //     $safetyScores = [];
        //     $operationalScores = [];
        //     $comfortScores = [];

        //     foreach ($request->jawaban as $pertanyaanId => $nilai) {
        //         // Ambil kategori pertanyaan
        //         $pertanyaan = DB::table('pertanyaan')->where('id', $pertanyaanId)->first();

        //         if ($pertanyaan) {
        //             if ($pertanyaan->kategori == 'safety') {
        //                 $safetyScores[] = $nilai;
        //             } elseif ($pertanyaan->kategori == 'operational') {
        //                 $operationalScores[] = $nilai;
        //             } elseif ($pertanyaan->kategori == 'comfort') {
        //                 $comfortScores[] = $nilai;
        //             }
        //         }
        //     }

        //     $avgSafety = count($safetyScores) > 0 ? array_sum($safetyScores) / count($safetyScores) : 0;
        //     $avgOperational = count($operationalScores) > 0 ? array_sum($operationalScores) / count($operationalScores) : 0;
        //     $avgComfort = count($comfortScores) > 0 ? array_sum($comfortScores) / count($comfortScores) : 0;

        //     // ============================
        //     // 2. HITUNG FUZZY (Sederhana)
        //     // ============================
        //     $nilaiFuzzy = ($avgSafety + $avgOperational + $avgComfort) * 2;

        //     if ($nilaiFuzzy >= 8) {
        //         $kategori = 'tinggi';
        //     } elseif ($nilaiFuzzy >= 5) {
        //         $kategori = 'sedang';
        //     } else {
        //         $kategori = 'rendah';
        //     }

        //     // ============================
        //     // 3. SIMPAN LAPORAN
        //     // ============================
        //     $laporan_id = DB::table('laporan')->insertGetId([
        //         'id_user' => Auth::id(),
        //         'id_bus' => $request->bus_id,
        //         'jenis_user' => Auth::user()->role,
        //         'avg_safety' => round($avgSafety, 2),
        //         'avg_operational' => round($avgOperational, 2),
        //         'avg_comfort' => round($avgComfort, 2),
        //         'nilai_fuzzy' => round($nilaiFuzzy, 2),
        //         'kategori_prioritas' => $kategori,
        //         'status_perbaikan' => 'menunggu',
        //         'created_at' => now(),
        //     ]);

        //     // ============================
        //     // 4. SIMPAN JAWABAN PER PERTANYAAN
        //     // ============================
        //     foreach ($request->jawaban as $pertanyaan_id => $nilai) {
        //         DB::table('jawaban_laporan')->insert([
        //             'laporan_id' => $laporan_id,
        //             'pertanyaan_id' => $pertanyaan_id,
        //             'nilai' => $nilai,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }

        //     // ============================
        //     // 5. SIMPAN LOG FUZZY
        //     // ============================
        //     DB::table('log_fuzzy')->insert([
        //         'laporan_id' => $laporan_id,
        //         'nilai_safety' => round($avgSafety, 2),
        //         'nilai_operational' => round($avgOperational, 2),
        //         'nilai_comfort' => round($avgComfort, 2),
        //         'output_fuzzy' => round($nilaiFuzzy, 2),
        //         'kategori' => $kategori,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);

        //     DB::commit();

        //     return redirect()->route('list_umpan_balik_penumpang')
        //         ->with('success', 'Terima kasih atas umpan balik Anda! Laporan telah berhasil disimpan.');

        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        // }
    }

    // Detail laporan
    public function detail_umpan_balik_penumpang($id)
    {
        $userId = Auth::id();

        $laporan = JawabanLaporan::where('id', $id)
            ->where('user_id', $userId)
            ->with(['laporan.pertanyaan', 'user', 'laporan.bus'])
            ->first();
        if (! $laporan) {
            abort(404);
        }

        // // Ambil detail jawaban
        // $jawaban = DB::table('jawaban_laporan')
        //     ->join('pertanyaan', 'jawaban_laporan.pertanyaan_id', '=', 'pertanyaan.id')
        //     ->where('jawaban_laporan.laporan_id', $id)
        //     ->select('jawaban_laporan.*', 'pertanyaan.teks_pertanyaan', 'pertanyaan.kategori')
        //     ->get();

        return view('page_penumpang.detail_umpan_balik_penumpang', compact('laporan'));
    }
}
