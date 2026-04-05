<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
       $count_pending = Transaksi::where('status','pending')->orderBy('id', 'desc')->count();
    //   $data_aktif = Transaksi::where('status','aktif')->orderBy('id', 'desc')->take(5)->get();
    //   $data_selesai = Transaksi::where('status','selesai')->orderBy('id', 'desc')->take(5)->get();
       
       
        $data_aktif = DB::table('transaksi')
            ->join('halte_pergi','halte_pergi.id','=','transaksi.id_penjemputan')
            ->join('halte_pulang','halte_pulang.id','=','transaksi.id_tujuan')
            ->join('bus','bus.id','=','transaksi.id_bus')
             ->join('users','users.id','=','transaksi.id_penumpang')
            ->select('transaksi.kode', 'bus.nama as nama_bus', 'halte_pergi.nama as halte_penjemputan', 'halte_pulang.nama as halte_tujuan', 'transaksi.status','users.nama as nama_penumpang', \DB::raw('DATE_FORMAT(transaksi.created_at, "%d-%m-%Y %H:%i:%s") as created_at'))
            ->where('transaksi.status','aktif')
            ->take(5)
            ->get();
            
            $data_selesai = DB::table('transaksi')
            ->join('halte_pergi','halte_pergi.id','=','transaksi.id_penjemputan')
            ->join('halte_pulang','halte_pulang.id','=','transaksi.id_tujuan')
            ->join('bus','bus.id','=','transaksi.id_bus')
             ->join('users','users.id','=','transaksi.id_penumpang')
            ->select('transaksi.kode', 'bus.nama as nama_bus', 'halte_pergi.nama as halte_penjemputan', 'halte_pulang.nama as halte_tujuan', 'transaksi.status','users.nama as nama_penumpang', \DB::raw('DATE_FORMAT(transaksi.created_at, "%d-%m-%Y %H:%i:%s") as created_at'))
            ->where('transaksi.status','selesai')
            ->take(5)
            ->get();
            

      return view('dashboard', compact('count_pending','data_aktif','data_selesai'));
    }
}
