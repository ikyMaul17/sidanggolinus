<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

class PenumpangBookingController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        if (Auth::check() && Auth::user()->status == 'tidak aktif') {
            Auth::logout();
            redirect('/tidak_aktif')->with('error', 'Akun Anda tidak aktif.')->send();
        }
    }
    
    public function getHalte(Request $request)
    {
        $rute = $request->rute;
        $table = $rute === 'pergi' ? 'halte_pergi' : 'halte_pulang';

        $excludedIds = DB::table('tracking')
                    ->join('bus', 'tracking.id_bus', '=', 'bus.id')
                    ->where('tracking.rute', $rute)
                    ->whereColumn('tracking.kapasitas', '<', 'bus.kapasitas') // Kondisi kapasitas tracking < kapasitas bus
                    ->whereNotNull('tracking.latitude')
                    ->pluck('tracking.id_halte')
                    ->toArray();

        if (empty($excludedIds)) {
            $haltes = DB::table($table)
                ->orderBy('id')
                ->get();
        } else {
            $haltes = DB::table($table)
                //->whereNotIn('id', $excludedIds)
                ->where('id', '>', $excludedIds)
                ->orderBy('id')
                ->get();
        }

        // $haltes = $haltes->values()->map(function ($halte, $index) {
        //     $halte->id = $index + 1; // Buat ID baru mulai dari 1
        //     return $halte;
        // });

        return response()->json($haltes);
    }

    public function getHalteTujuan(Request $request)
    {
        $rute = $request->rute;
        $table = $rute === 'pergi' ? 'halte_pergi' : 'halte_pulang';
        $idPenjemputan = $request->id_penjemputan;

        $haltes = DB::table($table)
            ->where('id', '>', $idPenjemputan)
            ->orderBy('id')
            ->get();

        return response()->json($haltes);
    }

    public function getEstimasi(Request $request)
    {
        $rute = $request->input('rute');
        $idPenjemputan = $request->input('id_penjemputan');

        // Ambil data tracking berdasarkan rute awal
        $tracking = DB::table('tracking')
                    ->join('bus', 'tracking.id_bus', '=', 'bus.id')
                    ->where('tracking.rute', $rute)
                    ->whereColumn('tracking.kapasitas', '<', 'bus.kapasitas') // Kapasitas tracking < kapasitas bus
                    ->select('tracking.*', 'bus.kapasitas as kapasitas_bus') // Pilih semua kolom tracking + kapasitas bus
                    ->first();


        if (!$tracking) {
            return response()->json(['error' => 'Rute bus tidak ditemukan.']);
        }

        $idTrackingHalte = $tracking->id_halte;

        if ($rute === 'pergi') {
            if ($idPenjemputan < $idTrackingHalte) {
                // Cari di rute 'pulang'
                $tracking = DB::table('tracking')
                            ->join('bus', 'tracking.id_bus', '=', 'bus.id')
                            ->where('tracking.rute', 'pulang')
                            ->whereColumn('tracking.kapasitas', '<', 'bus.kapasitas') // Kapasitas tracking < kapasitas bus
                            ->select('tracking.*', 'bus.kapasitas as kapasitas_bus') // Pilih semua kolom tracking + kapasitas bus
                            ->first();

                $idTrackingHalte = $tracking->id_halte ?? null;
            }
        } elseif ($rute === 'pulang') {
            if ($idPenjemputan < $idTrackingHalte) {
                // Cari di rute 'pergi'
                $tracking = DB::table('tracking')
                            ->join('bus', 'tracking.id_bus', '=', 'bus.id')
                            ->where('tracking.rute', 'pergi')
                            ->whereColumn('tracking.kapasitas', '<', 'bus.kapasitas') // Kapasitas tracking < kapasitas bus
                            ->select('tracking.*', 'bus.kapasitas as kapasitas_bus') // Pilih semua kolom tracking + kapasitas bus
                            ->first();
                $idTrackingHalte = $tracking->id_halte ?? null;
            }
        }

        // if (is_null($idTrackingHalte)) {
        //     return response()->json(['error' => 'Data halte tidak ditemukan pada rute lainnya.']);
        // }

        // Hitung selisih ID halte
        // $selisihId = abs($idPenjemputan - $idTrackingHalte);
        // $estimasi = $selisihId * 5; // Waktu estimasi per ID adalah 5 menit

        // Menentukan ID yang lebih kecil dan lebih besar
        $minId = min($idPenjemputan, $idTrackingHalte);
        $maxId = max($idPenjemputan, $idTrackingHalte);

        // Menghitung loncatan
        if ($minId == 0) {
            $loncatan = 0; // Jika ID yang lebih kecil adalah 0, loncatan = 1
        } else {
            $loncatan = $maxId - $minId; // Jika tidak, loncatan = selisih ID
        }

        $estimasi = $loncatan * 5; // Waktu estimasi per loncatan adalah 5 menit

        // Periksa kapasitas bus
        $bus = DB::table('bus')
            ->join('tracking', 'bus.id', '=', 'tracking.id_bus')
            ->where('tracking.rute', $rute)
            ->where('tracking.id_halte', $idTrackingHalte)
            ->first();

        // if ($bus && $bus->kapasitas == $tracking->kapasitas) {
        //     return response()->json(['error' => 'Bus sudah penuh, tidak bisa melakukan booking.']);
        // }

        return response()->json(['estimasi' => $estimasi]);
    }

    public function submit_booking(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'rute' => 'required|string',
            'id_penjemputan' => 'required|integer',
            'id_tujuan' => 'required|integer',
            'estimasi' => 'required|integer',
        ]);

        $rute = $validated['rute'];
        $idPenjemputan = $validated['id_penjemputan'];
        $idTujuan = $validated['id_tujuan'];
        $estimasi = $validated['estimasi'];

        $tracking = DB::table('tracking')
            ->where('tracking.rute', $rute)
            ->whereColumn('tracking.kapasitas', '<', 'bus.kapasitas')
            ->join('bus', 'tracking.id_bus', '=', 'bus.id')
            ->select('tracking.id', 'tracking.kapasitas', 'tracking.id_bus')
            ->first();

        if (is_null($tracking)) {
            return redirect()->back()->with('error', 'Kapasitas penuh');
        }

        try {
            DB::transaction(function () use ($rute, $idPenjemputan, $idTujuan, $estimasi) {
                // Ambil semua booking yang sedang diproses di session (misal simulasi multi-user booking)
                $existingBookings = session()->get('pending_bookings', []);
                
                // Tambahkan booking baru ke daftar pending dengan key priority (estimasi)
                $newBooking = [
                    'rute' => $rute,
                    'id_penjemputan' => $idPenjemputan,
                    'id_tujuan' => $idTujuan,
                    'estimasi' => $estimasi,
                ];
                $existingBookings[] = $newBooking;

                // Urutkan berdasarkan estimasi waktu dari yang paling lama
                usort($existingBookings, function ($a, $b) {
                    return $b['estimasi'] <=> $a['estimasi'];
                });

                // Simpan kembali ke session (simulasi proses multiple bookings)
                session()->put('pending_bookings', $existingBookings);

                // Proses booking berdasarkan prioritas
                foreach ($existingBookings as $booking) {
                    // Periksa kapasitas tracking untuk setiap booking
                    $tracking = DB::table('tracking')
                        ->where('tracking.rute', $booking['rute'])
                        //->where('id_halte', $booking['id_penjemputan'])
                        ->whereColumn('tracking.kapasitas', '<', 'bus.kapasitas')
                        ->join('bus', 'tracking.id_bus', '=', 'bus.id')
                        ->select('tracking.id', 'tracking.kapasitas','tracking.id_bus')
                        ->first();

                    if (is_null($tracking)) {
                        return redirect()->back()->with('success', 'Kapasitas penuh');
                    }

                    // Generate kode transaksi
                    $kodeTransaksi = 'TRX-' . substr(strtoupper(uniqid()), -5);

                    // Masukkan ke tabel transaksi
                    DB::table('transaksi')->insert([
                        'kode' => $kodeTransaksi,
                        'rute' => $booking['rute'],
                        'id_penjemputan' => $booking['id_penjemputan'],
                        'id_tujuan' => $booking['id_tujuan'],
                        'estimasi_waktu' => $booking['estimasi'],
                        'id_penumpang' => Auth::user()->id,
                        'id_bus' => $tracking->id_bus,
                        'created_at' => now(),
                        // 'updated_at' => now(),
                    ]);

                    // Update kapasitas tracking
                    DB::table('tracking')
                        ->where('id', $tracking->id)
                        ->update([
                            'kapasitas' => $tracking->kapasitas + 1,
                            'updated_at' => now(),
                        ]);

                    // Hapus booking dari daftar pending setelah sukses diproses
                    $existingBookings = array_filter($existingBookings, function ($b) use ($booking) {
                        return $b !== $booking;
                    });
                    session()->put('pending_bookings', $existingBookings);
                }
            });

            return redirect()->back()->with('success', 'Booking berhasil diproses!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function submit_booking_ulang($kode)
    {
        $get_transaksi = DB::table('transaksi')->where('kode', $kode)->first();
       
        $rute = $get_transaksi->rute;
        $idPenjemputan = $get_transaksi->id_penjemputan;
        $idTujuan = $get_transaksi->id_tujuan;
        $estimasi = $get_transaksi->estimasi_waktu;

        $tracking = DB::table('tracking')
            ->where('tracking.rute', $rute)
            ->whereColumn('tracking.kapasitas', '<', 'bus.kapasitas')
            ->join('bus', 'tracking.id_bus', '=', 'bus.id')
            ->select('tracking.id', 'tracking.kapasitas', 'tracking.id_bus')
            ->first();

        if (is_null($tracking)) {
            return redirect()->back()->with('error', 'Kapasitas penuh');
        }

        try {
            DB::transaction(function () use ($rute, $idPenjemputan, $idTujuan, $estimasi) {
                // Ambil semua booking yang sedang diproses di session (misal simulasi multi-user booking)
                $existingBookings = session()->get('pending_bookings', []);
                
                // Tambahkan booking baru ke daftar pending dengan key priority (estimasi)
                $newBooking = [
                    'rute' => $rute,
                    'id_penjemputan' => $idPenjemputan,
                    'id_tujuan' => $idTujuan,
                    'estimasi' => $estimasi,
                ];
                $existingBookings[] = $newBooking;

                // Urutkan berdasarkan estimasi waktu dari yang paling lama
                usort($existingBookings, function ($a, $b) {
                    return $b['estimasi'] <=> $a['estimasi'];
                });

                // Simpan kembali ke session (simulasi proses multiple bookings)
                session()->put('pending_bookings', $existingBookings);

                // Proses booking berdasarkan prioritas
                foreach ($existingBookings as $booking) {
                    // Periksa kapasitas tracking untuk setiap booking
                    $tracking = DB::table('tracking')
                        ->where('tracking.rute', $booking['rute'])
                        //->where('id_halte', $booking['id_penjemputan'])
                        ->whereColumn('tracking.kapasitas', '<', 'bus.kapasitas')
                        ->join('bus', 'tracking.id_bus', '=', 'bus.id')
                        ->select('tracking.id', 'tracking.kapasitas','tracking.id_bus')
                        ->first();

                    if (is_null($tracking)) {
                        return redirect()->back()->with('success', 'Kapasitas penuh');
                    }

                    // Generate kode transaksi
                    $kodeTransaksi = 'TRX-' . substr(strtoupper(uniqid()), -5);

                    // Masukkan ke tabel transaksi
                    DB::table('transaksi')->insert([
                        'kode' => $kodeTransaksi,
                        'rute' => $booking['rute'],
                        'id_penjemputan' => $booking['id_penjemputan'],
                        'id_tujuan' => $booking['id_tujuan'],
                        'estimasi_waktu' => $booking['estimasi'],
                        'id_penumpang' => Auth::user()->id,
                        'id_bus' => $tracking->id_bus,
                        'created_at' => now(),
                        // 'updated_at' => now(),
                    ]);

                    // Update kapasitas tracking
                    DB::table('tracking')
                        ->where('id', $tracking->id)
                        ->update([
                            'kapasitas' => $tracking->kapasitas + 1,
                            'updated_at' => now(),
                        ]);

                    // Hapus booking dari daftar pending setelah sukses diproses
                    $existingBookings = array_filter($existingBookings, function ($b) use ($booking) {
                        return $b !== $booking;
                    });
                    session()->put('pending_bookings', $existingBookings);
                }
            });

            return redirect()->back()->with('success', 'Booking berhasil diproses!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel_request()
    {
        $userId = Auth::user()->id;

        // Ambil data transaksi untuk mendapatkan id_bus
        $bus = DB::table('transaksi')->where('id_penumpang', $userId)->first();

        if ($bus) {
            // Kurangi kapasitas bus sebesar 1
            DB::table('tracking')
                ->where('id_bus', $bus->id_bus)
                ->decrement('kapasitas', 1);  // Mengurangi kapasitas sebanyak 1

            // Perbarui transaksi yang berstatus 'pending' menjadi 'cancel'
            DB::table('transaksi')
                ->where('id_penumpang', $userId)
                ->where('status', 'pending')
                ->update(['status' => 'cancel']);

            // Hitung jumlah transaksi yang dibatalkan dalam hari ini
            $cancelCount = DB::table('transaksi')
                ->where('id_penumpang', $userId)
                ->where('status', 'cancel')
                ->whereDate('created_at', today()) // Filter hanya untuk hari ini
                ->count();

            // Jika jumlah pembatalan dalam sehari >= 3, nonaktifkan akun
            if ($cancelCount >= 3) {
                DB::table('users')
                    ->where('id', $userId)
                    ->update(['status' => 'tidak aktif']);

                Auth::logout(); // Logout pengguna

                return redirect('/')->with('success', 'Akun Anda telah dinonaktifkan karena terlalu banyak pembatalan dalam sehari.');
            }

            return redirect()->route('booking')->with('success', 'Berhasil membatalkan request');
        } else {
            return redirect()->route('booking')->with('error', 'Transaksi tidak ditemukan.');
        }
    }

    public function checkTransactionStatus()
    {
        $userId = Auth::user()->id; // Ambil ID user yang sedang login

        // Cari transaksi terakhir yang selesai
        $transaksiSelesai = DB::table('transaksi')
            ->where('id_penumpang', $userId)
            ->where('flag_feedback', 'false') // Pastikan ada kolom status di tabel transaksi
            ->first();

        return response()->json([
            'show_alert' => $transaksiSelesai ? true : false
        ]);
    }

    public function updateTransaction()
    {
        $userId = Auth::user()->id; // Ambil ID user yang sedang login

        // Cari transaksi terakhir yang selesai
        $transaksiSelesai = DB::table('transaksi')
            ->where('id_penumpang', $userId)
            ->where('flag_feedback', 'false')
            ->first();

        if ($transaksiSelesai) {
            // Update kolom updated_at
            DB::table('transaksi')
                ->where('id', $transaksiSelesai->id)
                ->update([
                    'flag_feedback' => 'true',
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate.']);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada transaksi yang perlu diupdate.']);
    }

    public function checkTransactionStatusKonfirmasi()
    {
        $userId = Auth::user()->id; // Ambil ID user yang sedang login

        // Cari transaksi terakhir yang selesai
        $transaksiSelesai = DB::table('transaksi')
            ->where('id_penumpang', $userId)
            ->where('flag_konfirmasi', 'false') // Pastikan ada kolom status di tabel transaksi
            ->first();

        return response()->json([
            'show_alert' => $transaksiSelesai ? true : false
        ]);
    }

    public function updateTransactionKonfirmasi(Request $request)
    {
        $userId = Auth::user()->id; // Ambil ID user yang sedang login
        $status = $request->input('status');

        // Cari transaksi terakhir yang selesai
        $transaksiSelesai = DB::table('transaksi')
            ->where('id_penumpang', $userId)
            ->where('flag_konfirmasi', 'false')
            ->first();

        if (!empty($transaksiSelesai)) {
            // Update kolom updated_at
            if($status == 'true'){
                DB::table('transaksi')
                ->where('id', $transaksiSelesai->id)
                ->update([
                    'flag_konfirmasi' => 'true',
                    'updated_at' => now()
                ]);

                return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate.']);

            }else if($status == 'cancel'){
                
                // Kurangi kapasitas bus sebesar 1
                DB::table('tracking')
                    ->where('id_bus', $transaksiSelesai->id_bus)
                    ->decrement('kapasitas', 1);  // Mengurangi kapasitas sebanyak 1

                // Perbarui transaksi yang berstatus 'pending' menjadi 'cancel'
                DB::table('transaksi')
                    ->where('id_penumpang', $userId)
                    ->where('id', $transaksiSelesai->id)
                    ->update([
                        'status' => 'cancel',
                        'flag_konfirmasi' => 'true',
                    ]);

                return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate.']);

            }else{
                return response()->json(['success' => true, 'message' => 'Transaksi gagal diupdate.']);
            }
           
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada transaksi yang perlu diupdate.']);
    }

    public function checkTransactionReminder()
    {
        $userId = Auth::user()->id; // Ambil ID user yang sedang login

        // Cari transaksi terakhir yang selesai
        $transaksiSelesai = DB::table('transaksi')
            ->where('id_penumpang', $userId)
            ->where('flag_reminder', 'false') // Pastikan ada kolom status di tabel transaksi
            ->first();

        return response()->json([
            'show_alert' => $transaksiSelesai ? true : false
        ]);
    }

    public function updateTransactionReminder(Request $request)
    {
        $userId = Auth::user()->id; // Ambil ID user yang sedang login

        // Cari transaksi terakhir yang selesai
        $transaksiSelesai = DB::table('transaksi')
            ->where('id_penumpang', $userId)
            ->where('flag_reminder', 'false')
            ->first();

        if ($transaksiSelesai) {
            // Update kolom updated_at
            DB::table('transaksi')
                ->where('id', $transaksiSelesai->id)
                ->update([
                    'flag_reminder' => 'true',
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate.']);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada transaksi yang perlu diupdate.']);
    }

    public function checkTransactionKendala()
    {
        $userId = Auth::user()->id; // Ambil ID user yang sedang login

        // Cari transaksi terakhir yang selesai
        $transaksiSelesai = DB::table('transaksi')
            ->where('id_penumpang', $userId)
            ->where('flag_kendala', 'false') // Pastikan ada kolom status di tabel transaksi
            ->first();

        return response()->json([
            'show_alert' => $transaksiSelesai ? true : false
        ]);
    }

    public function updateTransactionKendala(Request $request)
    {
        $userId = Auth::user()->id; // Ambil ID user yang sedang login

        // Cari transaksi terakhir yang selesai
        $transaksiSelesai = DB::table('transaksi')
            ->where('id_penumpang', $userId)
            ->where('flag_kendala', 'false')
            ->first();

        if ($transaksiSelesai) {
            // Update kolom updated_at
            DB::table('transaksi')
                ->where('id', $transaksiSelesai->id)
                ->update([
                    'flag_kendala' => 'true',
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate.']);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada transaksi yang perlu diupdate.']);
    }

}
